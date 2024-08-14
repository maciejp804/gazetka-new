<?php

namespace App\Http\Controllers;

use App\Models\OcrResult;
use App\Models\Stopword;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lemat;

class OcrController extends Controller
{
    private $lemmas;
    private $stopwords;
    private $mistakewords;

    public function __construct()
    {
        $lemmasPreperation = Lemat::all();
        $stopwordsPreperation = Stopword::all();
        $this->lemmas = $lemmasPreperation->pluck('lemma', 'word')->toArray();
        $this->stopwords = $stopwordsPreperation->pluck('word')->toArray();
        $this->mistakewords = json_decode(file_get_contents(storage_path('mistakewords.json')), true);
    }

    private function lemmatize($word)
    {
        $word = mb_strtolower($word);
        if (str_contains($word, '-')) {
            $parts = explode('-', $word);
            $lemmatizedParts = array_map(function ($part) {
                return $this->lemmas[$part] ?? $part;
            }, $parts);
            return implode('-', $lemmatizedParts);
        }
        return $this->lemmas[$word] ?? $word;
    }

    private function removeStopwords($text)
    {
        $words = explode(' ', $text);
        $filteredWords = array_filter($words, function ($word) {
            return !in_array(mb_strtolower($word), $this->stopwords);
        });
        return implode(' ', $filteredWords);
    }

    private function mistakeword($word)
    {
        $word = mb_strtolower($word);
        return $this->mistakewords[$word] ?? $word;
    }

    private function removePunctuation($text)
    {
        return preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
    }

    private function preprocessText($text)
    {
        $text = $this->removePunctuation(mb_strtolower($text));
        $text = $this->removeStopwords($text);
        $words = explode(' ', $text);
        $correctedWords = array_map([$this, 'mistakeword'], $words);
        $lemmatizedWords = array_map([$this, 'lemmatize'], $correctedWords);
        return implode(' ', $lemmatizedWords);
    }

    private function sortOcrResults($ocrResults)
    {
        usort($ocrResults, function ($a, $b) {
            return $a['MinTop'] - $b['MinTop'];
        });
        return $ocrResults;
    }

    private function groupLinesByProximity($ocrResults, $baseThreshold = 20, $heightMultiplier = 0.5, $leftThreshold = 20, $rightThreshold = 20, $fontHeightRange = [10, 20]) {

        $products = [];
        $currentProduct = [];
        $lastGroupedTop = null;
        $lastGroupedLeft = null;
        $lastGroupedRight = null;
        $lastGroupedHeight = null;

        foreach ($ocrResults as $line) {
            $lineHeight = $line['MaxHeight'];

            // Pomijanie linii poza zakresem wysokości czcionki
            if ($lineHeight < $fontHeightRange[0] || $lineHeight > $fontHeightRange[1]) {
                continue;
            }

            $currentLeft = $line['Words'][0]['Left'];
            $currentRight = $currentLeft;

            foreach ($line['Words'] as $word) {
                $currentRight += $word['Width'];
            }

            // Dodanie odstępów między słowami (zakładamy standardowy odstęp, np. 5 jednostek)
            $currentRight += (count($line['Words']) - 1) * 5;

            if ($lastGroupedTop !== null) {
                // Obliczanie dynamicznego progu dla odległości pionowej
                $dynamicThreshold = $baseThreshold + ($lastGroupedHeight * $heightMultiplier);

                // Sprawdzanie warunków grupowania linii
                if (abs($line['MinTop'] - $lastGroupedTop) > $dynamicThreshold) {
                    // Warunki pionowe nie są spełnione, więc rozdzielamy linie
                    $products[] = [
                        'text' => implode(' ', array_column($currentProduct, 'LineText')),
                        'left' => $lastGroupedLeft,
                        'top' => $lastGroupedTop,
                        'width' => $lastGroupedRight - $lastGroupedLeft,
                        'height' => $lastGroupedHeight
                    ];
                    $currentProduct = [];
                }
            }

            // Dodanie linii do bieżącej grupy
            $currentProduct[] = $line;
            $lastGroupedTop = $line['MinTop'];
            $lastGroupedLeft = min($lastGroupedLeft ?? $currentLeft, $currentLeft);
            $lastGroupedRight = max($lastGroupedRight ?? $currentRight, $currentRight);
            $lastGroupedHeight = max($lastGroupedHeight ?? $lineHeight, $lineHeight);
        }

        // Dodanie ostatniej grupy do produktów
        if (!empty($currentProduct)) {
            $products[] = [
                'text' => implode(' ', array_column($currentProduct, 'LineText')),
                'left' => $lastGroupedLeft,
                'top' => $lastGroupedTop,
                'width' => $lastGroupedRight - $lastGroupedLeft,
                'height' => $lastGroupedHeight
            ];
        }

        return $products;
    }

