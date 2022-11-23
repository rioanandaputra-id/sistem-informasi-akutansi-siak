<?php

namespace App\Http\Controllers\KepalaWilayah;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProgramController;
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

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mProgram = app(ProgramController::class);
        $this->mRba = app(Rba::class);
    }

    public function apiGetAll()
    {
        $a_verif_wilayah = ($this->request->a_verif_wilayah) ? " AND  rba.a_verif_wilayah = '" . $this->request->a_verif_wilayah . "'" : "";
        $id_program = ($this->request->id_program) ? " AND  kgt.id_program = '" . $this->request->id_program . "'" : "";
        $id_divisi = ($this->request->id_divisi) ? " AND  div.id_divisi = '" . $this->request->id_divisi . "'" : "";
        $apiGetAll = DB::select("
            SELECT
                rba.id_rba,
                rba.tgl_buat,
                rba.tgl_submit,
                rba.catatan,
                rba.id_kegiatan_divisi,
                CASE rba.a_verif_rba
                WHEN '2' THEN 'Disetujui'
                WHEN '3' THEN 'Tidak Disetujui'
                ELSE 'Belum Diverifikasi'
                END AS a_verif_rba,
                rba.id_verif_rba,
                rba.tgl_verif_rba,
                CASE rba.a_verif_wilayah
                WHEN '2' THEN 'Disetujui'
                WHEN '3' THEN 'Tidak Disetujui'
                ELSE 'Belum Diverifikasi'
                END AS a_verif_wilayah,
                rba.id_verif_wilayah,
                rba.tgl_verif_wilayah,
                rba.created_at,
                rba.updated_at,
                rba.deleted_at,
                rba.id_updater,
                kdiv.id_divisi,
                kdiv.id_kegiatan,
                CASE kdiv.a_verif_rba
                WHEN '2' THEN 'Disetujui'
                WHEN '3' THEN 'Tidak Disetujui'
                ELSE 'Belum Diverifikasi'
                END AS kdiv_a_verif_rba,
                kdiv.catatan AS kdiv_catatan,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                div.nm_divisi,
                kgt.id_program,
                kgt.nm_kegiatan
            FROM
                rba AS rba
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = rba.id_kegiatan_divisi AND kdiv.deleted_at IS NULL
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program AND pgm.deleted_at IS NULL
            WHERE
                rba.deleted_at IS NULL
                AND rba.a_verif_rba = '2'
                " . $a_verif_wilayah . "
                " . $id_program . "
                " . $id_divisi . "
        ");
        return DaTables::of($apiGetAll)->make(true);
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

    public function viewGetAll()
    {
        $info = [
            'title' => 'Monitoring Kegiatan',
            'site_active' => 'MonitoringKegiatan',
        ];
        $program = $this->mProgram->apiGetAll()['response'] ?? [];
        return view('pages._kepalaWilayah.kegiatanMonitoring.viewGetAll', compact('info', 'program'));
    }
}
