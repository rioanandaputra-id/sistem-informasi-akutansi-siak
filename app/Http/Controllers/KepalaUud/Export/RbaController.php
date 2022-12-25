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
use App\Models\Divisi;
use App\Models\Misi;
use App\Models\Program;
use App\Models\Kegiatan;

use App\Exports\RbaExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class RbaController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function export()
    {
        try {
            $id_divisi = $this->request->id_divisi;
            $divisi = Divisi::find($id_divisi);
            $records = \DB::SELECT("
                SELECT
                    msi.id_misi,
                    msi.nm_misi
                FROM
                    kegiatan_divisi AS kdiv
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                    AND kgt.deleted_at IS NULL
                    JOIN program AS pr ON pr.id_program = kgt.id_program
                    AND pr.deleted_at IS NULL
                    JOIN misi AS msi ON msi.id_misi = pr.id_misi
                    AND msi.deleted_at IS NULL
                WHERE
                    kdiv.id_divisi = '".$id_divisi."'
                ORDER BY
                    msi.nm_misi DESC
            ");

            $filename = 'Dokumen RBA '.strtoupper($divisi->nm_divisi).'.xlsx';    
            return Excel::download(new RbaExcelExport($divisi, $records), $filename);

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
