<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function history(Request $request)
    {
        
        $userId = $request->user_id;
         try {
            $orders = Order::with('items')
                ->where('user_id', $request->user_id)
                ->latest('order_date')
                ->paginate($request->input('per_page', 10));

            return response()->json([
                'success' => true,
                'message' => 'Order history fetched successfully.',
                'data' => $orders,
            ]);
        } catch (\Throwable $e) {
            Log::error('Order History API Error', [
                'user_id' => $request->user_id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch order history.',
            ], 500);
        }

    }
}
