<?php

namespace App\Http\Controllers;
use DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $info = [
            'title' => 'Dashboard',
            'site_active' => 'Dashboard',
        ];
        $kegiatan = DB::SELECT("
            SELECT
                kgt.nm_kegiatan,
                dvs.nm_divisi,
                laks.waktu_pelaksanaan,
                laks.waktu_selesai,
                laks.lokasi
            FROM
                kegiatan AS kgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan=kgt.id_kegiatan
                JOIN divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi
                JOIN laksana_kegiatan AS laks ON laks.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
            WHERE
                laks.deleted_at IS NULL
                AND laks.a_verif_kabag_keuangan = '2'
        ");

        $id_divisi = (!is_null(\Auth::user()->id_divisi) && userRole(\Auth::user()->id_user)[0]->id_role != 2) ? " AND kdiv.id_divisi='".\Auth::user()->id_divisi."'" : " ";

        $pengeluaran = DB::SELECT("
            SELECT
                kdiv.id_divisi,
                SUM(rba.rencana_pengeluaran) AS rencana_pengeluaran,
                SUM(laks.realisasi_pengeluaran) AS realisasi_pengeluaran
            FROM
                kegiatan_divisi AS kdiv
                LEFT JOIN (
                    SELECT
                        rba.id_kegiatan_divisi,
                        SUM(drba.total) AS rencana_pengeluaran
                    FROM
                        rba
                        JOIN detail_rba AS drba ON drba.id_rba=rba.id_rba
                    WHERE
                        rba.deleted_at IS NULL
                    GROUP BY
                        rba.id_kegiatan_divisi
                ) AS rba ON rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                LEFT JOIN (
                    SELECT
                        laks.id_kegiatan_divisi,
                        SUM(dspj.total) AS realisasi_pengeluaran
                    FROM
                        laksana_kegiatan AS laks
                        JOIN spj ON spj.id_laksana_kegiatan=laks.id_laksana_kegiatan
                        JOIN detail_spj AS dspj ON dspj.id_spj=spj.id_spj
                    WHERE
                        laks.a_verif_kabag_keuangan='2'
                        AND (spj.a_verif_kabag_keuangan='2' OR spj.a_verif_bendahara_pengeluaran='2')
                    GROUP BY
                        laks.id_kegiatan_divisi
                ) AS laks ON laks.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
            WHERE
                kdiv.a_verif_rba='2'
                AND date_part('year', kdiv.created_at)='".date('Y')."'
                ".$id_divisi."
            GROUP BY
                kdiv.id_divisi
        ");

        if(!is_null(\Auth::user()->id_divisi) && userRole(\Auth::user()->id_user)[0]->id_role != 2) {
            $kdiv = \App\Models\KegiatanDivisi::where('id_divisi', \Auth::user()->id_divisi)->pluck('id_kegiatan_divisi');
            $laksKeg = \App\Models\LaksanaKegiatan::whereIn('id_kegiatan_divisi', $kdiv)->pluck('id_laksana_kegiatan');

            $realisasiKegiatan = \App\Models\LaksanaKegiatan::whereNull('deleted_at')->whereIn('id_kegiatan_divisi', $kdiv)->where('a_verif_kabag_keuangan', 2)->count();
            $spjday = \App\Models\Spj::whereNull('deleted_at')->whereIn('id_laksana_kegiatan', $laksKeg)->whereDay('created_at', date('d'))->count();
            $spjmonth = \App\Models\Spj::whereNull('deleted_at')->whereIn('id_laksana_kegiatan', $laksKeg)->whereMonth('created_at', date('m'))->count();
            $spjyears = \App\Models\Spj::whereNull('deleted_at')->whereIn('id_laksana_kegiatan', $laksKeg)->whereYear('created_at', date('Y'))->count();
        } else {
            $realisasiKegiatan = \App\Models\LaksanaKegiatan::whereNull('deleted_at')->where('a_verif_kabag_keuangan', 2)->count();
            $spjday = \App\Models\Spj::whereNull('deleted_at')->whereDay('created_at', date('d'))->count();
            $spjmonth = \App\Models\Spj::whereNull('deleted_at')->whereMonth('created_at', date('m'))->count();
            $spjyears = \App\Models\Spj::whereNull('deleted_at')->whereYear('created_at', date('Y'))->count();
        }
        
        return view('pages.dashboard', compact('info','kegiatan','pengeluaran', 'realisasiKegiatan', 'spjday', 'spjmonth', 'spjyears'));
    }
}
