<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\SiteType;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ChainController extends Controller
{
    public function index($slug, $store_category_id=0)
    {


        $storeCategories = CategoryStore::all();
        $storeCategory = $storeCategories->where('category_index',$store_category_id);
        $leaflets=Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();
        $leaflets_with_promo = $leaflets->where('is_promo_main', 1)->sortByDesc('created_at');
        $leaflets_without_promo = $leaflets->where('is_promo_main', 0)->sortByDesc('created_at');
        $remainingCount = 15 - $leaflets_with_promo->count();
        if ($remainingCount > 0){
            $leaflets_with_promo = $leaflets_with_promo->concat($leaflets_without_promo->take($remainingCount));
        }

        $stores = Store::with('category');
        if ($store_category_id != 0){
            $stores = $stores->whereHas('category', function ($query) use ($store_category_id){
                $query->where('category_store_id', $store_category_id);
            });
        }
        $stores = $stores->get();

        $site = 'chain-index-'.$store_category_id;

        $descriptions = siteValidator($site);



        if($descriptions->meta->isEmpty()){
            $meta_title = 'Uwaga przykładowy tytuł';
            $meta_description = 'Uwaga przykładowy opis';
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }
        $page_descriptions = $descriptions->descriptions;

        return view('main.chains',[
            'storeCategories' => $storeCategories,
            'storeCategory' => $storeCategory[$store_category_id],
            'storeCategoryPath' => 'sieci-handlowe',
            'leaflets_promo' => $leaflets_with_promo,
            'stores' => $stores,
            'metaTitle' => $meta_title,
            'metaDescription' => $meta_description,
            'pageDescriptions' => $page_descriptions,
            'pageQuestions' => $descriptions->questions,
        ]);
    }
}
