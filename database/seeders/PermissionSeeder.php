<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'index-dashboard',
            'index-item', 'create-item', 'delete-item', 'update-item',
            'index-room', 'create-room', 'delete-room', 'update-room',
            'index-category', 'create-category', 'delete-category', 'update-category',
            'index-permission', 'create-permission', 'delete-permission', 'update-permission',
            'index-role', 'create-role', 'delete-role', 'update-role',
            'index-user', 'create-user', 'delete-user', 'update-user',
            'index-order', 'create-order', 'delete-order', 'update-order',
            'index-rent', 'create-rent', 'delete-rent', 'update-rent',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
