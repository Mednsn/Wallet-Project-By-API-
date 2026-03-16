<?php

use App\Http\Controllers\api\AuthController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\WalletController;

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

Route::post('/register',[AuthController::class,'store']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('wallets', WalletController::class);
    Route::post('/wallets/{id}/withdraw',[TransactionController::class,'withdraw']);
    Route::post('/wallets/{id}/deposit',[TransactionController::class,'deposit']);
    Route::post('/wallets/{id}/transfer',[TransactionController::class,'transfer']);
    Route::post('/wallets/{id}/transactions',[TransactionController::class,'index']);

    Route::post('/logout',[AuthController::class,'logout']);

    });
Route::get('/cheklogin',[AuthController::class,'index'])->name('login');