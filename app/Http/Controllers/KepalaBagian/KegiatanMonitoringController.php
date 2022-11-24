<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProgramController;
use App\Models\DetailRba;
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
    private $mProgram;
    private $mRba;
    private $mDetailRba;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mProgram = app(ProgramController::class);
        $this->mRba = app(Rba::class);
        $this->mDetailRba = app(DetailRba::class);
    }

    public function apiGetAll()
    {
        $id_program = ($this->request->id_program) ? " AND  kgt.id_program = '" . $this->request->id_program . "'" : "";
        $id_divisi = (Auth::user()->id_divisi) ? " AND  kdiv.id_divisi = '" . Auth::user()->id_divisi . "'" : "";
        $apiGetAll = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                kdiv.id_kegiatan,
                msi.nm_misi,
                pgm.nm_program,
                kgt.nm_kegiatan,
                rba.id_rba,
                CASE
                    kdiv.a_verif_rba
                    WHEN '2' THEN 'Disetujui TIM RBA'
                    WHEN '3' THEN 'Tidak Disetujui TIM RBA'
                    ELSE 'Belum Diverifikasi TIM RBA'
                END AS kdiv_a_verif_rba,
                CASE
                    rba.a_verif_rba
                    WHEN '2' THEN 'Disetujui Kepala UUD'
                    WHEN '3' THEN 'Tidak Disetujui Kepala UUD'
                    ELSE 'Belum Diverifikasi Kepala UUD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Wilayah'
                    WHEN '3' THEN 'Tidak Disetujui Kepala Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Wilayah'
                END AS rba_a_verif_wilayah,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                rba.id_verif_rba AS rba_id_verif_rba,
                rba.id_verif_wilayah AS rba_id_verif_wilayah,
                kdiv.catatan AS kdiv_catatan,
                rba.catatan AS rba_catatan
            FROM
                kegiatan_divisi AS kdiv
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
                " . $id_program . "
                " . $id_divisi . "
        ");
        return DaTables::of($apiGetAll)->make(true);
    }

    public function apiGetById($idRba = null)
    {
        $id_rba = $idRba ?? $this->request->id_rba;
        $id_divisi = (Auth::user()->id_divisi) ? " AND  kdiv.id_divisi = '" . Auth::user()->id_divisi . "'" : "";
        $apiGetById = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                kdiv.id_kegiatan,
                msi.nm_misi,
                pgm.nm_program,
                kgt.nm_kegiatan,
                rba.id_rba,
                CASE
                    kdiv.a_verif_rba
                    WHEN '2' THEN 'Disetujui TIM RBA'
                    WHEN '3' THEN 'Tidak Disetujui TIM RBA'
                    ELSE 'Belum Diverifikasi TIM RBA'
                END AS kdiv_a_verif_rba,
                CASE
                    rba.a_verif_rba
                    WHEN '2' THEN 'Disetujui Kepala UUD'
                    WHEN '3' THEN 'Tidak Disetujui Kepala UUD'
                    ELSE 'Belum Diverifikasi Kepala UUD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Wilayah'
                    WHEN '3' THEN 'Tidak Disetujui Kepala Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Wilayah'
                END AS rba_a_verif_wilayah,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                rba.id_verif_rba AS rba_id_verif_rba,
                rba.id_verif_wilayah AS rba_id_verif_wilayah,
                kdiv.catatan AS kdiv_catatan,
                rba.catatan AS rba_catatan
            FROM
                kegiatan_divisi AS kdiv
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
                AND rba.id_rba = '" . $id_rba . "'
                " . $id_divisi . "
        ");
        return $apiGetById;
    }

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_rba' => 'required',
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

            $id_rba = $this->request->id_rba;
            $tgl_submit = now();
            $catatan = $this->request->catatan;
            $a_verif_wilayah = $this->request->a_verif_wilayah;
            $id_verif_wilayah = Auth::user()->id_user;
            $tgl_verif_wilayah = now();
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mRba->whereIn('id_rba', $id_rba)->update([
                'tgl_submit' => $tgl_submit,
                'catatan' => $catatan,
                'a_verif_wilayah' => $a_verif_wilayah,
                'id_verif_wilayah' => $id_verif_wilayah,
                'tgl_verif_wilayah' => $tgl_verif_wilayah,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Updated',
                'error' => null,
                'response' => ['id_rba' => $id_rba]
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

    public function apiCreateDetailRba()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_rba' => 'required|uuid',
                'id_akun' => 'required|uuid',
                'vol' => 'required|numeric',
                'satuan' => 'required',
                'tarif' => 'required|numeric',
                'total' => 'required|numeric',
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
            $id_detail_rba = guid();
            $id_rba = $this->request->id_rba;
            $id_akun = $this->request->id_akun;
            $vol = $this->request->vol;
            $satuan = $this->request->satuan;
            $tarif = $this->request->tarif;
            $total = $this->request->total;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            $this->mDetailRba->create([
                'id_detail_rba' => $id_detail_rba,
                'id_rba' => $id_rba,
                'id_akun' => $id_akun,
                'vol' => $vol,
                'satuan' => $satuan,
                'tarif' => $tarif,
                'total' => $total,
                'a_setuju' => 1,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_detail_rba' => $id_detail_rba]
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

    public function apiDeleteDetailRba()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_rba.*' => 'required|uuid',
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

            $id_detail_rba = $idKegiatanDivisi ?? $this->request->id_detail_rba;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailRba->whereIn('id_detail_rba', $id_detail_rba)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_detail_rba' => $id_detail_rba]
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
        $program = $this->mProgram->apiGetAll()['response'] ?? [];
        return view('pages._kepalaBagian.kegiatanMonitoring.viewGetAll', compact('info', 'program'));
    }

    public function viewDetail()
    {
        $id_rba = $this->request->id_rba;
        $info = [
            'title' => 'Detail Monitoring Kegiatan',
            'site_active' => 'MonitoringKegiatan',
        ];
        $kegiatan = $this->apiGetById($id_rba);
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
                    WHEN '3' THEN 'Tidak Disetujui'
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
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                drba.deleted_at IS NULL
                AND drba.id_rba = '" . $id_rba . "'
        ");
        $akun = DB::select("SELECT * FROM akun WHERE no_akun_induk = '5'");
        return view('pages._kepalaBagian.kegiatanMonitoring.viewDetail', compact('info', 'kegiatan', 'detailRba', 'akun'));
    }
}
