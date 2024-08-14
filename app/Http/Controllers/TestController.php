<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lemat;
use App\Models\Stopword;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    private $lemmas;
    private $stopwords;

    private $ocrResults;




    public function __construct()
    {
        $lemmasPreperation = Lemat::all();
        $stopwordsPreperation = Stopword::all();
        $this->lemmas = $lemmasPreperation->pluck('lemma', 'word')->toArray();
        $this->stopwords = $stopwordsPreperation->pluck('word')->toArray();
        $this->ocrResults = json_decode(file_get_contents(storage_path('app\public\json\_dino_29_www_1.json')), true);

    }

    private function removeStopwords($text)
    {
        $words = explode(' ', $text);
        $filteredWords = array_filter($words, function ($word) {
            return !in_array(mb_strtolower($word), $this->stopwords);
        });
        return implode(' ', $filteredWords);
    }

    private function lemmatize($word)
    {

            $parts = explode(' ', $word);
            $lemmatizedParts = array_map(function ($part) {
                return $this->lemmas[$part] ?? $part;
            }, $parts);
            return implode(' ', $lemmatizedParts);

    }


    public function weightWord()
    {
        set_time_limit(3000);
        $products = DB::table('searcher')->get();
        for ($i = 10520; $i <= 10570; $i++) {
            $productName = mb_strtolower($products[$i]->name_product);
            $productName = $this->removeStopwords($productName);
            $lemmaName = $this->lemmatize($productName);
            $words = explode(' ', $lemmaName);
            $count = count($words);
            if ($count > 1) {
                $ngrams = $this->generateNgrams($lemmaName);
                $response = json_decode($this->lematyzujSlowo($productName, $lemmaName), true);
                $ngramsWeight = $this->assignWeightsToNgrams($ngrams, $response);
                foreach ($ngramsWeight as $key => $value) {
                    $response[$key] = $value;
                }

            } else {
                $response = [];
                $response[$lemmaName] = 10;


            }
            DB::table('searcher')
                ->where('id', $products[$i]->id)
                ->update(['weight' => json_encode($response, JSON_UNESCAPED_UNICODE)]);
        }
        return 'done';
    }

    public  function lematyzujSlowo($lemma, $words) {

        //set_time_limit(3000);
        $apiKey = env('CHATGPT_API_KEY');
        $client = new Client();

        // $words = Lemat::whereBetween('id', [5001,7329])->get();

        //foreach ($words as $word) {
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Nadaj wagi dla słów w opisie produktu '$lemma' w skali 10, a suma tych wag ma wynosić 10. Tutaj nazwa produktu po lematyzaji $words. Odpowiedz w formacie JSON, gdzie kluczami będą słowa po lematyzaji, a wartościami będą ich wagi. Proszę uwzględnić odpowiednie znaczenie każdego słowa w kontekście tego produktu nie dodając żadnego, którego nie występuje w nazwie.
                                        Ważne jest też aby odpowiedzią dane w formacie json bez żadnych dodatków ponieważ Twoja odpowiedź jest dalej przetwarzana",
                    ],
                ],
                'temperature' => 1,
                'max_tokens' => 256,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $lemat = $data['choices'][0]['message']['content'];
        //$word->lemma = $lemat;
        // $word->save();

        // }

        return $lemat;
    }

    public function assignWeightsToNgrams($ngrams, $wordWeights)
    {
        $ngramWeights = [];

        foreach ($ngrams as $ngram) {
            $ngramWords = explode(' ', $ngram);
            $ngramWeight = 0;

            foreach ($ngramWords as $word) {
                $ngramWeight += $wordWeights[$word] ?? 1; // Domyślna waga 1, jeśli nie ma wagi dla słowa
            }

            $ngramWeights[$ngram] = $ngramWeight;
        }

        return $ngramWeights;
    }

    public function generateNgrams($text, $maxN = 3)
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

    public function index()
    {
        //set_time_limit(120);

        $stopwords = json_decode(file_get_contents(storage_path('stopwords.json')), true);

        foreach ($stopwords as $stopword) {
            DB::table('stopwords')->insert([
                'word' => $stopword
                ]);
        }
        return response()->json('done');
    }

