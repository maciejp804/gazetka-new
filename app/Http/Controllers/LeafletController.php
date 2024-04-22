<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\LeafletCategory;
use App\Models\Place;
use App\Models\Product;
use App\Models\SiteType;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use function Webmozart\Assert\Tests\StaticAnalysis\email;

class LeafletController extends Controller
{
    const SITE = 'leaflet-index';
    public function index($slug, $leaflet_category_id)
    {
        // Pobieramy wszystkie kategorie gazetek
        $leafletCategories = LeafletCategory::all();

        //Pobieranie kategorii gazetki
        $leafletCategory = $leafletCategories->where('slug','=',$slug)
            ->where('category_index','=',$leaflet_category_id);

        //Sprawdzanie czy dana kategoria istnieje, jeżeli tak to prztwarzanie dalej
        if (count($leafletCategory) === 0) {
            abort(404);
        }

        //Pobieramy wszystkie miejscowości
        $placesAll = Place::with('voivodeship')->get();

        //Wyszukujemy slug miejscowości lub najbliższej miejscowości
        $place = localSlug($placesAll);

        //Pobranie wszystkich gazetek ze sklepami przed datą zakończenia oferty
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'));

        if ($place) {

            // ID miejscowości  oraz rozszerzenie tytułu
            $placeId = $place->id;
            $title_extension = ' w '.$place->name_locative;

            //Slug miejscowości
            $placeSlug = $place->slug;

            //IDs lokalizacji w promieniu N km default 20 km
            $locationsWithinNkm = locationsInZone($placesAll, $place);

            //Pobranie wszystkich sklepów z kategoriami i miejscowościami
            $allStores = Store::with(['category','places'])->get();
            $allStoresWitIds = $allStores->pluck('id');

            //IDs sklepów w promieniu N km default 20 km
            $storesLocation = storesLocation($allStores, $locationsWithinNkm);

            //Pobranie sklepów z ofertą online
            $onlineStores = $allStores->where('is_online', 1);

            //IDs dla wszystkich sklepów online
            $onlineStoresIds = $onlineStores->pluck('id');

            // Sklepy online z wykluczeniem sklepów w promieniu 20km
            $onlineStoresNotIn20km = $onlineStores->whereNotIn('id', $storesLocation->storesWithinNkmIds);


            //Pobranie gazetek w zależności od kategorii
            $id = $leafletCategory[$leaflet_category_id]->id;

            if ($leaflet_category_id != 0){

                $productsInCategory = Product::whereHas('leaflet_categories', function ($query) use ($id) {
                    $query->where('category_id', $id);})->pluck('id');

                $leaflets = $leaflets->whereHas('products', function ($query) use ($productsInCategory){
                    $query->whereIn('product_id', $productsInCategory);
                });
            }

            $leaflets = $leaflets->get();

            $leafletsInLocations = $leaflets->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');

            $leafletsOnline = $leaflets->whereIn('store_id', $onlineStoresIds)->sortByDesc('created_at');

            $leafletsAll = $leafletsInLocations->concat($leafletsOnline);

        } else {
            $placeId = null;

            $placeSlug = '';

            //Deklaracja rozszrzenia tytułu z pustym ciągiem znaków
            $title_extension = '';

            $leafletsAll = $leaflets->get();
        }


        //Pobranie produktów 15szt.
        $products = Product::all()->take(15);

        // Pobieranie i przetwarzanie opisów strony
        $descCollection = siteValidator(self::SITE.'-'.$leaflet_category_id, $placeId);

        if($place){
            $h1Title = 'Gazetki <b>promocyjne</b>'.$title_extension;
            $leafletsPromoTitle = 'Polecane gazetki promocyjne'.$title_extension;
            $storesTitle =  'Sieci handlowe'.$title_extension;
            $pageDescription = $descCollection->descriptions->where('place','=','bottom');
            $placeDescription = $descCollection->descriptions->where('place', '=','middle')->first();

            if($descCollection->meta->isEmpty()) {
                $metaTitle = 'Gazetki promocyjne' . $title_extension . ' • aktualne oferty • GazetkaPromocyjna.com.pl';
                $metaDescription = 'Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych' . $title_extension . ' • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu';
            } else {
                $metaTitle = $descCollection->meta->first()->meta_title;
                $metaDescription = $descCollection->meta->first()->meta_description;
            }
        } else {
            $h1Title = 'Gazetki <b>promocyjne</b>';
            $metaTitle = 'Gazetki promocyjne • aktualne oferty • GazetkaPromocyjna.com.pl';
            $metaDescription = 'Aktualne gazetki promocyjne, przeceny i wyprzedaże • GazetkaPromocyjna.com.pl – wszystkie promocje w jednym miejscu';
            $leafletsPromoTitle = 'Polecane gazetki promocyjne';
            $storesTitle =  'Sieci handlowe';
            $pageDescription = $descCollection->descriptions->where('place','=','bottom');
            $placeDescription = null;


        }


        /*$descriptions = siteValidator($site, $placeId);

        if($descriptions->meta->isEmpty()){
            $meta_title = 'Uwaga przykładowy tytuł';
            $meta_description = 'Uwaga przykładowy opis';
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }
        $page_descriptions = $descriptions->descriptions->where('place','=','bottom');
        $place_descriptions = $descriptions->descriptions->where('place', 'middle')->first();*/

        return view('main.leaflets', data: [

            //meta
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,

            //h1 title
            'h1Title' => $h1Title,

            //leaflets
            'leaflets' => $leafletsAll,
            'leafletCategories' => $leafletCategories,
            'leafletCategory' => $leafletCategory[$leaflet_category_id],
            'leafletCategoryPath' => 'gazetki-promocyjne',

            //Products
            'products' => $products,

            //Page descriptions
            'pageDescription' => $pageDescription,
            'descCollection' => $descCollection,

            'place' => $place,
            'placeSlug' => $placeSlug,

            // Parametr to redirect url
            'route' => Route::currentRouteName(),
        ]);
    }


