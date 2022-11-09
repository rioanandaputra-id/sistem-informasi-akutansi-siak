<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::controller(PenggunaController::class)->prefix('pengguna')->group(function () {
        Route::get('/', 'apiGetAll')->name('pengguna.apiGetAll');
        Route::get('id', 'apiGetById')->name('pengguna.apiGetById');
        Route::post('create', 'apiCreate')->name('pengguna.apiCreate');
        Route::post('update', 'apiUpdate')->name('pengguna.apiUpdate');
        Route::post('delete', 'apiDelete')->name('pengguna.apiDelete');
    });
});

Route::prefix('view')->group(function () {
    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
    });
    Route::controller(PenggunaController::class)->prefix('pengguna')->group(function () {
        Route::get('/', 'viewGetAll')->name('pengguna.viewGetAll');
        Route::get('id', 'viewGetById')->name('pengguna.viewGetById');
        Route::get('create', 'viewCreate')->name('pengguna.viewCreate');
        Route::get('update', 'viewUpdate')->name('pengguna.viewUpdate');
    });
});
