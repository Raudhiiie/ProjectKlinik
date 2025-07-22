<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// routes/api.php
//pasien
// Route::get('/pasien', [PasienController::class, 'index']);
Route::post('/pasien', [PasienController::class, 'store']);
Route::put('/pasien/{no_rm}', [PasienController::class, 'update']);
Route::delete('/pasien/{no_rm}', [PasienController::class, 'destroy']);

//produk
Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/produk', [ProdukController::class, 'store']);
Route::put('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

//antrian
Route::post('/antrian/daftar', [AntrianController::class, 'daftarAntrian']);
Route::get('/antrian/hari-ini', [AntrianController::class, 'lihatAntrianHariIni']);
// Mengubah status antrian berdasarkan ID
Route::put('/antrian/{id}/status', [AntrianController::class, 'ubahStatus']);
// Menghapus antrian berdasarkan ID
Route::delete('/antrian/{id}', [AntrianController::class, 'hapusAntrian']);

//layanan
Route::get('/layanan', [LayananController::class, 'index']);
Route::post('/layanan', [LayananController::class, 'store']);
Route::post('/layanan/{id}/sub-layanan', [LayananController::class, 'storeSubLayanan']);
Route::put('/layanan/{id}', [LayananController::class, 'update']);
Route::delete('/layanan/{id}', [LayananController::class, 'destroy']);
Route::put('/sub-layanan/{id}', [LayananController::class, 'updateSubLayanan']);
Route::delete('/sub-layanan/{id}', [LayananController::class, 'destroySubLayanan']);

//transaksi
Route::post('/transaksi', [TransaksiController::class, 'simpanTransaksi']);
Route::get('/transaksi', [TransaksiController::class, 'index']);  // Optional
Route::post('/transaksi/laporan', [TransaksiController::class, 'laporan']);


