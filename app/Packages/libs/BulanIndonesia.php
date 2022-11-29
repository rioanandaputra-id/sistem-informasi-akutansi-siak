<?php

if( !function_exists('bulanIndonesia') ){
  function bulanIndonesia($o=false){
    $obj = [1=> 'Januari', 'Februari', 'Maret',
               'April',   'Mei',     'Juni',
             'Juli',    'Agustus', 'September',
             'Oktober', 'November','Desember'];

    if( $o === false )
      return $obj;
    else
      return( $obj[$o] );
  }
}
