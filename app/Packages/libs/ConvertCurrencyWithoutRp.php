<?php
/**
 * Created by PhpStorm.
 * User: Hendra
 * Date: 3/13/2018
 * Time: 5:50 PM
 */
if (!function_exists('number_to_currency_without_rp')) {
    function number_to_currency_without_rp($number, $belakang_koma = 2)
    {
        $new_number = number_format($number, $belakang_koma, ',', '.');

        return $new_number;
    }
}