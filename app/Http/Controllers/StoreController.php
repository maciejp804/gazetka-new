<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryStore;
use App\Models\Leaflet;
use App\Models\Place;
use App\Models\Store;
use App\Models\Voucher;
use DeepCopyTest\Matcher\Y;
use Illuminate\Http\Request;
use function Pest\Laravel\withHeader;
use function Webmozart\Assert\Tests\StaticAnalysis\stringWillNotBeRedundantIfAssertingAndNotUsingEither;

class StoreController extends Controller
{
    public function index()
    {
        $store = Store::with('category')->get();
        $category_stores= CategoryStore::all();
        $leaflets=Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))->get();
        $leaflets_with_promo = $leaflets->where('is_promo_main', 1)->sortByDesc('created_at');
        $leaflets_without_promo = $leaflets->where('is_promo_main', 0)->sortByDesc('created_at');
        $remainingCount = 15 - $leaflets_with_promo->count();
        if ($remainingCount > 0){
            $leaflets_with_promo = $leaflets_with_promo->concat($leaflets_without_promo->take($remainingCount));
        }

        $online = $store->where('is_online', 1);
        $vouchers=Voucher::with('voucherStore')->where('end_offer_date', '>=', date('Y-m-d'))->take(10)->get();
        $places = Place::all()->sortByDesc('population')->take(35);
        return view('main.index', [
            'stores' => $store,
            'category_stores' => $category_stores,
            'leaflets'=> $leaflets,
            'leaflets_promo' => $leaflets_with_promo,
            'online' => $online,
            'vouchers'=> $vouchers,
            'places' => $places,
        ]);

    }

}
