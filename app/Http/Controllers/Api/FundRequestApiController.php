<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminBankDetail;
use App\Models\FundRequest;
use App\Models\FundSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FundRequestApiController extends Controller
{
     /**
     * Get all fund requests (for admin)
     */
    public function index(Request $request)
    {
        try {
            $query = FundRequest::with(['user', 'bankDetail']);

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by user
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            $fundRequests = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $fundRequests,
                'message' => 'Fund requests fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fund requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve/Reject fund request
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_remark' => 'nullable|string',
        ]);

        try {
            $fundRequest = FundRequest::findOrFail($id);
            $fundRequest->update([
                'status' => $validated['status'],
                'admin_remark' => $validated['admin_remark'] ?? null,
            ]);

            // If approved, add to user's fund summary
            if ($validated['status'] === 'approved') {
                FundSummary::create([
                    'user_id' => $fundRequest->user_id,
                    'username' => $fundRequest->username,
                    'transaction_date' => now(),
                    'type' => 'ADMIN CREDIT',
                    'particular' => 'Fund Request Approved',
                    'remark' => $validated['admin_remark'] ?? 'Fund request approved by admin',
                    'credit' => $fundRequest->amount,
                    'debit' => 0,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Fund request {$validated['status']} successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active bank details for fund request
     */
    public function getBankDetails()
    {
        try {
            $bankDetails = AdminBankDetail::where('is_active', true)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bankDetails,
                'message' => 'Bank details fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bank details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit fund request
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
            'username' => 'required|string',
            'bank_detail_id' => 'required|exists:admin_bank_details,id',
            'payment_mode' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'remark' => 'nullable|string|max:500',
            'mode_of_payment' => 'required|string',
            'deposit_bank' => 'required|string',
            'transaction_no' => 'required|string',
            'deposit_date' => 'required|date',
            'hash_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('hash_code')) {
                $imagePath = $request->file('hash_code')->store('fund-requests', 'public');
            }

            $fundRequest = FundRequest::create([
                'user_id' => $validated['user_id'],
                'username' => $validated['username'],
                'bank_detail_id' => $validated['bank_detail_id'],
                'payment_mode' => $validated['payment_mode'],
                'amount' => $validated['amount'],
                'remark' => $validated['remark'] ?? null,
                'mode_of_payment' => $validated['mode_of_payment'],
                'deposit_bank' => $validated['deposit_bank'],
                'transaction_no' => $validated['transaction_no'],
                'deposit_date' => $validated['deposit_date'],
                'hash_code_image' => $imagePath,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'data' => $fundRequest,
                'message' => 'Fund request submitted successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit fund request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}