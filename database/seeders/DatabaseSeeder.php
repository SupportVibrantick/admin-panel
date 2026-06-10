<?php

namespace Database\Seeders;

use App\Models\MlmUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            AdminUserSeeder::class,
            RootMlmUserSeeder::class, 
            SeoPagesSeeder::class,
            // MlmUser::factory()->count(50)->create(),
        
        ]);

       
    }
}
