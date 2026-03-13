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
    Route::post('/wallets/withdraw',[TransactionController::class,'withdraw']);
    Route::post('/wallets/deposit',[TransactionController::class,'deposit']);
    Route::post('/wallets/transfer',[TransactionController::class,'transfer']);

    Route::post('/logout',[AuthController::class,'logout']);

    });
Route::get('/cheklogin',[AuthController::class,'index'])->name('login');