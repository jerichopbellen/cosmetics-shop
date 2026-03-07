<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Arrays to help randomize the names
        $firstNames = ['Maria', 'Juan', 'Elena', 'Ricardo', 'Sofia', 'Antonio', 'Isabella', 'Miguel', 'Carmela', 'Ramon'];
        $lastNames = ['Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Mendoza', 'Torres'];

        for ($i = 1; $i <= 50; $i++) {
            // Pick a random name from the arrays
            $fullName = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            
            DB::table('users')->insert([
                'name'       => $fullName . " ($i)", 
                'email'      => "customer$i@example.com",
                'password'   => Hash::make('password'), // Default password for all
                'role'       => 'customer',           // Set role as customer
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}