<?php

if( !function_exists( 'tglIndonesia' ) ){
  function tglIndonesia($date)
  {
      $date = strlen($date) > 10 ? substr($date, 0, 10) : $date;

      if( trim($date) != '' ){
        $arr_date = explode('-', $date);

        $month = bulanIndonesia( sprintf("%d", $arr_date[1]) );

        return "{$arr_date[2]} {$month} $arr_date[0]";
      }
      
      return $date;
  }
}