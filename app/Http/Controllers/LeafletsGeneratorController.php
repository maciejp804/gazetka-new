<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;


class LeafletsGeneratorController extends Controller
{

    public function generator(Request $request)
    {

        if ($_POST['productId'] !== ''){

            switch ($_POST['store']) {
                case 'rtveuroagd':

                    $url_api = "https://www.euro.com.pl/rest/api/products/".$_POST['productId']."/";

                    $scrapedContent = json_decode(getScrapedContent($url_api), true);

                    $data = $this->findTag($scrapedContent, $_POST['store']);
                    $urlOffer = $data['offerUrl'];
                    break;
                case 'lidl':

                    $url_api = "https://www.lidl.pl/p/api/gridboxes/PL/pl?erpNumbers=".$_POST['productId'];
                    $scrapedContent = json_decode(getScrapedContent($url_api), true);
                    $data = $this->findTag($scrapedContent, $_POST['store']);
                    $urlOffer = $data['offerUrl'];
                    break;
            }

        } else {

            // Pobierz zawartość strony za pomocą Scraping API
            $scrapedContent = getScrapedContent($_POST['url']);

            // Przykładowa logika przetwarzania:
            $data = $this->findTag($scrapedContent, $_POST['store']);

            $urlOffer = $_POST['url'];
        }
        $file = $request->file('photo');

        if ($file === null) {
            // Usuwanie tła białego i wstawienie przeźroczystego
            $image = $this->bgRemover($data['imageUrl']);
        } else {
            // Zapisz plik do katalogu na serwerze
            $fileName = $file->getClientOriginalName(); // Pobierz oryginalną nazwę pliku
            $file->move(public_path('uploads'), $fileName); // Zapisz plik w katalogu "uploads" w katalogu publicznym
            $image = 'uploads/'.$fileName;

        }

        // Tworzenie pliku z regionem
        $this->region($_POST['store'], $urlOffer, $_POST['filename']);

        // Zwróć widok lub odpowiedź HTTP w zależności od potrzeb


        return view('components.'.$_POST['store'].'.one',compact('data', 'image'));
    }


