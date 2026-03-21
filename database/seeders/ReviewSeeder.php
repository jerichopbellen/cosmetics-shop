<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Shade;
use App\Models\User;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Only get users who are customers
        $customers = User::where('role', 'customer')->get();
        
        // Get all shades and their associated product IDs
        $shades = Shade::select('id', 'product_id')->get();

        if ($shades->isEmpty() || $customers->isEmpty()) {
            return; // Exit if you don't have shades or customers yet
        }

        $comments = [
            'Maganda siya! Sobrang pigmented.',
            'Best purchase ever! My skin feels so glowy.',
            'Perfect shade match for me. Will buy again.',
            'Worth the price, very aesthetic packaging.',
            'Highly recommend! Very fast shipping too.',
            'The texture is amazing and lasts all day.',
            'Legit product! 5 stars for this.',
            'Subukan niyo guys, hindi nakakapagsisi.'
        ];

        foreach (range(1, 40) as $index) {
            // Pick a random shade
            $randomShade = $shades->random();
            
            Review::create([
                'user_id' => $customers->random()->id,
                'product_id' => $randomShade->product_id, // Ensures the product matches the shade
                'shade_id' => $randomShade->id,
                'rating' => rand(4, 5),
                'comment' => $comments[array_rand($comments)],
                'photo_path' => null, // Keeping it null for the seeder
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}