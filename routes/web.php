<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::post('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [ProductController::class, 'index']);
    Route::resource('products', ProductController::class);
    Route::get('transaksi/pemasukan', [TransaksiController::class, 'createPemasukan'])->name('transaksi.pemasukan');
    Route::get('transaksi/pengeluaran', [TransaksiController::class, 'createPengeluaran'])->name('transaksi.pengeluaran');
    Route::post('transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/get-product-price', [TransaksiController::class, 'getProductPrice'])->name('product.getPrice');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
    Route::resource('users', UserController::class);
});