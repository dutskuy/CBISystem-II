<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'owner@bearindo.com'],
            [
                'name'      => 'Owner Bearindo',
                'password'  => Hash::make('owner123'),
                'role'      => 'owner',
                'is_active' => true,
            ]
        );

        $owner->assignRole('owner');
    }
}