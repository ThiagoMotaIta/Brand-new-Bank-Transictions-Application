<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\WalletController;
use App\Http\Controllers\api\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// List users
Route::get('users', [UserController::class, 'getAllUsers']);

// Create user wallet
Route::post('create-wallet', [WalletController::class, 'createWallet']);

// Transaction
Route::post('create-transaction', [TransactionController::class, 'createTransaction']);

// Get all pending checks deposits
Route::get('get-all-pending-checks', [TransactionController::class, 'getAllPendingTransactions']);

// Get logged user wallet
Route::get('user-wallet/{id}', [WalletController::class, 'getWalletAmount']);

// Approve or Deny check deposit
Route::put('manage-check-deposit', [TransactionController::class, 'approveDenyCheck']);

// Get all transactions
Route::get('get-my-transactions/{id}', [TransactionController::class, 'getMyTransactions']);

require __DIR__.'/auth.php';
