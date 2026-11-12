<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1, // Coffee
                'name' => 'Caramel Macchiato',
                'description' => 'Espresso dengan susu segar dan sirup karamel manis.',
                'price' => 28000,
                'stock' => 50,
                'image' => 'products/caramel-macchiato.jpg',
            ],
            [
                'category_id' => 1, // Coffee
                'name' => 'Kopi Susu Terra',
                'description' => 'Signature kopi susu gula aren khas Terra Cafe.',
                'price' => 22000,
                'stock' => 100,
                'image' => 'products/kopi-susu-terra.jpg',
            ],
            [
                'category_id' => 2, // Non-Coffee
                'name' => 'Matcha Latte',
                'description' => 'Teh hijau Jepang premium dengan susu segar.',
                'price' => 30000,
                'stock' => 40,
                'image' => 'products/matcha-latte.jpg',
            ],
            [
                'category_id' => 3, // Main Course
                'name' => 'Nasi Goreng Special Terra',
                'description' => 'Nasi goreng rempah khas dengan telor, ayam, dan sate.',
                'price' => 35000,
                'stock' => 30,
                'image' => 'products/nasi-goreng.jpg',
            ],
            [
                'category_id' => 4, // Snack & Pastry
                'name' => 'Croissant Butter',
                'description' => 'Roti croissant renyah dengan aroma mentega yang gurih.',
                'price' => 18000,
                'stock' => 25,
                'image' => 'products/croissant.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => $product['image'],
                'is_active' => true,
            ]);
        }
    }
}
