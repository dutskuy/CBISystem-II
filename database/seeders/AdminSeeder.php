<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'         => 'Administrator',
            'email'        => 'admin@bearindo.com',
            'password'     => Hash::make('admin123'),
            'role'         => 'admin',
            'phone'        => '021-1234567',
            'company_name' => 'PT Central Bearindo International',
            'is_active'    => true,
        ]);

        $admin->assignRole('admin');
    }
}