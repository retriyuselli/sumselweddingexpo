<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenyelenggaraController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Halaman Lokasi Pameran
Route::view('/lokasi', 'lokasi')->name('lokasi');
// Halaman Penyelenggara (via controller)
Route::get('/penyelenggara', [PenyelenggaraController::class, 'index'])->name('penyelenggara');
// Halaman Gallery
Route::view('/gallery', 'gallery')->name('gallery');
// Halaman Partners
Route::view('/partners', 'partners')->name('partners');
// Halaman Jadwal
Route::view('/jadwal', 'jadwal')->name('jadwal');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Halaman Daftar Exhibitor (dengan data dari database)
Route::get('/exhibitor', [VendorController::class, 'exhibitorPage'])->name('exhibitor');
// Form Pendaftaran Exhibitor (harus login)
Route::get('/exhibitor/daftar', [VendorController::class, 'showRegistrationForm'])->name('exhibitor.form')->middleware('auth');
Route::post('/exhibitor/daftar', [VendorController::class, 'store'])->name('exhibitor.store')->middleware('auth');

// Vendor Resource Routes (untuk admin/management)
Route::middleware('auth')->group(function () {
    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
    Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::get('/vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    Route::get('/vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    // Additional vendor routes
    Route::get('/vendors/search', [VendorController::class, 'search'])->name('vendors.search');
    Route::get('/api/vendors', [VendorController::class, 'getAllVendors'])->name('vendors.api.all');
    Route::get('/api/vendors/jenis-usaha/{jenisUsahaId}', [VendorController::class, 'getByJenisUsaha'])->name('vendors.api.by-jenis-usaha');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Profile Routes
Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// Avatar Routes (Private Storage)
Route::get('/avatar/{user}', [AvatarController::class, 'show'])->name('avatar.show');
Route::get('/my-avatar', [AvatarController::class, 'showOwn'])->name('avatar.own')->middleware('auth');

// User Settings Routes
Route::get('/settings', [AuthController::class, 'settings'])->name('settings')->middleware('auth');
Route::put('/settings', [AuthController::class, 'updateSettings'])->name('settings.update')->middleware('auth');

// Dashboard Route
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');
