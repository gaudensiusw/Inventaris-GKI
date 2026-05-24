<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Admin User if it doesn't exist
        if (!User::where('email', 'admin@gkidelima.org')->exists()) {
            User::create([
                'name'     => 'Admin GKI',
                'email'    => 'admin@gkidelima.org',
                'password' => bcrypt('password'),
            ]);
        }

        // 2. Call the new reset seeder to populate all 20 rooms, categories, and 359 items
        $this->call(InventoryDataResetSeeder::class);
    }
}
