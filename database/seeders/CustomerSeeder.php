<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name'         => 'Budi Santoso',
                'email'        => 'budi@gmail.com',
                'phone'        => '081234567890',
                'company_name' => 'PT Maju Bersama',
                'address'      => 'Jl. Sudirman No. 10, Jakarta',
                'password'     => Hash::make('customer123'),
            ],
            [
                'name'         => 'Siti Rahayu',
                'email'        => 'siti@gmail.com',
                'phone'        => '082345678901',
                'company_name' => 'CV Teknik Jaya',
                'address'      => 'Jl. Gatot Subroto No. 25, Bandung',
                'password'     => Hash::make('customer123'),
            ],
        ];

        foreach ($customers as $data) {
            if (User::where('email', $data['email'])->exists()) continue;

            $user = User::create([
                ...$data,
                'role'      => 'customer',
                'is_active' => true,
            ]);

            $user->assignRole('customer');
            Cart::firstOrCreate(['user_id' => $user->id]);

            $this->command->info('✓ Customer: '.$user->name);
        }
    }
}