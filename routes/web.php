<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MisiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\VisiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/', function() {
    return view('auth.login');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
    });
    Route::controller(VisiController::class)->prefix('visi')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('visi.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('visi.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('visi.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('visi.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('visi.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('visi.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('visi.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('visi.viewUpdate');
    });
    Route::controller(MisiController::class)->prefix('misi')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('misi.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('misi.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('misi.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('misi.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('misi.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('misi.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('misi.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('misi.viewUpdate');
    });
    Route::controller(ProgramController::class)->prefix('program')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('program.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('program.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('program.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('program.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('program.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('program.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('program.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('program.viewUpdate');
    });
});
