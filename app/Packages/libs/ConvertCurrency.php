<?php
/**
 * Created by PhpStorm.
 * User: Hendra
 * Date: 3/13/2018
 * Time: 5:50 PM
 */
if (!function_exists('number_to_currency')) {
    function number_to_currency($number, $belakang_koma = 2)
    {
        $new_number = number_format($number, $belakang_koma, ',', '.');

        return 'Rp. '.$new_number;
    }
}