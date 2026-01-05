<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@skyvault.com'], // prevents duplicates
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'password'   => Hash::make('abc123@@@'),
                'user_role'  => UserRole::SUPER_ADMIN,
            ]
        );
    }
}
