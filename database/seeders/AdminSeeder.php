<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@bearindo.com'],
            [
                'name'      => 'Administrator',
                'password'  => Hash::make('admin123'),
                'role'      => 'super_admin',
                'is_active' => true,
            ]
        );
        $superAdmin->update(['role' => 'super_admin']);
        $superAdmin->syncRoles(['super_admin']);

        // Contoh Admin Biasa
        $admin = User::firstOrCreate(
            ['email' => 'staff@bearindo.com'],
            [
                'name'      => 'Staff Admin',
                'password'  => Hash::make('staff123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        $this->command->info('✓ Super Admin: admin@bearindo.com');
        $this->command->info('✓ Admin Staff: staff@bearindo.com');
    }
}