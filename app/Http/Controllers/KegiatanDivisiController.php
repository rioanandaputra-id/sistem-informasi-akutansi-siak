<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDivisi;
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
    private $mKegiatanDivisi;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mKegiatanDivisi = app(KegiatanDivisi::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    kgd.id_kegiatan_divisi,
                    kgd.id_divisi,
                    kgd.id_kegiatan,
                    kgd.a_verif_rba,
                    kgd.id_verif_rba,
                    kgd.catatan,
                    kgd.created_at,
                    kgd.updated_at,
                    kgd.id_updated,
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
                    ORDER BY kgt.nm_kegiatan ASC
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
                    kgd.id_updated,
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
                    $exists = $this->mKegiatanDivisi->where('id_divisi', $id_divisi)->where('id_kegiatan', $v)->exists();
                    if (!$exists) {
                        $this->mKegiatanDivisi->create([
                            'id_kegiatan_divisi' => guid(),
                            'id_divisi' => $id_divisi,
                            'id_kegiatan' => $v,
                            'a_verif_rba' => 1,
                            'created_at' => $created_at,
                            'id_updater' => $id_updater,
                        ]);
                    }
                }
            } else {
                $this->mKegiatanDivisi->create([
                    'id_kegiatan_divisi' => guid(),
                    'id_divisi' => $id_divisi,
                    'id_kegiatan' => $id_kegiatan,
                    'a_verif_rba' => 1,
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

    public function apiUpdate($noApi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_kegiatan_divisi' => 'required',
                'id_divisi' => 'required',
                'id_kegiatan' => 'required',
                // 'a_verif_rba' => 'required',
                // 'id_verif_rba' => 'required',
                // 'catatan' => 'required',
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

            $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
            $id_divisi = $this->request->id_divisi;
            $id_kegiatan = $this->request->id_kegiatan;
            $a_verif_rba = $this->request->a_verif_rba;
            $id_verif_rba = $this->request->id_verif_rba;
            $catatan = $this->request->catatan;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mKegiatanDivisi->where('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                'id_divisi' => $id_divisi,
                'id_kegiatan' => $id_kegiatan,
                'a_verif_rba' => $a_verif_rba,
                'id_verif_rba' => $id_verif_rba,
                'catatan' => $catatan,
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
}
