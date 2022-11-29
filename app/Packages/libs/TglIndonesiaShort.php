<?php

if( !function_exists( 'tglIndonesiaShort' ) ){
  function tglIndonesiaShort($date)
  {
      $date = strlen($date) > 10 ? substr($date, 0, 10) : $date;

      if( trim($date) != '' ){
        $arr_date = explode('-', $date);

        $month = bulanIndonesiaShort( sprintf("%d", $arr_date[1]) );

        return "{$arr_date[2]} {$month} $arr_date[0]";
      }
      
      return $date;
  }
}