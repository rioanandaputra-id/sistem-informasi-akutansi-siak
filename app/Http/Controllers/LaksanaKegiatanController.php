<?php

namespace App\Http\Controllers;

use App\Models\LaksanaKegiatan;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class LaksanaKegiatanController extends Controller
{
    private $request;
    private $mLaksanaKegiatan;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mLaksanaKegiatan = app(LaksanaKegiatan::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    lkgt.id_laksana_kegiatan,
                    lkgt.id_kegiatan_divisi,
                    kdiv.id_kegiatan_divisi,
                    kdiv.id_divisi,
                    kdiv.id_kegiatan,
                    kgt.id_program,
                    pgm.id_misi,
                    kdiv.id_verif_rba,
                    lkgt.tgl_ajuan,
                    lkgt.a_verif_kabag_keuangan,
                    lkgt.id_verif_kabag_keuangan,
                    lkgt.tgl_verif_kabag_keuangan,
                    lkgt.catatan,
                    lkgt.waktu_pelaksanaan,
                    lkgt.waktu_selesai,
                    lkgt.tahun,
                    kdiv.a_verif_rba,
                    kdiv.catatan,
                    div.nm_divisi,
                    kgt.nm_kegiatan,
                    kgt.a_aktif,
                    pgm.nm_program,
                    pgm.periode,
                    pgm.a_aktif,
                    msi.nm_misi,
                    msi.periode,
                    msi.a_aktif,
                    lkgt.created_at,
                    lkgt.updated_at,
                    lkgt.deleted_at,
                    lkgt.id_updater
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
                    JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                    AND msi.deleted_at IS NULL
            ");
            if ($this->request->ajax()) {
                return DaTables::of($apiGetAll)->make(true);
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Created',
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

    public function apiGetById($idLaksanaKegiatan = null)
    {
        try {
            $id_laksana_kegiatan = $idLaksanaKegiatan ?? $this->request->id_laksana_kegiatan;
            $apiGetById = DB::select("
                SELECT
                    lkgt.id_laksana_kegiatan,
                    lkgt.id_kegiatan_divisi,
                    kdiv.id_kegiatan_divisi,
                    kdiv.id_divisi,
                    kdiv.id_kegiatan,
                    kgt.id_program,
                    pgm.id_misi,
                    kdiv.id_verif_rba,
                    lkgt.tgl_ajuan,
                    lkgt.a_verif_kabag_keuangan,
                    lkgt.id_verif_kabag_keuangan,
                    lkgt.tgl_verif_kabag_keuangan,
                    lkgt.catatan,
                    lkgt.waktu_pelaksanaan,
                    lkgt.waktu_selesai,
                    lkgt.tahun,
                    kdiv.a_verif_rba,
                    kdiv.catatan,
                    div.nm_divisi,
                    kgt.nm_kegiatan,
                    kgt.a_aktif,
                    pgm.nm_program,
                    pgm.periode,
                    pgm.a_aktif,
                    msi.nm_misi,
                    msi.periode,
                    msi.a_aktif,
                    lkgt.created_at,
                    lkgt.updated_at,
                    lkgt.deleted_at,
                    lkgt.id_updater
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
                    JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                    AND msi.deleted_at IS NULL
                WHERE
                    lkgt.deleted_at IS NULL
                    AND lkgt.id_laksana_kegiatan = '".$id_laksana_kegiatan."'
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
                'tgl_ajuan' => 'required',
                'a_verif_kabag_keuangan' => 'required',
                'id_verif_kabag_keuangan' => 'required',
                'tgl_verif_kabag_keuangan' => 'required',
                'catatan' => 'required',
                'waktu_pelaksanaan' => 'required',
                'waktu_selesai' => 'required',
                'tahun' => 'required',
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

            $id_kegiatan_divisi = guid();
            $tgl_ajuan = $this->request->tgl_ajuan;
            $a_verif_kabag_keuangan = $this->request->a_verif_kabag_keuangan;
            $id_verif_kabag_keuangan = $this->request->id_verif_kabag_keuangan;
            $tgl_verif_kabag_keuangan = $this->request->tgl_verif_kabag_keuangan;
            $catatan = $this->request->catatan;
            $waktu_pelaksanaan = $this->request->waktu_pelaksanaan;
            $waktu_selesai = $this->request->waktu_selesai;
            $tahun = $this->request->tahun;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mLaksanaKegiatan->create([
                'id_kegiatan_divisi' => $id_kegiatan_divisi,
                'tgl_ajuan' => $tgl_ajuan,
                'a_verif_kabag_keuangan' => $a_verif_kabag_keuangan,
                'id_verif_kabag_keuangan' => $id_verif_kabag_keuangan,
                'tgl_verif_kabag_keuangan' => $tgl_verif_kabag_keuangan,
                'catatan' => $catatan,
                'waktu_pelaksanaan' => $waktu_pelaksanaan,
                'waktu_selesai' => $waktu_selesai,
                'tahun' => $tahun,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            if ($no_api) {
                return back()->with('success', 'Data Berhasil Ditambahkan!');
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Created',
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

    public function apiUpdate($noApi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_kegiatan_divisi' => 'required',
                'tgl_ajuan' => 'required',
                'a_verif_kabag_keuangan' => 'required',
                'id_verif_kabag_keuangan' => 'required',
                'tgl_verif_kabag_keuangan' => 'required',
                'catatan' => 'required',
                'waktu_pelaksanaan' => 'required',
                'waktu_selesai' => 'required',
                'tahun' => 'required',
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

            $id_kegiatan_divisi = guid();
            $tgl_ajuan = $this->request->tgl_ajuan;
            $a_verif_kabag_keuangan = $this->request->a_verif_kabag_keuangan;
            $id_verif_kabag_keuangan = $this->request->id_verif_kabag_keuangan;
            $tgl_verif_kabag_keuangan = $this->request->tgl_verif_kabag_keuangan;
            $catatan = $this->request->catatan;
            $waktu_pelaksanaan = $this->request->waktu_pelaksanaan;
            $waktu_selesai = $this->request->waktu_selesai;
            $tahun = $this->request->tahun;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mLaksanaKegiatan->where('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                'tgl_ajuan' => $tgl_ajuan,
                'a_verif_kabag_keuangan' => $a_verif_kabag_keuangan,
                'id_verif_kabag_keuangan' => $id_verif_kabag_keuangan,
                'tgl_verif_kabag_keuangan' => $tgl_verif_kabag_keuangan,
                'catatan' => $catatan,
                'waktu_pelaksanaan' => $waktu_pelaksanaan,
                'waktu_selesai' => $waktu_selesai,
                'tahun' => $tahun,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            if ($no_api) {
                return back()->with('success', 'Data Berhasil Diubah!');
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Updated',
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

    public function apiDelete($noApi = null, $idLaksanaKegiatan = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_laksana_kegiatan.*' => 'required|uuid',
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

            $id_laksana_kegiatan = $idLaksanaKegiatan ?? $this->request->id_laksana_kegiatan;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mLaksanaKegiatan->whereIn('id_laksana_kegiatan', $id_laksana_kegiatan)->update([
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
                    'response' => ['id_laksana_kegiatan' => $id_laksana_kegiatan]
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
}
