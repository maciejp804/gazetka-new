<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Leaflet;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function leafletAjax()
    {
        $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'));
        if ($_POST['category'] != 0) {
            $leaflets = $leaflets->where('leaflet_category_id', $_POST['category']);
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

        $leaflets = $leaflets->take(15)->get();



        return view('components.promotions-box-filter', compact('leaflets'));


    }
}
