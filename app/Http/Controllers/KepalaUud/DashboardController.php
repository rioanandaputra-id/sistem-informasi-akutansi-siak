<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        return view('pages._kepalaUud.dashboard.dashboard', compact('info'));
    }
}
