<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\AdminVillaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', [VillaController::class, 'index'])->name('home');

// Auth views (GET handled by controllers)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', function(){ return view('auth.passwords.email'); })->name('password.request');

// Admin pages with database connection
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/villas', [AdminVillaController::class, 'index'])->name('admin.villas.index');
    Route::get('/admin/villas/create', [AdminVillaController::class, 'create'])->name('admin.villas.create');
    Route::post('/admin/villas', [AdminVillaController::class, 'store'])->name('admin.villas.store');
    Route::get('/admin/villas/{id}/edit', [AdminVillaController::class, 'edit'])->name('admin.villas.edit');
    Route::put('/admin/villas/{id}', [AdminVillaController::class, 'update'])->name('admin.villas.update');
    Route::delete('/admin/villas/{id}', [AdminVillaController::class, 'destroy'])->name('admin.villas.destroy');
    Route::get('/admin/homepage/edit', [HomepageController::class, 'edit'])->name('admin.homepage.edit');
    Route::put('/admin/homepage', [HomepageController::class, 'update'])->name('admin.homepage.update');
    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('admin.reservations');

    // Admin users & finances
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::view('/admin/finances', 'admin.finances')->name('admin.finances');

    // Resepsionis (reception) pages with database
    Route::get('/reception/dashboard', [ReceptionController::class, 'dashboard'])->name('reception.dashboard');
    Route::get('/reception/reservations', [ReceptionController::class, 'reservations'])->name('reception.reservations');
    Route::get('/reception/calendar', [ReceptionController::class, 'calendar'])->name('reception.calendar');
});

// Guest home
Route::get('/home', [VillaController::class, 'index'])->name('home');

// Additional guest / user pages (static previews)
Route::view('/guest/home-before', 'guest.home_before')->name('guest.home.before');
Route::view('/guest/home-after', 'guest.home_after')->name('guest.home.after');
Route::get('/villa', [VillaController::class, 'search'])->name('villa.search');
Route::get('/villa/{id}', [VillaController::class, 'detail'])->name('guest.villa.detail');

// Protected booking routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/reservation/form', [VillaController::class, 'reservationForm'])->name('guest.reservation.form');
    Route::post('/reservation/store', [VillaController::class, 'storeBooking'])->name('guest.reservation.store');
    Route::get('/payment/{booking_id}', [VillaController::class, 'payment'])->name('guest.payment');
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/profile', [App\Http\Controllers\UserController::class, 'updateProfile']);
    Route::view('/user/bookings', 'user.bookings')->name('user.bookings');
    Route::post('/midtrans/token', [PaymentController::class, 'token'])->name('midtrans.token');
});

Route::view('/guest/payment/failed', 'guest.payment_failed')->name('guest.payment.failed');
Route::view('/guest/payment/success', 'guest.payment_success')->name('guest.payment.success');


// Password reset demo handler
Route::post('/password/reset', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
    ]);
    return redirect('/login')->with('status', 'If your email exists in our system, a reset link has been sent.');
});
