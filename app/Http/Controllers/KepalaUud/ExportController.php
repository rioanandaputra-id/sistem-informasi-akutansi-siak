<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExportController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function RbaViewGetAll()
    {
        $info = [
            'title' => 'Export Data',
            'site_active' => 'ExportData',
        ];

        return view('pages._kepalaUud.export.viewGetAll', compact('info'));
    }

    public function RbaApiGetAll()
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
                    rba.id_rba
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
            return DaTables::of($apiGetAll)->make(true);
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
}
