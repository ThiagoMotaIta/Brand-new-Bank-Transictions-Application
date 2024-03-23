<?php

namespace App\Http\Controllers\api;

use App\Services\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    
    public function createTransaction(TransactionService $transactionService, Request $request)
    {
        return $transactionService->createTransactionService($request);
    }


    public function getAllPendingTransactions(TransactionService $transactionService)
    {
        return $transactionService->getAllPedingTransactionService();
    }


    public function approveDenyCheck(TransactionService $transactionService, Request $request)
    {
        return $transactionService->approveDenyCheckService($request);
    }


    public function getMyTransactions(TransactionService $transactionService, $id)
    {
        return $transactionService->getMyTransactionService($id);
    }


}
