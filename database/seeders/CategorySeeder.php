<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Coffee',
            'Non-Coffee',
            'Main Course',
            'Snack & Pastry',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => 'Kategori menu pilihan ' . $category . ' di Terra Cafe.',
            ]);
        }
    }
}
