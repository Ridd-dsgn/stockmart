<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Makanan', 'Minuman', 'Alat Tulis', 'Elektronik', 'Kebersihan'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        $suppliers = [
            ['name' => 'PT Sumber Makmur', 'phone' => '081234567890', 'email' => 'sumber@contoh.test', 'address' => 'Pekanbaru'],
            ['name' => 'CV Maju Jaya',     'phone' => '081298765432', 'email' => 'maju@contoh.test',   'address' => 'Pekanbaru'],
            ['name' => 'UD Berkah Abadi',  'phone' => '081311122233', 'email' => 'berkah@contoh.test', 'address' => 'Dumai'],
        ];
        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['name' => $supplier['name']], $supplier);
        }

        $categoryIds = Category::pluck('id');
        $units = ['pcs', 'box', 'pak'];

        for ($i = 1; $i <= 20; $i++) {
            $buy = rand(5, 100) * 1000;

            Product::firstOrCreate(
                ['code' => sprintf('BRG-%03d', $i)],
                [
                    'name'        => "Barang Contoh $i",
                    'category_id' => $categoryIds->random(),
                    'unit'        => $units[array_rand($units)],
                    'buy_price'   => $buy,
                    'sell_price'  => $buy + rand(1, 20) * 500,
                    'stock'       => rand(0, 100),
                    'min_stock'   => 10,
                ]
            );
        }
    }
}