<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BkuMonitoringController extends Controller
{
    private $request;
    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {

    }

    public function viewGetAll()
    {
        $info = [
            'title' => 'Monitoring BKU',
            'site_active' => 'MonitoringBKU',
        ];
        return view('pages._kepalaBagian.bkuMonitoring.viewGetAll', compact('info'));
    }
}
