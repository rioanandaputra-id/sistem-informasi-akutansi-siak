<?php

namespace App\Http\Controllers\KepalaWilayah;

use App\Http\Controllers\Controller;
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
    private $mRba;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mRba = app(Rba::class);
    }

    public function apiGetAll()
    {
        $rba_a_verif_wilayah = ($this->request->rba_a_verif_wilayah) ? " AND  rba.a_verif_wilayah = '".$this->request->rba_a_verif_wilayah."'" : "";
        $id_kegiatan = ($this->request->id_kegiatan) ? " AND  kgt.id_kegiatan = '" . $this->request->id_kegiatan . "'" : "";
        $id_divisi = ($this->request->id_divisi) ? " AND  div.id_divisi = '" . $this->request->id_divisi . "'" : "";
        $apiGetAll = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                div.nm_divisi,
                kdiv.id_kegiatan,
                CONCAT('[ ', msi.periode, ' ] ', msi.nm_misi) AS nm_misi,
                CONCAT('[ ', pgm.periode, ' ] ', pgm.nm_program) AS nm_program,
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
                    WHEN '2' THEN 'Disetujui Kepala UDD'
                    WHEN '3' THEN 'Ditolak Kepala UDD'
                    ELSE 'Belum Diverifikasi Kepala UDD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Pengurus Wilayah'
                    WHEN '3' THEN 'Ditolak Kepala Pengurus Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Pengurus Wilayah'
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
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
                LEFT JOIN rba AS rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
            WHERE
                kdiv.deleted_at IS NULL
                AND rba.tgl_submit IS NOT NULL
                AND rba.a_verif_rba = '2'
                ".$rba_a_verif_wilayah."
                " . $id_kegiatan . "
                " . $id_divisi . "
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
                CONCAT('[ ', msi.periode, ' ] ', msi.nm_misi) AS nm_misi,
                CONCAT('[ ', pgm.periode, ' ] ', pgm.nm_program) AS nm_program,
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
                    WHEN '2' THEN 'Disetujui Kepala UDD'
                    WHEN '3' THEN 'Ditolak Kepala UDD'
                    ELSE 'Belum Diverifikasi Kepala UDD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Pengurus Wilayah'
                    WHEN '3' THEN 'Ditolak Kepala Pengurus Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Pengurus Wilayah'
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
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
                JOIN rba AS rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
            WHERE
                kdiv.deleted_at IS NULL
                AND rba.tgl_submit IS NOT NULL
                AND rba.a_verif_rba = '2'
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
                'a_verif_wilayah' => 'required',
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
            $catatan_verif_wilayah = $this->request->catatan_verif_wilayah;
            $a_verif_wilayah = $this->request->a_verif_wilayah;
            $id_verif_wilayah = Auth::user()->id_user;
            $tgl_verif_wilayah = now();
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mRba->whereIn('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                'catatan_verif_wilayah' => $catatan_verif_wilayah,
                'a_verif_wilayah' => $a_verif_wilayah,
                'id_verif_wilayah' => $id_verif_wilayah,
                'tgl_verif_wilayah' => $tgl_verif_wilayah,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            if($a_verif_wilayah=='3') {
                \App\Models\Rba::whereIn('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                    'tgl_submit' => null
                ]);
            }

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
        $kegiatan = DB::select("
            SELECT
                kgt.id_kegiatan,
                kgt.nm_kegiatan
            FROM
                kegiatan AS kgt
            WHERE
                kgt.deleted_at IS NULL
                AND kgt.a_aktif = '1'
            ORDER BY
                kgt.nm_kegiatan ASC
        ");
        $divisi = DB::select("
            SELECT
                div.id_divisi,
                div.nm_divisi
            FROM
                divisi AS div
            WHERE
                div.deleted_at IS NULL
                AND div.id_divisi != 'da138a9a-23ed-4941-932d-d1a457db0cdf'
            ORDER BY
                div.nm_divisi ASC
        ");
        return view('pages._kepalaWilayah.kegiatanMonitoring.viewGetAll', compact('info', 'kegiatan', 'divisi'));
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
                drba.created_at,
                drba.updated_at,
                drba.deleted_at,
                drba.id_updater,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun,
                akn.keterangan
            FROM
                detail_rba AS drba
                JOIN rba as rba ON rba.id_rba = drba.id_rba
                AND rba.deleted_at IS NULL AND rba.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                drba.deleted_at IS NULL
            ORDER BY drba.created_at ASC
        ");
        $laksKegiatan = DB::select("
            SELECT
                lkgt.id_laksana_kegiatan,
                lkgt.id_kegiatan_divisi,
                lkgt.tgl_ajuan,
                lkgt.urutan_laksana_kegiatan,
                CASE
                    lkgt.a_verif_kabag_keuangan
                    WHEN '2' THEN 'Disetujui Kabag. Keuangan'
                    WHEN '3' THEN 'Ditolak Kabag. Keuangan'
                    ELSE 'Belum Diverifikasi Kabag. Keuangan'
                END AS a_verif_kabag_keuangan,
                lkgt.id_verif_kabag_keuangan,
                lkgt.tgl_verif_kabag_keuangan,
                lkgt.catatan,
                lkgt.lokasi,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                lkgt.tahun,
                lkgt.created_at,
                lkgt.updated_at,
                lkgt.deleted_at,
                lkgt.id_updater,
                kgt.id_kegiatan,
                kgt.nm_kegiatan,
                (
                    SELECT
                        SUM(total)
                    FROM
                        detail_laksana_kegiatan
                    WHERE
                        deleted_at IS NULL
                        AND id_laksana_kegiatan = lkgt.id_laksana_kegiatan
                ) AS total_anggaran
            FROM
                laksana_kegiatan AS lkgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
            ORDER BY lkgt.created_at ASC
        ");
        
        $akun = \DB::SELECT("
            SELECT
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                akun AS akn
            WHERE
                akn.elemen IN ('1','5')
                AND akn.no_akun > '0000'
                AND akn.deleted_at IS NULL
        ");
        return view('pages._kepalaWilayah.kegiatanMonitoring.viewDetail', compact('info', 'kegiatan', 'detailRba', 'akun', 'laksKegiatan'));
    }

    public function viewGetAllLaksanaDetail()
    {
        $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
        $info = [
            'title' => 'Detail Pelaksanaan Kegiatan | Monitoring Kegiatan',
            'site_active' => 'MonitoringKegiatan',
        ];

        $kegiatan = DB::select("
            SELECT
                lkgt.id_laksana_kegiatan,
                div.nm_divisi,
                msi.nm_misi,
                pgm.nm_program,
                kgt.nm_kegiatan,
                lkgt.urutan_laksana_kegiatan,
                lkgt.tgl_ajuan,
                lkgt.lokasi,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                CASE
                    lkgt.a_verif_kabag_keuangan
                    WHEN '2' THEN 'Disetujui Kabag. Keuangan'
                    WHEN '3' THEN 'Ditolak Kabag. Keuangan'
                    ELSE 'Belum Diverifikasi Kabag. Keuangan'
                END AS a_verif_kabag_keuangan,
                lkgt.tgl_verif_kabag_keuangan,
                lkgt.catatan,
                (
                    SELECT
                        SUM(dlkgt.total)
                    FROM
                        detail_laksana_kegiatan AS dlkgt
                        JOIN laksana_kegiatan AS llkgt ON dlkgt.id_laksana_kegiatan = llkgt.id_laksana_kegiatan
                        AND llkgt.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                        AND llkgt.deleted_at IS NULL
                    WHERE
                        dlkgt.deleted_at IS NULL
                ) AS total_anggaran_terpakai,
                (
                    SELECT
                        SUM(drba.total)
                    FROM
                        rba AS rba
                        JOIN detail_rba AS drba ON drba.id_rba = rba.id_rba
                        AND drba.deleted_at IS NULL
                    WHERE
                        rba.deleted_at IS NULL
                        AND rba.a_verif_wilayah = '2'
                        AND rba.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                ) AS total_anggaran_tersedia
            FROM
                laksana_kegiatan AS lkgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
        ");

        $detailLaks = DB::select("
            SELECT
                dlkgt.id_detail_laksana_kegiatan,
                dlkgt.id_laksana_kegiatan,
                dlkgt.id_detail_rba,
                dlkgt.total,
                dlkgt.created_at,
                dlkgt.updated_at,
                dlkgt.deleted_at,
                dlkgt.id_updater,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                detail_laksana_kegiatan AS dlkgt
                JOIN detail_rba AS drba ON drba.id_detail_rba = dlkgt.id_detail_rba
                AND drba.deleted_at IS NULL
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                dlkgt.deleted_at IS NULL
                AND dlkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
            ORDER BY dlkgt.created_at ASC
        ");

        $akun = DB::select("
            SELECT
                drba.id_detail_rba,
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.no_akun_induk,
                akn.nm_akun
            FROM
                laksana_kegiatan AS lkgt
                JOIN rba AS rba ON rba.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
                JOIN detail_rba AS drba ON drba.id_rba = drba.id_rba
                AND drba.deleted_at IS NULL
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
            ORDER BY no_akun ASC
        ");

        return view('pages._kepalaWilayah.kegiatanMonitoring.viewGetAllLaksanaDetail', compact('info', 'kegiatan', 'detailLaks', 'akun'));
    }
}
