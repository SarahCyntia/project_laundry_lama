<?php

use App\Models\Penjemputan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JemputController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\PenjemputanController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AntarJemputController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Route
Route::middleware(['auth', 'json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });
    });


    Route::get('/dashboard', [DashboardController::class, 'index']);
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData']);
    Route::post('/update-status', [DashboardController::class, 'updateStatus']);


    Route::post('order', [OrderController::class, 'index']);
    Route::post('order', [OrderController::class, 'store']);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);



    Route::post('/antar-jemput', [PenjemputanController::class, 'store']);

    Route::middleware('can:penjemputan')->group(function () {
        // kalau mau tetap bisa diakses publik untuk create
        Route::post('penjemputan/store', [PenjemputanController::class, 'store']);

        // resource API (show, update, destroy, dll)
        Route::apiResource('penjemputan', PenjemputanController::class)
            ->except(['store']);
    });


    Route::get('/jemput', [JemputController::class, 'create']);
    Route::post('/jemput', [JemputController::class, 'store']);
    Route::get('/tracking/{trackingCode}', [JemputController::class, 'checkStatus']);

    // Route::get('/jemput', [JemputController::class, 'create']);
    
    // // Submit form jemput
    // Route::post('/jemput', [JemputController::class, 'store']);
    
    // // Cek status tracking
    // Route::get('/tracking/{trackingCode}', [JemputController::class, 'checkStatus']);

    //  Route::middleware('can:kurir')->group(function () {
//         Route::get('kurir', [KurirController::class, 'get'])->withoutMiddleware('can:kurir');
//         Route::post('kurir', [KurirController::class, 'index']);
//         Route::post('kurir/store', [KurirController::class, 'store']);
//         Route::apiResource('kurir', KurirController::class)
//             ->except(['index', 'store']);
//         // Route::delete('/kurirs', [KurirController::class, 'destroy']); hapus

    //     });

    Route::apiResource('orders', OrderController::class);
    Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder']);
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancelOrder']);
    // Route::get('/cek-status/{tracking_code}', [PesananController::class, 'cekStatus']);
    Route::get('/pesanan/{tracking_code}', [PesananController::class, 'getPesananByTracking']);



    Route::middleware('can:pelanggan')->group(function () {
        Route::get('pelanggan', [PelangganController::class, 'get'])->withoutMiddleware('can:pelanggan');
        Route::post('pelanggan', [PelangganController::class, 'index']);
        Route::post('pelanggan/store', [PelangganController::class, 'store']);
        Route::apiResource('pelanggan', PelangganController::class)
            ->except(['index', 'store']);
        // Route::get('pelanggan', [PelangganController::class, 'show']);
        // Route::put('pelanggan', [PelangganController::class, 'update']);
        // Route::delete('pelanggan', [PelangganController::class, 'destroy']);
    });

    // // Notifications
    // Route::get('/notifications', [NotificationController::class, 'index']);
    // Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    // Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    // Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

    // // Laundry Services
    // Route::get('/services', [ServiceController::class, 'index']);
    // Route::get('/services/{service}', [ServiceController::class, 'show']);

    // // Payment
    // Route::post('/orders/{order}/payment', [PaymentController::class, 'processPayment']);
    // Route::get('/orders/{order}/payment/status', [PaymentController::class, 'getPaymentStatus']);








});
