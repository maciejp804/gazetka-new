<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\Place;
use App\Models\Product;
use App\Models\SiteType;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ChainController extends Controller
{
    const SITE = 'chain-index';
    public function index($slug, $store_category_id)
    {
        // Pobieramy wszystkie kategorie sklepów
        $storeCategories = CategoryStore::all();

        // Pobranie kategorii
        $storeCategory = $storeCategories->where('category_index',$store_category_id)->where('slug', $slug);

        //Sprawdzanie czy dana kategoria istnieje, jeżeli tak to prztwarzanie dalej
        if (count($storeCategory) === 0) {
            abort(404);
        }

        //Pobieramy wszystkie miejscowości
        $placesAll = Place::with('voivodeship')->get();

        //Wyszukujemy slug miejscowości lub najbliższej miejscowości z cookies
        $place = localSlug($placesAll);

        //Pobranie wszystkich sklepów z kategoriami i miejscowościami
        $allStores = Store::with(['category','places']);

        //Pobranie sklepów dla danej kategorii jeżeli różna niż wszystkie ID!=0
        if($store_category_id != 0) {
            $allStores = $allStores->where('category_store_id', $store_category_id);
        }

        $allStores = $allStores->get();

        //Pobranie wszystkich gazetek ze sklepami przed datą zakończenia oferty
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();

        //Jeżeli mamy miejscowość w cookies
        if ($place) {

            // ID miejscowości  oraz rozszerzenie tytułu
            $placeId = $place->id;
            $title_extension = ' w '.$place->name_locative;

            //Slug miejscowości
            $placeSlug = $place->slug;

            //IDs lokalizacji w promieniu N km default 20 km
            $locationsWithinNkm = locationsInZone($placesAll, $place);

            //IDs sklepów w promieniu N km default 20 km
            $storesLocation = storesLocation($allStores, $locationsWithinNkm);

            $storesInLocation = $storesLocation->storesWithinNkm;

            //Pobranie sklepów z ofertą online
            $onlineStores = $allStores->where('is_online', 1);

            // Sklepy online z wykluczeniem sklepów w promieniu N km
            $onlineStoresNotInNkm = $onlineStores->whereNotIn('id', $storesLocation->storesWithinNkmIds);
            $onlineStoresNotInNkmIds = $onlineStoresNotInNkm->pluck('id');

            //Połączenie sklepów stacjonarnych w promieniu i online poza promieniem N km
            $allStores = $storesInLocation->concat($onlineStoresNotInNkm);

            $leafletsInLocations = $leaflets->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');

            $leafletsOnline = $leaflets->whereIn('store_id', $onlineStoresNotInNkmIds)->sortByDesc('created_at');

            $leaflets = $leafletsInLocations->concat($leafletsOnline);

        } else {
            $placeId = null;

            $placeSlug = '';

            //Deklaracja rozszrzenia tytułu z pustym ciągiem znaków
            $title_extension = '';
        }

        // Pobieranie i przetwarzanie opisów strony
        $descCollection = siteValidator(self::SITE.'-'.$store_category_id, $placeId);


        if($place){
            $h1Title = 'Sieci <b>handlowe</b>'.$title_extension;
            $leafletsPromoTitle = 'Polecane gazetki promocyjne'.$title_extension;
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
            $h1Title = 'Sieci <b>handlowe</b>';
            $metaTitle = 'Gazetki promocyjne • aktualne oferty • GazetkaPromocyjna.com.pl';
            $metaDescription = 'Aktualne gazetki promocyjne, przeceny i wyprzedaże • GazetkaPromocyjna.com.pl – wszystkie promocje w jednym miejscu';
            $leafletsPromoTitle = 'Polecane gazetki promocyjne';
            $pageDescription = $descCollection->descriptions->where('place','=','bottom');
            $placeDescription = null;


        }



        return view('main.chains',[
            //meta
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,

            //h1 title
            'h1Title' => $h1Title,

            'storeCategories' => $storeCategories,
            'storeCategory' => $storeCategory[$store_category_id],
            'storeCategoryPath' => 'sieci-handlowe',
            'leafletsPromo' => $leaflets,
            'leafletsPromoTitle' => $leafletsPromoTitle,
            'stores' => $allStores,

            //Page descriptions
            'pageDescription' => $pageDescription,
            'descCollection' => $descCollection,

            'placeSlug' => $placeSlug,
            'route' => Route::currentRouteName(),
        ]);
    }

    public function indexLocalisation($slug, $store_category_id, $place_slug)
    {
        // Pobieramy wszystkie kategorie sklepów
        $storeCategories = CategoryStore::all();

        // Pobranie kategorii
        $storeCategory = $storeCategories->where('category_index',$store_category_id)->where('slug', $slug);

        //Sprawdzanie czy dana kategoria istnieje, jeżeli tak to prztwarzanie dalej
        if (count($storeCategory) === 0) {
            abort(404);
        }

        //Pobieramy wszystkie miejscowości
        $placesAll = Place::with('voivodeship')->get();

        //Wysukanie miejscowości po slug
        $place = $placesAll->where('slug', $place_slug)->first();

        //Sprawdzenie czy dana miejscowość istnieje a jeżeli nie to błąd 404
        if($place === null){
            abort(404);
        }

        //Nazwa szablonu strony w bazie danych opisów
        $site = self::SITE.'-'.$store_category_id;

        //Przypisanie ID miejscowości do zmiennej placeId
        $placeId = $place->id;

        //Slug miejscowości
        $placeSlug = $place->slug;

        //Wyszukujemy slug miejscowości lub najbliżej miejscowości na podstawie cookies
        $closestPlace = localSlug($placesAll);

        if (isset($closestPlace)){
            if($place->slug !== $closestPlace->slug){
                setCookies($place);
            }
        }

        //Pobranie wszystkich sklepów z kategoriami i miejscowościami
        $allStores = Store::with(['category','places']);

        //Pobranie sklepów dla danej kategorii jeżeli różna niż wszystkie ID!=0
        if($store_category_id != 0) {
            $allStores = $allStores->where('category_store_id', $store_category_id);
        }

        $allStores = $allStores->get();

        //Pobranie wszystkich gazetek ze sklepami przed datą zakończenia oferty
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();

        // Rozszerzenie tytułu
        $title_extension = ' w '.$place->name_locative;

        //IDs lokalizacji w promieniu N km default 20 km
        $locationsWithinNkm = locationsInZone($placesAll, $place);

        //IDs sklepów w promieniu N km default 20 km
        $storesLocation = storesLocation($allStores, $locationsWithinNkm);

        $storesInLocation = $storesLocation->storesWithinNkm;

        //Pobranie sklepów z ofertą online
        $onlineStores = $allStores->where('is_online', 1);

        // Sklepy online z wykluczeniem sklepów w promieniu N km
        $onlineStoresNotInNkm = $onlineStores->whereNotIn('id', $storesLocation->storesWithinNkmIds);
        $onlineStoresNotInNkmIds = $onlineStoresNotInNkm->pluck('id');

        //Połączenie sklepów stacjonarnych w promieniu i online poza promieniem N km
        $allStores = $storesInLocation->concat($onlineStoresNotInNkm);

        $leafletsInLocations = $leaflets->whereIn('store_id', $storesLocation->storesWithinNkmIds)->sortByDesc('created_at');

        $leafletsOnline = $leaflets->whereIn('store_id', $onlineStoresNotInNkmIds)->sortByDesc('created_at');

        $leaflets = $leafletsInLocations->concat($leafletsOnline);

        // Pobieranie i przetwarzanie opisów strony
        $descCollection = siteValidator(self::SITE.'-'.$store_category_id, $placeId);

        $h1Title = 'Sieci <b>handlowe</b>'.$title_extension;
        $leafletsPromoTitle = 'Polecane gazetki promocyjne'.$title_extension;
        $pageDescription = $descCollection->descriptions->where('place','=','bottom');
        $placeDescription = $descCollection->descriptions->where('place', '=','middle')->first();

        if($descCollection->meta->isEmpty()) {
            $metaTitle = 'Gazetki promocyjne' . $title_extension . ' • aktualne oferty • GazetkaPromocyjna.com.pl';
            $metaDescription = 'Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych' . $title_extension . ' • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu';
        } else {
            $metaTitle = $descCollection->meta->first()->meta_title;
            $metaDescription = $descCollection->meta->first()->meta_description;
        }


        return view('main.chains',[
            //meta
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,

            //h1 title
            'h1Title' => $h1Title,

            'storeCategories' => $storeCategories,
            'storeCategory' => $storeCategory[$store_category_id],
            'storeCategoryPath' => 'sieci-handlowe',
            'leafletsPromo' => $leaflets,
            'leafletsPromoTitle' => $leafletsPromoTitle,
            'stores' => $allStores,

            //Page descriptions
            'pageDescription' => $pageDescription,
            'descCollection' => $descCollection,

            'placeSlug' => $placeSlug,
            'route' => Route::currentRouteName(),
        ]);
    }
}
