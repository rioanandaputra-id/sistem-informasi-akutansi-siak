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
use App\Exports\RbaKegiatanExcelExport;
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
                    DISTINCT msi.id_misi,
                    array_to_string(array_agg(pr.id_program), ',') AS id_program,
                    CASE WHEN msi.id_misi IS NULL THEN '-' ELSE msi.nm_misi END AS nm_misi
                FROM
                    kegiatan_divisi AS kdiv
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                    AND kgt.deleted_at IS NULL
                    JOIN program AS pr ON pr.id_program = kgt.id_program
                    AND pr.deleted_at IS NULL
                    LEFT JOIN misi AS msi ON msi.id_misi = pr.id_misi
                    AND msi.deleted_at IS NULL
                WHERE
                    kdiv.id_divisi = '".$id_divisi."'
                GROUP BY
                    msi.id_misi,
                    nm_misi
                ORDER BY
                    nm_misi DESC
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

    public function exportKegiatan()
    {
        try {
            $id_kegiatan = $this->request->id_kegiatan;
            $records = \DB::SELECT("
                SELECT
                    dvs.nm_divisi,
                    kgt.nm_kegiatan,
                    pr.nm_program,
                    msi.nm_misi,
                    rba.id_rba,
                    rba.tgl_submit
                FROM
                    kegiatan AS kgt
                    JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan=kgt.id_kegiatan AND kdiv.deleted_at IS NULL
                    JOIN rba ON rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi AND rba.deleted_at IS NULL AND rba.tgl_submit IS NOT NULL
                    JOIN divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi AND dvs.deleted_at IS NULL
                    JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL
                    LEFT JOIN misi AS msi ON msi.id_misi=pr.id_misi AND msi.deleted_at IS NULL
                WHERE
                    kgt.id_kegiatan='".$id_kegiatan."'
                    AND kgt.deleted_at IS NULL
            ")[0];
            
            $data = \DB::SELECT("
                SELECT
                    CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                    akn.nm_akun,
                    drba.vol,
                    drba.indikator,
                    drba.satuan,
                    drba.tarif,
                    drba.total
                FROM
                    rba
                    JOIN detail_rba AS drba ON drba.id_rba=rba.id_rba AND drba.deleted_at IS NULL
                    JOIN akun AS akn ON akn.id_akun=drba.id_akun AND akn.deleted_at IS NULL
                WHERE
                    rba.deleted_at IS NULL
                    AND rba.id_rba='".$records->id_rba."'
                ORDER BY
                    no_akun ASC
            ");
            $records->detail = $data;

            $filename = 'Dokumen RBA '.strtoupper($records->nm_divisi).' - '.$records->nm_kegiatan.'.xlsx';    
            return Excel::download(new RbaKegiatanExcelExport($records->nm_divisi, $records), $filename);

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
