<?php

if( !function_exists( 'tglFormat' ) ){
  function tglFormat($date)
  {
      $date = strlen($date) > 10 ? substr($date, 0, 10) : $date;

      if( trim($date) != '' ){
        $arr_date = explode('-', $date);

        return $arr_date[2] ."-". $arr_date[1] ."-". $arr_date[0];
      }
      
      return $date;
  }
}