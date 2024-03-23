<?php

use App\Models\Transaction;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;

test('get all transactions', function () {
    $wallet = Wallet::where('user_id', 1)->get();
    $transactions = Transaction::where('wallet_id', 1)->get();
    $response = $this
        ->get('api/get-my-transactions/1');

    // Giving wrong parameters
    $response->assertStatus(500);
});


test('get pending transactions', function () {
    $transaction = Transaction::where('transaction_status', 'P')->get();
    $response = $this
        ->get('api/get-all-pending-checks');

    $response->assertStatus(202);
});


test('create new transaction', function () {
    $response = $this->post('api/create-transaction', [
        'wallet_id' => '1',
        'transaction_type' => 'P',
        'amount' => '100.00',
        'description' => 'Something',
    ]);
    // Missing user_id due to no Authenticated user
    $response->assertStatus(500);
});