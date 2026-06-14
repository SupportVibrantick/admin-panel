<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\CashBonusRequest;
use App\Models\ClaimCashRequest;
use App\Models\CashBonusHistory;
use App\Models\AwardReward;
use Illuminate\Http\Request;

class WalletIncomeApiController extends Controller
{
    /**
     * Get all wallet transactions
     */
    public function getWalletTransactions(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id);

            // Filter by reference type
            if ($request->filled('reference_type') && $request->reference_type !== 'all') {
                $query->where('reference_type', $request->reference_type);
            }

            // Filter by transaction type (credit/debit)
            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            $totalCredit = $transactions->where('type', 'credit')->sum('amount');
            $totalDebit = $transactions->where('type', 'debit')->sum('amount');

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'totals' => [
                    'credit' => $totalCredit,
                    'debit' => $totalDebit,
                ],
                'message' => 'Wallet transactions fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wallet transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Direct Income
     */
    public function getDirectIncome(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id)
                ->where('reference_type', 'direct_income');

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

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

    /**
     * Get Matching Income
     */
    public function getMatchingIncome(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id)
                ->where('reference_type', 'matching_income');

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

            $totalCredit = $transactions->where('type', 'credit')->sum('amount');

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'totals' => [
                    'credit' => $totalCredit,
                    'debit' => 0,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch matching income',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Generation Income
     */
    public function getGenerationIncome(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id)
                ->where('reference_type', 'generation_income');

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

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
                'message' => 'Failed to fetch generation income',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Cash Bonus Requests
     */
    public function getCashBonusRequests(Request $request)
    {
        try {
            $query = CashBonusRequest::where('user_id', $request->user_id);

            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('status', $request->type);
            }

            $data = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Cash bonus requests fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cash bonus requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Claim Cash Requests
     */
    public function getClaimCashRequests(Request $request)
    {
        try {
            $query = ClaimCashRequest::where('user_id', $request->user_id);

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $data = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Claim cash requests fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch claim requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Cash Bonus History
     */
    public function getCashBonusHistory(Request $request)
    {
        try {
            $query = CashBonusHistory::where('user_id', $request->user_id);

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $data = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Cash bonus history fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cash bonus history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Awards and Rewards
     */
    public function getAwardsRewards(Request $request)
    {
        try {
            $query = AwardReward::where('user_id', $request->user_id);

            if ($request->filled('type')) {
                switch ($request->type) {
                    case 'current_business':
                        $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year);
                        break;
                    case 'closing_wise':
                        $query->whereDate('created_at', '>=', now()->subDays(30));
                        break;
                    case 'date_calendar':
                        if ($request->filled('date_from')) {
                            $query->whereDate('created_at', '>=', $request->date_from);
                        }
                        if ($request->filled('date_to')) {
                            $query->whereDate('created_at', '<=', $request->date_to);
                        }
                        break;
                }
            }

            $data = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Awards and rewards fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch awards and rewards',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Account Summary (All Transactions)
     */
    public function getAccountSummary(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id);

            $type = $request->get('type', 'all');

            switch ($type) {
                case 'current_business':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                
                case 'date_calendar':
                    if ($request->filled('date_from')) {
                        $query->whereDate('created_at', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->whereDate('created_at', '<=', $request->date_to);
                    }
                    break;
                
                case 'closing_wise':
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    break;
                
                case 'all':
                default:
                    break;
            }

            $transactions = $query->orderBy('created_at', 'desc')->get();

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
        /**
     * Get Downline Rank
     */
    public function getDownlineRank(Request $request)
    {
        try {
            // This would typically query a downline_ranks table
            // For now, returning empty array
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Downline rank fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch downline rank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Weekly Payout
     */
    public function getWeeklyPayout(Request $request)
    {
        try {
            // This would query payout tables
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Weekly payout fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch weekly payout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Retreat Tours
     */
    public function getRetreatTours(Request $request)
    {
        try {
            // This would query retreat_tours table
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Retreat tours fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch retreat tours',
                'error' => $e->getMessage()
            ], 500);
        }
    }
        /**
     * Get Order History
     */
/**
 * Get Order History
 */
public function getOrderHistory(Request $request)
{
    try {
        // Simple query without filters first
        $orders = \App\Models\Order::where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $orders, // Raw data bhej rahe hain
            'count' => $orders->count(),
            'message' => 'Order history fetched successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed: ' . $e->getMessage(),
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get By Hand Delivery
     */
    public function getByHandDelivery(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'By hand delivery fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch by hand delivery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Courier Delivery
     */
    public function getCourierDelivery(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Courier delivery fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courier delivery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get By Hand Award
     */
    public function getByHandAward(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'By hand award fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch by hand award',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get By Courier Award
     */
    public function getByCourierAward(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'By courier award fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch by courier award',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Other Products
     */
    public function getOtherProducts(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Other products fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch other products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}