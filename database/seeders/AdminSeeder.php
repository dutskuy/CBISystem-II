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

<<<<<<< HEAD
        // Admin Biasa
=======
        // Contoh Admin Biasa
>>>>>>> 43089a946ef58a777a6ddd9901b6091f0c032aa0
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
<<<<<<< HEAD

        // Admin Gudang
        $adminGudang = User::firstOrCreate(
            ['email' => 'gudang@bearindo.com'],
            [
                'name'      => 'Staff Gudang',
                'password'  => Hash::make('gudang123'),
                'role'      => 'admin_gudang',
                'is_active' => true,
            ]
        );
        $adminGudang->update(['role' => 'admin_gudang']);
        $adminGudang->syncRoles(['admin_gudang']);

        $this->command->info('✓ Admin Gudang : gudang@bearindo.com / gudang123');
=======
>>>>>>> 43089a946ef58a777a6ddd9901b6091f0c032aa0
    }
}