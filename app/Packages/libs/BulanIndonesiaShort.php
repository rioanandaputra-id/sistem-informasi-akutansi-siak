<?php

if( !function_exists('bulanIndonesiaShort') ){
  function bulanIndonesiaShort($o=false){
    $obj = [1=> 'Jan', 'Feb', 'Mar',
               'Apr',   'Mei',     'Jun',
             'Jul',    'Agu', 'Sep',
             'Okt', 'Nov','Des'];

    if( $o === false )
      return $obj;
    else
      return( $obj[$o] );
  }
}