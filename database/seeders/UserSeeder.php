<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        /**
         * Admin user
         */
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin@123'),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        /**
         * Standard user
         */
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Standard User',
                'password' => Hash::make('User@123'),
            ]
        );

        $user->roles()->syncWithoutDetaching([$userRole->id]);
    }
}
