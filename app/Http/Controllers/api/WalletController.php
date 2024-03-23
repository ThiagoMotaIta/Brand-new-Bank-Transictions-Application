<?php

namespace App\Http\Controllers\api;

use App\Services\WalletService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    
    public function createWallet(WalletService $walletService)
    {
        return $walletService->createWalletService();
    }

    public function getWalletAmount(WalletService $walletService, $id)
    {
        return $walletService->getWalletAmountService($id);
    }

}
