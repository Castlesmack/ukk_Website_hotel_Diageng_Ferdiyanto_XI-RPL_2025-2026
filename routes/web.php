<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VillaController;

Route::get('/', function () {
    return view('guest.home');
});

// Auth views (GET handled by controllers)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', function(){ return view('auth.passwords.email'); })->name('password.request');

// Admin pages (static placeholders)
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
Route::view('/admin/villas', 'admin.manage')->name('admin.villas');
Route::view('/admin/villas/create', 'admin.villas.create')->name('admin.villas.create');
Route::view('/admin/villas/{id}/edit', 'admin.villas.edit')->name('admin.villas.edit');
Route::view('/admin/homepage/edit', 'admin.homepage.edit')->name('admin.homepage.edit');
Route::view('/admin/reservations', 'admin.reservations')->name('admin.reservations');

// Admin users & finances
Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
Route::view('/admin/finances', 'admin.finances')->name('admin.finances');

// Resepsionis (reception) static pages
Route::view('/reception/dashboard', 'reception.dashboard')->name('reception.dashboard');
Route::view('/reception/reservations', 'reception.reservation')->name('reception.reservations');
Route::view('/reception/calendar', 'reception.calendar')->name('reception.calendar');

// Guest home
Route::view('/home', 'guest.home')->name('home');

// Additional guest / user pages (static previews)
Route::view('/guest/home-before', 'guest.home_before')->name('guest.home.before');
Route::view('/guest/home-after', 'guest.home_after')->name('guest.home.after');
Route::get('/villa', [VillaController::class, 'search'])->name('villa.search');
Route::get('/villa/{id}', [VillaController::class, 'detail'])->name('guest.villa.detail');
Route::get('/reservation/form', [VillaController::class, 'reservationForm'])->name('guest.reservation.form');
Route::view('/guest/payment', 'guest.payment_method')->name('guest.payment');
Route::view('/guest/payment/failed', 'guest.payment_failed')->name('guest.payment.failed');
Route::view('/guest/payment/success', 'guest.payment_success')->name('guest.payment.success');

// Midtrans token endpoint (server-side). Returns JSON { token, order_id, client_key }
Route::post('/midtrans/token', [PaymentController::class, 'token'])->name('midtrans.token');

// User pages
Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
Route::post('/user/profile', [App\Http\Controllers\UserController::class, 'updateProfile']);
Route::view('/user/bookings', 'user.bookings')->name('user.bookings');


// Password reset demo handler
Route::post('/password/reset', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
    ]);
    return redirect('/login')->with('status', 'If your email exists in our system, a reset link has been sent.');
});
