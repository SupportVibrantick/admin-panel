<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FundTransfer;
use App\Models\FundSummary;
use App\Models\MlmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FundTransferApiController extends Controller
{
    /**
     * Transfer funds to another user
     */
    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:mlm_users,id',
            'receiver_username' => 'required|string|exists:mlm_users,user_name',
            'amount' => 'required|numeric|min:1',
            'transaction_password' => 'required|string',
        ]);

        try {
            $sender = MlmUser::findOrFail($validated['sender_id']);
            $receiver = MlmUser::where('user_name', $validated['receiver_username'])->first();

            // Verify transaction password
            if (!Hash::check($validated['transaction_password'], $sender->transaction_password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid transaction password'
                ], 422);
            }

            // Check if sender has sufficient balance
            $senderBalance = FundSummary::where('user_id', $sender->id)
                ->sum(DB::raw('credit - debit'));

            if ($senderBalance < $validated['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance'
                ], 422);
            }

            // Create fund transfer record
            $transfer = FundTransfer::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'sender_username' => $sender->user_name,
                'receiver_username' => $receiver->user_name,
                'amount' => $validated['amount'],
                'remark' => $validated['remark'] ?? null,
                'transaction_password' => $validated['transaction_password'],
                'status' => 'completed',
            ]);

            // Deduct from sender
            FundSummary::create([
                'user_id' => $sender->id,
                'username' => $sender->user_name,
                'transaction_date' => now(),
                'type' => 'Debit Transfer',
                'particular' => 'Fund Transfer to ' . $receiver->user_name,
                'remark' => 'Transferred ' . $validated['amount'] . ' to ' . $receiver->user_name,
                'credit' => 0,
                'debit' => $validated['amount'],
            ]);

            // Add to receiver
            FundSummary::create([
                'user_id' => $receiver->id,
                'username' => $receiver->user_name,
                'transaction_date' => now(),
                'type' => 'Credit Transfer',
                'particular' => 'Fund Received from ' . $sender->user_name,
                'remark' => 'Received ' . $validated['amount'] . ' from ' . $sender->user_name,
                'credit' => $validated['amount'],
                'debit' => 0,
            ]);

            return response()->json([
                'success' => true,
                'data' => $transfer,
                'message' => 'Fund transferred successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sender's transfer history
     */
    public function getSentTransfers(Request $request)
    {
        try {
            $query = FundTransfer::where('sender_id', $request->user_id);

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transfers = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $transfers,
                'message' => 'Transfers fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch transfers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get receiver's transfer history
     */
    public function getReceivedTransfers(Request $request)
    {
        try {
            $query = FundTransfer::where('receiver_id', $request->user_id);

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transfers = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $transfers,
                'message' => 'Received transfers fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch received transfers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's fund wallet balance
     */
    public function getWalletBalance(Request $request)
    {
        try {
            $balance = FundSummary::where('user_id', $request->user_id)
                ->sum(DB::raw('credit - debit'));

            return response()->json([
                'success' => true,
                'balance' => $balance,
                'message' => 'Balance fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch balance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}