    private function matchProducts($ocrTexts, $products, $threshold = 95)
    {
        $matches = [];
        $seenMatches = [];

        foreach ($ocrTexts as $text) {
            $processedText = $this->preprocessText($text['text']);

            // Sprawdzenie dokładnego dopasowania (Exact Match)
            foreach ($products as $product) {
                $productText = $product['name'];
                if ($processedText === $productText) {
                    $matches[] = [
                        'OCR Text' => $text['text'],
                        'Matched Product' => $product['name'],
                        'Product ID' => $product['id'],
                        'Similarity' => 100, // Exact Match ma zawsze 100% podobieństwa
                        'Page' => $product['page'] ?? null,
                        'Score' => 100,
                        'Partial Match Score' => 100,
                        'Coordinates' => [
                            'left' => $text['left'],
                            'top' => $text['top'],
                            'width' => $text['width'],
                            'height' => $text['height']
                        ]
                    ];
                    continue 2; // Przerwij dalsze przetwarzanie dla tego tekstu OCR
                }
            }

            // Jeśli nie znaleziono dokładnego dopasowania, przejdź do obliczeń podobieństwa
            foreach ($products as $product) {
                $productText = $product['name'];
                $productWeights = isset($product['weight']) && !is_array($product['weight'])
                    ? json_decode($product['weight'], true)
                    : $product['weight'];

                $partialMatchScore = $this->calculatePartialMatchScore($processedText, $productText, $productWeights);
                $score = $this->calculateWeightedLevenshteinSimilarity($processedText, $productText, $productWeights);
                $maxScore = min(max($score, $partialMatchScore), 100);

                if ($partialMatchScore > $threshold) {
                    $matchKey = $product['id'];
                    if (!isset($seenMatches[$matchKey]) || $seenMatches[$matchKey]['Score'] < $maxScore) {
                        $seenMatches[$matchKey] = [
                            'OCR Text' => $text['text'],
                            'Matched Product' => $product['name'],
                            'Product ID' => $product['id'],
                            'Similarity' => $maxScore,
                            'Page' => $product['page'] ?? null,
                            'Score' => $score,
                            'Partial Match Score' => $partialMatchScore,
                            'Coordinates' => [
                                'left' => $text['left'],
                                'top' => $text['top'],
                                'width' => $text['width'],
                                'height' => $text['height']
                            ]
                        ];
                    }
                }
            }
        }

        foreach ($seenMatches as $match) {
            $matches[] = $match;
        }

        return $matches;
    }

    private function calculatePartialMatchScore($text1, $text2, $weights)
    {
        // Upewnij się, że wagi są zdefiniowane jako tablica
        $weights = $weights ?? [];

        $s1 = explode(' ', $this->normalizeText($this->removePunctuation(mb_strtolower($text1))));
        $s2 = explode(' ', $this->normalizeText($this->removePunctuation(mb_strtolower($text2))));
        $commonWords = array_intersect($s1, $s2);

        $score = 0;
        foreach ($commonWords as $word) {
            $score += $weights[$word] ?? 1;
        }

        $ngrams1 = $this->generateNgrams($text1, 3); // Generowanie n-gramów do trzech słów
        $ngrams2 = $this->generateNgrams($text2, 3);
        $commonNgrams = array_intersect($ngrams1, $ngrams2);

        foreach ($commonNgrams as $ngram) {
            $score += $weights[$ngram] ?? 1;
        }

        $s1Count = count($s1) + count($ngrams1);
        $s2Count = count($s2) + count($ngrams2);
        $countCommon = count($commonWords) + count($commonNgrams);
        $totalPossibleScore = array_sum($weights);

        if ($totalPossibleScore == 0) {
            $totalPossibleScore = 1; // Aby uniknąć dzielenia przez zero
        }

        $finalScore = ($score / $totalPossibleScore) * 100;

        return min($finalScore, 100);
    }

