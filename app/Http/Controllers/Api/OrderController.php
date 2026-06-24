<?php

namespace App\Http\Controllers\Api;

use App\Services\PurchaseService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PurchaseProductRequest;
use App\Models\MLMTree;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function purchase(PurchaseProductRequest $request)
    {
        // return response()->json($request->all());
        try {
            $order = $this->purchaseService->purchase(
                $request->user_id,
                $request->product_id,
                $request->quantity
            );

            return response()->json([
                'status' => true,
                'message' => 'Product purchased successfully.',
                'data' => $order
            ]);

        } catch (\DomainException $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Purchase failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


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
