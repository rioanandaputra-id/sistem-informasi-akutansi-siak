<?php

namespace App\Http\Controllers\KepalaWilayah;

use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    public function index()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        return view('pages._kepalaWilayah.dashboard.dashboard', compact('info'));
    }
}
