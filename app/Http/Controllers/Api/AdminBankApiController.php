<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminBankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminBankApiController extends Controller
{
    /**
     * Get all active bank details
     */
    public function index()
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
     * Get single bank detail by ID
     */
    public function show($id)
    {
        try {
            $bankDetail = AdminBankDetail::where('id', $id)
                ->where('is_active', true)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $bankDetail,
                'message' => 'Bank detail fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bank detail not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}