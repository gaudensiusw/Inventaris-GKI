<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat atau memperbarui user admin
        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'department' => 'Umum',
                'status' => 1,
            ]
        );

        // Menempelkan role (Hanya jalan jika tabel roles sudah ada)
        $user->assignRole('Super Admin');
    }
}