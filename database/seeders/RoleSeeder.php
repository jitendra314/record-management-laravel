<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrator']
        );

        $user = Role::firstOrCreate(
            ['name' => 'user'],
            ['label' => 'Standard User']
        );

        /**
         * Admin → all permissions
         */
        $admin->permissions()->sync(
            Permission::pluck('id')
        );

        /**
         * Standard User → limited permissions
         */
        $user->permissions()->sync(
            Permission::whereIn('name', [
                'products.view',
                'products.create',
            ])->pluck('id')
        );
    }
}
