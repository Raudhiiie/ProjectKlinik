<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TerapisController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\AuthConttroller;
use App\Http\Controllers\rekamMedisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\LayananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'terapis') {
            return redirect()->route('terapis.pasien.index');
        } elseif ($role === 'dokter') {
            return redirect()->route('dokter.rekamMedis.index'); // tambahkan route ini kalau ada
        }
        return view('template.master'); // fallback
    }

    return redirect()->route('login');
});

// ✅ Route Login
Route::get('/login', [AuthConttroller::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthConttroller::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthConttroller::class, 'logout'])->name('logout');

Route::get('/monitor-antrian', [AntrianController::class, 'monitor'])->name('monitor.antrian');

// Route::get('/terapis/pasien', [PasienController::class, 'index'])->name('terapis.pasien.index');
Route::prefix('terapis')->name('terapis.')->group(function () {
    Route::resource('pasien', PasienController::class);
    Route::resource('transaksi', TransaksiController::class);
    // web.php
    Route::put('/transaksi/{id}/bayar', [TransaksiController::class, 'bayar'])
        ->name('transaksi.bayar');
    Route::post('/transaksi/{id}/konfirmasi', [TransaksiController::class, 'konfirmasi'])
        ->name('transaksi.konfirmasi');

    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetak'])
        ->name('transaksi.cetak');
    Route::resource('terapis', TerapisController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('laporanKeuangan', LaporanKeuanganController::class);
    Route::delete('/layanan/sub/{id}', [LayananController::class, 'destroySubLayanan'])->name('layanan.sub.destroy');
    Route::put('/layanan/sub/{id}', [LayananController::class, 'updateSubLayanan'])->name('layanan.sub.update');
    Route::post('/layanan/sub/store', [LayananController::class, 'storeSubLayanan'])->name('layanan.storeSubLayanan');
    Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
    Route::get('/layanan/{layanan_id}/sub/create', [LayananController::class, 'createSubLayanan'])->name('layanan.createSubLayanan');


    Route::post('/layanan/sub/store', [LayananController::class, 'storeSubLayanan'])->name('layanan.storeSubLayanan');

    Route::get('/sublayanan/create/{layanan_id}', [LayananController::class, 'createSubLayanan'])->name('sublayanan.create');
    Route::post('/sublayanan/store', [LayananController::class, 'storeSubLayanan'])->name('sublayanan.store');
    Route::get('dashboard', [DashboardController::class, 'indexterapis'])->name('dashboard.index');
    Route::post('antrian', [AntrianController::class, 'store'])->name('antrian.store');
    Route::post('/antrian/{id}/panggil', [AntrianController::class, 'panggilPasien'])->name('antrian.panggil');



    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('{posisi}', [ProdukController::class, 'index'])->name('index');
        Route::get('{posisi}/create', [ProdukController::class, 'create'])->name('create');
        Route::post('{posisi}', [ProdukController::class, 'store'])->name('store');
        Route::get('{posisi}/{id}/edit', [ProdukController::class, 'edit'])->name('edit');
        Route::put('{posisi}/{id}', [ProdukController::class, 'update'])->name('update');
        Route::delete('{posisi}/{id}', [ProdukController::class, 'destroy'])->name('destroy');
    });
});


Route::prefix('dokter')->name('dokter.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'indexdokter'])->name('dashboard.index');
    Route::resource('rekamMedis', rekamMedisController::class)->parameters([
        'rekamMedis' => 'rekamMedis'
    ]);
});



//Route::get('/terapis/pasien', [PasienController::class, 'index'])->name('terapis.pasien.index');
//Route::post('terapis/pasien', [PasienController::class, 'store']);
//Route::put('/pasien/{no_rm}', [PasienController::class, 'update']);
//Route::delete('/pasien/{no_rm}', [PasienController::class, 'destroy']);


// Route::get('/', [PasienController::class, 'index']);
// Route::resource('pasien', PasienController::class);
