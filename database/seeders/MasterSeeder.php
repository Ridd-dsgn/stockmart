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
        // Kategori
        $categories = [
            'Makanan', 'Minuman', 'Alat Tulis', 'Elektronik', 'Kebersihan',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        // Supplier
        $suppliers = [
            ['name' => 'PT Sumber Makmur', 'phone' => '081234567890', 'email' => 'sumber@contoh.test', 'address' => 'Jl. Sudirman No. 12, Pekanbaru'],
            ['name' => 'CV Maju Jaya',     'phone' => '081298765432', 'email' => 'maju@contoh.test',   'address' => 'Jl. Soekarno-Hatta No. 45, Pekanbaru'],
            ['name' => 'UD Berkah Abadi',  'phone' => '081311122233', 'email' => 'berkah@contoh.test', 'address' => 'Jl. Jenderal Sudirman No. 8, Dumai'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['name' => $supplier['name']], $supplier);
        }

        // Barang: [kode, nama, kategori, satuan, harga beli, harga jual, stok, stok minimum]
        $products = [
            // Makanan
            ['MKN-001', 'Mie Instan Goreng',        'Makanan',    'pcs',  2800,   3500, 120, 24],
            ['MKN-002', 'Beras Premium 5 kg',       'Makanan',    'pak', 62000,  68000,  40, 10],
            ['MKN-003', 'Minyak Goreng 1 L',        'Makanan',    'pcs', 16000,  18000,  60, 12],
            ['MKN-004', 'Gula Pasir 1 kg',          'Makanan',    'pak', 14500,  16500,   8, 15], // menipis
            ['MKN-005', 'Tepung Terigu 1 kg',       'Makanan',    'pak', 11000,  13000,  35, 10],
            ['MKN-006', 'Biskuit Kelapa',           'Makanan',    'box',  9000,  11000,   0, 10], // habis

            // Minuman
            ['MNM-001', 'Air Mineral 600 ml',       'Minuman',    'pcs',  2000,   3000, 200, 48],
            ['MNM-002', 'Teh Botol 350 ml',         'Minuman',    'pcs',  3500,   5000,  90, 24],
            ['MNM-003', 'Kopi Sachet (isi 10)',     'Minuman',    'box', 11000,  14000,  45, 10],
            ['MNM-004', 'Susu UHT Cokelat 200 ml',  'Minuman',    'pcs',  4500,   6000,  12, 24], // menipis
            ['MNM-005', 'Sirup Markisa 460 ml',     'Minuman',    'pcs', 15000,  19000,  20,  6],

            // Alat Tulis
            ['ATK-001', 'Pulpen Hitam',             'Alat Tulis', 'pcs',  2500,   4000, 150, 30],
            ['ATK-002', 'Buku Tulis 38 Lembar',     'Alat Tulis', 'pcs',  3500,   5000, 100, 20],
            ['ATK-003', 'Pensil 2B',                'Alat Tulis', 'pcs',  1800,   3000,  80, 20],
            ['ATK-004', 'Penghapus',                'Alat Tulis', 'pcs',  1000,   2000,   5, 15], // menipis
            ['ATK-005', 'Kertas A4 80 gsm',         'Alat Tulis', 'rim', 42000,  48000,  25,  5],
            ['ATK-006', 'Spidol Whiteboard',        'Alat Tulis', 'pcs',  6000,   9000,   0,  6], // habis

            // Elektronik
            ['ELK-001', 'Baterai AA (isi 4)',       'Elektronik', 'pak', 12000,  16000,  40, 10],
            ['ELK-002', 'Kabel USB Type-C 1 m',     'Elektronik', 'pcs', 18000,  28000,  30,  8],
            ['ELK-003', 'Lampu LED 9 Watt',         'Elektronik', 'pcs', 14000,  19000,  55, 12],
            ['ELK-004', 'Stop Kontak 4 Lubang',     'Elektronik', 'pcs', 35000,  48000,  14,  5],
            ['ELK-005', 'Powerbank 10000 mAh',      'Elektronik', 'pcs', 120000, 155000,  4,  5], // menipis

            // Kebersihan
            ['KBR-001', 'Sabun Cuci Piring 800 ml', 'Kebersihan', 'pcs', 14000,  17000,  50, 12],
            ['KBR-002', 'Detergen Bubuk 800 g',     'Kebersihan', 'pak', 16000,  20000,  38, 10],
            ['KBR-003', 'Pembersih Lantai 800 ml',  'Kebersihan', 'pcs', 12000,  15000,  22,  8],
            ['KBR-004', 'Tisu Gulung (isi 6)',      'Kebersihan', 'pak',  9000,  12000,   9, 10], // menipis
            ['KBR-005', 'Sabun Mandi Cair 450 ml',  'Kebersihan', 'pcs', 18000,  23000,  30,  8],
            ['KBR-006', 'Pewangi Pakaian 900 ml',   'Kebersihan', 'pcs', 15000,  19000,   3,  8], // menipis
        ];

        $categoryIds = Category::pluck('id', 'name');

        foreach ($products as [$code, $name, $category, $unit, $buy, $sell, $stock, $min]) {
            Product::updateOrCreate(
                ['code' => $code],
                [
                    'name'        => $name,
                    'category_id' => $categoryIds[$category],
                    'unit'        => $unit,
                    'buy_price'   => $buy,
                    'sell_price'  => $sell,
                    'stock'       => $stock,
                    'min_stock'   => $min,
                ]
            );
        }
    }
}