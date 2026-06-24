<?php

namespace Database\Seeders;

use App\Models\MlmUser;
use App\Models\Wallet;
use App\Models\WalletBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Wallet::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Direct Income',
                'code' => 'PURCHASE',
                'currency_code' => 'INR',
                'eligibility' => 'ALL',
                'type' => 'CREDIT',
                'is_active' => 1,
                'min_balance' => 1000,
                'max_balance' => null,
                'description' => null,
            ]
        );

        Wallet::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Matching Income',
                'code' => 'COMMISSION',
                'currency_code' => 'INR',
                'eligibility' => 'SPONSORED_ONLY',
                'type' => 'BOTH',
                'is_active' => 1,
                'min_balance' => 0,
                'max_balance' => null,
                'description' => null,
            ]
        );


        $users = MlmUser::pluck('id');
        $wallets = Wallet::pluck('id');

        foreach ($users as $userId) {

            foreach ($wallets as $walletId) {

                WalletBalance::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'wallet_id' => $walletId,
                    ],
                    [
                        'balance' => 0,
                        'total_earned' => 0,
                    ]
                );
            }
        }
    }
}
