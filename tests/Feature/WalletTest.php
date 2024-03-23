<?php

use App\Models\Wallet;
use App\Providers\RouteServiceProvider;

test('get amount from wallet', function () {
    $wallet = Wallet::get();
    $response = $this
        ->get('api/user-wallet/1');

    $response->assertStatus(202);
});

test('create wallet', function () {
    $response = $this->post('api/create-wallet', [
        'user_id' => '1',
        'amount' => '100.00',
    ]);
    // This is because of ID is retrieven from Auth::user()
    $response->assertStatus(500);
});