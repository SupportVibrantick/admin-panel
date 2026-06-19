<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], 
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => '9518257449',
                'bio' => 'Super Admin User',
                'password' => 'Admin@123', 
            ]
        );

        $admin->assignRole('admin');


    }
}
