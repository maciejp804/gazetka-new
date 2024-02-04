<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Leaflet;
use App\Models\LeafletCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function leafletAjax()
    {
        $leafletCategory = LeafletCategory::where('category_index','=',$_POST['category'])->get();

        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'));
        if ($_POST['category'] != 0) {
            $leafletCategory = LeafletCategory::where('category_index',$_POST['category'])->first();
            $id = $leafletCategory->id;
            $productsInCategory = Product::whereHas('leaflet_categories', function ($query) use ($id) {
                $query->where('category_id', $id);})->pluck('id');

            $leaflets = $leaflets->whereHas('products', function ($query) use ($productsInCategory){
                $query->whereIn('product_id', $productsInCategory);
            });
        }

        switch ($_POST['sort']){
            case 0:
                $leaflets = $leaflets->orderBy('created_at', 'desc');
                break;
            case 1:
                $leaflets = $leaflets->where('start_offer_date', '>', date('Y-m-d'))->orderBy('start_offer_date', 'desc');
                break;
            case 2:
                $leaflets = $leaflets->where('start_offer_date', '<', date('Y-m-d'))->orderBy('end_offer_date', 'asc');
                break;

        }

        if ($_POST['search'] != '') {
            $leaflets = $leaflets->whereHas('store', function ($q) {
                $q->where('name', 'like', $_POST['search'] . '%');
            });
        }

        $items = $leaflets->take(15)->get();



        return view('components.promotions-box-filter', compact('items'));


    }
}
