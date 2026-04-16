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
        Role::create(['name' => 'Super Admin']);
        
        $admin = Role::create(['name' => 'Admin']);
        $adminPermissions = Permission::whereIn('name', [
            'index-dashboard',
            'index-item', 'create-item', 'update-item',
            'index-room', 'create-room', 'update-room',
            'index-category', 'create-category', 'update-category',
            'index-order', 'update-order',
            'index-rent', 'update-rent',
        ])->get();
        $admin->givePermissionTo($adminPermissions);

        $customer = Role::create(['name' => 'Customer']);
        $customerPermissions = Permission::whereIn('name', [
            'index-order', 'create-order', 'index-rent', 'create-rent'
        ])->get();
        $customer->givePermissionTo($customerPermissions);
    }
}
