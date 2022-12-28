<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class RbaKegiatanExcelExport implements FromView
{
    public function __construct($divisi, $records)
    {
        $this->divisi = $divisi;
        $this->records = $records;
    }
    
    /**
     * @return Builder
     */
    public function view(): View
    {
        $divisi = $this->divisi;
        $records = $this->records;
        return view('exports.rbaKegiatan', compact('divisi','records'));
    }
}
