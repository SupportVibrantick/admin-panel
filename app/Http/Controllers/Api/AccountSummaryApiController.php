<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AccountSummaryApiController extends Controller
{
    /**
     * Get account summary data
     */
    public function index(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id);

            $type = $request->get('type', 'all');

            // Filter based on type
            switch ($type) {
                case 'current_business':
                    // Current month transactions
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                
                case 'date_calendar':
                    // Filter by date range if provided
                    if ($request->filled('date_from')) {
                        $query->whereDate('created_at', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->whereDate('created_at', '<=', $request->date_to);
                    }
                    break;
                
                case 'closing_wise':
                    // Last 30 days transactions
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    break;
                
                case 'all':
                default:
                    // No filter
                    break;
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
                'message' => 'Failed to fetch account summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}