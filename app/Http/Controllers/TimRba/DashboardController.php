<?php

namespace App\Http\Controllers\TimRba;

use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    public function index()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        return view('pages._timRba.dashboard.dashboard', compact('info'));
    }
}