    public function indexLocalisation($slug, $leaflet_category_id, $place_slug)
    {
        //Pobranie wszystkich miejscowości z województwami
        $placesAll = Place::with('voivodeship')->get();

        //Wysukanie miejscowości po slug
        $place = $placesAll->where('slug', $place_slug)->first();

        //Sprawdzenie czy dana miejscowość istnieje a jeżeli nie to błąd 404
        if($place === null){
            abort(404);
        }

        //Nazwa szablonu strony w bazie danych opisów
        $site = self::SITE.'-'.$leaflet_category_id;

        //Przypisanie ID miejscowości do zmiennej placeId
        $placeId = $place->id;
        $placeSlug = $place->slug;

        //Wyszukujemy slug miejscowości lub najbliżej miejscowości na podstawie cookies
        $closestPlace = localSlug($placesAll);

        if (isset($closestPlace)){
            if($place->slug !== $closestPlace->slug){
                setCookies($place);
            }
        }

        //Pobranie wszystkich sklepów z kategoriami i miejscowościami
        $allStores = Store::with(['category','places'])->get();
        $allStoresWitIds = $allStores->pluck('id');

        //Pobranie sklepów z ofertą online
        $onlineStores = $allStores->where('is_online', 1);

        //ID dla wszystkich sklepów online
        $onlineStoresIds = $onlineStores->pluck('id');


        // Dodajemy odległość do każdego elementu kolekcji $placesAll
        $placesAll->each(function ($location) use ($place) {
            $distance = calculateDistance([$location->lat, $location->lng], [$place->lat, $place->lng]);
            $location->distance = $distance; // Dodajemy odległość do każdego miejsca w kolekcji
        });

        // Filtrujemy kolekcję $placesAll, aby uzyskać tylko te lokalizacje oddalone o 20 km od danej lokalizacji
        $locationsWithin20km = $placesAll->filter(function ($location) {
            return $location->distance <= 20; // Zwracamy true tylko dla miejsc oddalonych o 20 km
        });

        // Sortujemy po odległości
        $locationsWithin20km = $locationsWithin20km->sortBy('distance');

        // Pobieramy identyfikatory miejscowości oddalonych o 20 km od danej lokalizacji
        $locationsWithin20kmIds = $locationsWithin20km->pluck('id');

        //Pobranie sklepów, które są w miejscowościach w promieniu 20km
        $storesWithin20km = $allStores->filter(function ($store) use ($locationsWithin20kmIds) {
            return $store->places->pluck('id')->intersect($locationsWithin20kmIds)->isNotEmpty();

        });

        //ID sklepów w promieniu 20km
        $storesWithin20kmIds = $storesWithin20km->pluck('id');

        // Dopisanie rozszerzenia tytułu
        $title_extension = 'w ' . $place->name_locative;

        // Pobieramy wszystkie kategorie gazetek
        $leafletCategories = LeafletCategory::all();

        //Pobieranie kategorii gazetki
        $leafletCategory = $leafletCategories->where('slug','=',$slug)
            ->where('category_index','=',$leaflet_category_id);

        //Pobranie gazetek w zależności od kategorii i miejscowości
        if (count($leafletCategory) > 0)  {

            $id = $leafletCategory[$leaflet_category_id]->id;
            //Pobranie wszystkich gazetek ze sklepami przed datą zakończenia oferty
            $leaflets = Leaflet::with('store', 'products')
                ->where('end_offer_date', '>=', date('Y-m-d'));

            if ($leaflet_category_id != 0) {

                $productsInCategory = Product::whereHas('leaflet_categories', function ($query) use ($id) {
                    $query->where('category_id', $id);
                })->pluck('id');
                $leaflets = $leaflets->whereHas('products', function ($query) use ($productsInCategory) {
                    $query->whereIn('product_id', $productsInCategory);
                });

            }


            $leaflets = $leaflets->get();

            $leafletsWithin20km = $leaflets->whereIn('store_id', $storesWithin20kmIds);
            $leafletsWithin20kmIds = $leafletsWithin20km->pluck('id');
            $onlineLeaflets = $leaflets->whereIn('store_id', $onlineStoresIds)->whereNotIn('id', $leafletsWithin20kmIds);
            $leaflets = $leafletsWithin20km->concat($onlineLeaflets);

        } else {
            abort(404);
        }

        //Pobranie produktów 15szt.
        $products = Product::all()->take(15);

        // Pobieramy i przetwarzamy opisy strony
        $descCollection = siteValidator(self::SITE.'-'.$leaflet_category_id, $placeId);

        $h1Title = 'Gazetki <b>promocyjne</b>'.$title_extension;
        $leafletsPromoTitle = 'Polecane gazetki promocyjne'.$title_extension;
        $storesTitle =  'Sieci handlowe'.$title_extension;
        $pageDescription = $descCollection->descriptions->where('place','=','bottom');
        $placeDescription = $descCollection->descriptions->where('place', '=','middle')->first();
        if($descCollection->meta->isEmpty()) {
            $metaTitle = 'Gazetki promocyjne' . $title_extension . ' • aktualne oferty • GazetkaPromocyjna.com.pl';
            $metaDescription = 'Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych' . $title_extension . ' • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu';
        } else {
            $metaTitle = $descCollection->meta->first()->meta_title;
            $metaDescription = $descCollection->meta->first()->meta_description;
        }



        return view('main.leaflets', data: [
            //meta
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,

            //h1 title
            'h1Title' => $h1Title,

            'leaflets' => $leaflets,
            'leafletCategories' => $leafletCategories,
            'leafletCategory' => $leafletCategory[$leaflet_category_id],
            'leafletCategoryPath' => 'gazetki-promocyjne',

            'products' => $products,

            //Place
            'placeDescription' => $placeDescription,
            'place' => $place, //use in: markers

            //Page descriptions
            'pageDescription' => $pageDescription,
            'descCollection' => $descCollection,

            'placeSlug' => $placeSlug,
            'route' => Route::currentRouteName(),
        ]);
    }
}
