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
use App\Http\Controllers\KepalaUud\Keuangan\ManajemenKeuangan as KepalaUudManajemenKeuanganController;
use App\Http\Controllers\KepalaUud\Export\RbaController as KepalaUudExportRbaController;

use App\Http\Controllers\KepalaBagian\KegiatanController as KepalaBagianKegiatanController;
use App\Http\Controllers\KepalaBagian\KegiatanRutinController as KepalaBagianKegiatanRutinController;
use App\Http\Controllers\KepalaBagian\KegiatanMonitoringController as KepalaBagianKegiatanMonitoringController;
use App\Http\Controllers\KepalaBagian\Keuangan\KegiatanPelaksanaanController AS KepalaBagianKegiatanPelaksanaanController;
use App\Http\Controllers\KepalaBagian\BkuMonitoringController as KepalaBagianBkuMonitoringController;
use App\Http\Controllers\KepalaBagian\Keuangan\ManajemenKeuangan as KepalaBagianManajemenKeuanganController;
use App\Http\Controllers\KepalaBagian\SPJKegiatanController as KepalaBagianSPJKegiatanController;
use App\Http\Controllers\KepalaBagian\Keuangan\SPJKegiatanMonitoringController as KepalabagianSPJKegiatanMonitoringController;
use App\Http\Controllers\KepalaBagian\Keuangan\KegiatanPendapatanController as KepalaBagianKegiatanPendapatanController;

use App\Http\Controllers\BendaharaPengeluaran\KegiatanRutinController as BendaharaPengeluaranKegiatanRutinController;
use App\Http\Controllers\BendaharaPengeluaran\KegiatanRutinPelaksanaanController as BendaharaPengeluaranKegiatanRutinPelaksanaanController;
use App\Http\Controllers\BendaharaPengeluaran\SPJKegiatanRutinController as BendaharaPengeluaranSPJKegiatanRutinController;
use App\Http\Controllers\BendaharaPengeluaran\Keuangan\ManajemenKeuanganController as BendaharaPengeluaranManajemenKeuanganController;

use App\Http\Controllers\TimRba\KegiatanMonitoringController as TimRbaKegiatanMonitoringController;
use App\Http\Controllers\TimRba\Keuangan\ManajemenKeuanganController as TimRbaManajemenKeuanganController;

use App\Http\Controllers\KepalaWilayah\KegiatanMonitoringController as KepalaWilayahKegiatanMonitoringController;
use App\Http\Controllers\KepalaWilayah\Keuangan\ManajemenKeuangan as KepalaWilayahManajemenKeuanganController;

