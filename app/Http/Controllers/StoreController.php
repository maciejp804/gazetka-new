<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\LeafletCategory;
use App\Models\Map;
use App\Models\Place;
use App\Models\Product;
use App\Models\SiteDescription;
use App\Models\SiteType;
use App\Models\Store;
use App\Models\Voucher;
use DeepCopyTest\Matcher\Y;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use function Pest\Laravel\withHeader;
use function Webmozart\Assert\Tests\StaticAnalysis\stringWillNotBeRedundantIfAssertingAndNotUsingEither;

class StoreController extends Controller
{

const SITE = 'main-index';

    public function index()
    {
        //Ilość gazetek w sliderze promowane
        $promoNumber = 15;

        //Nazwa szablonu strony w bazie danych opisów oraz deklaracja rozszrzenia tytułu z pustym ciągiem znaków
        $title_extension = '';

        //Pobieramy wszystkie miejscowości
        $placesAll = Place::with('voivodeship')->get();

        //Wyszukujemy slug miejscowości lub najbliżej miejscowości
        $place = localSlug($placesAll);

        // Pobieramy wszystkie sklepy z ich kategoriami i miejscowościami
        $allStores = Store::with(['category','places'])->get();
        $allStoresWitIds = $allStores->pluck('id');

        //Pobranie sklepów z ofertą online
        $onlineStores = $allStores->where('is_online', 1);

        //ID dla wszystkich sklepów online
        $onlineStoresIds = $onlineStores->pluck('id');

        //Pobranie szystkich kategorii sklepów
        $categoryStores= CategoryStore::all();

        //Pobranie wszystkich gazetek ze sklepami przed datą zakończenia oferty
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();

        // Pobieramy wszystki miejscowości filtrujemy i limitujemy do 35
        $places = $placesAll->sortByDesc('population');

        //Jeżeli istnieje zmienna $closestPlace (najbliższa miejscowość)
        if ($place) {

            // Ustawiamy $placeId oraz rozszerzenie tytułu
            $placeId = $place->id;
            $title_extension = ' w '.$place->name_locative;

            //ID lokalizacji w promieniu N km default 20 km
            $locationsWithinNkm = locationsInZone($placesAll, $place);

            //ID sklepów w promieniu N km default 20 km
            $storesLocation = storesLocation($allStores, $locationsWithinNkm);

            // Sklepy online z wykluczeniem sklepów w promieniu 20km
            $onlineStoresNotIn20km = $onlineStores->whereNotIn('id', $storesLocation->storesWithinNkmIds);

            $remainingCount = $promoNumber - $storesLocation->storesWithinNkm->count();
            if ($remainingCount > 0){
                $storesInLocation = $storesLocation->storesWithinNkm->concat($onlineStoresNotIn20km->take($remainingCount));
            }



            //Pobranie tylko promowanych gazetek 1. w obrębie 20km 2. sklepów online
            $leafletsWithPromo = $leaflets->where('is_promo_main', 1)->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');
            $leafletsWithPromoOnline = $leaflets->where('is_promo_main', 1)->whereIn('store_id', $onlineStoresIds)->sortByDesc('created_at');

            //Polączenie gazetek promowanych do 15 szt
            $remainingCount = $promoNumber - $leafletsWithPromo->count();
            if ($remainingCount > 0){
                $leafletsWithPromoAll = $leafletsWithPromo->concat($leafletsWithPromoOnline->take($remainingCount));
            }

            //Pobranie tylko niepromowanych gazetek 1. w obrębie 20km 2. sklepów online
            $leafletsWithoutPromo = $leaflets->where('is_promo_main', 0)->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');
            $leafletsWithoutPromoOnline = $leaflets->where('is_promo_main', 0)->whereIn('store_id', $onlineStoresIds)->sortByDesc('created_at');

            //Połączenie wszystki gazetek niepromowanych
            $leafletsWithoutPromoAll = $leafletsWithoutPromo->concat($leafletsWithoutPromoOnline);

            //Polączenie gazetek promowanych i nie promowanych
            $remainingCount = $promoNumber - $leafletsWithPromoAll->count();

            if ($remainingCount > 0){
                $leafletsPromoSlider = $leafletsWithPromoAll->concat($leafletsWithoutPromoAll->take($remainingCount));
            }

            // Pobieramy wszystki miejscowości filtrujemy i limitujemy do 35
            $places = $places->where('id','!=', $placeId)->take(35);
            $places->title = 'Zobacz inne miasta';

            //Pobieramy wszystkie markery dla miejscowości
            $markers = Map::with('stores', 'places')
                ->where('place_id', '=', $placeId)
                ->orWhere(function($query) use ($place) {
                    $query->whereRaw("6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(lat))) <= 20", [$place->lat, $place->lng, $place->lat]);
                })->get();

            $markersInLocations = $markers->whereIn('store_id', $allStoresWitIds);

            //Dodajemy do kolekcji dystans od geolokalizacji
            $markersWithDistance = $markersInLocations->map(function ($marker) use ($place) {
                $distance = calculateDistance([$marker->lat, $marker->lng], [$place->lat, $place->lng]);
                $marker->distance = $distance;

                return $marker;
            });

            //Sortujemy markery po odległości od geolokalizacji
            $sortedMarkers = $markersWithDistance->sortBy('distance');

            //Filtrujem po dystansie mniejszym niż 20km i ograniczamy do 10
            $filteredMarkers = $sortedMarkers->filter(function ($marker) {
                return $marker->distance <= 20;
            })->take(10);

        }  else {
            $placeId = null;
            $markersInLocations = null;
            $filteredMarkers = null;

            // Jeśli nie ma najbliższej miejscowości, pobieramy wszystkie sklepy i gazetki
            $storesInLocation = $allStores;

            $leafletsAll = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();
            $leaflets = $leafletsAll;

            // Pobieramy wszystki miejscowości filtrujemy i limitujemy do 35
            $places = $places->take(35);

            $places->title = 'Gazetki promocyjne w najwiekszych polskich miastach';

            // Filtrujemy te gazetki które są specjalnie promowane
            $leafletsWithPromo = $leaflets->where('is_promo_main', 1)->sortByDesc('created_at');

            // Filtrujemy te gazetki które nie są specjalnie promowane
            $leafletsWithoutPromo = $leaflets->where('is_promo_main', 0)->sortByDesc('created_at');

            // Łączymy oba zbiory gazetek
            $remainingCount = 15 - $leafletsWithPromo->count();
            if ($remainingCount > 0) {
                $leafletsPromoSlider = $leafletsWithPromo->concat($leafletsWithoutPromo->take($remainingCount));
            }
        }
        // Filtrujemy sklepy, które online
        $onlineStores = $allStores->where('is_online', 1);

        // Pobieramy wszystki kupony
        $vouchers=Voucher::with('voucherStore')->where('end_offer_date', '>=', date('Y-m-d'))->take(10)->get();

        // Pobieramy wszystkie wpisy blogowe z ich kategoriami
        $blogs = Blog::with('categories')->get();

        // Flitrujemy wpisy blogowe oraz limitujemy do 10
        $blogs = $blogs->sortByDesc('updated_at')->take(10);

        // Pobieramy wszystkie produkty i limitujemy do 15
        $products = Product::all()->take(15);

        // Pobieramy wszystkie kategorie gazetek
        $leafletCategories = LeafletCategory::all();

        // Pobieranie i przetwarzanie opisów strony
        $descCollection = siteValidator(self::SITE, $placeId);

        if($place){
            $h1Title = 'Wszystkie gazetki promocyjne'.$title_extension.' w <b>jednym miejscu</b>';
            $leafletsPromoTitle = 'Polecane gazetki promocyjne'.$title_extension;
            $storesTitle =  'Sieci handlowe'.$title_extension;
            $pageDescription = $descCollection->descriptions->where('place','=','bottom');
            $placeDescription = $descCollection->descriptions->where('place', '=','middle')->first();
            $placeSlug = $place->slug;
            if($descCollection->meta->isEmpty()) {
                $metaTitle = 'Gazetki promocyjne' . $title_extension . ' • aktualne oferty • GazetkaPromocyjna.com.pl';
                $metaDescription = 'Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych' . $title_extension . ' • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu';
            } else {
                $metaTitle = $descCollection->meta->first()->meta_title;
                $metaDescription = $descCollection->meta->first()->meta_description;
            }
        } else {
            $h1Title = 'Wszystkie gazetki promocyjne w <b>jednym miejscu</b>';
            $metaTitle = 'Gazetki promocyjne • aktualne oferty • GazetkaPromocyjna.com.pl';
            $metaDescription = 'Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu';
            $leafletsPromoTitle = 'Polecane gazetki promocyjne';
            $storesTitle =  'Sieci handlowe';
            $pageDescription = null;
            $placeDescription = null;
            $placeSlug = '';

        }



        //dd($placeSlug);


        return view('main.index', [
            //meta
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,

            //h1 title
            'h1Title' => $h1Title,

            //leafletPromo
            'leafletsPromo' => $leafletsPromoSlider,
            'leafletsPromoTitle' => $leafletsPromoTitle,

            //Stores
            'stores' => $storesInLocation,
            'storesTitle' => $storesTitle,
            'storesSlug' => 'wszystkie,0',
            'onlineStores' => $onlineStores,
            'onlineStoresTitle' => 'Sklepy online',
            'onlineStoresSlug' => 'online,8',

            //Category Stores
            'categoryStores' => $categoryStores,
            'categoryStoresTitle' => 'Kategorie sieci handlowych',
            'leafletCategoryPath' => 'sieci-handlowe',

            //Page descriptions
            'pageDescription' => $pageDescription,
            'descCollection' => $descCollection,

            // Vouchers
            'vouchers'=> $vouchers,

            // Largest Cities
            'places' => $places,

            // Blogs
            'blogs' => $blogs,

            // Products
            'products' => $products,

            // All leaflets
            'leafletCategories' => $leafletCategories,
            'leaflets'=> $leaflets,
            'leafletCategoriesHeader' => 'Kategorie sieci handlowych',


            //Place
            'placeDescription' => $placeDescription,
            'place' => $place, //use in: markers


            //Markers
            'markers' => $markersInLocations,
            'markersInZone' =>$filteredMarkers,
            'weekday' => weekday(),
            'placeSlug' => $placeSlug,

            // Parametr to redirect url
            'route' => Route::currentRouteName(),
        ]);

    }

