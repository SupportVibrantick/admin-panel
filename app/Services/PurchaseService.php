<?php
namespace App\Services;

use App\Models\MLMTree;
use App\Models\MlmUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\WalletBalance;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    private const MAX_PRODUCTS = 40;

    public function purchase(
        int $userId,
        int $productId,
        int $quantity
    ): Order {
        $this->validateUserPosition($userId,);

        $product = Product::findOrFail($productId);

        $this->validateStock($product, $quantity);

        $this->validatePurchaseLimit(
            $userId,
            $quantity
        );

        return DB::transaction(function () use (
            $userId,
            $product,
            $quantity,
        ) {

            $totalAmount = $product->price * $quantity;
            $ccPoints = ($product->cc_points ?? 0) * $quantity;

            $order = Order::create([
                'user_id' => $userId,
                'package_id' => null,
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'total_cc_points' => $ccPoints,
                'status' => Order::STATUS_COMPLETED,
                'order_type' => Order::TYPE_SELF,
                'payment_mode' => Order::PAYMENT_WALLET,
                'note' => "Purchased {$quantity}x {$product->name}",
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'cc_points' => $ccPoints,
                'status' => 'COMPLETED',
            ]);

            $product->decrement('stock', $quantity);

            MLMTree::where('mlm_user_id', $userId)
                ->update([
                    'business_volume' => DB::raw("business_volume + {$ccPoints}"),
                    'earned_amount' => DB::raw("earned_amount + {$totalAmount}")
                ]);


            $commission = $this->updateCommissionLevel(
                $userId,
                $quantity
            ); 

            $this->generateDirectIncome(
                $userId,
                $commission,
                $order,
                $quantity,
            );


            return $order->load('items');
        });
    }

    public function updateCommissionLevel(
        int $userId,
        int $quantity
    ): int {

        $user = MlmUser::findOrFail($userId);

        // Already assigned once, do nothing
        if (!is_null($user->commission_percentage)) {
            return $user->commission_percentage;
        }

        $percentage = match (true) {
            $quantity >= 40 => 20,
            $quantity >= 20 => 18,
            $quantity >= 12 => 16,
            $quantity >= 6  => 14,
            $quantity >= 2  => 12,
            default         => 10,
        };

        $user->update([
            'commission_percentage' => $percentage
        ]);

        return $percentage;
    }


    public function generateDirectIncome(
        int $userId,
        int $commission,
        Order $order,
        int $quantity
    ): void {

        $sponsorId = MLMTree::where(
            'mlm_user_id',
            $userId
        )->value('parent_id');

        if (!$sponsorId) {
            return;
        }

        $incomeAmount = $commission >= 20 ? 200 : 100;

        $this->creditWallet(
            userId: $userId,
            amount: $incomeAmount * $quantity,
            type: 'credit',
            referenceId: $order->id,
            description: "Direct income from User {$userId}"
        );
    }

    private function validateStock(Product $product, int $quantity): void
    {
        if ($product->stock < $quantity) {
            throw new \DomainException(
                'Product not available or insufficient stock'
            );
        }
    }

    private function validatePurchaseLimit(
        int $userId,
        int $quantity
    ): void {

        $purchased = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('quantity');

        if (($purchased + $quantity) > self::MAX_PRODUCTS) {
            throw new \DomainException(
                "You can only purchase a maximum of " . self::MAX_PRODUCTS . " products."
            );
        }
    }

    
    private function validateUserPosition(int $userId): void
    {
        $parentId = MlmTree::where('mlm_user_id', $userId)
            ->value('parent_id');

        if ($userId != 1 && !$parentId) {
    throw new \DomainException(
        'You are not positioned under any sponsor.'
    );
}
    }


    public function creditWallet(
        int $userId,
        float $amount,
        string $type,
        int $referenceId,
        string $description
    ): void {

        $wallet = WalletBalance::firstOrCreate(
            ['user_id' => $userId],
            [
                'wallet_id' => 1,
                'balance' => 0,
                'total_earned' => 0
            ]
        );

        $wallet->increment('balance', $amount);
        $wallet->increment('total_earned', $amount);

        $wallet->refresh();

        WalletTransaction::create([
            'wallet_id' => $wallet->wallet_id,
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $wallet->balance,
            'reference_type' => Order::class,
            'reference_id' => $referenceId,
            'status' => 'completed',
            'description' => $description
        ]);
    }
}