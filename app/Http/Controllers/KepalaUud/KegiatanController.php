<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
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
                    kgt.id_program,
                    kgt.id_kegiatan,
                    CONCAT('( ', msi.periode, ' ) ', msi.nm_misi) AS nm_misi,
                    CONCAT('( ', pgm.periode, ' ) ', pgm.nm_program) AS nm_program,
                    kgt.nm_kegiatan,
                    CASE
                        WHEN kgt.a_aktif = '1' THEN 'Aktif'
                        ELSE 'Non Aktif'
                    END AS a_aktif,
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

    public function apiGetById()
    {
        try {
            $id_kegiatan = $this->request->id_kegiatan;
            $apiGetById = DB::select("
                SELECT
                    kgt.id_kegiatan,
                    kgt.id_program,
                    kgt.nm_kegiatan,
                    kgt.a_aktif,
                    pgm.nm_program,
                    pgm.periode AS periode_program,
                    kgt.created_at,
                    kgt.updated_at,
                    kgt.id_updater
                FROM
                    kegiatan AS kgt
                    JOIN program AS pgm ON pgm.id_program = kgt.id_program
                    AND pgm.deleted_at IS NULL AND pgm.a_aktif = '1'
                WHERE
                    kgt.deleted_at IS NULL
                    AND kgt.id_kegiatan = '" . $id_kegiatan . "'
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
                'id_program' => 'required|uuid',
                'nm_kegiatan' => 'required',
                'a_aktif' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_kegiatan = guid();
            $id_program = $this->request->id_program;
            $nm_kegiatan = $this->request->nm_kegiatan;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            Kegiatan::create([
                'id_program' => $id_program,
                'id_kegiatan' => $id_kegiatan,
                'nm_kegiatan' => $nm_kegiatan,
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
                'id_kegiatan' => 'required|uuid',
                'id_program' => 'required|uuid',
                'nm_kegiatan' => 'required',
                'a_aktif' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_kegiatan = $this->request->id_kegiatan;
            $id_program = $this->request->id_program;
            $nm_kegiatan = $this->request->nm_kegiatan;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            Kegiatan::where('id_kegiatan', $id_kegiatan)->update([
                'id_program' => $id_program,
                'nm_kegiatan' => $nm_kegiatan,
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
            $rules = ['id_kegiatan.*' => 'required|uuid'];
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
            $id_kegiatan = $this->request->id_kegiatan;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            Kegiatan::whereIn('id_kegiatan', $id_kegiatan)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
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
        return view('pages._kepalaUud.kegiatan.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Kegiatan',
            'site_active' => 'Kegiatan',
        ];
        $program = DB::select("
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
        return view('pages._kepalaUud.kegiatan.viewCreate', compact('info', 'program'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Kegiatan',
            'site_active' => 'Kegiatan',
        ];
        $program = DB::select("
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
        $kegiatan = $this->apiGetById()['response'] ?? [];
        return view('pages._kepalaUud.kegiatan.viewUpdate', compact('info', 'program', 'kegiatan'));
    }
}