    private function calculateWeightedLevenshteinSimilarity($text1, $text2, $weights)
    {
        $s1 = $this->normalizeText($this->removePunctuation(mb_strtolower($text1)));
        $s2 = $this->normalizeText($this->removePunctuation(mb_strtolower($text2)));

        if ($s1 === $s2) {
            return 100;
        }

        $levenshteinDistance = levenshtein($s1, $s2);
        $maxLength = max(strlen($s1), strlen($s2));

        if ($maxLength == 0) {
            return 100;
        }

        $similarity = (1 - $levenshteinDistance / $maxLength) * 100;

        if (is_null($weights)) {
            return $similarity;
        }

        // Uwzględnij wagi słów
        $weightedSimilarity = 0;
        $totalWeight = 0;
        $ngrams1 = $this->generateNgrams($s1, 3); // Generowanie n-gramów do trzech słów
        $ngrams2 = $this->generateNgrams($s2, 3);

        foreach ($ngrams1 as $ngram) {
            $weightedSimilarity += ($weights[$ngram] ?? 1) * $similarity;
            $totalWeight += $weights[$ngram] ?? 1;
        }

        foreach ($ngrams2 as $ngram) {
            $weightedSimilarity += ($weights[$ngram] ?? 1) * $similarity;
            $totalWeight += $weights[$ngram] ?? 1;
        }

        if ($totalWeight == 0) {
            return $similarity;
        }

        $finalSimilarity = $weightedSimilarity / (2 * $totalWeight);

        return min($finalSimilarity, 100);
    }

    private function generateNgrams($text, $maxN = 3)
    {
        $words = explode(' ', $text);
        $ngrams = [];
        $count = count($words);

        for ($n = 2; $n <= $maxN; $n++) {
            for ($i = 0; $i <= $count - $n; $i++) {
                $ngrams[] = implode(' ', array_slice($words, $i, $n));
            }
        }

        return $ngrams;
    }

