<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionService {

    // Insert Check Deposits and Purchase Transactions
	public function createTransactionService(Request $request) {
        try {
            // Logged user
            $user = Auth::user();
    
            // Customer wallet
            $wallet = Wallet::where('user_id', $user->id)->get();
    
            // If there is no wallet yet for this customer/user, create one
            if ($wallet->count() == 0){
                $createWallet = new Wallet;
                $createWallet->user_id = $user->id;
                $createWallet->amount = '0.00';
                $createWallet->save();
    
                $wallet = Wallet::where('user_id', $user->id)->get();
            }
    
            $validation = true;
    
            // If trasaction is purchase, verify customer balance
            if ($request->transaction_type == 'P'){
    
                if($wallet[0]->amount < $request->amount){
                    $validation = false;
                    return response()->json([
                      "error" => "enough-balance"
                    ], 400);
                }
    
            }
    
            if ($validation){
                $newTransaction = new Transaction;
                $newTransaction->wallet_id = $wallet[0]->id;
                $newTransaction->transaction_type = $request->transaction_type;
                $newTransaction->amount = $request->amount;
                $newTransaction->description = $request->description;
                if ($request->transaction_type == 'C'){
                    $newTransaction->check_picture = $request->check_picture;
                    $newTransaction->transaction_status = 'P';    
                } else {
                    $newTransaction->transaction_status = 'A';
                    // Update Wallet amount
                    $walletUpDate = Wallet::find($wallet[0]->id);
                    $walletUpDate->amount = $walletUpDate->amount - $request->amount;
                    $walletUpDate->save();
                }
                $newTransaction->save();
    
                return response()->json([
                  "message" => "Transaction Created!"
                ], 202);
            }
        } catch (Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 405);
        }

	}


    // List all Peding check deposits (for this test, i am not using pagination)
    public function getAllPedingTransactionService(){
        try {
            $transactions = Transaction::where('transaction_status', 'P')->get();
            if ($transactions->count() == 0){
                return response()->json([
                  "message" => "There is no pending checks yet!"
                ], 400);
            } else {
                return response()->json([
                  "transactionsList" => $transactions
                ], 202);
            }
        } catch (Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 405);
        }
    }


    // Approve or Deny Check Deposit
    public function approveDenyCheckService(Request $request){

        try {
            $transaction = Transaction::where('id', $request->transaction_id)->get();
    
            if ($transaction->count() > 0){
                $checkDeposit = Transaction::find($request->transaction_id);
                $checkDeposit->transaction_status = $request->transaction_aval_value;
                $checkDeposit->save();
    
                if($request->transaction_aval_value == 'A'){
                    // Update Wallet amount if check is approved
                    $wallet = Wallet::find($checkDeposit->wallet_id);
                    $wallet->amount = $wallet->amount + $checkDeposit->amount;
                    $wallet->save();
                }
    
                return response()->json([
                  "message" => "Check Updated!"
                ], 202);
            } else {
                return response()->json([
                  "message" => "There is no transaction"
                ], 400);
            }
        } catch (Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 405);
        }
    }


    // My Transactions history (once again, i am not using pagination)
    public function getMyTransactionService($id) {

        // Here, i am not using "Auth::user()". I am just testing ID via URL
        $wallet = Wallet::where('user_id', $id)->get();
        if ($wallet->count() > 0){
                $transactions = Transaction::where('wallet_id', $wallet[0]->id)->get();

            if ($transactions->count() == 0){
                return response()->json([
                  "warning" => "0.00",
                  "message" => "Total Transactions: 0.00"
                ], 202);
            } else {
                return response()->json([
                  "warning" => "Transactions Listed!",
                  "transactions" => $transactions
                ], 202);
            }
        } else {
            return response()->json([
                  "message" => "There is no customer for that ID",
                ], 400);
        }

    }

}
