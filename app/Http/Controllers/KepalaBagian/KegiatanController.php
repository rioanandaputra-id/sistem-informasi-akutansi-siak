<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Models\KegiatanDivisi;
use App\Models\Rba;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    pgm.id_misi,
                    kgt.id_program,
                    kgt.id_kegiatan,
                    CONCAT('( ', msi.periode, ' ) ', msi.nm_misi) AS nm_misi,
                    CONCAT('( ', pgm.periode, ' ) ', pgm.nm_program) AS nm_program,
                    kgt.nm_kegiatan,
                    kgt.created_at,
                    kgt.updated_at,
                    kgt.id_updater
                FROM
                    kegiatan AS kgt
                    JOIN program AS pgm ON pgm.id_program = kgt.id_program
                    AND pgm.deleted_at IS NULL
                    AND pgm.a_aktif = '1'
                    JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                    AND msi.deleted_at IS NULL
                    AND msi.a_aktif = '1'
                WHERE
                    kgt.deleted_at IS NULL
                    AND kgt.a_aktif = '1'
                    AND kgt.id_kegiatan NOT IN(
                        SELECT
                            id_kegiatan
                        FROM
                            kegiatan_divisi
                        WHERE
                            deleted_at IS NULL
                    )
                ORDER BY
                    msi.periode,
                    msi.nm_misi,
                    pgm.periode,
                    pgm.nm_program,
                    kgt.nm_kegiatan ASC
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

    public function apiCreate()
    {
        try {
            DB::beginTransaction();
            $rules = ['id_kegiatan' => 'required'];
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

            $id_divisi = Auth::user()->id_divisi;
            $id_kegiatan = $this->request->id_kegiatan;
            $a_verif_rba = '1';
            $a_verif_wilayah = '1';
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            if (is_array($id_kegiatan)) {
                foreach ($id_kegiatan as $v) {
                    $id_kegiatan_divisi = guid();
                    $exists = KegiatanDivisi::where('id_divisi', $id_divisi)->where('id_kegiatan', $v)->exists();
                    if (!$exists) {
                        KegiatanDivisi::create([
                            'id_kegiatan_divisi' => $id_kegiatan_divisi,
                            'id_divisi' => $id_divisi,
                            'id_kegiatan' => $v,
                            'a_verif_rba' => $a_verif_rba,
                            'created_at' => $created_at,
                            'id_updater' => $id_updater,
                        ]);
                        Rba::create([
                            'id_rba' => guid(),
                            'tgl_buat' => $created_at,
                            'id_kegiatan_divisi' => $id_kegiatan_divisi,
                            'a_verif_rba' => $a_verif_rba,
                            'a_verif_wilayah' => $a_verif_wilayah,
                            'created_at' => $created_at,
                            'id_updater' => $id_updater,
                        ]);
                    } else {
                        return [
                            'status' => false,
                            'latency' => AppLatency(),
                            'message' => 'Exists',
                            'error' => null,
                            'response' => ['id_kegiatan' => $id_kegiatan]
                        ];
                    }
                }
            } else {
                $id_kegiatan_divisi = guid();
                $this->mKegiatanDivisi->create([
                    'id_kegiatan_divisi' => $id_kegiatan_divisi,
                    'id_divisi' => $id_divisi,
                    'id_kegiatan' => $id_kegiatan,
                    'a_verif_rba' => 1,
                    'created_at' => $created_at,
                    'id_updater' => $id_updater,
                ]);
                $this->mRba->create([
                    'id_rba' => guid(),
                    'tgl_buat' => $created_at,
                    'id_kegiatan_divisi' => $id_kegiatan_divisi,
                    'a_verif_rba' => 1,
                    'a_verif_wilayah' => 1,
                    'created_at' => $created_at,
                    'id_updater' => $id_updater,
                ]);
            }
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_kegiatan' => $id_kegiatan]
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
            'title' => 'Kegiatan',
            'site_active' => 'Kegiatan',
        ];
        return view('pages._kepalaBagian.kegiatan.viewGetAll', compact('info'));
    }
}
