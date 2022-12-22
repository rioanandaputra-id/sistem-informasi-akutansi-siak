<?php

namespace App\Http\Controllers\KepalaUud\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RbaController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {
        try {
            $tahun = ($this->request->tahun == "-") ? " " : " AND pr.periode='".$this->request->tahun."'";
            $divisi = ($this->request->divisi == "-") ? " " : " AND kdiv.id_divisi='".$this->request->divisi."'";
            $apiGetAll = DB::SELECT("
                SELECT
                    keg.id_kegiatan,
                    keg.nm_kegiatan,
                    dvs.nm_divisi,
                    pr.nm_program,
                    rba.id_rba,
                    rba.tgl_submit,
                    kdiv.id_kegiatan_divisi
                FROM
                    kegiatan AS keg
                    JOIN program AS pr ON pr.id_program = keg.id_program
                    AND pr.deleted_at IS NULL
                    LEFT JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan = keg.id_kegiatan
                    AND kdiv.deleted_at IS NULL
                    LEFT JOIN divisi AS dvs ON dvs.id_divisi = kdiv.id_divisi
                    AND dvs.deleted_at IS NULL
                    LEFT JOIN rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                    AND rba.deleted_at IS NULL
                WHERE
                    keg.deleted_at IS NULL
                    AND pr.id_misi IS NOT NULL
                    ".$divisi."
                    ".$tahun."
                ORDER BY
                    keg.nm_kegiatan ASC
            ");
            return DaTables::of($apiGetAll)->addIndexColumn()->make(true);
        } catch (QueryException $e) {
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'QueryException',
                'error' => null,
                'response' => null
            ];
        } catch (Exception $e) {
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'Exception',
                'error' => null,
                'response' => null
            ];
        }
    }

    public function viewGetAll()
    {
        $info = [
            'title' => 'Export Data',
            'site_active' => 'ExportData',
        ];

        $divisi = \App\Models\Divisi::whereNull('deleted_at')->orderBy('nm_divisi')->get();

        return view('pages._kepalaUud._export._rba.viewGetAll', compact('info','divisi'));
    }
}
