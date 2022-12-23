<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
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
                    akn.id_akun,
                    CONCAT(rakn.elemen, rakn.sub_elemen, rakn.jenis, rakn.no_akun) AS nm_akun_induk,
                    CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                    akn.nm_akun,
                    akn.keterangan,
                    akn.created_at,
                    akn.updated_at,
                    akn.deleted_at,
                    akn.id_updater
                FROM
                    akun AS akn
                    LEFT JOIN akun AS rakn ON rakn.id_akun=akn.no_akun_induk AND rakn.deleted_at IS NULL
                WHERE
                    akn.deleted_at IS NULL
                ORDER BY
                    no_akun ASC
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
            $id_akun = $this->request->id_akun;
            $apiGetById = DB::select("
                SELECT
                    akn.id_akun,
                    akn.no_akun_induk,
                    akn.elemen,
                    akn.sub_elemen,
                    akn.jenis,
                    akn.no_akun,
                    akn.nm_akun,
                    akn.keterangan,
                    akn.created_at,
                    akn.updated_at,
                    akn.deleted_at,
                    akn.id_updater
                FROM
                    akun AS akn
                WHERE
                    akn.deleted_at IS NULL
                    AND akn.id_akun = '" . $id_akun . "'
                LIMIT 1
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
                'elemen' => 'required',
                'sub_elemen' => 'required',
                'jenis' => 'required',
                'no_akun' => 'required',
                'nm_akun' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }

            $id_akun = guid();
            $no_akun_induk = $this->request->no_akun_induk;
            $elemen = $this->request->elemen;
            $sub_elemen = $this->request->sub_elemen;
            $jenis = $this->request->jenis;
            $no_akun = $this->request->no_akun;
            $nm_akun = $this->request->nm_akun;
            $keterangan = $this->request->keterangan;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            Akun::create([
                'id_akun' => $id_akun,
                'no_akun_induk' => $no_akun_induk,
                'elemen' => $elemen,
                'sub_elemen' => $sub_elemen,
                'jenis' => $jenis,
                'no_akun' => $no_akun,
                'nm_akun' => $nm_akun,
                'keterangan' => $keterangan,
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
                'elemen' => 'required',
                'sub_elemen' => 'required',
                'jenis' => 'required',
                'no_akun' => 'required',
                'nm_akun' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_akun = $this->request->id_akun;
            $no_akun_induk = $this->request->no_akun_induk;
            $elemen = $this->request->elemen;
            $sub_elemen = $this->request->sub_elemen;
            $jenis = $this->request->jenis;
            $no_akun = $this->request->no_akun;
            $nm_akun = $this->request->nm_akun;
            $keterangan = $this->request->keterangan;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            Akun::where('id_akun', $id_akun)->update([
                'id_akun' => $id_akun,
                'no_akun_induk' => $no_akun_induk,
                'elemen' => $elemen,
                'sub_elemen' => $sub_elemen,
                'jenis' => $jenis,
                'no_akun' => $no_akun,
                'nm_akun' => $nm_akun,
                'keterangan' => $keterangan,
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
                'id_akun.*' => 'required|uuid',
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
            $id_akun = $this->request->id_akun;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            Akun::whereIn('id_akun', $id_akun)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_akun' => $id_akun]
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
            'title' => 'Data Master | Akun',
            'site_active' => 'Akun',
        ];
        return view('pages._kepalaUud.akun.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Data Master | Tambah Akun',
            'site_active' => 'Akun',
        ];
        $noInduk = DB::select("
            SELECT
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                akun AS akn
            WHERE
                akn.deleted_at IS NULL
            ORDER BY akn.no_akun ASC
        ");
        return view('pages._kepalaUud.akun.viewCreate', compact('info', 'noInduk'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Data Master | Ubah Akun',
            'site_active' => 'Akun',
        ];
        $noInduk = DB::select("
            SELECT
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                akun AS akn
            WHERE
                akn.deleted_at IS NULL
            ORDER BY akn.no_akun ASC
        ");
        $akun = $this->apiGetById()['response'];
        return view('pages._kepalaUud.akun.viewUpdate', compact('info', 'akun', 'noInduk'));
    }
}
