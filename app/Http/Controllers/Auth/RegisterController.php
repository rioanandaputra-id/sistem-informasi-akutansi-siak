<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
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
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'username' => ['required', 'string',  'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
            'gender' => ['required', 'string', 'max:1'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'id_user' => guid(),
            'id_role' => $data['id_role'],
            'a_active' => 0, //$data['a_active'],
            'full_name' => $data['full_name'],
            'gender' => $data['gender'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
        ]);
    }

    public function showRegistrationForm()
    {
        $roles = Role::whereNull('deleted_at')->get();
        return view('auth.register', compact('roles'));
    }
}
