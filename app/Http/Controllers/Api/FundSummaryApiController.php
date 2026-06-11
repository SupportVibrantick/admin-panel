<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FundSummary;
use Illuminate\Http\Request;

class FundSummaryApiController extends Controller
{
    /**
     * Get fund summary with filters
     */
    public function index(Request $request)
    {
        try {
            $query = FundSummary::query();

            // Filter by user_id
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by type
            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('transaction_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('transaction_date', '<=', $request->date_to);
            }

            $fundSummaries = $query->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            // Calculate totals
            $totalCredit = $fundSummaries->sum('credit');
            $totalDebit = $fundSummaries->sum('debit');

            return response()->json([
                'success' => true,
                'data' => $fundSummaries,
                'totals' => [
                    'credit' => $totalCredit,
                    'debit' => $totalDebit,
                ],
                'message' => 'Fund summary fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fund summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}