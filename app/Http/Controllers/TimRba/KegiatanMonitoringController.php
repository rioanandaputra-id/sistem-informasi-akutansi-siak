<?php

namespace App\Http\Controllers\TimRba;

use App\Http\Controllers\Controller;
use App\Models\KegiatanDivisi;
use App\Models\Rba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KegiatanMonitoringController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {
        $kdiv_a_verif_rba = ($this->request->kdiv_a_verif_rba) ? " AND  kdiv.a_verif_rba = '".$this->request->kdiv_a_verif_rba."'" : "";
        $apiGetAll = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                div.nm_divisi,
                kdiv.id_kegiatan,
                CONCAT('( ', msi.periode, ' ) ', msi.nm_misi) AS nm_misi,
                CONCAT('( ', pgm.periode, ' ) ', pgm.nm_program) AS nm_program,
                kgt.nm_kegiatan,
                rba.id_rba,
                rba.tgl_submit,
                CASE
                    kdiv.a_verif_rba
                    WHEN '2' THEN 'Disetujui TIM RBA'
                    WHEN '3' THEN 'Ditolak TIM RBA'
                    ELSE 'Belum Diverifikasi TIM RBA'
                END AS kdiv_a_verif_rba,
                CASE
                    rba.a_verif_rba
                    WHEN '2' THEN 'Disetujui Kepala UUD'
                    WHEN '3' THEN 'Ditolak Kepala UUD'
                    ELSE 'Belum Diverifikasi Kepala UUD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Wilayah'
                    WHEN '3' THEN 'Ditolak Kepala Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Wilayah'
                END AS rba_a_verif_wilayah,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                kdiv.tgl_verif_rba AS kdiv_tgl_verif_rba,
                kdiv.catatan AS kdiv_catatan,
                rba.id_verif_rba AS rba_id_verif_rba,
                rba.tgl_verif_rba AS rba_tgl_verif_rba,
                rba.catatan_verif_rba AS rba_catatan_verif_rba,
                rba.id_verif_wilayah AS rba_id_verif_wilayah,
                rba.tgl_verif_wilayah AS rba_tgl_verif_wilayah,
                rba.catatan_verif_wilayah AS rba_catatan_verif_wilayah
            FROM
                kegiatan_divisi AS kdiv
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
                LEFT JOIN rba AS rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
            WHERE
                kdiv.deleted_at IS NULL
                AND rba.tgl_submit IS NOT NULL
                ".$kdiv_a_verif_rba."
        ");
        return DaTables::of($apiGetAll)->make(true);
    }

    public function apiGetById()
    {
        $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
        $apiGetById = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                div.nm_divisi,
                kdiv.id_kegiatan,
                CONCAT('( ', msi.periode, ' ) ', msi.nm_misi) AS nm_misi,
                CONCAT('( ', pgm.periode, ' ) ', pgm.nm_program) AS nm_program,
                kgt.nm_kegiatan,
                rba.id_rba,
                rba.tgl_submit,
                CASE
                    kdiv.a_verif_rba
                    WHEN '2' THEN 'Disetujui TIM RBA'
                    WHEN '3' THEN 'Ditolak TIM RBA'
                    ELSE 'Belum Diverifikasi TIM RBA'
                END AS kdiv_a_verif_rba,
                CASE
                    rba.a_verif_rba
                    WHEN '2' THEN 'Disetujui Kepala UUD'
                    WHEN '3' THEN 'Ditolak Kepala UUD'
                    ELSE 'Belum Diverifikasi Kepala UUD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Wilayah'
                    WHEN '3' THEN 'Ditolak Kepala Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Wilayah'
                END AS rba_a_verif_wilayah,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                kdiv.tgl_verif_rba AS kdiv_tgl_verif_rba,
                kdiv.catatan AS kdiv_catatan,
                rba.id_verif_rba AS rba_id_verif_rba,
                rba.tgl_verif_rba AS rba_tgl_verif_rba,
                rba.catatan_verif_rba AS rba_catatan_verif_rba,
                rba.id_verif_wilayah AS rba_id_verif_wilayah,
                rba.tgl_verif_wilayah AS rba_tgl_verif_wilayah,
                rba.catatan_verif_wilayah AS rba_catatan_verif_wilayah
            FROM
                kegiatan_divisi AS kdiv
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
                JOIN rba AS rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
            WHERE
                kdiv.deleted_at IS NULL
                AND rba.tgl_submit IS NOT NULL
                AND kdiv.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
        ");
        return $apiGetById;
    }

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_kegiatan_divisi' => 'required',
                'a_verif_rba' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'BadRequest',
                    'error' => $validator->errors(),
                    'response' => null
                ];
            }

            $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
            $a_verif_rba = $this->request->a_verif_rba;
            $tgl_verif_rba = now();
            $id_verif_rba = Auth::user()->id_user;
            $catatan = $this->request->catatan;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            KegiatanDivisi::whereIn('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                'a_verif_rba' => $a_verif_rba,
                'tgl_verif_rba' => $tgl_verif_rba,
                'id_verif_rba' => $id_verif_rba,
                'catatan' => $catatan,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Updated',
                'error' => null,
                'response' => ['id_kegiatan_divisi' => $id_kegiatan_divisi]
            ];
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'QueryException',
                'error' => null,
                'response' => null
            ];
        } catch (Exception $e) {
            DB::rollBack();
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
            'title' => 'Monitoring Kegiatan',
            'site_active' => 'MonitoringKegiatan',
        ];
        return view('pages._timRba.kegiatanMonitoring.viewGetAll', compact('info'));
    }

    public function viewDetail()
    {
        $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
        $info = [
            'title' => 'Detail Monitoring Kegiatan',
            'site_active' => 'MonitoringKegiatan',
        ];
        $kegiatan = $this->apiGetById();
        $detailRba = DB::select("
            SELECT
                drba.id_detail_rba,
                drba.id_rba,
                drba.id_akun,
                drba.vol,
                drba.satuan,
                drba.indikator,
                drba.tarif,
                drba.total,
                CASE
                    drba.a_setuju
                    WHEN '2' THEN 'Disetujui'
                    WHEN '3' THEN 'Ditolak'
                    ELSE 'Belum Diverifikasi'
                END AS a_setuju,
                drba.created_at,
                drba.updated_at,
                drba.deleted_at,
                drba.id_updater,
                akn.no_akun,
                akn.nm_akun,
                akn.sumber_akun,
                akn.keterangan
            FROM
                detail_rba AS drba
                JOIN rba as rba ON rba.id_rba = drba.id_rba
                AND rba.deleted_at IS NULL AND rba.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                drba.deleted_at IS NULL
        ");
        $detailLaksKegiatan = DB::select("
            SELECT
                dkgt.id_detail_laksana_kegiatan,
                dkgt.id_laksana_kegiatan,
                dkgt.id_detail_rba,
                dkgt.jumlah,
                dkgt.total,
                dkgt.created_at,
                dkgt.updated_at,
                dkgt.deleted_at,
                dkgt.id_updater,
                lkgt.id_kegiatan_divisi,
                lkgt.tgl_ajuan,
                CASE
                    lkgt.a_verif_bend_kegiatan
                    WHEN '2' THEN 'Disetujui Bend. Kegiatan'
                    WHEN '3' THEN 'Ditolak Bend. Kegiatan'
                    ELSE 'Belum Diverifikasi Bend. Kegiatan'
                END AS a_verif_bend_kegiatan,
                lkgt.id_verif_bend_kegiatan,
                lkgt.tgl_verif_bend_kegiatan,
                lkgt.catatan,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                lkgt.tahun,
                akn.id_akun,
                akn.no_akun,
                akn.nm_akun
            FROM
                detail_laksana_kegiatan AS dkgt
                JOIN laksana_kegiatan AS lkgt ON lkgt.id_laksana_kegiatan = dkgt.id_laksana_kegiatan
                AND lkgt.deleted_at IS NULL
                JOIN detail_rba AS drba ON drba.id_detail_rba = dkgt.id_detail_rba
                AND drba.deleted_at IS NULL
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                dkgt.deleted_at IS NULL
                AND lkgt.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
        ");
        return view('pages._timRba.kegiatanMonitoring.viewDetail', compact('info', 'kegiatan', 'detailRba', 'detailLaksKegiatan'));
    }
}
