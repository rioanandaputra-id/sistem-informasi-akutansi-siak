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
        Gate::define('kepalawilayah', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 1;
        });
        Gate::define('kepalauud', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 2;
        });
        Gate::define('timrba', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 3;
        });
        Gate::define('kepalabagian', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 4;
        });
        Gate::define('bendpenerimaan', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 5;
        });
        Gate::define('bendpengeluaran', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 6;
        });
        Gate::define('bendkegiatan', function (User $user) {
            return userRole($user->id_user)[0]->id_role == 7;
        });
    }
}
