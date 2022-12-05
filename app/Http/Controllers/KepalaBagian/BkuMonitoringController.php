<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;

class BkuMonitoringController extends Controller
{
    private $request;
    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {
        $apiGetAll = DB::select("
            SELECT
                bku.id_bku,
                bku.id_laksana_kegiatan,
                lkgt.urutan_laksana_kegiatan,
                kgt.nm_kegiatan,
                pgm.nm_program,
                msi.nm_misi,
                akn.no_akun,
                akn.nm_akun,
                bku.masuk,
                bku.keluar,
                bku.saldo
            FROM
                bku AS bku
                LEFT JOIN akun AS akn ON akn.id_akun = bku.id_akun
                AND akn.deleted_at IS NULL
                JOIN laksana_kegiatan AS lkgt ON lkgt.id_laksana_kegiatan = bku.id_laksana_kegiatan
                AND lkgt.deleted_at IS NULL
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
            WHERE
                bku.deleted_at IS NULL
                AND bku.id_divisi = '" . Auth::user()->id_divisi . "'
        ");
        return DaTables::of($apiGetAll)
        ->editColumn('masuk', function ($ed) {
            return number_to_currency_without_rp($ed->masuk, 0);
        })
        ->editColumn('saldo', function ($ed) {
            return number_to_currency_without_rp($ed->saldo, 0);
        })
        ->make(true);
    }

    public function viewGetAll()
    {
        $info = [
            'title' => 'Monitoring BKU',
            'site_active' => 'MonitoringBKU',
        ];
        return view('pages._kepalaBagian.bkuMonitoring.viewGetAll', compact('info'));
    }

    public function viewDetail()
    {
        $id_bku = $this->request->id_bku;
        $info = [
            'title' => 'Detail Monitoring BKU',
            'site_active' => 'MonitoringBKU',
        ];
        $bku = DB::select("
            SELECT
                bku.id_bku,
                bku.id_laksana_kegiatan,
                lkgt.urutan_laksana_kegiatan,
                kgt.nm_kegiatan,
                pgm.nm_program,
                msi.nm_misi,
                akn.no_akun,
                akn.nm_akun,
                bku.masuk,
                bku.keluar,
                bku.saldo
            FROM
                bku AS bku
                LEFT JOIN akun AS akn ON akn.id_akun = bku.id_akun
                AND akn.deleted_at IS NULL
                JOIN laksana_kegiatan AS lkgt ON lkgt.id_laksana_kegiatan = bku.id_laksana_kegiatan
                AND lkgt.deleted_at IS NULL
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
            WHERE
                bku.deleted_at IS NULL
                AND bku.id_bku = '" . $id_bku . "'
        ");
        return view('pages._kepalaBagian.bkuMonitoring.viewDetail', compact('info', 'bku'));
    }
}
