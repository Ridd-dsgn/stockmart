<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Semua role boleh melihat daftar supplier
Route::middleware('auth')->group(function () {
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
});

// Hanya Admin yang boleh tambah/ubah/hapus
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('suppliers', SupplierController::class)->except(['index', 'show']);
});

// Semua role boleh melihat daftar kategori
Route::middleware('auth')->group(function () {
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
});

// Hanya Admin yang boleh tambah/ubah/hapus
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
});

require __DIR__.'/auth.php';