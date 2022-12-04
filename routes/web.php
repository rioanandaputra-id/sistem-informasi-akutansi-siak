<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KepalaUud\AkunController as KepalaUudAkunController;
use App\Http\Controllers\KepalaUud\DivisiController as KepalaUudDivisiController;
use App\Http\Controllers\KepalaUud\ManAksesController as KepalaUudManAksesController;
use App\Http\Controllers\KepalaUud\VisiController as KepalaUudVisiController;
use App\Http\Controllers\KepalaUud\KegiatanController as KepalaUudKegiatanController;
use App\Http\Controllers\KepalaUud\MisiController as KepalaUudMisiController;
use App\Http\Controllers\KepalaUud\ProgramController as KepalaUudProgramController;
use App\Http\Controllers\KepalaUud\KegiatanMonitoringController as KepalaUudKegiatanMonitoringController;

use App\Http\Controllers\KepalaBagian\KegiatanController as KepalaBagianKegiatanController;
use App\Http\Controllers\KepalaBagian\KegiatanMonitoringController as KepalaBagianKegiatanMonitoringController;
use App\Http\Controllers\KepalaBagian\Keuangan\KegiatanPelaksanaanController AS KepalaBagianKegiatanPelaksanaanController;
use App\Http\Controllers\KepalaBagian\BkuMonitoringController as KepalaBagianBkuMonitoringController;
use App\Http\Controllers\KepalaBagian\ManajemenKeuangan;

use App\Http\Controllers\TimRba\KegiatanMonitoringController as TimRbaKegiatanMonitoringController;
use App\Http\Controllers\KepalaWilayah\KegiatanMonitoringController as KepalaWilayahKegiatanMonitoringController;

