<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function directIncome(Request $request)
    {
        $userId = $request->user_id;

        $directIncome = WalletTransaction::with('user')->where('user_id', $userId)->where('wallet_id', 1)->get();
        $totalAmount = $directIncome->sum('amount');
        
        return response()->json([
            'success' => true,
            'data' => $directIncome,
            'total' => $totalAmount,
        ]);
    }

    public function matchingIncome(Request $request)
    {
        $userId = $request->user_id;

        $directIncome = WalletTransaction::with('user')->where('user_id', $userId)->where('wallet_id', 2)->get();
        $totalAmount = $directIncome->sum('amount');
        
        return response()->json([
            'success' => true,
            'data' => $directIncome,
            'total' => $totalAmount,
        ]);
    }
}
