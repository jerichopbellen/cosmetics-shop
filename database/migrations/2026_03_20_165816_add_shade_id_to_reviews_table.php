<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Adding shade_id as a foreign key
            $table->foreignId('shade_id')->after('product_id')->constrained('shades')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['shade_id']);
            $table->dropColumn('shade_id');
        });
    }
};