    public function findTag($html, $store)
    {

        if ($store === 'rtveuroagd') {

            if ($html['activeProductDetails']['voucherDetails'] === null){
                $promoCode = null;
            } else {
                $promoCode = $html['activeProductDetails']['voucherDetails']['voucherCode'];
            }

            $h1Tag = $html['frontName'];
            $prices = [];
            if ($html['activeProductDetails']['voucherDetails'] !== null){
                $price = $html['activeProductDetails']['prices']['voucherDiscountedPrice'];
                $prices[] = ['price' => $html['activeProductDetails']['prices']['lowestPrice']['price'], 'label' => 'Najniższa cena z 30 dni przed obniżką'];
                $prices[] = ['price' => $html['activeProductDetails']['prices']['mainPrice'], 'label' => 'Cena bez kodu:'];
            } elseif ($html['activeProductDetails']['prices']['promotionalPrice'] !== null){
                $price = $html['activeProductDetails']['prices']['promotionalPrice']['price'];
                $prices[] = ['price' => $html['activeProductDetails']['prices']['lowestPrice']['price'], 'label' => 'Najniższa cena z 30 dni przed obniżką'];
                $prices[] = ['price' => $html['activeProductDetails']['prices']['mainPrice'], 'label' => 'Cena bezpośrednio przed obniżką'];
            } else {
                $price = $html['activeProductDetails']['prices']['mainPrice'];
                $prices = null;
            }

            $price = explode('.', $price);

            $priceWhole = $price[0];
            if (!isset($price[1])){
                $priceRest = '00';
            } else {
                $priceRest = $price[1];
            }

            foreach ($html['technicalAttributes'][0]['attributes'] as $item)
            {
                $attributes[] = ['name' => $item['name'], 'values' => $item['value'][0]['name']];
            }

            $imageUrl = $html['images'][2]['url'];
            $offerUrl = $html['seo']['canonical'];

        } elseif ($store === 'media-expert') {
            // W tym przypadku, przykładowo, możemy użyć Symfony DomCrawler do znajdowania znacznika <h1>
            $crawler = new Crawler($html);

            $h1Tag = $crawler->filter('h1')->text();

            // Filtrowanie elementów HTML za pomocą klasy CSS
            $dynamicContent = $crawler->filter('div[class*="dynamic-content"]');

            $elements = $crawler->filter('.promo-code');
            if (count($elements) > 0) {
                $promoCode = $elements->first()->text();
            } else {
                $promoCode = null;
            }

            $elementsWhole = $dynamicContent->filter('.whole');
            $elementsRest = $dynamicContent->filter('.rest');

// Inicjalizuj zmienne na najwyższą cenę i odpowiadający jej indeks
            $priceWholeIndex = null;
            $priceWhole = 0;

// Iteruj przez wszystkie elementy o klasie '.whole' i znajdź najwyższą cenę oraz jej indeks
            $elementsWhole->each(function ($element, $index) use (&$priceWholeIndex, &$priceWhole) {
                $price = $element->text();
                if ($price > $priceWhole) {
                    $priceWhole = $price;
                    $priceWholeIndex = $index;
                }
            });

// Jeśli znaleziono najwyższą cenę, pobierz odpowiadający jej priceRest
            if ($priceWholeIndex !== null) {
                $priceRestElement = $elementsRest->eq($priceWholeIndex); // Pobierz element priceRest z tym samym indeksem co priceWhole
                $priceRest = $priceRestElement->text(); // Pobierz wartość priceRest
            }

            $elements = $dynamicContent->filter('.info-box');

            $prices = [];
            $existingPrices = [];

            $elePrices = $elements->filter('.price');
            $eleLabels = $elements->filter('.label');

            // Sprawdzenie, czy liczba elementów z nazwami ceną i etykietą jest taka sama
            if ($elePrices->count() === $eleLabels->count()) {
                $elePrices->each(function ($elePrice, $i) use ($eleLabels, &$prices, &$existingPrices) {
                    $price = $elePrice->text();
                    $label = $eleLabels->eq($i)->text(); // Pobranie wartości dla tego samego indeksu

                    // Sprawdzenie, czy atrybut już istnieje w tablicy
                    if (!isset($existingPrices[$price])) {
                        $label = trim(str_replace('Prezentowana najniższa cena z 30 dni przed obniżką dla kanału sprzedaży mediaexpert.pl.', '', $label));
                        // Jeśli nie istnieje, dodaj go do tablicy atrybutów i oznacz jako dodany w tablicy istniejących atrybutów
                        $prices[] = ['price' => $price, 'label' => $label];
                        $existingPrices[$price] = true;
                    }
                });
            }


            // Przetwarzanie atrybutów
            $attributes = [];
            $existingAttributes = []; // Tablica do przechowywania już dodanych atrybutów

            $elements = $crawler->filter('.attribute-name');
            $elementsValues = $crawler->filter('.attribute-values');

            // Sprawdzenie, czy liczba elementów z nazwami atrybutów i wartościami jest taka sama
            if ($elements->count() === $elementsValues->count()) {
                $elements->each(function ($element, $i) use ($elementsValues, &$attributes, &$existingAttributes) {
                    if (count($attributes) >= 10) {
                        return false; // Przerwij iterację, jeśli liczba atrybutów przekroczyła 10
                    }

                    $name = $element->text();
                    $value = $elementsValues->eq($i)->text(); // Pobranie wartości dla tego samego indeksu

                    // Sprawdzenie, czy atrybut już istnieje w tablicy
                    if (!isset($existingAttributes[$name])) {
                        // Jeśli nie istnieje, dodaj go do tablicy atrybutów i oznacz jako dodany w tablicy istniejących atrybutów
                        $attributes[] = ['name' => $name, 'values' => $value];
                        $existingAttributes[$name] = true;
                    } else {
                        // Jeśli atrybut już istnieje, dodaj tylko wartość do istniejącej pary
                        $existingIndex = array_search($name, array_column($attributes, 'name'));
                        $attributes[$existingIndex]['values'] .= ', ' . $value;
                    }
                });
            }


            $divsWithImages = $crawler->filter('div[src*="https://prod-api.mediaexpert.pl/api/images/gallery_500_500/thumbnails/images"]')->first();
            $imageUrl = $divsWithImages->attr('src');
            $offerUrl = null;
        } elseif ( $store === 'home-you'){
            $promoCode = null;

            $crawler = new Crawler($html);

            $h1Tag = $crawler->filter('h1')->text();

            $images = $crawler->filter('img');

            $imageUrl = '';
            // Przetwórz znalezione znaczniki <img>
            $images->each(function ($node) use (&$imageUrl) {
                // Pobierz atrybut 'src' każdego znacznika <img>
                $src = $node->attr('src');
                // Zrób coś z atrybutem 'src' (np. wyświetl go lub przetwórz)

                if ((str_contains($src, 'https://media.home-you.com/catalog/product/'))) {

                    $imageUrl = preg_split('/\?/', $src)[0];
                    return false; // Przerwij funkcję each()

                }
            });

            $priceElement = $crawler->filterXPath('//div[@data-cy="undefined_price"]');

               // Sprawdź, czy znaleziono element
            if ($priceElement->count() > 0) {
                // Pobierz tekst z elementu, usuń znaki nieliterowe i przecinki, a następnie zamień kropkę na przecinek
                $priceText = $priceElement->text();
                $priceText = str_replace([' ', ',', 'zł'], ['', '.', ''], $priceText);
                $price = preg_split('/\./', $priceText);
                $priceWhole = $price[0];
                if (!isset($price[1])){
                    $priceRest = '00';
                } else {
                    $priceRest = $price[1];
                }

            } else {
                $priceWhole = '00';
                $priceRest = '00';
            }
            $prices = [];
            $priceElement = $crawler->filterXPath('//div[@data-cy="undefined_price_old"]');

            if ($priceElement->count() > 0) {
                // Pobierz tekst z elementu, usuń znaki nieliterowe i przecinki, a następnie zamień kropkę na przecinek
                $priceText = $priceElement->text();
                $priceText = str_replace([' ', ',', 'zł'], ['', '.', ''], $priceText);
                $prices[] = ['price' => $priceText, 'label' => 'Cena regularna'];
                }
            $priceElement = $crawler->filterXPath('//span[@data-cy="product_price_average"]');
            if ($priceElement->count() > 0) {
                // Pobierz tekst z elementu, usuń znaki nieliterowe i przecinki, a następnie zamień kropkę na przecinek
                $priceText = $priceElement->text();
                $priceText = str_replace([' ', ',', 'zł'], ['', '.', ''], $priceText);
                $prices[] = ['price' => $priceText, 'label' => 'Najniższa cena z 30 dni przed obniżką'];
            }

            $attributes = [];


            $offerUrl = null;
        } elseif ( $store === 'lidl') {

            $promoCode = null;
            $h1Tag = $html[0]['fullTitle'];

            $priceText = str_replace([' ', ',', 'zł'], ['', '.', ''], $html[0]['price']['price']);
            $price = preg_split('/\./', $priceText);
            $priceWhole = $price[0];
            if (!isset($price[1])){
                $priceRest = '00';
            } else {

                $priceRest = str_pad($price[1], 2, '0', STR_PAD_RIGHT);

            }
            $prices = [];
            if(isset($html[0]['price']['oldPrice'])) {
                $prices[] = ['price' => $html[0]['price']['oldPrice'], 'label' => '* Najniższa cena z 30 dni przed obniżką'];
            }
            // Przetwarzanie atrybutów
            $attributes = [];
            if(isset($html[0]['keyfacts']['description'])){
                $attributesText = str_replace(['#0050aa'], ['white'], $html[0]['keyfacts']['description']); // Tablica do przechowywania już dodanych atrybutów
                $attributes = $attributesText;
            } else {
                $attributesText = str_replace(['#0050aa'], ['white'], $html[0]['keyfacts']['supplementalDescription']); // Tablica do przechowywania już dodanych atrybutów
                $attributes = $attributesText;
            }

            //dd($existingAttributes);

            $imageUrl = $html[0]['image'];
            $offerUrl = 'https://www.lidl.pl'.$html[0]['canonicalPath'];

        }


// Tworzenie struktury danych do zwrócenia
        $dataArray = [
            'promoCode' => $promoCode,
            'h1Tag' => $h1Tag,
            'priceWhole' => $priceWhole,
            'priceRest' => $priceRest,
            'attributes' => $attributes,
            'prices' => $prices,
            'imageUrl' => $imageUrl,
            'offerUrl' => $offerUrl,

        ];

        return $dataArray;


    }


