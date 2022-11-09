<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'dashboard',
        ];
        return view('pages.dashboard', compact('info'));
    }
}
