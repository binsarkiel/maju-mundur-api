<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Customer\ProductController;
use App\Http\Controllers\API\Customer\RewardController;
use App\Http\Controllers\API\Merchant\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']); // Customer can view products


// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Merchant routes
    Route::prefix('merchant')->middleware('is_merchant')->group(function () {
        Route::apiResource('products', \App\Http\Controllers\API\Merchant\ProductController::class);
        Route::get('orders', [OrderController::class, 'index']); // List of orders
        Route::get('orders/{order}', [OrderController::class, 'show']); // Order details

    });

    // Customer routes
     Route::prefix('customer')->middleware('is_customer')->group(function(){
        Route::post('/orders', [\App\Http\Controllers\API\Customer\OrderController::class, 'store']); // Create an order
        Route::get('/orders', [\App\Http\Controllers\API\Customer\OrderController::class, 'index']);  //get list order by user login
        Route::get('/rewards', [RewardController::class, 'index']);       //get all reward
        Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem']); // Redeem a reward
     });
});