use App\Http\Controllers\BendaharaPenerimaan\KegiatanMonitoringController as BendaharaPenerimaanKegiatanMonitoringController;
use App\Http\Controllers\BendaharaPenerimaan\SPJKegiatanController as BendaharaPenerimaanSPJKegiatanController;
use App\Http\Controllers\BendaharaPenerimaan\Keuangan\ManajemenKeuanganController as BendaharaPenerimaanManajemenKeuanganController;

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
    Route::controller(KepalaUudManajemenKeuanganController::class)->prefix('kepalauud/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('kepalauud.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('kepalauud.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN PENDAPATAN
        Route::get('penganggaranPendapatan/viewGetAll', 'penganggaranPendapatanViewGetAll')->name('kepalauud.ManajemenKeuangan.penganggaranPendapatan.viewGetAll');
        Route::get('penganggaranPendapatan/apiGetAll', 'penganggaranPendapatanApiGetAll')->name('kepalauud.ManajemenKeuangan.penganggaranPendapatan.apiGetAll');
        // PENGANGGARAN PENGELUARAN
        Route::get('penganggaranPengeluaran/viewGetAll', 'penganggaranPengeluaranViewGetAll')->name('kepalauud.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll');
        Route::get('penganggaranPengeluaran/apiGetAll', 'penganggaranPengeluaranApiGetAll')->name('kepalauud.ManajemenKeuangan.penganggaranPengeluaran.apiGetAll');
        // PENATAUSAHAAN
        Route::get('penatausahaan/apiGetAll', 'penatausahaanApiGetAll')->name('kepalauud.ManajemenKeuangan.penatausahaan.apiGetAll');
        Route::get('penatausahaan/viewGetAll', 'penatausahaanViewGetAll')->name('kepalauud.ManajemenKeuangan.penatausahaan.viewGetAll');
        // PELAPORAN
        Route::get('pelaporan/apiGetAll', 'pelaporanApiGetAll')->name('kepalauud.ManajemenKeuangan.pelaporan.apiGetAll');
        Route::get('pelaporan/viewGetAll', 'pelaporanViewGetAll')->name('kepalauud.ManajemenKeuangan.pelaporan.viewGetAll');
    });
    Route::controller(KepalaUudExportRbaController::class)->prefix('kepalauud/Export/Rba')->group(function () {
        Route::get('export', 'export')->name('kepalauud.Export.Rba.export');
    });
    // =====================================TIM RBA========================================
    Route::controller(TimRbaKegiatanMonitoringController::class)->prefix('timrba/kegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('timrba.kegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('timrba.kegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('timrba.kegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('timrba.kegiatanMonitoring.viewDetail');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('timrba.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    Route::controller(TimRbaManajemenKeuanganController::class)->prefix('timrba/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('timrba.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('timrba.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN PENDAPATAN
        Route::get('penganggaranPendapatan/viewGetAll', 'penganggaranPendapatanViewGetAll')->name('timrba.ManajemenKeuangan.penganggaranPendapatan.viewGetAll');
        Route::get('penganggaranPendapatan/apiGetAll', 'penganggaranPendapatanApiGetAll')->name('timrba.ManajemenKeuangan.penganggaranPendapatan.apiGetAll');
        // PENGANGGARAN PENGELUARAN
        Route::get('penganggaranPengeluaran/viewGetAll', 'penganggaranPengeluaranViewGetAll')->name('timrba.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll');
        Route::get('penganggaranPengeluaran/apiGetAll', 'penganggaranPengeluaranApiGetAll')->name('timrba.ManajemenKeuangan.penganggaranPengeluaran.apiGetAll');
        // PENATAUSAHAAN
        Route::get('penatausahaan/apiGetAll', 'penatausahaanApiGetAll')->name('timrba.ManajemenKeuangan.penatausahaan.apiGetAll');
        Route::get('penatausahaan/viewGetAll', 'penatausahaanViewGetAll')->name('timrba.ManajemenKeuangan.penatausahaan.viewGetAll');
        // PELAPORAN
        Route::get('pelaporan/apiGetAll', 'pelaporanApiGetAll')->name('timrba.ManajemenKeuangan.pelaporan.apiGetAll');
        Route::get('pelaporan/viewGetAll', 'pelaporanViewGetAll')->name('timrba.ManajemenKeuangan.pelaporan.viewGetAll');
    });
    // =====================================KEPALA WILAYAH========================================
    Route::controller(KepalaWilayahKegiatanMonitoringController::class)->prefix('kepalawilayah/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalawilayah.kegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalawilayah.kegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalawilayah.kegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalawilayah.kegiatanMonitoring.viewDetail');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('kepalawilayah.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    Route::controller(KepalaWilayahManajemenKeuanganController::class)->prefix('kepalawilayah/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('kepalawilayah.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('kepalawilayah.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN PENDAPATAN
        Route::get('penganggaranPendapatan/viewGetAll', 'penganggaranPendapatanViewGetAll')->name('kepalawilayah.ManajemenKeuangan.penganggaranPendapatan.viewGetAll');
        Route::get('penganggaranPendapatan/apiGetAll', 'penganggaranPendapatanApiGetAll')->name('kepalawilayah.ManajemenKeuangan.penganggaranPendapatan.apiGetAll');
        // PENGANGGARAN PENGELUARAN
        Route::get('penganggaranPengeluaran/viewGetAll', 'penganggaranPengeluaranViewGetAll')->name('kepalawilayah.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll');
        Route::get('penganggaranPengeluaran/apiGetAll', 'penganggaranPengeluaranApiGetAll')->name('kepalawilayah.ManajemenKeuangan.penganggaranPengeluaran.apiGetAll');
        // PENATAUSAHAAN
        Route::get('penatausahaan/apiGetAll', 'penatausahaanApiGetAll')->name('kepalawilayah.ManajemenKeuangan.penatausahaan.apiGetAll');
        Route::get('penatausahaan/viewGetAll', 'penatausahaanViewGetAll')->name('kepalawilayah.ManajemenKeuangan.penatausahaan.viewGetAll');
        // PELAPORAN
        Route::get('pelaporan/apiGetAll', 'pelaporanApiGetAll')->name('kepalawilayah.ManajemenKeuangan.pelaporan.apiGetAll');
        Route::get('pelaporan/viewGetAll', 'pelaporanViewGetAll')->name('kepalawilayah.ManajemenKeuangan.pelaporan.viewGetAll');
    });
    // =====================================KEPALA BAGIAN========================================
    Route::controller(KepalaBagianKegiatanController::class)->prefix('kepalabagian/Kegiatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.Kegiatan.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('kepalabagian.Kegiatan.apiCreate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.Kegiatan.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalabagian.Kegiatan.viewCreate');
    });
    Route::controller(KepalaBagianKegiatanRutinController::class)->prefix('kepalabagian/KegiatanRutin')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.KegiatanRutin.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('kepalabagian.KegiatanRutin.apiCreate');
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.KegiatanRutin.viewDetail');
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
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.BkuMonitoring.viewDetail');
    });
    Route::controller(KepalaBagianSPJKegiatanController::class)->prefix('kepalabagian/SPJKegiatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.SPJKegiatan.apiGetAll');
        Route::post('apiCreateDetailSpj', 'apiCreateDetailSpj')->name('kepalabagian.SPJKegiatan.apiCreateDetailSpj');
        Route::post('apiUpdateDetailSpj', 'apiUpdateDetailSpj')->name('kepalabagian.SPJKegiatan.apiUpdateDetailSpj');
        Route::post('apiDeleteDetailSpj', 'apiDeleteDetailSpj')->name('kepalabagian.SPJKegiatan.apiDeleteDetailSpj');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalabagian.SPJKegiatan.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.SPJKegiatan.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.SPJKegiatan.viewDetail');
    });
    Route::controller(KepalabagianSPJKegiatanMonitoringController::class)->prefix('kepalabagian/SPJKegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.SPJKegiatanMonitoring.apiGetAll');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalabagian.SPJKegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.SPJKegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('kepalabagian.SPJKegiatanMonitoring.viewDetail');
    });
    Route::controller(KepalaBagianManajemenKeuanganController::class)->prefix('kepalabagian/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('kepalabagian.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('kepalabagian.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN PENDAPATAN
        Route::get('penganggaranPendapatan/viewGetAll', 'penganggaranPendapatanViewGetAll')->name('kepalabagian.ManajemenKeuangan.penganggaranPendapatan.viewGetAll');
        Route::get('penganggaranPendapatan/apiGetAll', 'penganggaranPendapatanApiGetAll')->name('kepalabagian.ManajemenKeuangan.penganggaranPendapatan.apiGetAll');
        // PENGANGGARAN PENGELUARAN
        Route::get('penganggaranPengeluaran/viewGetAll', 'penganggaranPengeluaranViewGetAll')->name('kepalabagian.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll');
        Route::get('penganggaranPengeluaran/apiGetAll', 'penganggaranPengeluaranApiGetAll')->name('kepalabagian.ManajemenKeuangan.penganggaranPengeluaran.apiGetAll');
        // PENATAUSAHAAN
        Route::get('penatausahaan/apiGetAll', 'penatausahaanApiGetAll')->name('kepalabagian.ManajemenKeuangan.penatausahaan.apiGetAll');
        Route::get('penatausahaan/viewGetAll', 'penatausahaanViewGetAll')->name('kepalabagian.ManajemenKeuangan.penatausahaan.viewGetAll');
        // PELAPORAN
        Route::get('pelaporan/apiGetAll', 'pelaporanApiGetAll')->name('kepalabagian.ManajemenKeuangan.pelaporan.apiGetAll');
        Route::get('pelaporan/viewGetAll', 'pelaporanViewGetAll')->name('kepalabagian.ManajemenKeuangan.pelaporan.viewGetAll');
    });
    Route::controller(KepalaBagianKegiatanPendapatanController::class)->prefix('kepalabagian/kegiatanPendapatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('kepalabagian.kegiatanPendapatan.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('kepalabagian.kegiatanPendapatan.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('kepalabagian.kegiatanPendapatan.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('kepalabagian.kegiatanPendapatan.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('kepalabagian.kegiatanPendapatan.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('kepalabagian.kegiatanPendapatan.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('kepalabagian.kegiatanPendapatan.viewUpdate');
    });
    // =====================================BENDAHARA PENGELUARAN========================================
    Route::controller(BendaharaPengeluaranKegiatanRutinController::class)->prefix('bendaharapengeluaran/kegiatanRutin')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('bendaharapengeluaran.kegiatanRutin.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('bendaharapengeluaran.kegiatanRutin.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('bendaharapengeluaran.kegiatanRutin.apiUpdate');
        Route::post('apiDelete', 'apiDelete')->name('bendaharapengeluaran.kegiatanRutin.apiDelete');
        Route::get('viewGetAll', 'viewGetAll')->name('bendaharapengeluaran.kegiatanRutin.viewGetAll');
        Route::get('viewCreate', 'viewCreate')->name('bendaharapengeluaran.kegiatanRutin.viewCreate');
        Route::get('viewUpdate', 'viewUpdate')->name('bendaharapengeluaran.kegiatanRutin.viewUpdate');
    });
    Route::controller(BendaharaPengeluaranKegiatanRutinPelaksanaanController::class)->prefix('bendaharapengeluaran/KegiatanRutinPelaksana')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('bendaharapengeluaran.KegiatanRutinPelaksana.apiGetAll');
        Route::post('apiCreate', 'apiCreate')->name('bendaharapengeluaran.KegiatanRutinPelaksana.apiCreate');
        Route::post('apiUpdate', 'apiUpdate')->name('bendaharapengeluaran.KegiatanRutinPelaksana.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('bendaharapengeluaran.KegiatanRutinPelaksana.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('bendaharapengeluaran.KegiatanRutinPelaksana.viewDetail');
    });
    Route::controller(BendaharaPengeluaranSPJKegiatanRutinController::class)->prefix('bendaharapengeluaran/SPJKegiatanRutin')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('bendaharapengeluaran.SPJKegiatanRutin.apiGetAll');
        Route::post('apiCreateDetailSpj', 'apiCreateDetailSpj')->name('bendaharapengeluaran.SPJKegiatanRutin.apiCreateDetailSpj');
        Route::post('apiUpdateDetailSpj', 'apiUpdateDetailSpj')->name('bendaharapengeluaran.SPJKegiatanRutin.apiUpdateDetailSpj');
        Route::post('apiDeleteDetailSpj', 'apiDeleteDetailSpj')->name('bendaharapengeluaran.SPJKegiatanRutin.apiDeleteDetailSpj');
        Route::post('apiUpdate', 'apiUpdate')->name('bendaharapengeluaran.SPJKegiatanRutin.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('bendaharapengeluaran.SPJKegiatanRutin.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('bendaharapengeluaran.SPJKegiatanRutin.viewDetail');
    });
    Route::controller(BendaharaPengeluaranManajemenKeuanganController::class)->prefix('bendaharapengeluaran/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN PENDAPATAN
        Route::get('penganggaranPendapatan/viewGetAll', 'penganggaranPendapatanViewGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.penganggaranPendapatan.viewGetAll');
        Route::get('penganggaranPendapatan/apiGetAll', 'penganggaranPendapatanApiGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.penganggaranPendapatan.apiGetAll');
        // PENGANGGARAN PENGELUARAN
        Route::get('penganggaranPengeluaran/viewGetAll', 'penganggaranPengeluaranViewGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll');
        Route::get('penganggaranPengeluaran/apiGetAll', 'penganggaranPengeluaranApiGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.penganggaranPengeluaran.apiGetAll');
        // PENATAUSAHAAN
        Route::get('penatausahaan/apiGetAll', 'penatausahaanApiGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.penatausahaan.apiGetAll');
        Route::get('penatausahaan/viewGetAll', 'penatausahaanViewGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.penatausahaan.viewGetAll');
        // PELAPORAN
        Route::get('pelaporan/apiGetAll', 'pelaporanApiGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.pelaporan.apiGetAll');
        Route::get('pelaporan/viewGetAll', 'pelaporanViewGetAll')->name('bendaharapengeluaran.ManajemenKeuangan.pelaporan.viewGetAll');
    });
    // =====================================BENDAHARA PENERIMAAN========================================
    Route::controller(BendaharaPenerimaanKegiatanMonitoringController::class)->prefix('bendaharapenerimaan/KegiatanMonitoring')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('bendaharapenerimaan.KegiatanMonitoring.apiGetAll');

        Route::post('apiCreateLaksana', 'apiCreateLaksana')->name('bendaharapenerimaan.KegiatanMonitoring.apiCreateLaksana');
        Route::post('apiUpdateLaksana', 'apiUpdateLaksana')->name('bendaharapenerimaan.KegiatanMonitoring.apiUpdateLaksana');
        Route::post('apiDeleteLaksana', 'apiDeleteLaksana')->name('bendaharapenerimaan.KegiatanMonitoring.apiDeleteLaksana');

        Route::post('apiUpdate', 'apiUpdate')->name('bendaharapenerimaan.KegiatanMonitoring.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('bendaharapenerimaan.KegiatanMonitoring.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('bendaharapenerimaan.KegiatanMonitoring.viewDetail');

        Route::post('apiCreateDetailLaksana', 'apiCreateDetailLaksana')->name('bendaharapenerimaan.KegiatanMonitoring.apiCreateDetailLaksana');
        Route::post('apiUpdateDetailLaksana', 'apiUpdateDetailLaksana')->name('bendaharapenerimaan.KegiatanMonitoring.apiUpdateDetailLaksana');
        Route::post('apiDeleteDetailLaksana', 'apiDeleteDetailLaksana')->name('bendaharapenerimaan.KegiatanMonitoring.apiDeleteDetailLaksana');
        Route::get('viewGetAllLaksanaDetail', 'viewGetAllLaksanaDetail')->name('bendaharapenerimaan.KegiatanMonitoring.viewGetAllLaksanaDetail');
    });
    Route::controller(BendaharaPenerimaanSPJKegiatanController::class)->prefix('bendaharapenerimaan/SPJKegiatan')->group(function () {
        Route::get('apiGetAll', 'apiGetAll')->name('bendaharapenerimaan.SPJKegiatan.apiGetAll');
        Route::post('apiCreateDetailSpj', 'apiCreateDetailSpj')->name('bendaharapenerimaan.SPJKegiatan.apiCreateDetailSpj');
        Route::post('apiUpdateDetailSpj', 'apiUpdateDetailSpj')->name('bendaharapenerimaan.SPJKegiatan.apiUpdateDetailSpj');
        Route::post('apiDeleteDetailSpj', 'apiDeleteDetailSpj')->name('bendaharapenerimaan.SPJKegiatan.apiDeleteDetailSpj');
        Route::post('apiUpdate', 'apiUpdate')->name('bendaharapenerimaan.SPJKegiatan.apiUpdate');
        Route::get('viewGetAll', 'viewGetAll')->name('bendaharapenerimaan.SPJKegiatan.viewGetAll');
        Route::get('viewDetail', 'viewDetail')->name('bendaharapenerimaan.SPJKegiatan.viewDetail');
    });
    Route::controller(BendaharaPenerimaanManajemenKeuanganController::class)->prefix('bendaharapenerimaan/ManajemenKeuangan')->group(function () {
        // PERENCANAAN
        Route::get('perencanaan/apiGetAll', 'perencanaanApiGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.perencanaan.apiGetAll');
        Route::get('perencanaan/viewGetAll', 'perencanaanViewGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.perencanaan.viewGetAll');
        // PENGANGGARAN PENDAPATAN
        Route::get('penganggaranPendapatan/viewGetAll', 'penganggaranPendapatanViewGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.penganggaranPendapatan.viewGetAll');
        Route::get('penganggaranPendapatan/apiGetAll', 'penganggaranPendapatanApiGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.penganggaranPendapatan.apiGetAll');
        // PENGANGGARAN PENGELUARAN
        Route::get('penganggaranPengeluaran/viewGetAll', 'penganggaranPengeluaranViewGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.penganggaranPengeluaran.viewGetAll');
        Route::get('penganggaranPengeluaran/apiGetAll', 'penganggaranPengeluaranApiGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.penganggaranPengeluaran.apiGetAll');
        // PENATAUSAHAAN
        Route::get('penatausahaan/apiGetAll', 'penatausahaanApiGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.penatausahaan.apiGetAll');
        Route::get('penatausahaan/viewGetAll', 'penatausahaanViewGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.penatausahaan.viewGetAll');
        // PELAPORAN
        Route::get('pelaporan/apiGetAll', 'pelaporanApiGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.pelaporan.apiGetAll');
        Route::get('pelaporan/viewGetAll', 'pelaporanViewGetAll')->name('bendaharapenerimaan.ManajemenKeuangan.pelaporan.viewGetAll');
    });
});
