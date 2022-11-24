<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KegiatanDivisiController;
use App\Http\Controllers\MisiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\VisiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KepalaBagian\KegiatanMonitoringController as KepalaBagianKegiatanMonitoringController;
use App\Http\Controllers\KepalaUud\KegiatanMonitoringController as KepalaUudKegiatanMonitoringController;
use App\Http\Controllers\KepalaWilayah\KegiatanMonitoringController as KepalaWilayahKegiatanMonitoringController;

Auth::routes();
Route::get('/', function () {
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
    Route::controller(KegiatanController::class)->prefix('kegiatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kegiatan.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kegiatan.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kegiatan.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kegiatan.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kegiatan.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kegiatan.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kegiatan.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kegiatan.viewUpdate');
    });
    Route::controller(KegiatanDivisiController::class)->prefix('kegiatanDivisi')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kegiatanDivisi.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kegiatanDivisi.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kegiatanDivisi.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kegiatanDivisi.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kegiatanDivisi.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kegiatanDivisi.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kegiatanDivisi.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kegiatanDivisi.viewUpdate');
    });


    // =====================================KEPALA UUD========================================
    Route::controller(KepalaUudKegiatanMonitoringController::class)->prefix('kepalauud/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.KegiatanMonitoring.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.KegiatanMonitoring.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.KegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.KegiatanMonitoring.viewGetAll');
    });
    // =====================================KEPALA WILAYAH========================================
    Route::controller(KepalaWilayahKegiatanMonitoringController::class)->prefix('kepalawilayah/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalawilayah.KegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalawilayah.KegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalawilayah.KegiatanMonitoring.viewGetAll');
    });
    // =====================================KEPALA BAGIAN========================================
    Route::controller(KepalaBagianKegiatanMonitoringController::class)->prefix('kepalabagian/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.KegiatanMonitoring.apiGetAll');
        Route::post('apiCreateDetailRba', 'apiCreateDetailRba')->name('kepalabagian.KegiatanMonitoring.apiCreateDetailRba');
        Route::post('apiDeleteDetailRba', 'apiDeleteDetailRba')->name('kepalabagian.KegiatanMonitoring.apiDeleteDetailRba');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalabagian.KegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.KegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.KegiatanMonitoring.viewDetail');
    });
});
