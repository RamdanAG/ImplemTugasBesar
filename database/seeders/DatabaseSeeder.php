<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // akun admin untuk login (password diganti nanti kalau sudah produksi)
        Admin::create([
            'name' => 'Admin Menora',
            'email' => 'admin@menora.test',
            'password' => 'password123',
        ]);

        $makanan = Category::create(['name' => 'Makanan', 'description' => 'Menu makanan berat dan ringan']);
        $minuman = Category::create(['name' => 'Minuman', 'description' => 'Menu minuman dingin dan panas']);

        MenuItem::create([
            'category_id' => $makanan->id,
            'name' => 'Nasi Goreng',
            'description' => 'Nasi goreng dengan telur',
            'price' => 18000,
        ]);
        MenuItem::create([
            'category_id' => $makanan->id,
            'name' => 'Mie Ayam',
            'description' => 'Mie ayam dengan pangsit',
            'price' => 15000,
        ]);
        MenuItem::create([
            'category_id' => $minuman->id,
            'name' => 'Es Teh Manis',
            'description' => 'Teh manis dingin',
            'price' => 5000,
        ]);
        MenuItem::create([
            'category_id' => $minuman->id,
            'name' => 'Kopi Hitam',
            'description' => 'Kopi hitam panas',
            'price' => 8000,
            'is_available' => false, // buat tes menu tidak tersedia
        ]);

        // 5 meja dengan token unik
        for ($i = 1; $i <= 5; $i++) {
            RestaurantTable::create([
                'table_number' => (string) $i,
                'qr_token' => Str::random(32),
            ]);
        }
    }
}