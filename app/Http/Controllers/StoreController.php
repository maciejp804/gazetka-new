<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\LeafletCategory;
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
use function Pest\Laravel\withHeader;
use function Webmozart\Assert\Tests\StaticAnalysis\stringWillNotBeRedundantIfAssertingAndNotUsingEither;
$site = 'main-index';
class StoreController extends Controller
{
    public function index()
    {
        $site = 'main-index';
        $store = Store::with('category')->get();
        $category_stores= CategoryStore::all();
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();
        $leaflets_with_promo = $leaflets->where('is_promo_main', 1)->sortByDesc('created_at');
        $leaflets_without_promo = $leaflets->where('is_promo_main', 0)->sortByDesc('created_at');
        $remainingCount = 15 - $leaflets_with_promo->count();
        if ($remainingCount > 0){
            $leaflets_with_promo = $leaflets_with_promo->concat($leaflets_without_promo->take($remainingCount));
        }

        $online = $store->where('is_online', 1);
        $vouchers=Voucher::with('voucherStore')->where('end_offer_date', '>=', date('Y-m-d'))->take(10)->get();
        $places = Place::all()->sortByDesc('population')->take(35);
        $blogs = Blog::all()->sortByDesc('updated_at')->take(10);
        $products = Product::all()->take(15);
        $leafletCategories = LeafletCategory::all();

       $descriptions = siteValidator($site);


        if($descriptions->meta->isEmpty()){
            $meta_title = "Gazetki promocyjne • aktualne oferty • GazetkaPromocyjna.com.pl";
            $meta_description = "Gazetki promocyjne sieci handlowych pozwolą Ci zaoszczędzić czas i pieniądze. Dzięki nowym ulotkom poznasz aktualną ofertę sklepów.";
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }
        $page_descriptions = $descriptions->descriptions->where('place','=','bottom');

        return view('main.index', [
            'stores' => $store,
            'category_stores' => $category_stores,
            'leaflets'=> $leaflets,
            'leaflets_promo' => $leaflets_with_promo,
            'online' => $online,
            'vouchers'=> $vouchers,
            'places' => $places,
            'blogs' => $blogs,
            'products' => $products,
            'leafletCategories' => $leafletCategories,
            'leafletCategoriesHeader' => 'Kategorie sieci handlowych',
            'leafletCategoryPath' => 'sieci-handlowe',
            'pageDescriptions' => $page_descriptions,
            'pageQuestions' => $descriptions->questions,
            "metaTitle" => $meta_title,
            'metaDescription' => $meta_description,
        ]);

    }

    public function indexLocalisation($slug)
    {

        /*
        DB::enableQueryLog();
        $queryLog = DB::getQueryLog();
        dd($queryLog);
        */
        $places = Place::with('voivodeship')->get();
        $place = $places->where('slug', $slug)->first();
        if($place === null){
            abort(404);
        }
        $places = $places->sortBy('population')->take(35);
        $placeId = $place->id;
        $site = 'main-index';

        $allStores = Store::with(['category','places'])->get();
        $storesInLocation = $allStores->filter(function ($stores) use ($placeId){
           return $stores->places->contains('id',$placeId);
        });


        $category_stores= CategoryStore::all();

        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();
        $leaflets_with_promo = $leaflets->where('is_promo_main', 1)->sortByDesc('created_at');
        $leaflets_without_promo = $leaflets->where('is_promo_main', 0)->sortByDesc('created_at');
        $remainingCount = 15 - $leaflets_with_promo->count();
        if ($remainingCount > 0){
            $leaflets_with_promo = $leaflets_with_promo->concat($leaflets_without_promo->take($remainingCount));
        }

        $leafletsInLocation = $leaflets->filter(function ($leaflet) use ($storesInLocation) {
            return $storesInLocation->contains('id', $leaflet->store->id);
        });

        $online = $allStores->where('is_online', 1);
        $vouchers=Voucher::with('voucherStore')->where('end_offer_date', '>=', date('Y-m-d'))->take(10)->get();
        $blogs = Blog::all()->sortByDesc('updated_at')->take(10);
        $products = Product::all()->take(15);
        $leafletCategories = LeafletCategory::all();
        $descriptions = siteValidator($site, $place->id);


        if($descriptions->meta->isEmpty()){
            $meta_title = "Gazetki promocyjne w $place->name_locative • aktualne oferty • GazetkaPromocyjna.com.pl";
            $meta_description = "Aktualne gazetki promocyjne, wyprzedaże, okazje i oferty sieci handlowych w $place->name_locative • GazetkaPromocyjna.com.pl • wiele promocji w jednym miejscu";
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }


        $page_descriptions = $descriptions->descriptions->where('place', 'bottom');
        $place_descriptions = $descriptions->descriptions->where('place', 'middle')->first();
        //dd($place_descriptions);
        return view('main.index', [
            'stores' => $storesInLocation,
            'category_stores' => $category_stores,
            'leaflets'=> $leaflets,
            'leaflets_promo' => $leafletsInLocation,
            'online' => $online,
            'vouchers'=> $vouchers,
            'places' => $places,
            'blogs' => $blogs,
            'products' => $products,
            'leafletCategories' => $leafletCategories,
            'leafletCategoriesHeader' => 'Kategorie sieci handlowych',
            'leafletCategoryPath' => 'sieci-handlowe',
            'pageDescriptions' => $page_descriptions,
            'pageQuestions' => $descriptions->questions,
            "metaTitle" => $meta_title,
            'metaDescription' => $meta_description,
            'place' => $place,
            'placeDescription' => $place_descriptions,
        ]);


    }

}
