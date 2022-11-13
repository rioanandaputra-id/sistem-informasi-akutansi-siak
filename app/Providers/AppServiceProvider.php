<?php

namespace App\Providers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Gate::define('kepalapmikuasa', function (User $user) {
            return $user->id_role == 1;
        });
        Gate::define('kepalauud', function (User $user) {
            return $user->id_role == 2;
        });
        Gate::define('kordinatortimrba', function (User $user) {
            return $user->id_role == 3;
        });
        Gate::define('kepaladepartemenkabagian', function (User $user) {
            return $user->id_role == 4;
        });
        Gate::define('bendaharapenerimaan', function (User $user) {
            return $user->id_role == 5;
        });
        Gate::define('bendaharapengeluaran', function (User $user) {
            return $user->id_role == 6;
        });
        Gate::define('bendaharakegiatanpanitiapelaksana', function (User $user) {
            return $user->id_role == 7;
        });
        Gate::define('developer', function (User $user) {
            return $user->id_role == 99;
        });
    }
}
