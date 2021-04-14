<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('is_verified');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/transaction', [TransactionController::class, 'transaction']);
    Route::post('/transaction/confirm', [TransactionController::class, 'confirm']);

    Route::get('/points', [UserController::class, 'getPoints']);
    Route::get('/transactions', [UserController::class, 'getTransactions']);
});
