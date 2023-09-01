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

use App\Exports\SpjKegiatanExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class SpjController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
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
                    rba.tgl_submit,
                    kdiv.id_kegiatan_divisi
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
                    laks.id_laksana_kegiatan,
                    laks.urutan_laksana_kegiatan,
                    laks.tgl_ajuan,
                    laks.lokasi,
                    laks.waktu_pelaksanaan,
                    laks.waktu_selesai,
                    laks.tahun,
                    (
                        SELECT
                            SUM(dlaks.total)
                        FROM
                            detail_laksana_kegiatan AS dlaks
                        WHERE
                            dlaks.id_laksana_kegiatan=laks.id_laksana_kegiatan AND dlaks.deleted_at IS NULL
                    ) AS total_pengajuan
                FROM
                    laksana_kegiatan AS laks
                WHERE
                    laks.deleted_at IS NULL
                    AND laks.id_kegiatan_divisi='".$records->id_kegiatan_divisi."'
                ORDER BY
                    laks.urutan_laksana_kegiatan ASC
            ");

            foreach($data AS $item) {
                $item->spj = \DB::SELECT("
                    SELECT
                        CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                        akn.nm_akun,
                        SUM(dspj.total) AS total_realisasi
                    FROM
                        spj
                        JOIN detail_spj AS dspj ON dspj.id_spj=spj.id_spj AND dspj.deleted_at IS NULL
                        JOIN akun AS akn ON akn.id_akun=dspj.id_akun AND akn.deleted_at IS NULL
                    WHERE
                        spj.deleted_at IS NULL
                        AND spj.id_laksana_kegiatan='".$item->id_laksana_kegiatan."'
                    GROUP BY
                        akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun, akn.nm_akun
                    ORDER BY
                        no_akun ASC
                ");
            }

            $records->detail = $data;

            $filename = 'Dokumen SPJ '.strtoupper($records->nm_divisi).' - '.$records->nm_kegiatan.'.xlsx';
            return Excel::download(new SpjKegiatanExcelExport($records->nm_divisi, $records), $filename);

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
