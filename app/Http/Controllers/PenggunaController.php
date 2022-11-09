<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\RolePengguna;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    private $request;
    private $mPengguna;
    private $mRolePengguna;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mPengguna = app(Pengguna::class);
        $this->mRolePengguna = app(RolePengguna::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    rpg.id_role_pengguna,
                    rpg.id_peran,
                    rpg.id_pengguna,
                    rpg.a_aktif,
                    pga.username,
                    pga.nm_pengguna,
                    pga.jk,
                    pga.alamat,
                    pga.no_hp,
                    prn.nm_peran
                FROM
                    siak.role_pengguna AS rpg
                    JOIN siak.pengguna AS pga ON pga.id_pengguna = rpg.id_pengguna
                    AND pga.deleted_at IS NULL
                    JOIN siak.peran AS prn ON prn.id_peran = rpg.id_peran
                    AND prn.deleted_at IS NULL
                WHERE
                    rpg.deleted_at IS NULL
                ORDER BY
                    pga.nm_pengguna ASC
            ");

            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'OK',
                'error' => null,
                'response' => $apiGetAll
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

    public function apiGetById()
    {
        try {
            $rules = ['id_pengguna' => 'required|uuid'];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            }
            $id_pengguna = $this->request->id_pengguna;
            $apiGetById = DB::select("
                SELECT
                    rpg.id_role_pengguna,
                    rpg.id_peran,
                    rpg.id_pengguna,
                    rpg.a_aktif,
                    pga.username,
                    pga.nm_pengguna,
                    pga.jk,
                    pga.alamat,
                    pga.no_hp,
                    prn.nm_peran
                FROM
                    siak.role_pengguna AS rpg
                    JOIN siak.pengguna AS pga ON pga.id_pengguna = rpg.id_pengguna
                    AND pga.deleted_at IS NULL
                    JOIN siak.peran AS prn ON prn.id_peran = rpg.id_peran
                    AND prn.deleted_at IS NULL
                WHERE
                    rpg.deleted_at IS NULL
                    AND rpg.id_pengguna = ?
                LIMIT 1
            ", [$id_pengguna]);

            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'OK',
                'error' => null,
                'response' => $apiGetById
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

    public function apiCreate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_peran' => 'required|numeric',
                'username' => 'required|max:100|min:5|unique:siak.pengguna',
                'password' => 'required|max:100|min:8',
                'nm_pengguna' => 'required|max:255',
                'jk' => 'required|max:1',
                'alamat' => 'required',
                'no_hp' => 'required|max:15',
                'a_aktif' => 'required|max:1',
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

            $id_pengguna = guid();
            $id_role_pengguna = guid();
            $id_peran = $this->request->id_peran;
            $username = $this->request->username;
            $password = $this->request->password;
            $nm_pengguna = $this->request->nm_pengguna;
            $jk = $this->request->jk;
            $alamat = $this->request->alamat;
            $no_hp = $this->request->no_hp;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = null;

            $this->mPengguna->create([
                'id_pengguna' => $id_pengguna,
                'username' => $username,
                'password' => $password,
                'nm_pengguna' => $nm_pengguna,
                'jk' => $jk,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);

            $this->mRolePengguna->create([
                'id_role_pengguna' => $id_role_pengguna,
                'id_pengguna' => $id_pengguna,
                'id_peran' => $id_peran,
                'a_aktif' => $a_aktif,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_pengguna' => $id_pengguna]
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

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_pengguna' => 'required|uuid',
                'id_peran' => 'required|numeric',
                'username' => 'required|max:100|min:5|unique:siak.pengguna',
                // 'password' => 'required|max:100|min:8',
                'nm_pengguna' => 'required|max:255',
                'jk' => 'required|max:1',
                'alamat' => 'required',
                'no_hp' => 'required|max:15',
                'a_aktif' => 'required|max:1',
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

            $id_pengguna = $this->request->id_pengguna;
            $id_peran = $this->request->id_peran;
            $username = $this->request->username;
            // $password = $this->request->password;
            $nm_pengguna = $this->request->nm_pengguna;
            $jk = $this->request->jk;
            $alamat = $this->request->alamat;
            $no_hp = $this->request->no_hp;
            $a_aktif = $this->request->a_aktif;
            $updated_at = now();
            $id_updater = null;

            $this->mPengguna->where('id_pengguna', $id_pengguna)->update([
                'username' => $username,
                // 'password' => $password ?? DB::raw('password'),
                'nm_pengguna' => $nm_pengguna,
                'jk' => $jk,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            $this->mRolePengguna->where('id_pengguna', $id_pengguna)->update([
                'id_peran' => $id_peran,
                'a_aktif' => $a_aktif,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Updated',
                'error' => null,
                'response' => ['id_pengguna' => $id_pengguna]
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

    public function apiDelete()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_pengguna' => 'required|uuid',
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

            $id_pengguna = $this->request->id_pengguna;
            $deleted_at = now();
            $id_updater = null;

            $this->mPengguna->where('id_pengguna', $id_pengguna)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            $this->mRolePengguna->where('id_pengguna', $id_pengguna)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_pengguna' => $id_pengguna]
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
            'title' => 'Manajemen Akses',
            'site_active' => 'manakses',
        ];
        return view('pages.pengguna.viewGetAll', compact('info'));
    }

    public function viewGetById()
    {
        $info = [
            'title' => 'Pengguna - Manajemen Akses',
            'site_active' => 'manakses',
        ];
        $data = $this->apiGetById();
        return view('pages.pengguna.viewGetById', compact('info', 'data'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Tambah Pengguna - Manajemen Akses',
            'site_active' => 'manakses',
        ];
        return view('pages.pengguna.viewCreate', compact('info'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Ubah Pengguna - Manajemen Akses',
            'site_active' => 'manakses',
        ];
        return view('pages.pengguna.viewUpdate', compact('info'));
    }
}
