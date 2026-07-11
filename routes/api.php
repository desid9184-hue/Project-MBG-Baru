<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeliveryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
 
// ── Route Terproteksi (wajib pakai token, header: Authorization: Bearer {token}) ──
Route::middleware('auth:sanctum')->group(function () {

 // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
 
    // Delivery / Tugas Driver
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::get('/deliveries/{id}', [DeliveryController::class, 'show']);
    Route::post('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus']);
    Route::post('/deliveries/{id}/location', [DeliveryController::class, 'updateLocation']);
});
