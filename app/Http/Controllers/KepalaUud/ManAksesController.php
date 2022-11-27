<?php

namespace App\Http\Controllers\KepalaUud;

use App\Http\Controllers\Controller;
use App\Models\RoleUser;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class ManAksesController extends Controller
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
                    usr.id_user,
                    usr.id_divisi,
                    div.nm_divisi,
                    rusr.id_role,
                    CASE
                        rusr.a_active
                        WHEN '1' THEN 'Aktif'
                        ELSE 'Non Aktif'
                    END AS a_active,
                    rol.role_name,
                    usr.full_name,
                    usr.gender,
                    usr.username,
                    usr.password,
                    usr.phone,
                    usr.email,
                    usr.address,
                    usr.remember_token,
                    usr.email_verified_at,
                    usr.created_at,
                    usr.updated_at,
                    usr.deleted_at,
                    usr.id_updater
                FROM
                    users AS usr
                    JOIN divisi AS div ON div.id_divisi = usr.id_divisi
                    AND div.deleted_at IS NULL
                    JOIN role_users AS rusr ON rusr.id_user = usr.id_user
                    AND rusr.deleted_at IS NULL
                    JOIN roles AS rol ON rol.id_role = rusr.id_role
                    AND rol.deleted_at IS NULL
                WHERE
                    usr.deleted_at IS NULL
                ORDER BY
                    div.nm_divisi,
                    rol.role_name,
                    usr.full_name ASC
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
            $id_user = $this->request->id_user;
            $apiGetById = DB::select("
                SELECT
                    usr.id_user,
                    usr.id_divisi,
                    div.nm_divisi,
                    rusr.id_role,
                    rusr.a_active,
                    rol.role_name,
                    usr.full_name,
                    usr.gender,
                    usr.username,
                    usr.password,
                    usr.phone,
                    usr.email,
                    usr.address,
                    usr.remember_token,
                    usr.email_verified_at,
                    usr.created_at,
                    usr.updated_at,
                    usr.deleted_at,
                    usr.id_updater
                FROM
                    users AS usr
                    JOIN divisi AS div ON div.id_divisi = usr.id_divisi
                    AND div.deleted_at IS NULL
                    JOIN role_users AS rusr ON rusr.id_user = usr.id_user
                    AND rusr.deleted_at IS NULL
                    JOIN roles AS rol ON rol.id_role = rusr.id_role
                    AND rol.deleted_at IS NULL
                WHERE
                    usr.deleted_at IS NULL
                    AND usr.id_user = '" . $id_user . "'
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
                'id_divisi' => 'required',
                'id_role' => 'required',
                'a_active' => 'required',
                'full_name' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required',
                'email' => 'required',
                'gender' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_user = guid();
            $id_role_user = guid();
            $id_divisi = $this->request->id_divisi;
            $id_role = $this->request->id_role;
            $a_active = $this->request->a_active;
            $full_name = $this->request->full_name;
            $username = $this->request->username;
            $password = $this->request->password;
            $phone = $this->request->phone;
            $email = $this->request->email;
            $gender = $this->request->gender;
            $address = $this->request->address;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            User::create([
                'id_user' => $id_user,
                'id_divisi' => $id_divisi,
                'full_name' => $full_name,
                'username' => $username,
                'password' => bcrypt($password),
                'phone' => $phone,
                'email' => $email,
                'gender' => $gender,
                'address' => $address,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);
            RoleUser::create([
                'id_role_user' => $id_role_user,
                'id_role' => $id_role,
                'id_user' => $id_user,
                'a_active' => $a_active,
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
                'id_user' => 'required',
                'id_divisi' => 'required',
                'id_role' => 'required',
                'a_active' => 'required',
                'full_name' => 'required',
                'username' => 'required',
                'email' => 'required',
                'gender' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_user = $this->request->id_user;
            $id_divisi = $this->request->id_divisi;
            $id_role = $this->request->id_role;
            $a_active = $this->request->a_active;
            $full_name = $this->request->full_name;
            $username = $this->request->username;
            $password = $this->request->password;
            $phone = $this->request->phone;
            $email = $this->request->email;
            $gender = $this->request->gender;
            $address = $this->request->address;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            User::where('id_user', $id_user)->update([
                'id_divisi' => $id_divisi,
                'full_name' => $full_name,
                'username' => $username,
                'password' => ($password) ? bcrypt($password) : DB::raw('password'),
                'phone' => $phone,
                'email' => $email,
                'gender' => $gender,
                'address' => $address,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);
            RoleUser::where('id_user', $id_user)->update([
                'id_role' => $id_role,
                'id_user' => $id_user,
                'a_active' => $a_active,
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
                'id_user.*' => 'required|uuid',
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
            $id_user = $this->request->id_user;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            User::whereIn('id_user', $id_user)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            RoleUser::whereIn('id_user', $id_user)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_user' => $id_user]
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
            'title' => 'Data Master | Man. Akses',
            'site_active' => 'ManAkses',
        ];
        return view('pages._kepalaUud.manAkses.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Data Master | Tambah Man. Akses',
            'site_active' => 'ManAkses',
        ];
        $divisi = DB::select("
            SELECT
                div.id_divisi,
                div.nm_divisi
            FROM
                divisi AS div
            WHERE
                div.deleted_at IS NULL
            ORDER BY
                div.nm_divisi ASC
        ");
        $role = DB::select("
            SELECT
                rol.id_role,
                rol.role_name
            FROM
                roles AS rol
            WHERE
                rol.deleted_at IS NULL
            ORDER BY
                rol.role_name ASC
        ");
        return view('pages._kepalaUud.manAkses.viewCreate', compact('info', 'divisi', 'role'));
    }

    public function viewUpdate()
    {
        $info = [
            'title' => 'Data Master | Ubah Man. Akses',
            'site_active' => 'ManAkses',
        ];
        $user = $this->apiGetById()['response'];
        $divisi = DB::select("
            SELECT
                div.id_divisi,
                div.nm_divisi
            FROM
                divisi AS div
            WHERE
                div.deleted_at IS NULL
            ORDER BY
                div.nm_divisi ASC
        ");
        $role = DB::select("
            SELECT
                rol.id_role,
                rol.role_name
            FROM
                roles AS rol
            WHERE
                rol.deleted_at IS NULL
            ORDER BY
                rol.role_name ASC
        ");
        return view('pages._kepalaUud.manAkses.viewUpdate', compact('info', 'user', 'divisi', 'role'));
    }
}
