<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@dimsummakangga.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Test User
        User::firstOrCreate(
            ['email' => 'user@dimsummakangga.com'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('user123'),
                'role'     => 'user',
            ]
        );

        // Sample Products
        $products = [
            ['name' => 'Dimsum Ayam',     'price' => 15000, 'stock' => 50, 'description' => 'Dimsum isi ayam cincang lembut'],
            ['name' => 'Dimsum Udang',    'price' => 18000, 'stock' => 40, 'description' => 'Dimsum isi udang segar pilihan'],
            ['name' => 'Dimsum Sapi',     'price' => 17000, 'stock' => 45, 'description' => 'Dimsum isi daging sapi cincang'],
            ['name' => 'Siomay Kulit',    'price' => 12000, 'stock' => 60, 'description' => 'Siomay kulit crispy goreng'],
            ['name' => 'Hakau Udang',     'price' => 20000, 'stock' => 30, 'description' => 'Hakau original isi udang jumbo'],
            ['name' => 'Ceker Dimsum',    'price' => 10000, 'stock' => 35, 'description' => 'Ceker empuk bumbu hitam'],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['is_available' => true])
            );
        }
    }
}
