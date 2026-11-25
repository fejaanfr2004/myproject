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
                'name' => 'Premium Black T-Shirt',
                'price' => 999,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600',
                'description' => 'A premium everyday black T-shirt designed for comfort and timeless style.',
                'stock' => 50,
            ],
            [
                'name' => 'Men Casual Shirt',
                'price' => 1499,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600',
                'description' => 'A smart casual shirt for a clean and confident look.',
                'stock' => 50,
            ],
            [
                'name' => 'Classic Blue Jeans',
                'price' => 1799,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600',
                'description' => 'Classic blue denim jeans made for everyday comfort.',
                'stock' => 50,
            ],
            [
                'name' => 'Men Denim Jacket',
                'price' => 2499,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600',
                'description' => 'A stylish denim jacket for a modern look.',
                'stock' => 30,
            ],
            [
                'name' => 'Men White Shirt',
                'price' => 1299,
                'image' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?w=600',
                'description' => 'A versatile white shirt for casual and smart occasions.',
                'stock' => 40,
            ],
            [
                'name' => 'Men Hoodie',
                'price' => 1899,
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600',
                'description' => 'A comfortable hoodie for a relaxed modern look.',
                'stock' => 35,
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                $item + ['slug' => Str::slug($item['name'])]
            );
        }
    }
}
