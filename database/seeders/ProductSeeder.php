<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $bearing     = Category::where('slug', 'bearing')->first();
        $conveyor    = Category::where('slug', 'conveyor-belt')->first();
        $powerTrans  = Category::where('slug', 'power-transmission')->first();
        $linear      = Category::where('slug', 'linear-motion')->first();
        $sealing     = Category::where('slug', 'sealing-lubrication')->first();

        $fag   = Brand::where('slug', 'fag')->first();
        $ina   = Brand::where('slug', 'ina')->first();
        $nachi = Brand::where('slug', 'nachi')->first();
        $fyh   = Brand::where('slug', 'fyh')->first();
        $fbj   = Brand::where('slug', 'fbj')->first();
        $luk   = Brand::where('slug', 'luk')->first();

        $products = [
            [
                'brand'       => $fag,
                'category'    => $bearing,
                'name'        => 'FAG 6205-2Z Deep Groove Ball Bearing',
                'sku'         => 'FAG-6205-2Z',
                'part_number' => '6205-2Z',
                'description' => 'Deep groove ball bearing dengan penutup baja di kedua sisi. Cocok untuk aplikasi umum industri dengan kecepatan tinggi dan kebisingan rendah.',
                'specification' => "Bore Diameter : 25 mm\nOuter Diameter : 52 mm\nWidth          : 15 mm\nDynamic Load   : 14.0 kN\nStatic Load    : 7.80 kN\nSpeed Limit    : 13000 rpm",
                'price'       => 85000,
                'cost_price'  => 62000,
                'stock'       => 50,
                'min_stock'   => 10,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $fag,
                'category'    => $bearing,
                'name'        => 'FAG 6305-2RSR Rubber Sealed Ball Bearing',
                'sku'         => 'FAG-6305-2RSR',
                'part_number' => '6305-2RSR',
                'description' => 'Deep groove ball bearing dengan segel karet di kedua sisi. Tahan terhadap debu dan percikan air, ideal untuk aplikasi pompa dan motor listrik.',
                'specification' => "Bore Diameter : 25 mm\nOuter Diameter : 62 mm\nWidth          : 17 mm\nDynamic Load   : 22.5 kN\nStatic Load    : 11.2 kN\nSpeed Limit    : 9500 rpm",
                'price'       => 120000,
                'cost_price'  => 88000,
                'stock'       => 35,
                'min_stock'   => 8,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $ina,
                'category'    => $linear,
                'name'        => 'INA KGSC 20 PP-AS Linear Ball Bearing',
                'sku'         => 'INA-KGSC20PP',
                'part_number' => 'KGSC20PP-AS',
                'description' => 'Linear ball bearing dengan housing bulat, dilengkapi segel debu di kedua sisi. Cocok untuk gerakan linier presisi tinggi pada mesin CNC dan otomasi.',
                'specification' => "Shaft Diameter : 20 mm\nOuter Diameter : 32 mm\nLength         : 45 mm\nDynamic Load   : 2.48 kN\nStatic Load    : 3.20 kN\nMaterial       : Steel / Polymer Seal",
                'price'       => 285000,
                'cost_price'  => 210000,
                'stock'       => 20,
                'min_stock'   => 5,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $nachi,
                'category'    => $bearing,
                'name'        => 'NACHI 6004-2NSE9 Ball Bearing',
                'sku'         => 'NACHI-6004-2NSE9',
                'part_number' => '6004-2NSE9',
                'description' => 'Deep groove ball bearing buatan Jepang dengan segel karet NBR berkualitas tinggi. Tahan suhu tinggi dan cocok untuk motor listrik serta peralatan industri.',
                'specification' => "Bore Diameter : 20 mm\nOuter Diameter : 42 mm\nWidth          : 12 mm\nDynamic Load   : 9.95 kN\nStatic Load    : 5.00 kN\nSpeed Limit    : 15000 rpm",
                'price'       => 95000,
                'cost_price'  => 70000,
                'stock'       => 45,
                'min_stock'   => 10,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $fyh,
                'category'    => $bearing,
                'name'        => 'FYH UCFL 207 Flange Bearing Unit',
                'sku'         => 'FYH-UCFL207',
                'part_number' => 'UCFL207',
                'description' => 'Flange bearing unit dengan 2 lubang baut oval. Housing besi cor, dilengkapi self-aligning insert bearing. Ideal untuk konveyor, fan, dan aplikasi pertanian.',
                'specification' => "Bore Diameter  : 35 mm\nBolt Hole Dia  : 13.5 mm\nBolt Spacing   : 121 mm\nHousing Mat.   : Cast Iron\nBearing Insert : UC207\nGrease         : Shell Alvania",
                'price'       => 320000,
                'cost_price'  => 235000,
                'stock'       => 25,
                'min_stock'   => 5,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $fbj,
                'category'    => $bearing,
                'name'        => 'FBJ 32210 Tapered Roller Bearing',
                'sku'         => 'FBJ-32210',
                'part_number' => '32210',
                'description' => 'Tapered roller bearing mampu menahan beban radial dan aksial secara bersamaan. Cocok untuk roda kendaraan berat, gearbox, dan diferensial.',
                'specification' => "Bore Diameter  : 50 mm\nOuter Diameter : 90 mm\nWidth (T)      : 24.75 mm\nDynamic Load   : 72.0 kN\nStatic Load    : 82.5 kN\nCone Width     : 21.75 mm",
                'price'       => 175000,
                'cost_price'  => 128000,
                'stock'       => 30,
                'min_stock'   => 8,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $luk,
                'category'    => $powerTrans,
                'name'        => 'LUK 500 0571 10 Clutch Bearing',
                'sku'         => 'LUK-5000571',
                'part_number' => '500057110',
                'description' => 'Release bearing kopling untuk kendaraan komersial. Presisi tinggi, tahan panas, dan dirancang untuk siklus kerja berat dengan umur pakai panjang.',
                'specification' => "Inner Diameter : 35 mm\nOuter Diameter : 65 mm\nHeight         : 44 mm\nType           : Self-centering\nApplication    : Commercial Vehicle\nMaterial       : Chrome Steel",
                'price'       => 450000,
                'cost_price'  => 330000,
                'stock'       => 15,
                'min_stock'   => 3,
                'unit'        => 'pcs',
            ],
            [
                'brand'       => $ina,
                'category'    => $sealing,
                'name'        => 'INA AS 3552 Thrust Washer Bearing',
                'sku'         => 'INA-AS3552',
                'part_number' => 'AS3552',
                'description' => 'Thrust washer tipis untuk menahan beban aksial ringan. Biasa digunakan sebagai komponen pendukung pada needle roller thrust bearing assembly.',
                'specification' => "Inner Diameter : 35 mm\nOuter Diameter : 52 mm\nThickness      : 1 mm\nMaterial       : Hardened Steel\nSurface        : Phosphated\nLoad Direction : Axial",
                'price'       => 45000,
                'cost_price'  => 32000,
                'stock'       => 100,
                'min_stock'   => 20,
                'unit'        => 'pcs',
            ],
        ];

        foreach ($products as $data) {
            // Skip jika brand atau kategori tidak ditemukan
            if (!$data['brand'] || !$data['category']) continue;

            // Skip jika SKU sudah ada
            if (Product::where('sku', $data['sku'])->exists()) continue;

            $product = Product::create([
                'brand_id'      => $data['brand']->id,
                'category_id'   => $data['category']->id,
                'name'          => $data['name'],
                'slug'          => Str::slug($data['name']),
                'sku'           => $data['sku'],
                'part_number'   => $data['part_number'],
                'description'   => $data['description'],
                'specification' => $data['specification'],
                'price'         => $data['price'],
                'cost_price'    => $data['cost_price'],
                'is_active'     => true,
            ]);

            ProductStock::create([
                'product_id' => $product->id,
                'quantity'   => $data['stock'],
                'min_stock'  => $data['min_stock'],
                'unit'       => $data['unit'],
            ]);

            $this->command->info('✓ Produk dibuat: '.$product->name);
        }

        $this->command->info('');
        $this->command->info('Total: '.count($products).' produk berhasil ditambahkan.');
    }
}