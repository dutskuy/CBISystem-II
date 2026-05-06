<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'FAG',   'origin_country' => 'Germany',  'description' => 'Bagian dari Schaeffler Group, produsen bearing presisi tinggi asal Jerman.'],
            ['name' => 'INA',   'origin_country' => 'Germany',  'description' => 'Bagian dari Schaeffler Group, spesialis needle roller bearing.'],
            ['name' => 'LUK',   'origin_country' => 'Germany',  'description' => 'Bagian dari Schaeffler Group, spesialis clutch & torque converter.'],
            ['name' => 'NACHI', 'origin_country' => 'Japan',    'description' => 'Produsen bearing dan cutting tools presisi tinggi asal Jepang.'],
            ['name' => 'FYH',   'origin_country' => 'Japan',    'description' => 'Produsen mounted bearing units dan housed bearing terkemuka dari Jepang.'],
            ['name' => 'FBJ',   'origin_country' => 'Japan',    'description' => 'Produsen bearing industri berkualitas tinggi dari Jepang.'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name'           => $brand['name'],
                'slug'           => Str::slug($brand['name']),
                'origin_country' => $brand['origin_country'],
                'description'    => $brand['description'],
                'is_active'      => true,
            ]);
        }
    }
}