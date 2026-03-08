<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productNames = ['Velvet Lip Cream', 'Skin Tint SPF 30', 'Matte Powder', 'Serum Concealer', 'Liquid Blush'];
        $finishes = ['Matte', 'Dewy', 'Satin', 'Natural'];

        for ($i = 1; $i <= 15; $i++) {
            // 1. Insert into 'products' table
            $productId = DB::table('products')->insertGetId([
                'brand_id'    => rand(1, DB::table('brands')->count()), 
                'category_id' => rand(1, DB::table('categories')->count()),
                'name'        => $productNames[array_rand($productNames)] . " #$i",
                'description' => "Professional formula for a flawless finish.",
                'finish'      => $finishes[array_rand($finishes)],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // 2. Insert into 'product_images' table (Gallery)
            // Using image_path as you specified
            for ($img = 1; $img <= 2; $img++) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_path' => '/placeholders/product.png', 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. Insert into 'shades' table (Swatches)
            // Using image_path here too
            for ($j = 1; $j <= 3; $j++) {
                DB::table('shades')->insert([
                    'product_id' => $productId,
                    'shade_name' => "Shade $j for Product $i",
                    'hex_code'   => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
                    'price'      => rand(199, 899),
                    'stock'      => rand(10, 100),
                    'image_path' => '/placeholders/product.png', 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}