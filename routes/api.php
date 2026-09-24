<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'resolve.tenant'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', fn (Request $request) => $request->user()->load('company'));
    Route::apiResource('sales', SaleController::class)->only(['index', 'store', 'show', 'update'])->names('api.sales');
    Route::apiResource('customers', CustomerController::class)->names('api.customers');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('products', ProductController::class)->names('api.products');
});