Auth::routes();
Auth::routes(['verify' => true]);
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
    });
    // =====================================KEPALA UUD========================================
    Route::controller(KepalaUudAkunController::class)->prefix('kepalauud/master/akun')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.master.akun.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.master.akun.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.master.akun.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.master.akun.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.master.akun.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.master.akun.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.master.akun.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.master.akun.viewUpdate');
    });
    Route::controller(KepalaUudDivisiController::class)->prefix('kepalauud/master/divisi')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.master.divisi.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.master.divisi.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.master.divisi.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.master.divisi.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.master.divisi.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.master.divisi.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.master.divisi.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.master.divisi.viewUpdate');
    });
    Route::controller(KepalaUudManAksesController::class)->prefix('kepalauud/master/manAkses')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.master.manAkses.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.master.manAkses.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.master.manAkses.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.master.manAkses.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.master.manAkses.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.master.manAkses.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.master.manAkses.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.master.manAkses.viewUpdate');
    });
    Route::controller(KepalaUudVisiController::class)->prefix('kepalauud/visi')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.visi.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.visi.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.visi.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.visi.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.visi.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.visi.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.visi.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.visi.viewUpdate');
    });
    Route::controller(KepalaUudMisiController::class)->prefix('kepalauud/misi')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.misi.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.misi.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.misi.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.misi.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.misi.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.misi.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.misi.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.misi.viewUpdate');
    });
    Route::controller(KepalaUudProgramController::class)->prefix('kepalauud/program')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.program.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.program.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.program.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.program.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.program.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.program.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.program.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.program.viewUpdate');
    });
    Route::controller(KepalaUudKegiatanController::class)->prefix('kepalauud/kegiatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.kegiatan.apiGetAll');
        Route::get('apiGetById', 'apiGetById')->name('kepalauud.kegiatan.apiGetById');
        Route::post('apiCreate', 'apiCreate')->name('kepalauud.kegiatan.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.kegiatan.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalauud.kegiatan.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.kegiatan.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalauud.kegiatan.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalauud.kegiatan.viewUpdate');
    });
    Route::controller(KepalaUudKegiatanMonitoringController::class)->prefix('kepalauud/kegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalauud.kegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalauud.kegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalauud.kegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalauud.kegiatanMonitoring.viewDetail');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('kepalauud.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    // =====================================TIM RBA========================================
    Route::controller(TimRbaKegiatanMonitoringController::class)->prefix('timrba/kegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('timrba.kegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('timrba.kegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('timrba.kegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('timrba.kegiatanMonitoring.viewDetail');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('timrba.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    // =====================================KEPALA WILAYAH========================================
    Route::controller(KepalaWilayahKegiatanMonitoringController::class)->prefix('kepalawilayah/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalawilayah.kegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalawilayah.kegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalawilayah.kegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalawilayah.kegiatanMonitoring.viewDetail');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('kepalawilayah.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    // =====================================KEPALA BAGIAN========================================
    Route::controller(KepalaBagianKegiatanController::class)->prefix('kepalabagian/Kegiatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.Kegiatan.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('kepalabagian.Kegiatan.apiCreate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.Kegiatan.viewGetAll');
    });
    Route::controller(KepalaBagianKegiatanMonitoringController::class)->prefix('kepalabagian/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.KegiatanMonitoring.apiGetAll');

        Route::post('apiCreateDetailRba', 'apiCreateDetailRba')->name('kepalabagian.KegiatanMonitoring.apiCreateDetailRba');
        Route::post('apiUpdateDetailRba', 'apiUpdateDetailRba')->name('kepalabagian.KegiatanMonitoring.apiUpdateDetailRba');
        Route::post('apiDeleteDetailRba', 'apiDeleteDetailRba')->name('kepalabagian.KegiatanMonitoring.apiDeleteDetailRba');

        Route::post('apiCreateLaksana', 'apiCreateLaksana')->name('kepalabagian.KegiatanMonitoring.apiCreateLaksana');
        Route::post('apiUpdateLaksana', 'apiUpdateLaksana')->name('kepalabagian.KegiatanMonitoring.apiUpdateLaksana');
        Route::post('apiDeleteLaksana', 'apiDeleteLaksana')->name('kepalabagian.KegiatanMonitoring.apiDeleteLaksana');

        Route::post('apiUpdate', 'apiUpdate')->name('kepalabagian.KegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.KegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.KegiatanMonitoring.viewDetail');

        Route::post('apiCreateDetailLaksana', 'apiCreateDetailLaksana')->name('kepalabagian.KegiatanMonitoring.apiCreateDetailLaksana');
        Route::post('apiUpdateDetailLaksana', 'apiUpdateDetailLaksana')->name('kepalabagian.KegiatanMonitoring.apiUpdateDetailLaksana');
        Route::post('apiDeleteDetailLaksana', 'apiDeleteDetailLaksana')->name('kepalabagian.KegiatanMonitoring.apiDeleteDetailLaksana');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('kepalabagian.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    Route::controller(KepalaBagianKegiatanPelaksanaanController::class)->prefix('kepalabagian/KegiatanPelaksana')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.KegiatanPelaksana.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('kepalabagian.KegiatanPelaksana.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalabagian.KegiatanPelaksana.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.KegiatanPelaksana.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.KegiatanPelaksana.viewDetail');
    });
    Route::controller(KepalaBagianBkuMonitoringController::class)->prefix('kepalabagian/BkuMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.BkuMonitoring.apiGetAll');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.BkuMonitoring.viewGetAll');
    });
    // =====================================KEPALA KEUANGAN========================================
    Route::controller(ManajemenKeuangan::class)->prefix('kepalabagian/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('kepalabagian.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('kepalabagian.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN
        Route::get('penganggaran/viewGetAll', 'penganggaranViewGetAll')->name('kepalabagian.ManajemenKeuangan.penganggaran.viewGetAll');
        Route::get('penganggaran/apiGetAll', 'penganggaranApiGetAll')->name('kepalabagian.ManajemenKeuangan.penganggaran.apiGetAll');
        Route::get('penganggaran/viewDetail', 'penganggaranViewDetail')->name('kepalabagian.ManajemenKeuangan.penganggaran.viewDetail');
        Route::post('penganggaran/apiUpdate', 'penganggaranApiUpdate')->name('kepalabagian.ManajemenKeuangan.penganggaran.apiUpdate');
    });
});
