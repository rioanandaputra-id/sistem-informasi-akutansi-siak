<?php

namespace App\Http\Controllers;

use App\Models\Misi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class MisiController extends Controller
{
    private $request;
    private $mMisi;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mMisi = app(Misi::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    msi.id_misi,
                    msi.nm_misi,
                    msi.periode,
                    CASE
                        WHEN msi.a_aktif = '1' THEN 'Aktif'
                        ELSE 'Non Aktif'
                    END AS a_aktif,
                    msi.created_at,
                    msi.updated_at,
                    msi.deleted_at
                FROM
                    misi AS msi
                WHERE
                    msi.deleted_at IS NULL
                ORDER BY
                    msi.periode DESC
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

    public function apiGetById($idMisi = null)
    {
        try {
            $id_misi = $idMisi ?? $this->request->id_misi;
            $apiGetById = DB::select("
                SELECT
                    msi.id_misi,
                    msi.nm_misi,
                    msi.periode,
                    msi.a_aktif,
                    msi.created_at,
                    msi.updated_at,
                    msi.deleted_at
                FROM
                    misi AS msi
                WHERE
                    msi.deleted_at IS NULL
                    AND msi.id_misi = '" . $id_misi . "'
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
                'nm_misi' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
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

            $id_misi = guid();
            $nm_misi = $this->request->nm_misi;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mMisi->create([
                'id_misi' => $id_misi,
                'nm_misi' => $nm_misi,
                'periode' => $periode,
                'a_aktif' => $a_aktif,
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
                    'response' => ['id_misi' => $id_misi]
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

    public function apiUpdate($noApi = null, $idMisi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_misi' => 'required|uuid',
                'nm_misi' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
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

            $id_misi = $idMisi ?? $this->request->id_misi;
            $nm_misi = $this->request->nm_misi;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mMisi->where('id_misi', $id_misi)->update([
                'nm_misi' => $nm_misi,
                'periode' => $periode,
                'a_aktif' => $a_aktif,
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
                    'response' => ['id_misi' => $id_misi]
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

    public function apiDelete($noApi = null, $idMisi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_misi.*' => 'required|uuid',
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

            $id_misi = $idMisi ?? $this->request->id_misi;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mMisi->whereIn('id_misi', $id_misi)->update([
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
                    'response' => ['id_misi' => $id_misi]
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
            'title' => 'Misi',
            'site_active' => 'Misi',
        ];
        return view('pages.misi.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Misi',
            'site_active' => 'Misi',
        ];
        return view('pages.misi.viewCreate', compact('info'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Misi',
            'site_active' => 'Misi',
        ];
        $misi = $this->apiGetById()['response'];
        return view('pages.misi.viewUpdate', compact('info', 'misi'));
    }
}
