<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Role;
use App\Models\RoleUser;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id_divisi' => ['required'],
            'id_role' => ['required'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'username' => ['required', 'string',  'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
            'gender' => ['required', 'string', 'max:1'],
        ]);
    }

    protected function create(array $data)
    {
        $id_user = guid();
        $id_role_user = guid();

        $user = User::create([
            'id_user' => $id_user,
            'id_divisi' => $data['id_divisi'],
            'full_name' => $data['full_name'],
            'gender' => $data['gender'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
        ]);

        RoleUser::create([
            'id_role_user' => $id_role_user,
            'id_role' => $data['id_role'],
            'id_user' => $id_user,
            'a_active' => 0,
        ]);

        return $user;
    }

    public function showRegistrationForm()
    {
        $roles = Role::whereNull('deleted_at')->get();
        $divisis = Divisi::whereNull('deleted_at')->get();
        return view('auth.register', compact('roles', 'divisis'));
    }
}
