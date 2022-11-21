<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $request;
    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function dashboard()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        return view('pages.dashboard', compact('info'));
    }
}
