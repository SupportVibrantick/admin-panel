<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class DirectIncomeApiController extends Controller
{
    /**
     * Get Direct Income data
     */
    public function index(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id)
                ->where('reference_type', 'direct_income');

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            // Calculate totals
            $totalCredit = $transactions->where('type', 'credit')->sum('amount');
            $totalDebit = $transactions->where('type', 'debit')->sum('amount');

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'totals' => [
                    'credit' => $totalCredit,
                    'debit' => $totalDebit,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch direct income',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}