    public function bgRemover($imageUrl)
    {
        $client = new Client();
        $res = $client->post('https://api.remove.bg/v1.0/removebg', [
            'multipart' => [
                [
                    'name'     => 'image_file',
                    'contents' => fopen($imageUrl, 'r')
                ],
                [
                    'name'     => 'size',
                    'contents' => 'auto'
                ]
            ],
            'headers' => [
                'X-Api-Key' => 'jnFLRVmii2udmB3AGN9MWQZo'
            ]
        ]);

        $fp = fopen("assets/image/templates/mediaexpert-1.png", "wb");
        fwrite($fp, $res->getBody());
        fclose($fp);

        return 'assets/image/templates/mediaexpert-1.png';
    }

    public function region($store, $url, $filename = 2, $affUrl = null, $x = 10, $y = 10, $width = 440, $height = 580, $class = 'link')
    {
        if ($affUrl === null) {
            if($store === 'media-expert') {
                $affUrl = 'https://clkpl.tradedoubler.com/click?p(237638)a(2387415)g(21260760)url(' . $url . ')';
            }
            if($store === 'rtveuroagd') {
                $affUrl = 'https://clkpl.tradedoubler.com/click?p(118512)a(2387415)g(18030892)url(' . $url . ')';
            }
            if($store === 'home-you') {
                $affUrl = 'https://clkpl.tradedoubler.com/click?p(330299)a(2387415)g(25241040)url(' . $url . ')';
            }
            if($store === 'lidl') {
                $affUrl = 'https://clkpl.tradedoubler.com/click?p(298327)a(2387415)g(24558098)url(' . $url . ')';
            }
        }
        $fileDataArray = array();
        $dataArray = array(
            'x' => $x,
            'y' => $y,
            'width' => $width,
            'height' => $height,
            'class'=> $class,
            'data' => array(
                'url' => $affUrl)
        );
        $fileDataArray[] = $dataArray;
        $fileDataJson = json_encode($fileDataArray);
        $fp = fopen('assets/regions/'.$store.'/'.$filename.'.json', "wb");
        fputs($fp, $fileDataJson);
        fclose($fp);

    }

}
