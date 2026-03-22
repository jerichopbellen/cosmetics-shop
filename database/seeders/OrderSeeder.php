<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->toArray();
        $shades = DB::table('shades')->get();

        if (empty($customerIds) || $shades->isEmpty()) {
            $this->command->error("Please seed Users and Products/Shades first!");
            return;
        }

        for ($i = 1; $i <= 25; $i++) {
            $randomDate = Carbon::now()->subDays(rand(0, 1160));
            
            // FIX: Removed 'total_amount' from the insert array
            $orderId = DB::table('orders')->insertGetId([
                'user_id'        => $customerIds[array_rand($customerIds)],
                'order_number'   => 'GLOW-' . strtoupper(Str::random(10)),
                'status'         => ['Delivered', 'Pending', 'Cancelled'][rand(0, 2)],
                'payment_method' => 'COD',
                'created_at'     => $randomDate,
                'updated_at'     => $randomDate,
            ]);

            $numberOfItems = rand(1, 4);

            for ($j = 0; $j < $numberOfItems; $j++) {
                $shade = $shades->random();
                $quantity = rand(1, 2);
                
                DB::table('order_items')->insert([
                    'order_id'   => $orderId,
                    'shade_id'   => $shade->id,
                    'quantity'   => $quantity,
                    'price'      => $shade->price, 
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}