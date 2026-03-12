<?php

use App\Http\Controllers\Auth\SsoAuthController;
use App\Http\Controllers\Auth\SsoBackchannelLogoutController;
use App\Http\Controllers\AktivitasHarianController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\LupaAbsenController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TugasDinasController;
use App\Http\Controllers\UnitKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('sso.redirect');
    }

    return redirect('/absensis');
});

Route::get('/login', function () {
    return redirect()->route('sso.redirect');
})->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/auth/sso/redirect', [SsoAuthController::class, 'redirect'])->name('sso.redirect');
    Route::get('/auth/sso/callback', [SsoAuthController::class, 'callback'])->name('sso.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/auth/sso/logout', [SsoAuthController::class, 'logout'])->name('sso.logout');
    Route::post('/logout', [SsoAuthController::class, 'logout'])->name('logout');
});

Route::post('/auth/sso/backchannel/logout', SsoBackchannelLogoutController::class)
    ->name('sso.backchannel.logout');

Route::middleware('auth')->group(function () {
    Route::get('/surat', [HomeController::class, 'indexSurat'])->name('surat.index');
    Route::get('/surat/create', [HomeController::class, 'createsurat'])->name('surat.create');
    Route::post('/surat/store', [HomeController::class, 'storeSurat'])->name('create-surat');

    Route::get('/disposisi', [HomeController::class, 'indexDisposisi'])->name('disposisi.index');
    Route::get('/disposisi/create', [HomeController::class, 'createDisposisi'])->name('disposisi.create');
    Route::post('/disposisi/store', [HomeController::class, 'storeDisposisi'])->name('create-disposisi');
    Route::get('/disposisi/{id}/delete', [HomeController::class, 'destroyDisposisi'])->name('disposisi.destroy');

    Route::resource('absensis', AbsensiController::class)->only(['index', 'store', 'destroy']);
    Route::post('/absensis/pulang', [AbsensiController::class, 'pulang'])->name('absensis.pulang');
    Route::get('/monitor_absensi', [AbsensiController::class, 'monitor'])->name('absensis.monitor');
    Route::post('/rekap_kehadiran', [AbsensiController::class, 'exportExcel'])->name('absensis.rekap');
    Route::post('/rekap_kehadiran_sendiri', [AbsensiController::class, 'exportExcelSendiri'])->name('absensis.rekap.self');

    Route::resource('cutis', CutiController::class)->only(['index', 'store', 'destroy']);
    Route::get('/monitor_cuti', [CutiController::class, 'monitorIndex'])->name('cutis.monitor');
    Route::get('/validasi_cuti', [CutiController::class, 'validasiIndex'])->name('cutis.validasi');
    Route::put('/cutis/validasi/atasan/{id}', [CutiController::class, 'validasiCutiBawahan'])->name('cutis.validasi.atasan');
    Route::post('/cutis/validasi/pejabat/{id}', [CutiController::class, 'validasiCutiPejabat'])->name('cutis.validasi.pejabat');

    Route::resource('aktivitas_harians', AktivitasHarianController::class)->only(['index', 'store', 'destroy']);
    Route::get('/monitor', [AktivitasHarianController::class, 'monitor'])->name('aktivitas.monitor');
    Route::get('/aktivitas/past_date', [AktivitasHarianController::class, 'createByDate'])->name('aktivitas.past-date');
    Route::post('/aktivitas/store_past_date', [AktivitasHarianController::class, 'storeByDate'])->name('aktivitas.store-past-date');
    Route::post('/cetak_pdf/pribadi', [AktivitasHarianController::class, 'cetakPdf'])->name('aktivitas.cetak.pribadi');
    Route::post('/cetak_pdf/bawahan', [AktivitasHarianController::class, 'cetakPdfBawahan'])->name('aktivitas.cetak.bawahan');

    Route::resource('jabatans', JabatanController::class)->only(['index', 'store', 'destroy']);
    Route::resource('golongans', GolonganController::class)->only(['index', 'store', 'destroy']);
    Route::resource('pangkats', PangkatController::class)->only(['index', 'store', 'destroy']);
    Route::resource('unit_kerjas', UnitKerjaController::class)->only(['index', 'store', 'destroy']);
    Route::resource('kampuses', KampusController::class)->only(['index', 'store', 'destroy']);
    Route::resource('shifts', ShiftController::class)->only(['index', 'store', 'destroy']);
    Route::resource('liburs', HariLiburController::class)->only(['index', 'store', 'destroy']);

    Route::resource('pegawais', PegawaiController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::resource('lupa_absens', LupaAbsenController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/validasi_lupa_absen', [LupaAbsenController::class, 'validasi'])->name('lupa_absens.validasi');

    Route::resource('tugas_dinas', TugasDinasController::class)->only(['index', 'store', 'destroy']);
});
