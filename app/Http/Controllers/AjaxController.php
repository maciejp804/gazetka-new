<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Leaflet;
use App\Models\LeafletCategory;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AjaxController extends Controller
{
    public function leafletAjax()
    {
        $route = $_POST['route'];
        switch ($route) {
            case 'leafletLocal':
            case 'leaflet':
            case 'home':
            case 'homeLocal':

            $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'));
            if ($_POST['category'] != 0) {
                $leafletCategory = LeafletCategory::where('category_index', $_POST['category'])->first();
                $id = $leafletCategory->id;
                $productsInCategory = Product::whereHas('leaflet_categories', function ($query) use ($id) {
                    $query->where('category_id', $id);
                })->pluck('id');

                $leaflets = $leaflets->whereHas('products', function ($query) use ($productsInCategory) {
                    $query->whereIn('product_id', $productsInCategory);
                });
            }

            switch ($_POST['sort']) {
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
            break;

            case 'chain':
            case 'chainLocal':

            //Pobranie wszystkich sklepów z kategoriami i miejscowościami
            $allStores = Store::with(['category','places']);

            //Pobranie sklepów dla danej kategorii jeżeli różna niż wszystkie ID!=0
            if($_POST['category'] != 0) {
                $allStores = $allStores->where('category_store_id', $_POST['category']);
            }

            switch ($_POST['sort']) {
                case 3:
                    $allStores = $allStores->orderBy('name', 'asc');
                break;
                case 4:
                    $allStores = $allStores->orderBy('name', 'desc');
                break;
                case 5:
                    $allStores = $allStores->orderBy('rate', 'desc');
                break;
            }

            if ($_POST['search'] != '') {
                $allStores = $allStores->where('name', 'like', $_POST['search'] . '%');
                }

            $items = $allStores->get();

            break;


        }

        return view('components.promotions-box-filter', compact('items', 'route'));
    }

    public function location(Request $request)
    {
        Cookie::queue(Cookie::forget('local')); // Zresetuj ciasteczko lokalizacji
        $parametr = $request->input('parametr');

        switch ($parametr) {
            case 'leaflet':
            case 'leafletLocal':
                $url = route('leaflet', ['slug' => 'wszystkie', 'leaflet_category_id' => '0']);
                break;
            case 'chain':
            case 'chainLocal':
                $url = route('chain', ['slug' => 'wszystkie', 'store_category_id' => '0']);
                break;
            case 'home':
            case 'homeLocal':
                $url = route('home');
                break;
            default:
                // Domyślny przypadek dla nieznanej nazwy trasy
                $url = route('home');
                break;
        }

        return response()->json(['message' => 'Lokalizacja została zresetowana', 'url' => $url]); // Zwróć odpowiedź JSON
    }
}
