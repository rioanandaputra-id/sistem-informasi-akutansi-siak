<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class DetailRbaTemplate implements FromView
{
    
    public function __construct($akun)
    {
        $this->akun = $akun;
    }
    
    /**
     * @return Builder
     */
    public function view(): View
    {
        $akun = $this->akun;
        return view('exports.detailRba', compact('akun'));
    }
}
