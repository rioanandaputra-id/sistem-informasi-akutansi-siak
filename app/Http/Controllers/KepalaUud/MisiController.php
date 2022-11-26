<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;
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
    public function __construct()
    {
        $this->request = app(Request::class);
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
            $id_misi = $this->request->id_misi;
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

    public function apiCreate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'nm_misi' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_misi = guid();
            $nm_misi = $this->request->nm_misi;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            Misi::create([
                'id_misi' => $id_misi,
                'nm_misi' => $nm_misi,
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
                'id_misi' => 'required|uuid',
                'nm_misi' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_misi = $this->request->id_misi;
            $nm_misi = $this->request->nm_misi;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            Misi::where('id_misi', $id_misi)->update([
                'nm_misi' => $nm_misi,
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
            $rules = ['id_misi.*' => 'required|uuid'];
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
            $id_misi = $idMisi ?? $this->request->id_misi;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            Misi::whereIn('id_misi', $id_misi)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_misi' => $id_misi]
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
            'title' => 'Misi',
            'site_active' => 'Misi',
        ];
        return view('pages._kepalaUud.misi.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Misi',
            'site_active' => 'Misi',
        ];
        return view('pages._kepalaUud.misi.viewCreate', compact('info'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Misi',
            'site_active' => 'Misi',
        ];
        $misi = $this->apiGetById()['response'];
        return view('pages._kepalaUud.misi.viewUpdate', compact('info', 'misi'));
    }
}
