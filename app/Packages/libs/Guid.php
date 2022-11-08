<?php

if (!function_exists('guid')) {
    function guid()
    {
        $sql = DB::SELECT(DB::raw('SELECT uuid_generate_v4() AS id'));
        if (is_object($sql[0])) {
            return (string) $sql[0]->id;
        } else {
            return (string) $sql[0]['id'];
        }
    }
}
