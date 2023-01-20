<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class SpjKegiatanExcelExport implements FromView
{
    public function __construct($divisi, $records)
    {
        $this->divisi = $divisi;
        $this->records = $records;
    }
    
    public function view(): View
    {
        $divisi = $this->divisi;
        $records = $this->records;
        return view('exports.spjKegiatan', compact('divisi','records'));
    }
}
