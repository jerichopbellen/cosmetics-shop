<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $newBrands = [
            'Lucky Beauty',
            'Colourette',
            'Happy Skin',
            'Detail Cosmetics',
            'Clocheflame',
            'Kiko Milano',
            'Rare Beauty',
            'Fenty Beauty',
            'Elf Cosmetics',
            'Maybelline'
        ];

        foreach ($newBrands as $brandName) {
            // This checks if the name exists before inserting
            DB::table('brands')->updateOrInsert(
                ['name' => $brandName],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}