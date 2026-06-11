<?php

namespace Database\Seeders;

use App\Models\FundSummary;
use App\Models\MlmUser;
use Illuminate\Database\Seeder;

class FundSummarySeeder extends Seeder
{
    public function run()
    {
        // Get first user or create one
        $user = MlmUser::first();
        
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $fundSummaries = [
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-06-10',
                'type' => 'ADMIN CREDIT',
                'particular' => 'Commission Credit',
                'remark' => 'Monthly commission payout',
                'credit' => 5000.00,
                'debit' => 0.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-06-08',
                'type' => 'Product Purchase',
                'particular' => 'Product Order #12345',
                'remark' => 'Purchased wellness package',
                'credit' => 0.00,
                'debit' => 2500.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-06-05',
                'type' => 'Credit Transfer',
                'particular' => 'Transfer from Sponsor',
                'remark' => 'Sponsor bonus transfer',
                'credit' => 1500.00,
                'debit' => 0.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-06-03',
                'type' => 'ADMIN DEBIT',
                'particular' => 'Service Charge',
                'remark' => 'Monthly maintenance fee',
                'credit' => 0.00,
                'debit' => 500.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-06-01',
                'type' => 'Admin Transfer',
                'particular' => 'Welcome Bonus',
                'remark' => 'New member welcome bonus',
                'credit' => 1000.00,
                'debit' => 0.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-05-28',
                'type' => 'Debit Transfer',
                'particular' => 'Withdrawal Request',
                'remark' => 'Bank transfer withdrawal',
                'credit' => 0.00,
                'debit' => 3000.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-05-25',
                'type' => 'ADMIN CREDIT',
                'particular' => 'Referral Bonus',
                'remark' => '5 direct referrals bonus',
                'credit' => 2500.00,
                'debit' => 0.00,
            ],
            [
                'user_id' => $user->id,
                'username' => $user->user_name,
                'transaction_date' => '2026-05-20',
                'type' => 'Product Purchase',
                'particular' => 'Product Order #12340',
                'remark' => 'Purchased health supplement',
                'credit' => 0.00,
                'debit' => 1800.00,
            ],
        ];

        foreach ($fundSummaries as $summary) {
            FundSummary::create($summary);
        }

        $this->command->info('✅ Fund summary dummy data seeded successfully!');
    }
}