<?php

use App\Models\SiteMeta;
use App\Models\SiteType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

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

if (! function_exists('localSlug')) {

    function localSlug ($placesAll)
    {

        if(isset($_COOKIE['local'])){
            $cookie = $_COOKIE['local'];

            $cookieJson = json_decode($cookie, true);
            $cookieLat = $cookieJson['lat'];
            $cookieLng = $cookieJson['lng'];

            $placesCookie = $placesAll->where('name', $cookieJson['place']);

            if ($placesCookie->isEmpty()) {
                // Brak miejsc o danej nazwie, znajdź najbliższe
                $minDistance = PHP_INT_MAX;
                $closestPlace = null;

                foreach ($placesAll as $place) {
                    // Oblicz odległość pomiędzy współrzędnymi z ciasteczka a miejscem w bazie danych
                    $distance = calculateDistance([$place->lat, $place->lng], [$cookieLat, $cookieLng]);

                    // Sprawdź czy aktualne miejsce jest bliżej niż poprzednie
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $closestPlace = $place;
                    }
                }
            } elseif ($placesCookie->count() == 1) {
                // Tylko jedno miejsce o danej nazwie
                $closestPlace = $placesCookie->first();
            } else {
                // Wiele miejsc o danej nazwie, znajdź najbliższe
                $minDistance = PHP_INT_MAX;
                $closestPlace = null;

                foreach ($placesCookie as $place) {
                    // Oblicz odległość pomiędzy współrzędnymi z ciasteczka a miejscem w bazie danych
                    $distance = calculateDistance([$place->lat, $place->lng], [$cookieLat, $cookieLng]);

                    // Sprawdź czy aktualne miejsce jest bliżej niż poprzednie
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $closestPlace = $place;
                    }
                }
            }

            return $closestPlace;
        }

        return null;
    }
}



if (! function_exists('setCookies')) {

    function setCookies($closestPlace)
    {
        $minutes = 43200;

        $cookieArray = [
            'place' => $closestPlace->name,
            'district' => $closestPlace->voivodeship->name,
            'lat' => $closestPlace->lat,
            'lng' => $closestPlace->lng
        ];

        $cookieJson = json_encode($cookieArray);
        //dd($cookieJson);
        // Ustaw ciasteczko
        Cookie::queue('local', $cookieJson, $minutes);

        // Zwróć odpowiedź z ustawionym ciasteczkiem
        return true;
    }
}

if (! function_exists('locationsInZone')) {
    function locationsInZone($placesAll, $place, $zone = 20)
    {
        // Dodajemy odległość do każdego elementu kolekcji $placesAll
        $placesAll->each(function ($location) use ($place) {
            $distance = calculateDistance([$location->lat, $location->lng], [$place->lat, $place->lng]);
            $location->distance = $distance; // Dodajemy odległość do każdego miejsca w kolekcji
        });

        // Filtrujemy kolekcję $placesAll, aby uzyskać tylko te lokalizacje oddalone o N km od danej lokalizacji
        $locationsWithinNkm = $placesAll->filter(function ($location) use ($zone) {
            return $location->distance <= $zone; // Zwracamy true tylko dla miejsc oddalonych o N km
        });

        // Sortujemy po odległości
        $locationsWithinNkm = $locationsWithinNkm->sortBy('distance');

        // Zwracamy identyfikatory miejscowości oddalonych o 20 km od danej lokalizacji

        return $locationsWithinNkm->pluck('id');
    }
}


if (! function_exists('storesLocation')) {

    function storesLocation($allStores, $locationsWithinNkmIds)
    {

        //Pobranie sklepów, które są w miejscowościach w promieniu Nkm
        $storesWithinNkm = $allStores->filter(function ($store) use ($locationsWithinNkmIds) {
            return $store->places->pluck('id')->intersect($locationsWithinNkmIds)->isNotEmpty();
        });

        //ID sklepów w promieniu 20km
        $storesWithinNkmIds = $storesWithinNkm->pluck('id');

        $storesLocation=collect();
        $storesLocation->storesWithinNkmIds = $storesWithinNkmIds;
        $storesLocation->storesWithinNkm = $storesWithinNkm;

        return $storesLocation;

    }
}


