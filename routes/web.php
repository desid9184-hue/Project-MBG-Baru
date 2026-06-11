<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Asisten\AsisteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Driver\DriverController;
use App\Http\Controllers\Guru\GuruController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
});

// ── Guru ──────────────────────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');

    // Orders
    Route::get('/orders', [GuruController::class, 'orders'])->name('orders');
    Route::get('/orders/create', [GuruController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [GuruController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [GuruController::class, 'showOrder'])->name('orders.show');

    // Tracking
    Route::get('/tracking/{order}', [GuruController::class, 'tracking'])->name('tracking');
    Route::get('/tracking/{order}/data', [GuruController::class, 'getTrackingData'])->name('tracking.data');
});

// ── Asisten ───────────────────────────────────────────────
Route::prefix('asisten')->name('asisten.')->middleware(['auth', 'role:asisten'])->group(function () {
    Route::get('/dashboard', [AsisteController::class, 'dashboard'])->name('dashboard');

    // Orders
    Route::get('/orders', [AsisteController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AsisteController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/accept', [AsisteController::class, 'acceptOrder'])->name('orders.accept');
    Route::post('/orders/{order}/status', [AsisteController::class, 'updateStatus'])->name('orders.status');

    // Menu
    Route::get('/orders/{order}/menu', [AsisteController::class, 'inputMenu'])->name('orders.menu');
    Route::post('/orders/{order}/menu', [AsisteController::class, 'storeMenu'])->name('orders.menu.store');

    // Driver Assignment
    Route::post('/orders/{order}/driver', [AsisteController::class, 'assignDriver'])->name('orders.driver');
});

// ── Driver ────────────────────────────────────────────────
Route::prefix('driver')->name('driver.')->middleware(['auth', 'role:driver'])->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');

    // Deliveries
    Route::get('/deliveries', [DriverController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/{delivery}', [DriverController::class, 'showDelivery'])->name('deliveries.show');
    Route::post('/deliveries/{delivery}/complete', [DriverController::class, 'completeDelivery'])->name('deliveries.complete');

    // Tracking (AJAX)
    Route::post('/deliveries/{delivery}/start-tracking', [DriverController::class, 'startTracking'])->name('deliveries.start-tracking');
    Route::post('/deliveries/{delivery}/update-location', [DriverController::class, 'updateLocation'])->name('deliveries.update-location');
    Route::post('/deliveries/{delivery}/arrived', [DriverController::class, 'arrivedAtSchool'])->name('deliveries.arrived');
    Route::post('/deliveries/{delivery}/stop-tracking', [DriverController::class, 'stopTracking'])->name('deliveries.stop-tracking');
});
