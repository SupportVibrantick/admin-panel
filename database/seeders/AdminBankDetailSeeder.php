<?php

namespace Database\Seeders;

use App\Models\AdminBankDetail;
use Illuminate\Database\Seeder;

class AdminBankDetailSeeder extends Seeder
{
    public function run()
    {
        $bankDetails = [
            
          
            [
                'mode_name' => 'VSR MLM Payment',
                'address' => 'SCO 123, 2nd Floor, Ludhiana',
                'account_no' => '918020012345678',
                'bank_name' => 'Axis Bank',
                'ifsc_code' => 'UTIB0001234',
                'image' => null,
                'is_active' => true,
            ]
           
        ];

        foreach ($bankDetails as $detail) {
            AdminBankDetail::firstOrCreate(
                ['account_no' => $detail['account_no']],
                $detail
            );
        }

        $this->command->info('✅ Admin bank details seeded successfully!');
    }
}