public function download()
{
    set_time_limit(300);
// URL to the JSON data
$jsonUrl = 'https://fast.wistia.com/embed/medias/tynnxc4l3p.json';
$name = '11. Przykład Indywidualizacji - Artefakt';

// Fetch the JSON data
$jsonData = file_get_contents($jsonUrl);
$data = json_decode($jsonData, true);

// Check if JSON data was read successfully
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Failed to read JSON data: ' . json_last_error_msg());
}

// Find the best resolution MP4 URL
$bestResolutionUrl = '';
foreach ($data['media']['assets'] as $asset) {
    if ($asset['type'] === 'original') {
        $bestResolutionUrl = $asset['url'];
        break;
    }
}

// Check if the URL was found
if (empty($bestResolutionUrl)) {
    die('Best resolution MP4 URL not found.');
}

// Destination path where the file will be saved
$outputPath = $name.'.mp4';

// Open a file in write-binary mode
$file = fopen($outputPath, 'wb');

// Initialize a cURL session
$ch = curl_init($bestResolutionUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FILE, $file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Execute the cURL session
curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Download complete: ' . $outputPath;
}

// Close the cURL session and the file
curl_close($ch);
fclose($file);


}

public function carrefour()
{

    $url = "https://yep.auchan.com/api/corp/cms/v4/pl/template/tracts/94821?api-key=8039b23d-30f2-456f-a9b9-4100344de25d&lang=pl";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $response;
    }

    curl_close($ch);
}


public function tchibo()
{
    set_time_limit(300);
    for ($i = 739500; $i <= 739500 + 600; $i++) {

        $url = "https://catalogue.tchibo.pl/frontend/getappcatalogdata.do?path=img&f=catcover.jpg&catalogid=$i&catalogVersion=1&upperHalfCover=false";

        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


        // Execute the cURL session
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch) . PHP_EOL;
        } else {
            // Get the HTTP response status code
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Process the response based on the status code
            if ($http_code == 200) {
                echo "Catalog ID $i exists and is accessible." . PHP_EOL;
                $file_path = "catalog_$i.jpg";
                file_put_contents($file_path, $response);
                echo "File saved as $file_path" . PHP_EOL;
            } else {
                echo "Catalog ID $i is not accessible. HTTP Status Code: $http_code" . PHP_EOL;
            }
        }

        // Close the cURL session
        curl_close($ch);


    }


}

    public function groupLinesByProximity($baseThreshold = 20, $heightMultiplier = 0.5, $leftThreshold = 20, $rightThreshold = 20, $fontHeightRange = [10, 20]) {
        $ocrResults = $this->ocrResults['ParsedResults'][0]['TextOverlay']['Lines'];


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
                        'width' => 50,
                        'height' => 50
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
                'width' => 50,
                'height' => 50
            ];
        }

        return $products;
    }

    public function aldi()
    {
        set_time_limit(3200);
        $data = json_decode(file_get_contents(storage_path('app\public\json\combinations_with_repeats_extended_with_t.json')), true);
        $i = 1;
        foreach ($data['combinations'] as $combination) {
            $url = 'https://gazetki.aldi.pl/2024/kw34/24k34g03'.$combination.'//GetPDF.ashx';
            //$url = 'https://gazetki.aldi.pl/2024/kw33/24k33g01cdga//GetPDF.ashx';
            $ch = curl_init($url);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


            // Execute the cURL session
            $response = curl_exec($ch);

            // Check for errors
            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch) . PHP_EOL;
            } else {
                // Get the HTTP response status code
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                // Process the response based on the status code
                if ($http_code == 200) {
                    echo "<span style='color: green'>$i. Catalog ID $url exists and is accessible.</span>" . '<br/>';
                    curl_close($ch);

                    break;

                } else {
                    echo "<span style='color: red'>$i. Catalog ID $url is not accessible. HTTP Status Code: $http_code </span>" . '<br/>';
                }
            }

            // Close the cURL session
            curl_close($ch);
            $i++;
           // break;
        }
    }
}