    private function normalizeText($text)
    {
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    public function processOcr(Request $request)
    {
        set_time_limit(1200);
        $file = $request->file('pdfFile');
        $fileName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();

        switch ($fileExtension) {
            case 'pdf':
                $fileNameJson = empty($fileName) ? null : str_replace($fileExtension, '', $fileName) . 'json';
                $filePath = storage_path('app/public/pdf/' . $fileName);
                $file->move(storage_path('app/public/pdf/'), $fileName);

                $fileData = fopen($filePath, 'r');
                $client = new Client();
                try {
                    $r = $client->request('POST', 'https://apipro2.ocr.space/parse/image', [
                        'headers' => ['apiKey' => 'PD8Y8E1E9M0X'],
                        'multipart' => [
                            [
                                'name' => 'file',
                                'contents' => $fileData
                            ],
                            [
                                'name' => 'language',
                                'contents' => 'pol'
                            ],
                            [
                                'name' => 'isOverlayRequired',
                                'contents' => 'true'
                            ],
                            [
                                'name' => 'OCREngine',
                                'contents' => '1'
                            ],
                            [
                                'name' => 'scale',
                                'contents' => 'true'
                            ],
                        ]
                    ], ['file' => $fileData]);
                    $response = $r->getBody();
                    $fileJson = fopen(storage_path('app/public/json/' . $fileNameJson), "w");
                    fwrite($fileJson, $response);
                    fclose($fileJson);

                    return $response;

                } catch (Exception $err) {
                    header('HTTP/1.0 403 Forbidden');
                    return $err->getMessage();
                }

            case 'json':
                $requestData = json_decode(file_get_contents($file->getRealPath()), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json(['error' => 'Invalid JSON', 'message' => json_last_error_msg()], 400);
                }

                if (empty($requestData)) {
                    return response()->json(['error' => 'Request data is empty', 'request' => $request->all()], 400);
                }

                if (!isset($requestData['ParsedResults'])) {
                    return response()->json(['error' => 'ParsedResults not found', 'requestData' => $requestData], 400);
                }

                $parsedResults = $requestData['ParsedResults'];

                if (empty($parsedResults)) {
                    return response()->json(['error' => 'ParsedResults is empty', 'requestData' => $requestData], 400);
                }

                $allMatches = [];
                $page = 1;
                foreach ($parsedResults as $parsedResult) {
                    if (!isset($parsedResult['TextOverlay']['Lines'])) {
                        continue;
                    }

                    $ocrResults = $parsedResult['TextOverlay']['Lines'];

                    foreach ($ocrResults as &$line) {
                        if (isset($line['Words'][0])) {
                            $line['left'] = $line['Words'][0]['Left'];
                            $line['top'] = $line['Words'][0]['Top'];
                            $line['width'] = $line['Words'][0]['Width'];
                            $line['height'] = $line['Words'][0]['Height'];
                        } else {
                            $line['left'] = 0;
                            $line['top'] = 0;
                            $line['width'] = 0;
                            $line['height'] = 0;
                        }

                        if (!isset($line['LineText'])) {
                            $line['LineText'] = '';
                        }
                    }

                    $sortedOcrResults = $this->sortOcrResults($ocrResults);
                    $groupedOcrResults = $this->groupLinesByProximity($ocrResults, 20, 0.5, 20, 20, [10, 20]);

                    foreach ($groupedOcrResults as $ocrText) {
                        $processedText = $this->preprocessText($ocrText['text']);
                        $keywords = $this->extractKeywords($processedText);

                        OcrResult::create([
                            'text' => $ocrText['text'],
                            'processed_text' => $processedText,
                            'keywords' => json_encode($keywords, JSON_UNESCAPED_UNICODE),
                            'page' => $page,
                            'left' => $ocrText['left'],
                            'top' => $ocrText['top'],
                            'width' => $ocrText['width'],
                            'height' => $ocrText['height']
                        ]);
                    }

                    $page++;
                }

                return response()->json(['message' => 'OCR results saved successfully']);
                break;
        }
    }

    private function extractKeywords($text)
    {
        $words = explode(' ', $text);
        $keywords = [];
        foreach ($words as $word) {
            if (!in_array($word, $this->stopwords)) {
                $keywords[] = $word;
            }
        }
        return $keywords;
    }

    public function compareOcrResults()
    {
        set_time_limit(600);
        $ocrResults = OcrResult::all();
        $products = DB::table('searcher')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $this->preprocessText($product->name_product),
                'weight' => $product->weight ? json_decode($product->weight, true) : null,
            ];
        })->toArray();

        $allMatches = [];
        $threshold = 95; // Próg podobieństwa

        foreach ($ocrResults as $ocrResult) {
            $ocrKeywords = json_decode($ocrResult->keywords, true);

            $filteredProducts = array_filter($products, function ($product) use ($ocrKeywords) {
                foreach ($ocrKeywords as $keyword) {
                    if (stripos($product['name'], $keyword) !== false) {
                        return true;
                    }
                }
                return false;
            });

            if (!empty($filteredProducts)) {
                $matches = $this->matchProducts([[
                    'text' => $ocrResult->processed_text,
                    'left' => $ocrResult->left,
                    'top' => $ocrResult->top,
                    'width' => $ocrResult->width,
                    'height' => $ocrResult->height
                ]], $filteredProducts, $threshold);
                foreach ($matches as &$match) {
                    $match['Page'] = $ocrResult->page ?? null;
                }
                $allMatches = array_merge($allMatches, $matches);
            }
        }

        $resultJson = [];
        foreach ($allMatches as $match) {
            $page = $match['Page'];
            $productId = $match['Product ID'];
            if (!isset($resultJson[$page])) {
                $resultJson[$page] = [];
            }
            $resultJson[$page][] = $productId;
        }

        $resultFile = storage_path('app/public/json/results10.json');
        file_put_contents($resultFile, json_encode($resultJson, JSON_PRETTY_PRINT));

        return response()->json($allMatches);
    }
}
