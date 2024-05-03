<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;

class WalletService {

	public function createWalletService() {
        try {
            $user = Auth::user();
    
            $createWallet = new Wallet;
            $createWallet->user_id = $user->id;
            $createWallet->amount = '0.00';
            $createWallet->save();
    
            return response()->json([
                "message" => "User wallet created!",
            ], 200);
        } catch (Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 405);
        }

	}


    public function getWalletAmountService($id) {
        try {
            $wallet = Wallet::where('user_id', $id)->get();
            if ($wallet->count() == 0){
                return response()->json([
                  "wallet" => "0.00"
                ], 202);
            } else {
                return response()->json([
                  "wallet" => $wallet
                ], 202);
            }
        } catch (Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 405);
        }

    }

}
