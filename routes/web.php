<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Models\Gallery;
use App\Http\Controllers\PenyelenggaraController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PesertaController;
use App\Models\ProductVendor;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Halaman Lokasi Pameran
Route::view('/lokasi', 'lokasi')->name('lokasi');
// Halaman Penyelenggara (via controller)
Route::get('/penyelenggara', [PenyelenggaraController::class, 'index'])->name('penyelenggara');
// Halaman Gallery (menampilkan image_path dari model Gallery)
Route::get('/gallery', function () {
    $galleries = Gallery::orderBy('created_at', 'desc')->get();
    return view('gallery', compact('galleries'));
})->name('gallery');
// Halaman Peserta (Daftar Vendor Partisipan)
Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');
Route::get('/peserta/{vendor:slug}', [PesertaController::class, 'show'])->name('peserta.show');
// Halaman Partners
Route::get('/partners', [VendorController::class, 'partners'])->name('partners');
// Halaman Jadwal
Route::get('/jadwal', function () {
    $expo = \App\Models\Expo::where('status', true)
        ->whereDate('tanggal_mulai', '>=', now()->toDateString())
        ->with(['rundowns' => function ($query) {
            $query->orderBy('tanggal', 'asc')->orderBy('waktu', 'asc');
        }])
        ->orderBy('tanggal_mulai', 'asc')
        ->first();

    if (! $expo) {
        $expo = \App\Models\Expo::where('status', true)
            ->with(['rundowns' => function ($query) {
                $query->orderBy('tanggal', 'asc')->orderBy('waktu', 'asc');
            }])
            ->orderBy('tanggal_mulai', 'desc')
            ->first();
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
    // Route::get('/vendors/{vendor:slug}', [VendorController::class, 'show'])->name('vendors.show'); // Moved to public
    Route::get('/vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    // Additional vendor routes
    Route::get('/vendors/search', [VendorController::class, 'search'])->name('vendors.search');
    Route::get('/api/vendors', [VendorController::class, 'getAllVendors'])->name('vendors.api.all');
    Route::get('/api/vendors/jenis-usaha/{jenisUsahaId}', [VendorController::class, 'getByJenisUsaha'])->name('vendors.api.by-jenis-usaha');
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

// Checkout (Midtrans Snap)
Route::view('/checkout', 'checkout')->name('checkout')->middleware(['auth','verified']);
Route::view('/cart', 'cart')->name('cart')->middleware(['auth','verified']);
Route::get('/payments/success', function (\Illuminate\Http\Request $request) {
    $code = (string) $request->query('code', '');
    $payment = \App\Models\Payment::where('provider', 'midtrans')->where('external_id', $code)->first();
    return view('payments.success', compact('payment', 'code'));
})->name('payment.success')->middleware(['auth','verified']);
Route::get('/payments/status', function (\Illuminate\Http\Request $request) {
    $code = (string) $request->query('code', '');
    $payment = \App\Models\Payment::where('provider', 'midtrans')->where('external_id', $code)->first();
    if (!$payment) {
        return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
    }
    return response()->json([
        'ok' => true,
        'status' => (string) ($payment->status ?? ''),
        'method' => (string) ($payment->method ?? ''),
        'amount' => (float) ($payment->amount ?? 0),
        'paid_at' => $payment->paid_at ? $payment->paid_at->toIso8601String() : null,
    ]);
})->name('payments.status')->middleware(['auth','verified']);

Route::post('/payments/refresh', function (\Illuminate\Http\Request $request) {
    $code = (string) $request->input('code', '');
    $payment = \App\Models\Payment::where('provider', 'midtrans')->where('external_id', $code)->first();
    if (!$payment) {
        return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
    }
    $svc = new \App\Services\MidtransService();
    $res = $svc->getStatus($code);
    if (isset($res['error']) && $res['error']) {
        return response()->json(['ok' => false, 'midtrans' => $res], 400);
    }
    $status = (string) ($res['transaction_status'] ?? '');
    $amount = (float) ($res['gross_amount'] ?? 0);
    $method = (string) ($res['payment_type'] ?? '');
    $va = null;
    if (!empty($res['va_numbers']) && is_array($res['va_numbers'])) {
        $first = $res['va_numbers'][0] ?? [];
        $va = $first['va_number'] ?? null;
    }
    $payment->update([
        'transaction_id' => (string) ($res['transaction_id'] ?? ''),
        'status' => $status,
        'amount' => $amount,
        'method' => $method,
        'va_number' => $va,
        'paid_at' => in_array($status, ['capture','settlement']) ? now() : null,
        'raw_response' => $res,
    ]);
    $order = $payment->order;
    if ($order) {
        if (in_array($status, ['capture','settlement'])) {
            $order->update(['status' => 'paid']);
        } elseif ($status === 'pending') {
            $order->update(['status' => 'pending']);
        } elseif (in_array($status, ['expire','cancel','failure'])) {
            $order->update(['status' => 'failed']);
        }
    }
    return response()->json(['ok' => true, 'status' => $status, 'method' => $method, 'amount' => $amount, 'paid_at' => $payment->paid_at ? $payment->paid_at->toIso8601String() : null]);
})->name('payments.refresh')->middleware(['auth','verified']);

Route::get('/payments/receipt', function (\Illuminate\Http\Request $request) {
    $code = (string) $request->query('code', '');
    $payment = \App\Models\Payment::with(['order.items.vendor'])->where('provider', 'midtrans')->where('external_id', $code)->first();
    if (!$payment) {
        abort(404);
    }
    $order = $payment->order;
    $user = auth()->user();
    if ($user && $order && $order->customer_id && $order->customer_id !== $user->id) {
        abort(403);
    }
    $org = optional(\App\Models\Home::active()->with('penyelenggara')->first())->penyelenggara;
    return view('payments.receipt', ['payment' => $payment, 'code' => $code, 'penyelenggara' => $org]);
})->name('payments.receipt')->middleware(['auth','verified']);

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
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

    Route::post('/payments/snap', [PaymentController::class, 'createSnap'])->name('payments.snap');
});

Route::post('/webhooks/midtrans', [PaymentController::class, 'handleWebhook'])->name('webhooks.midtrans');
