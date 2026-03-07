<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Foundation',
            'Lipstick',
            'Lip Tint',
            'Blush',
            'Concealer',
            'Eyeshadow',
            'Mascara',
            'Setting Powder',
            'Eyebrow Pencil',
            'Sunscreen',
            'Setting Spray',
            'Highlighter'
        ];

        foreach ($categories as $name) {
            DB::table('categories')->updateOrInsert(
                ['name' => $name], // Check if this name exists
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}