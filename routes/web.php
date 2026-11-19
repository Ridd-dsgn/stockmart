<?php

use App\Http\Controllers\AccessRightController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockMonitoringController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// SESUDAH
Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Semua pengguna yang sudah login
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daftar supplier, kategori, barang
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('products', [ProductController::class, 'index'])->name('products.index');

    // Monitoring stok
    Route::get('stocks', [StockMonitoringController::class, 'index'])->name('stocks.index');

    // Daftar transaksi barang masuk dan keluar
    Route::get('stock-ins', [StockInController::class, 'index'])->name('stock-ins.index');
    Route::get('stock-outs', [StockOutController::class, 'index'])->name('stock-outs.index');
});

// Hanya Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('access-rights', [AccessRightController::class, 'index'])->name('access-rights.index');

    // Tambah/ubah/hapus supplier, kategori, barang
    Route::resource('suppliers', SupplierController::class)->except(['index', 'show']);
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('products', ProductController::class)->except(['index', 'show']);
});

// Hanya Petugas Gudang: transaksi barang masuk/keluar dan laporan
Route::middleware(['auth', 'role:petugas_gudang'])->group(function () {
    Route::get('stock-ins/create', [StockInController::class, 'create'])->name('stock-ins.create');
    Route::post('stock-ins', [StockInController::class, 'store'])->name('stock-ins.store');

    Route::get('stock-outs/create', [StockOutController::class, 'create'])->name('stock-outs.create');
    Route::post('stock-outs', [StockOutController::class, 'store'])->name('stock-outs.store');
});

Route::middleware(['auth', 'role:admin,kepala_toko'])->group(function () {
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';