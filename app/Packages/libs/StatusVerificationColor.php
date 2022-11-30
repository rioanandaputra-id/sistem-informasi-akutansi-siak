<?php

if (!function_exists('status_verification_color')) {
    function status_verification_color($status)
    {
        if (str_contains($status, 'Belum')) {
            return "<span class='bg-yellow'>" . $status . "</span>";
        }
        if (str_contains($status, 'Ditolak') || str_contains($status, 'Non Aktif')) {
            return "<span class='bg-red'>" . $status . "</span>";
        }
        if (str_contains($status, 'Disetujui') || str_contains($status, 'Aktif')) {
            return "<span class='bg-green'>" . $status . "</span>";
        }
    }
}
