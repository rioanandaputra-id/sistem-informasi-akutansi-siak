<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDivisi;
use App\Models\Rba;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class KegiatanDivisiController extends Controller
{
    private $request;
    private $mProgram;
    private $mKegiatanDivisi;
    private $mRba;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mProgram = app(ProgramController::class);
        $this->mKegiatanDivisi = app(KegiatanDivisi::class);
        $this->mRba = app(Rba::class);
    }

    public function apiGetAll()
    {
        try {
            $a_verif_rba = ($this->request->a_verif_rba) ? " AND  kgd.a_verif_rba = '".$this->request->a_verif_rba."'" : "";
            $id_program = ($this->request->id_program) ? " AND  kgt.id_program = '".$this->request->id_program."'" : "";
            $id_divisi = (Auth::user()->id_divisi != 'da138a9a-23ed-4941-932d-d1a457db0cdf') ? " AND  div.id_divisi = '".Auth::user()->id_divisi."'" : "";
            $apiGetAll = DB::select("
                SELECT
                    kgd.id_kegiatan_divisi,
                    kgd.id_divisi,
                    kgd.id_kegiatan,
                    CASE kgd.a_verif_rba
                    WHEN '2' THEN 'Disetujui'
                    WHEN '3' THEN 'Tidak Disetujui'
                    ELSE 'Belum Diverifikasi'
                    END AS a_verif_rba,
                    kgd.id_verif_rba,
                    kgd.catatan,
                    kgd.created_at,
                    kgd.updated_at,
                    kgd.id_updater,
                    kgt.nm_kegiatan,
                    div.nm_divisi
                FROM
                    kegiatan_divisi AS kgd
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan = kgd.id_kegiatan
                    AND kgt.deleted_at IS NULL
                    AND kgt.a_aktif = '1'
                    JOIN divisi AS div ON div.id_divisi = kgd.id_divisi
                    AND div.deleted_at IS NULL
                WHERE
                    kgd.deleted_at IS NULL
                    ".$a_verif_rba."
                    ".$id_program."
                    ".$id_divisi."
                    ORDER BY kgt.nm_kegiatan ASC
            ");
            if ($this->request->ajax()) {
                return DaTables::of($apiGetAll)->make(true);
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'OK',
                    'error' => null,
                    'response' => $apiGetAll
                ];
            }
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

    public function apiGetById($idKegiatanDivisi = null)
    {
        try {
            $id_kegiatan_divisi = $idKegiatanDivisi ?? $this->request->id_kegiatan_divisi;
            $apiGetById = DB::select("
                SELECT
                    kgd.id_kegiatan_divisi,
                    kgd.id_divisi,
                    kgd.id_kegiatan,
                    kgd.a_verif_rba,
                    kgd.id_verif_rba,
                    kgd.catatan,
                    kgd.created_at,
                    kgd.updated_at,
                    kgd.id_updater,
                    kgt.nm_kegiatan,
                    div.nm_divisi
                FROM
                    kegiatan_divisi AS kgd
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan = kgd.id_kegiatan
                    AND kgt.deleted_at IS NULL
                    AND kgt.a_aktif = '1'
                    JOIN divisi AS div ON div.id_divisi = kgd.id_divisi
                    AND div.deleted_at IS NULL
                WHERE
                    kgd.deleted_at IS NULL
                    AND kgd.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
                LIMIT
                    1
            ");
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'OK',
                'error' => null,
                'response' => $apiGetById
            ];
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

    public function apiCreate($noApi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_kegiatan' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                if ($no_api) {
                    return back()->withInput()->withErrors($validator);
                } else {
                    return [
                        'status' => false,
                        'latency' => AppLatency(),
                        'message' => 'BadRequest',
                        'error' => $validator->errors(),
                        'response' => null
                    ];
                }
            }

            $id_divisi = Auth::user()->id_divisi;
            $id_kegiatan = $this->request->id_kegiatan;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            if (is_array($id_kegiatan)) {
                foreach ($id_kegiatan as $v) {
                    $id_kegiatan_divisi = guid();
                    $exists = $this->mKegiatanDivisi->where('id_divisi', $id_divisi)->where('id_kegiatan', $v)->exists();
                    if (!$exists) {
                        $this->mKegiatanDivisi->create([
                            'id_kegiatan_divisi' => $id_kegiatan_divisi,
                            'id_divisi' => $id_divisi,
                            'id_kegiatan' => $v,
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
            if ($no_api) {
                return back()->with('success', 'Data Berhasil Ditambahkan!');
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Created',
                    'error' => null,
                    'response' => ['id_kegiatan' => $id_kegiatan]
                ];
            }
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            if ($no_api) {
                return back()->with('error', 'Internal Server Error | QueryException');
            } else {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'QueryException',
                    'error' => null,
                    'response' => null
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            if ($no_api) {
                return back()->with('error', 'Internal Server Error | Exception');
            } else {
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
            $id_verif_rba = Auth::user()->id_user;
            $catatan = $this->request->catatan;
            // $updated_at = now();

            $this->mKegiatanDivisi->whereIn('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                'id_verif_rba' => $id_verif_rba,
                'a_verif_rba' => $a_verif_rba,
                'catatan' => $catatan,
                // 'updated_at' => $updated_at,
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

    public function apiDelete($noApi = null, $idKegiatanDivisi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_kegiatan_divisi.*' => 'required|uuid',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                if ($no_api) {
                    return back()->withInput()->withErrors($validator);
                } else {
                    return [
                        'status' => false,
                        'latency' => AppLatency(),
                        'message' => 'BadRequest',
                        'error' => $validator->errors(),
                        'response' => null
                    ];
                }
            }

            $id_kegiatan_divisi = $idKegiatanDivisi ?? $this->request->id_kegiatan_divisi;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mKegiatanDivisi->whereIn('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            if ($no_api) {
                return back()->with('success', 'Data Berhasil Dihapus!');
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Deleted',
                    'error' => null,
                    'response' => ['id_kegiatan_divisi' => $id_kegiatan_divisi]
                ];
            }
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            if ($no_api) {
                return back()->with('error', 'Internal Server Error | QueryException');
            } else {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'QueryException',
                    'error' => null,
                    'response' => null
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            if ($no_api) {
                return back()->with('error', 'Internal Server Error | Exception');
            } else {
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

    public function viewGetAll()
    {
        $info = [
            'title' => 'Kegiatan',
            'site_active' => 'KegiatanDivisi',
        ];
        $program = $this->mProgram->apiGetAll()['response'] ?? [];
        return view('pages.kegiatanDivisi.viewGetAll', compact('info', 'program'));
    }
}
