<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bearing',               'description' => 'Ball bearing, roller bearing, dan berbagai jenis bearing industri.'],
            ['name' => 'Conveyor Belt',          'description' => 'Belt konveyor untuk berbagai aplikasi industri.'],
            ['name' => 'Power Transmission',     'description' => 'Komponen transmisi daya seperti coupling, gear, dan chain.'],
            ['name' => 'Linear Motion',          'description' => 'Komponen gerak linear seperti linear guide dan ball screw.'],
            ['name' => 'Sealing & Lubrication',  'description' => 'Produk sealing dan pelumasan untuk memperpanjang umur mesin.'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'        => $category['name'],
                'slug'        => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active'   => true,
            ]);
        }
    }
}