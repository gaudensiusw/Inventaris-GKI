<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions (Biar tidak nyangkut)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Role Super Admin
        Role::updateOrCreate(
            ['name' => 'Super Admin'],
            ['guard_name' => 'web']
        );
        
        // 2. Role Admin
        $admin = Role::updateOrCreate(
            ['name' => 'Admin'],
            ['guard_name' => 'web']
        );

        $adminPermissions = Permission::whereIn('name', [
            'index-dashboard',
            'index-item', 'create-item', 'update-item',
            'index-room', 'create-room', 'update-room',
            'index-category', 'create-category', 'update-category',
            'index-order', 'update-order',
            'index-rent', 'update-rent',
        ])->get();
        $admin->syncPermissions($adminPermissions); // Pakai sync agar tidak duplikat

        // 3. Role Customer (Mungkin untuk jemaat yang mau pinjam barang)
        $customer = Role::updateOrCreate(
            ['name' => 'Customer'],
            ['guard_name' => 'web']
        );

        $customerPermissions = Permission::whereIn('name', [
            'index-order', 'create-order', 'index-rent', 'create-rent'
        ])->get();
        $customer->syncPermissions($customerPermissions);
    }
}