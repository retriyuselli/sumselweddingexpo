<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenyelenggaraController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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
Route::get('/exhibitor/daftar', [VendorController::class, 'showRegistrationForm'])->name('exhibitor.form')->middleware(['auth','verified']);
Route::post('/exhibitor/daftar', [VendorController::class, 'store'])->name('exhibitor.store')->middleware(['auth','verified']);

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

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');

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
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware(['auth','verified']);

// Email Verification Routes
Route::get('/email/verify', function () {
    return redirect('/')->with('success', 'Link verifikasi telah dikirim ke email Anda.');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi telah dikirim ulang.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Appointments (Frontend)
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});
