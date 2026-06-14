<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletApiController extends Controller
{
    /**
     * Get user wallets
     */
    public function getWallets(Request $request)
    {
        try {
            $wallets = Wallet::where('user_id', $request->user_id)->get();

            return response()->json([
                'success' => true,
                'data' => $wallets,
                'message' => 'Wallets fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wallets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wallet transactions
     */
    public function getTransactions(Request $request)
    {
        try {
            $query = WalletTransaction::where('user_id', $request->user_id);

            // Filter by wallet type (reference_type)
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

            // Calculate totals
            $totalCredit = $transactions->where('type', 'credit')->sum('amount');
            $totalDebit = $transactions->where('type', 'debit')->sum('amount');

            return response()->json([
                'success' => true,
                'data' => $transactions,
                'totals' => [
                    'credit' => $totalCredit,
                    'debit' => $totalDebit,
                ],
                'message' => 'Transactions fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create wallet transaction
     */
    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'user_id' => 'required|exists:mlm_users,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'reference_type' => 'required|string',
            'reference_id' => 'nullable|integer',
        ]);

        try {
            // Get current wallet balance
            $wallet = Wallet::findOrFail($validated['wallet_id']);
            
            // Calculate new balance
            $newBalance = $validated['type'] === 'credit' 
                ? $wallet->balance + $validated['amount']
                : $wallet->balance - $validated['amount'];

            // Create transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $validated['wallet_id'],
                'user_id' => $validated['user_id'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'balance_after' => $newBalance,
                'reference_type' => $validated['reference_type'],
                'reference_id' => $validated['reference_id'] ?? null,
            ]);

            // Update wallet balance
            $wallet->update([
                'balance' => $newBalance,
            ]);

            return response()->json([
                'success' => true,
                'data' => $transaction,
                'message' => 'Transaction created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}