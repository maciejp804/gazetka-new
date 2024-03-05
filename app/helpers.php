<?php

use App\Models\SiteMeta;
use App\Models\SiteType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

if (! function_exists('monthReplace')) {
    function monthReplace ($date, $format = 'd-m-Y', $separator = ' '){

        $month_array = array(
            1 => 'STY',
            2 => 'LUT',
            3 => 'MAR',
            4 => 'KWI',
            5 => 'MAJ',
            6 => 'CZE',
            7 => 'LIP',
            8 => 'SIE',
            9 => 'WRZ',
            10 => 'PAŹ',
            11 => 'LIS',
            12 => 'GRU',

        );

        // format daty jest YYYY-mm-dd
        $day = date('j', strtotime($date));
        $month = $month_array[date('n', strtotime($date))];
        $year = date('Y', strtotime($date));

        // wyciągnąć miesiąc z daty i zamienić zgodnie z tablicą
        //zamienić miesiąć z liczby na litery
        if ($format === 'd-m-Y') {
            return $day . $separator . $month . $separator . $year;
        } elseif ($format === 'd-m') {
            return $day . $separator . $month;
        }
    }
}

if (! function_exists('siteValidator'))
{
    function siteValidator ($site, $place = null)
    {
        try {
            $descriptions = SiteType::with(['descriptions','questions', 'meta'])->where('name',$site)
                ->where('place_id', '=', $place)->firstOrFail();

        } catch (ModelNotFoundException $e) {
            $descriptions = new SiteType();
        }

             return $descriptions;
    }
}
if (! function_exists('calculateDistance')) {
    function calculateDistance($point1, $point2) {
        $lat1 = $point1[0];
        $lon1 = $point1[1];
        $lat2 = $point2[0];
        $lon2 = $point2[1];

        $earthRadius = 6371; // Średnia odległość od środka Ziemi do jej powierzchni w kilometrach

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}

if (! function_exists('weekday')) {

    function weekday()
    {
        return match (date('N')){
          1,2,3,4,5 => 'weekdays',
            6 => 'saturday',
            7 => 'sunday',
            default => 'null'
        };

    }

}
