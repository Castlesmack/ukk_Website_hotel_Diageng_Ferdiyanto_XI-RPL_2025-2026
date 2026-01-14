<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AdminVillaController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\ReceptionController;

Route::get('/', [VillaController::class, 'index'])->name('home');

// Auth views (GET handled by controllers)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.request');
Route::post('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/password/update', [App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');

// Admin pages with database connection
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    
    // Villas management
    Route::get('/admin/villas', [AdminVillaController::class, 'index'])->name('admin.villas.index');
    Route::get('/admin/villas/create', [AdminVillaController::class, 'create'])->name('admin.villas.create');
    Route::post('/admin/villas', [AdminVillaController::class, 'store'])->name('admin.villas.store');
    Route::get('/admin/villas/{villa}/edit', [AdminVillaController::class, 'edit'])->name('admin.villas.edit');
    Route::put('/admin/villas/{villa}', [AdminVillaController::class, 'update'])->name('admin.villas.update');
    Route::delete('/admin/villas/{villa}', [AdminVillaController::class, 'destroy'])->name('admin.villas.destroy');
    
    // Reservations
    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('admin.reservations.index');
    Route::get('/admin/reservations/{booking}', [ReservationController::class, 'show'])->name('admin.reservations.show');
    Route::post('/admin/reservations/{booking}/status', [ReservationController::class, 'updateStatus'])->name('admin.reservations.updateStatus');
    Route::delete('/admin/reservations/{booking}', [ReservationController::class, 'destroy'])->name('admin.reservations.destroy');
    
    // Users
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Finances
    Route::get('/admin/finances', [FinanceController::class, 'index'])->name('admin.finances.index');
    
    // Settings
    Route::get('/admin/settings/homepage', [SettingController::class, 'editHomepage'])->name('admin.settings.homepage');
    Route::post('/admin/settings/homepage', [SettingController::class, 'updateHomepage'])->name('admin.settings.homepage.update');
    Route::put('/admin/settings/homepage', [SettingController::class, 'updateHomepage']); // Also support PUT
    Route::post('/admin/settings/homepage/delete-image', [SettingController::class, 'deleteHomepageImage'])->name('admin.homepage.delete-image');
    Route::get('/admin/settings/facilities', [SettingController::class, 'manageFacilities'])->name('admin.settings.facilities');
    Route::post('/admin/settings/facilities', [SettingController::class, 'storeFacility'])->name('admin.settings.facilities.store');
    Route::delete('/admin/settings/facilities/{facility}', [SettingController::class, 'destroyFacility'])->name('admin.settings.facilities.destroy');
    Route::get('/admin/settings/gallery', [SettingController::class, 'villaGallery'])->name('admin.settings.gallery');
});

// Reception pages (receptionist access)
Route::middleware(['auth', 'receptionist'])->group(function () {
    Route::get('/reception/dashboard', [ReceptionController::class, 'dashboard'])->name('reception.dashboard');
    Route::get('/reception/reservations', [ReceptionController::class, 'reservations'])->name('reception.reservations');
    Route::get('/reception/calendar', [ReceptionController::class, 'calendar'])->name('reception.calendar');
});

// Guest home
Route::get('/home', [VillaController::class, 'index'])->name('guest.home');

// API routes for search
Route::get('/api/villas/search', [VillaController::class, 'searchAPI'])->name('api.villas.search');

// Additional guest / user pages (static previews)
Route::view('/guest/home-before', 'guest.home_before')->name('guest.home.before');
Route::view('/guest/home-after', 'guest.home_after')->name('guest.home.after');
Route::get('/villa', [VillaController::class, 'search'])->name('villa.search');
Route::get('/villa/{id}', [VillaController::class, 'detail'])->name('guest.villa.detail');

// Public booking routes (no auth required - guest can book)
Route::post('/paymentlink', [VillaController::class, 'storeBooking'])->name('guest.store.booking');
Route::get('/payment/{booking_id}', [VillaController::class, 'payment'])->name('guest.payment');
Route::post('/midtrans/token', [PaymentController::class, 'token'])->name('midtrans.token');

// Protected booking routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/reservation/form', [VillaController::class, 'reservationForm'])->name('guest.reservation.form');
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/profile', [App\Http\Controllers\UserController::class, 'updateProfile']);
    Route::get('/user/bookings', [App\Http\Controllers\UserController::class, 'bookings'])->name('user.bookings');
});

Route::get('/guest/payment/failed', [PaymentController::class, 'failed'])->name('guest.payment.failed');
Route::get('/guest/payment/success', [PaymentController::class, 'success'])->name('guest.payment.success');
