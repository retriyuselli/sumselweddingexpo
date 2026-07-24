<?php

use App\Http\Controllers\Auth\GoogleAuthenticationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Models\Gallery;
use App\Http\Controllers\PenyelenggaraController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PesertaController;
use App\Models\ProductVendor;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LabaRugiReportController;
use App\Http\Controllers\PartisipasiPdfController;

Route::get('/form-tring-pegadaian.pdf', function () {
    $penyelenggara = \App\Models\Penyelenggara::first();
    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.form-tring-pegadaian', compact('penyelenggara'));

    return $pdf->stream('form-tring-pegadaian.pdf');
})->name('form.tring-pegadaian.pdf');

Route::get('/doorprizes/{doorprize}/form-tring-pegadaian.pdf', function (\App\Models\Doorprize $doorprize) {
    $penyelenggara = \App\Models\Penyelenggara::first();

    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.form-tring-pegadaian', [
        'penyelenggara' => $penyelenggara,
        'doorprize' => $doorprize,
    ]);

    return $pdf->stream('form-tring-pegadaian-' . $doorprize->id . '.pdf');
})->middleware(['auth'])->name('doorprizes.form-tring-pegadaian.pdf');

Route::get('/laporan-laba-rugi/{record}', [LabaRugiReportController::class, 'stream'])
    ->middleware(['auth'])
    ->name('laporan.laba-rugi.stream');
Route::get('/laporan-laba-rugi/{record}/download', [LabaRugiReportController::class, 'download'])
    ->middleware(['auth'])
    ->name('laporan.laba-rugi.download');

Route::get('/partisipasis/{expo}/pdf', [PartisipasiPdfController::class, 'download'])
    ->middleware(['auth'])
    ->name('partisipasis.pdf');

Route::get('/partisipasis/{partisipasi}/invoice', [PartisipasiPdfController::class, 'invoice'])
    ->middleware(['auth'])
    ->name('partisipasis.invoice');

Route::get('/', [HomeController::class, 'index'])->name('home');
// Halaman Lokasi Pameran
Route::view('/lokasi', 'lokasi')->name('lokasi');
// Syarat & Ketentuan
Route::view('/syarat-ketentuan', 'terms')->name('terms');
// Halaman Penyelenggara (via controller)
Route::get('/penyelenggara', [PenyelenggaraController::class, 'index'])->name('penyelenggara');
// Halaman Gallery (menampilkan image_path dari model Gallery)
Route::get('/gallery', function () {
    $galleries = Gallery::orderBy('created_at', 'desc')->paginate(24);

    return view('gallery', compact('galleries'));
})->name('gallery');
// Halaman Peserta (Daftar Vendor Partisipan)
Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');
Route::get('/peserta/{vendor:slug}', [PesertaController::class, 'show'])->name('peserta.show');
// Halaman Partners
Route::get('/partners', [VendorController::class, 'partners'])->name('partners');
// Halaman Jadwal
Route::get('/jadwal', function (\App\Services\ExpoResolver $expoResolver) {
    $expo = $expoResolver->nearestActive();
    if ($expo) {
        $expo->load(['rundowns' => function ($query) {
            $query->orderBy('tanggal', 'asc')->orderBy('waktu', 'asc');
        }]);
    }

    return view('jadwal', compact('expo'));
})->name('jadwal');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Product Detail (public)
Route::get('/products/{product:slug}', function (ProductVendor $product) {
    $product->load('vendor:id,nama_vendor,slug,logo');

    return view('products.show', compact('product'));
})->name('products.show');

// Halaman Daftar Exhibitor (dengan data dari database)
Route::get('/exhibitor', [VendorController::class, 'exhibitorPage'])->name('exhibitor');
// Form Pendaftaran Exhibitor (harus login)
Route::get('/exhibitor/daftar', [VendorController::class, 'showRegistrationForm'])->name('exhibitor.form')->middleware(['auth','verified']);
Route::post('/exhibitor/daftar', [VendorController::class, 'store'])->name('exhibitor.store')->middleware(['auth','verified']);

// Halaman Detail Vendor (Public)
Route::get('/vendors/{vendor:slug}', [VendorController::class, 'show'])->name('vendors.show');

// Vendor Resource Routes (untuk admin/management)
Route::middleware('auth')->group(function () {
    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
    Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::get('/vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    // Additional vendor routes
    Route::get('/vendors/search', [VendorController::class, 'search'])->name('vendors.search');
    Route::get('/api/vendors', [VendorController::class, 'getAllVendors'])
        ->middleware('throttle:60,1')
        ->name('vendors.api.all');
    Route::get('/api/vendors/jenis-usaha/{jenisUsahaId}', [VendorController::class, 'getByJenisUsaha'])
        ->middleware('throttle:60,1')
        ->name('vendors.api.by-jenis-usaha');
    Route::post('/vendors/{vendor}/products', [VendorController::class, 'storeProduct'])
        ->name('vendors.products.store')
        ->middleware(['verified']);
    Route::get('/vendors/{vendor}/products/{productVendor}/edit', [VendorController::class, 'editProduct'])
        ->name('vendors.products.edit')
        ->middleware(['verified']);
    Route::put('/vendors/{vendor}/products/{productVendor}', [VendorController::class, 'updateProduct'])
        ->name('vendors.products.update')
        ->middleware(['verified']);
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware(['guest', 'throttle:5,1']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware(['guest', 'throttle:5,1']);

// Google OAuth
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthenticationController::class, 'redirect'])
        ->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthenticationController::class, 'callback'])
        ->name('auth.google.callback');
});

// Password Reset
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->middleware('throttle:5,1')
        ->name('password.update');
});

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

// Checkout (Midtrans Snap)
Route::view('/checkout', 'checkout')->name('checkout')->middleware(['auth','verified']);
Route::view('/cart', 'cart')->name('cart')->middleware(['auth','verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payments/status', [PaymentController::class, 'status'])
        ->middleware('throttle:30,1')
        ->name('payments.status');
    Route::post('/payments/refresh', [PaymentController::class, 'refresh'])
        ->middleware('throttle:10,1')
        ->name('payments.refresh');
    Route::get('/payments/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
});

// Email Verification Routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended('/');
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('success', 'Link verifikasi telah dikirim ulang. Cek inbox/spam Anda.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Appointments (Frontend)
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

    Route::post('/payments/snap', [PaymentController::class, 'createSnap'])
        ->middleware('throttle:20,1')
        ->name('payments.snap');
});

Route::post('/webhooks/midtrans', [PaymentController::class, 'handleWebhook'])
    ->middleware('throttle:120,1')
    ->name('webhooks.midtrans');
