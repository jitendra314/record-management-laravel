<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Products
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            // Users
            'users.view',
            'users.update',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['label' => ucwords(str_replace('.', ' ', $permission))]
            );
        }
    }
}
