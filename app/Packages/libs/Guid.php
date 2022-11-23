<?php

if (!function_exists('guid')) {
    function guid()
    {
        return \Str::uuid();
    }
}
