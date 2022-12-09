<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        return view('pages._kepalaBagian.dashboard.dashboard', compact('info'));
    }
}