    public function indexLocalisation($slug)
    {
        //Pobranie wszystkich miejscowości z województwami
        $placesAll = Place::with('voivodeship')->get();

        //Wysukanie miejscowości po slug
        $place = $placesAll->where('slug', $slug)->first();

        //Sprawdzenie czy dana miejscowość istnieje a jeżeli nie to błąd 404
        if($place === null){
            abort(404);
        }

        //Ilość gazetek w sliderze promowane
        $promoNumber = 15;

        //Przypisanie ID miejscowości do zmiennej placeId
        $placeId = $place->id;
        $placeSlug = $place->slug;

        //Wyszukujemy slug miejscowości lub najbliżej miejscowości
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

        //Pobranie szystkich kategorii sklepów
        $categoryStores= CategoryStore::all();

        //Pobranie wszystkich gazetek ze sklepami przed datą zakończenia oferty
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();

        //ID lokalizacji w promieniu N km default 20 km
        $locationsWithinNkm = locationsInZone($placesAll, $place);

        //ID sklepów w promieniu N km default 20 km
        $storesLocation = storesLocation($allStores, $locationsWithinNkm);

        // Dopisanie rozszerzenia tytułu
        $title_extension = ' w ' . $place->name_locative;

        //Pobranie 35 największych miejscowości bez aktualnej miejscowości
        $places = $placesAll->where('id', '!=', $placeId)->sortByDesc('population')->take(35);

        //Dodanie tytułu do kolekcji dla box z miejscowościami (35 największych)
        $places->title = 'Zobacz inne miasta';

        // Sklepy online z wykluczeniem sklepów w promieniu 20km
        $onlineStoresNotIn20km = $onlineStores->whereNotIn('id', $storesLocation->storesWithinNkmIds);

        $remainingCount = $promoNumber - $storesLocation->storesWithinNkm->count();
        if ($remainingCount > 0){
            $storesInLocation = $storesLocation->storesWithinNkm->concat($onlineStoresNotIn20km->take($remainingCount));
        }

        //Pobranie tylko promowanych gazetek 1. w obrębie 20km 2. sklepów online
        $leafletsWithPromo = $leaflets->where('is_promo_main', 1)->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');
        $leafletsWithPromoOnline = $leaflets->where('is_promo_main', 1)->whereIn('store_id', $onlineStoresIds)->sortByDesc('created_at');

        //Polączenie gazetek promowanych do 15 szt
        $remainingCount = $promoNumber - $leafletsWithPromo->count();
        if ($remainingCount > 0){
            $leafletsWithPromoAll = $leafletsWithPromo->concat($leafletsWithPromoOnline->take($remainingCount));
        }

        //Pobranie tylko niepromowanych gazetek 1. w obrębie 20km 2. sklepów online
        $leafletsWithoutPromo = $leaflets->where('is_promo_main', 0)->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');
        $leafletsWithoutPromoOnline = $leaflets->where('is_promo_main', 0)->whereIn('store_id', $onlineStoresIds)->sortByDesc('created_at');

        //Połączenie wszystki gazetek niepromowanych
        $leafletsWithoutPromoAll = $leafletsWithoutPromo->concat($leafletsWithoutPromoOnline);

        //Polączenie gazetek promowanych i nie promowanych
        $remainingCount = $promoNumber - $leafletsWithPromoAll->count();
        if ($remainingCount > 0){
            $leafletsPromoSlider = $leafletsWithPromoAll->concat($leafletsWithoutPromoAll->take($remainingCount));
        }

        // Pobieramy i przetwarzamy opisy strony
        $descCollection = siteValidator(self::SITE, $placeId);



        $metaTitle = "Gazetki promocyjne $title_extension • aktualne oferty • GazetkaPromocyjna.com.pl";
        $metaDescription= "Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych w $place->name_locative • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu";
        $h1Title = 'Wszystkie gazetki promocyjne'.$title_extension.' w <b>jednym miejscu</b>';
        $leafletsPromoTitle = 'Polecane gazetki promocyjne'.$title_extension;
        $storesTitle =  'Sieci handlowe'.$title_extension;
        $pageDescription = $descCollection->descriptions->where('place','=','bottom');
        $placeDescription = $descCollection->descriptions->where('place', '=','middle')->first();




        //dd($place_descriptions);

        //Pobieramy wszystkie markery dla miejscowości
        $markers = Map::with('stores', 'places')
            ->where('place_id', '=', $placeId)
            ->orWhere(function($query) use ($place) {
                $query->whereRaw("6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(lat))) <= 20", [$place->lat, $place->lng, $place->lat]);
            })->get();

        $markersInLocations = $markers->whereIn('store_id', $allStoresWitIds);

        //Dodajemy do kolekcji dystans od geolokalizacji
        $markersWithDistance = $markersInLocations->map(function ($marker) use ($place) {
            $distance = calculateDistance([$marker->lat, $marker->lng], [$place->lat, $place->lng]);
            $marker->distance = $distance;

            return $marker;
        });

        //Sortujemy markery po odległości od geolokalizacji
        $sortedMarkers = $markersWithDistance->sortBy('distance');

        //Filtrujem po dystansie mniejszym niż 20km i ograniczamy do 10
        $filteredMarkers = $sortedMarkers->filter(function ($marker) {
            return $marker->distance <= 20;
        })->take(10);

        //dd($filteredMarkers);
        //**********************************************
        //Pobranie kuponów
        $vouchers=Voucher::with('voucherStore')->where('end_offer_date', '>=', date('Y-m-d'))->take(10)->get();

        // Pobieramy wszystkie wpisy blogowe z ich kategoriami
        $blogs = Blog::with('categories')->get();

        // Flitrujemy wpisy blogowe oraz limitujemy do 10
        $blogs = $blogs->sortByDesc('updated_at')->take(10);

        // Pobieramy wszystkie produkty i limitujemy do 15
        $products = Product::all()->take(15);

        // Pobieramy wszystkie kategorie gazetek
        $leafletCategories = LeafletCategory::all();

        //dd($placeSlug);

        return view('main.index', [
            //meta
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,

            //h1 title
            'h1Title' => $h1Title,

            //leafletPromo
            'leafletsPromo' => $leafletsPromoSlider,
            'leafletsPromoTitle' => $leafletsPromoTitle,

            //Stores
            'stores' => $storesInLocation,
            'storesTitle' => $storesTitle,
            'storesSlug' => 'wszystkie,0',
            'onlineStores' => $onlineStores,
            'onlineStoresTitle' => 'Sklepy online',
            'onlineStoresSlug' => 'online,8',

            //Category Stores
            'categoryStores' => $categoryStores,
            'categoryStoresTitle' => 'Kategorie sieci handlowych',
            'leafletCategoryPath' => 'sieci-handlowe',

            //Page descriptions
            'pageDescription' => $pageDescription,
            'descCollection' => $descCollection,

            // Vouchers
            'vouchers'=> $vouchers,

            // Largest Cities
            'places' => $places,

            // Blogs
            'blogs' => $blogs,

            // Products
            'products' => $products,

            // All leaflets
            'leafletCategories' => $leafletCategories,
            'leaflets'=> $leaflets,
            'leafletCategoriesHeader' => 'Kategorie sieci handlowych',


            //Place
            'placeDescription' => $placeDescription,
            'place' => $place, //use in: markers

            //Markers
            'markers' => $markersInLocations,
            'markersInZone' =>$filteredMarkers,
            'weekday' => weekday(),
            'placeSlug' => $placeSlug,

            // Parametr to redirect url
            'route' => Route::currentRouteName(),
        ]);


    }

}
/*
        DB::enableQueryLog();
        $queryLog = DB::getQueryLog();
        dd($queryLog);
        */
