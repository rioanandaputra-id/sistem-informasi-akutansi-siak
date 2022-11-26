<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;

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
                    pgm.id_program,
                    CONCAT('( ', msi.periode, ' ) ', msi.nm_misi) AS nm_misi,
                    CONCAT('( ', pgm.periode, ' ) ', pgm.nm_program) AS nm_program,
                    CASE
                        WHEN pgm.a_aktif = '1' THEN 'Aktif'
                        ELSE 'Non Aktif'
                    END AS a_aktif,
                    pgm.created_at,
                    pgm.updated_at,
                    pgm.deleted_at,
                    pgm.id_updater
                FROM
                    program AS pgm
                    JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                    AND msi.deleted_at IS NULL
                    AND msi.a_aktif = '1'
                WHERE
                    pgm.deleted_at IS NULL
                ORDER BY
                    msi.periode,
                    msi.nm_misi,
                    pgm.periode,
                    pgm.nm_program ASC
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

    public function apiGetById()
    {
        try {
            $id_program = $this->request->id_program;
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

    public function apiCreate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_misi' => 'required|uuid',
                'nm_program' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_program = guid();
            $id_misi = $this->request->id_misi;
            $nm_program = $this->request->nm_program;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            Program::create([
                'id_misi' => $id_misi,
                'id_program' => $id_program,
                'nm_program' => $nm_program,
                'periode' => $periode,
                'a_aktif' => $a_aktif,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return back()->with('success', 'Data Berhasil Ditambahkan!');
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return back()->with('error', 'Internal Server Error | QueryException');
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return back()->with('error', 'Internal Server Error | Exception');
        }
    }

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_program' => 'required|uuid',
                'id_misi' => 'required|uuid',
                'nm_program' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_program = $this->request->id_program;
            $id_misi = $this->request->id_misi;
            $nm_program = $this->request->nm_program;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            Program::where('id_program', $id_program)->update([
                'id_misi' => $id_misi,
                'nm_program' => $nm_program,
                'periode' => $periode,
                'a_aktif' => $a_aktif,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return back()->with('success', 'Data Berhasil Diubah!');
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return back()->with('error', 'Internal Server Error | QueryException');
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return back()->with('error', 'Internal Server Error | Exception');
        }
    }

    public function apiDelete()
    {
        try {
            DB::beginTransaction();
            $rules = ['id_program.*' => 'required|uuid'];
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
            $id_program = $this->request->id_program;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            Program::whereIn('id_program', $id_program)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_program' => $id_program]
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
            'title' => 'Program',
            'site_active' => 'Program',
        ];
        return view('pages._kepalaUud.program.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Program',
            'site_active' => 'Program',
        ];
        $misi = DB::select("
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
        return view('pages._kepalaUud.program.viewCreate', compact('info', 'misi'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Program',
            'site_active' => 'Program',
        ];
        $program = $this->apiGetById()['response'];
        $misi = DB::select("
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
        return view('pages._kepalaUud.program.viewUpdate', compact('info', 'program', 'misi'));
    }
}
