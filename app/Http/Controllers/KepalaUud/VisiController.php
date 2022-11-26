<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;
use App\Models\Visi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class VisiController extends Controller
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
                    vsi.id_visi,
                    vsi.nm_visi,
                    vsi.periode,
                    CASE
                        WHEN vsi.a_aktif = '1' THEN 'Aktif'
                        ELSE 'Non Aktif'
                    END AS a_aktif,
                    vsi.created_at,
                    vsi.updated_at,
                    vsi.deleted_at
                FROM
                    visi AS vsi
                WHERE
                    vsi.deleted_at IS NULL
                ORDER BY
                    vsi.periode DESC
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
            $id_visi = $this->request->id_visi;
            $apiGetById = DB::select("
                SELECT
                    vsi.id_visi,
                    vsi.nm_visi,
                    vsi.periode,
                    vsi.a_aktif,
                    vsi.created_at,
                    vsi.updated_at,
                    vsi.deleted_at
                FROM
                    visi AS vsi
                WHERE
                    vsi.deleted_at IS NULL
                    AND vsi.id_visi = '" . $id_visi . "'
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
                'nm_visi' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_visi = guid();
            $nm_visi = $this->request->nm_visi;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            Visi::create([
                'id_visi' => $id_visi,
                'nm_visi' => $nm_visi,
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
                'id_visi' => 'required|uuid',
                'nm_visi' => 'required|max:255',
                'periode' => 'required|min:4|max:4',
                'a_aktif' => 'required|max:1',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_visi = $idVisi ?? $this->request->id_visi;
            $nm_visi = $this->request->nm_visi;
            $periode = $this->request->periode;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            Visi::where('id_visi', $id_visi)->update([
                'nm_visi' => $nm_visi,
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
            $rules = [
                'id_visi.*' => 'required|uuid',
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
            $id_visi = $this->request->id_visi;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            Visi::whereIn('id_visi', $id_visi)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_visi' => $id_visi]
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
            'title' => 'Visi',
            'site_active' => 'Visi',
        ];
        return view('pages._kepalaUud.visi.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Visi',
            'site_active' => 'Visi',
        ];
        return view('pages._kepalaUud.visi.viewCreate', compact('info'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Visi',
            'site_active' => 'Visi',
        ];
        $visi = $this->apiGetById()['response'];
        return view('pages._kepalaUud.visi.viewUpdate', compact('info', 'visi'));
    }
}
