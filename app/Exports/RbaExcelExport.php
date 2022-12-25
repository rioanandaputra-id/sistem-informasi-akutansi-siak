<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Program;
use App\Models\Kegiatan;
use DB;

class RbaExcelExport implements FromView, WithTitle
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
        foreach($records AS $item) {
            $item->program = Program::where('id_misi', $item->id_misi)->whereNull('deleted_at')->orderBy('nm_program')->get();
            foreach($item->program AS $values) {
                $values->kegiatan = DB::SELECT("
                    SELECT
                        kgt.nm_kegiatan,
                        (
                            SELECT
                                SUM(drba.total)
                            FROM
                                rba
                                JOIN detail_rba AS drba ON drba.id_rba=rba.id_rba AND drba.deleted_at IS NULL
                            WHERE
                                rba.deleted_at IS NULL
                                AND rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                        ) AS pagu_anggaran,
                        (
                            SELECT
                                SUM(dspj.total)
                            FROM
                                spj
                                JOIN detail_spj AS dspj ON spj.id_spj=dspj.id_spj AND dspj.deleted_at IS NULL
                                JOIN laksana_kegiatan AS laks ON laks.id_laksana_kegiatan=spj.id_laksana_kegiatan
                            WHERE
                                spj.deleted_at IS NULL
                                AND laks.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                        ) AS realisasi
                    FROM
                        kegiatan_divisi AS kdiv
                        JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                    WHERE
                        kgt.id_program='".$values->id_program."'
                        AND kdiv.id_divisi='".$divisi->id_divisi."'
                        AND kdiv.deleted_at IS NULL
                ");
            }
        }
        return view('exports.rba', compact('divisi','records'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $divisi = $this->divisi;
        return \Str::slug($divisi->nm_divisi,'_');
    }
}
