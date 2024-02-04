<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\LeafletCategory;
use App\Models\Product;
use App\Models\SiteType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Webmozart\Assert\Tests\StaticAnalysis\email;

class LeafletController extends Controller
{
    public function index($slug, $leaflet_category_id)
    {
        $site = 'leaflet-index-'.$leaflet_category_id;
        $leafletCategories = LeafletCategory::all();

        $leafletCategory = $leafletCategories->where('slug','=',$slug)
            ->where('category_index','=',$leaflet_category_id);


        if (count($leafletCategory) > 0)  {
            $id = $leafletCategory[$leaflet_category_id]->id;
            $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'));

            if ($leaflet_category_id != 0){

                $productsInCategory = Product::whereHas('leaflet_categories', function ($query) use ($id) {
                   $query->where('category_id', $id);})->pluck('id');

                $leaflets = $leaflets->whereHas('products', function ($query) use ($productsInCategory){
                $query->whereIn('product_id', $productsInCategory);
            });
           }

            $leaflets = $leaflets->get();
        } else {
            abort(404);
        }

        $products = Product::all()->take(15);

        $descriptions = siteValidator($site);

        if($descriptions->meta->isEmpty()){
            $meta_title = 'Uwaga przykładowy tytuł';
            $meta_description = 'Uwaga przykładowy opis';
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }
        $page_descriptions = $descriptions->descriptions;

        return view('main.leaflets', [
            'leaflets' => $leaflets,
            'leafletCategories' => $leafletCategories,
            'leafletCategory' => $leafletCategory[$leaflet_category_id],
            'leafletCategoryPath' => 'gazetki-promocyjne',
            'products' => $products,
            'metaTitle' => $meta_title,
            'metaDescription' => $meta_description,
            'pageDescriptions' => $page_descriptions,
            'pageQuestions' => $descriptions->questions,
        ]);
    }
}
