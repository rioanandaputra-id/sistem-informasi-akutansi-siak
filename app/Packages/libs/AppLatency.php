<?php

if (!function_exists('AppLatency')) {
    function AppLatency()
    {
        $start = constant('LARAVEL_START');
        $end = microtime(true);
        $exec = $end - $start;

        return $exec;
    }
}
