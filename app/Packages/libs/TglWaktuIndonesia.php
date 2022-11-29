<?php

if( !function_exists( 'tglWaktuIndonesia' ) ){
  function tglWaktuIndonesia($date)
  {
      if( trim($date) != '' ){
          $pecah_waktu = explode(' ',$date);
          $pecah_waktu_2 = explode(':',$pecah_waktu[1]);
          $arr_date = explode('-', $pecah_waktu[0]);
          $month = bulanIndonesiaShort( sprintf("%d", $arr_date[1]) );

          if (count($pecah_waktu)>1) {
              return "{$arr_date[2]} {$month} $arr_date[0] ".' '.$pecah_waktu_2[0].':'.$pecah_waktu_2[1]. ':'.$pecah_waktu_2[2]." WIB";
          } else {
              return "{$arr_date[2]} {$month} $arr_date[0]";
          }
      }

      return $date;
  }
}
