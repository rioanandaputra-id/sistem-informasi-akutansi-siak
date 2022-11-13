<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    private $request;
    private $mProgram;
    private $mMisi;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mProgram = app(Program::class);
        $this->mMisi = app(MisiController::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    pgm.id_program,
                    pgm.id_misi,
                    pgm.nm_program,
                    pgm.periode AS periode_program,
                    pgm.a_aktif,
                    pgm.created_at,
                    pgm.updated_at,
                    pgm.deleted_at,
                    pgm.id_updater,
                    msi.nm_misi,
                    msi.periode AS periode_misi
                FROM
                    program AS pgm
                    JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                    AND msi.deleted_at IS NULL AND msi.a_aktif = '1'
                WHERE
                    pgm.deleted_at IS NULL
                ORDER BY
                    pgm.periode DESC
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

    public function apiGetById($idProgram = null)
    {
        try {
            $id_program = $idProgram ?? $this->request->id_program;
            $apiGetById = DB::select("
                SELECT
                    pgm.id_program,
                    pgm.id_misi,
                    pgm.nm_program,
                    pgm.periode AS periode_program,
                    pgm.a_aktif,
                    pgm.created_at,
                    pgm.updated_at,
                    pgm.deleted_at,
                    pgm.id_updater,
                    msi.nm_misi,
                    msi.periode AS periode_misi
                FROM
                    program AS pgm
                    JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                    AND msi.deleted_at IS NULL AND msi.a_aktif = '1'
                WHERE
                    pgm.deleted_at IS NULL
                    AND pgm.id_program = '" . $id_program . "'
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
                'id_misi' => 'required|uuid',
                'nm_program' => 'required|max:255',
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

            $id_program = guid();
            $id_misi = $this->request->id_misi;
            $nm_program = $this->request->nm_program;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mProgram->create([
                'id_misi' => $id_misi,
                'id_program' => $id_program,
                'nm_program' => $nm_program,
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
                    'response' => ['id_program' => $id_program]
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

    public function apiUpdate($noApi = null, $idProgram = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_program' => 'required|uuid',
                'id_misi' => 'required|uuid',
                'nm_program' => 'required|max:255',
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

            $id_program = $idProgram ?? $this->request->id_program;
            $id_misi = $this->request->id_misi;
            $nm_program = $this->request->nm_program;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mProgram->where('id_program', $id_program)->update([
                'id_misi' => $id_misi,
                'nm_program' => $nm_program,
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
                    'response' => ['id_program' => $id_program]
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

    public function apiDelete($noApi = null, $idProgram = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_program.*' => 'required|uuid',
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

            $id_program = $idProgram ?? $this->request->id_program;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mProgram->whereIn('id_program', $id_program)->update([
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
                    'response' => ['id_program' => $id_program]
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
            'title' => 'Program',
            'site_active' => 'Program',
        ];
        return view('pages.program.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Program',
            'site_active' => 'Program',
        ];
        $misi = $this->mMisi->apiGetAll()['response'] ?? [];
        return view('pages.program.viewCreate', compact('info', 'misi'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Program',
            'site_active' => 'Program',
        ];
        $program = $this->apiGetById()['response'];
        $misi = $this->mMisi->apiGetAll()['response'] ?? [];
        return view('pages.program.viewUpdate', compact('info', 'program', 'misi'));
    }
}
