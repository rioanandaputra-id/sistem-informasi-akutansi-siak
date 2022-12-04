<?php

namespace App\Http\Controllers\BendaharaKegiatan;

use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    public function index()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        return view('pages._bendaharaKegiatan.dashboard.dashboard', compact('info'));
    }
}
