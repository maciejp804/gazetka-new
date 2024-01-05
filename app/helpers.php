<?php

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

