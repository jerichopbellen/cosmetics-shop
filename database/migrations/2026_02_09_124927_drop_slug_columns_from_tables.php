<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    public function down(): void
    {
        // If you ever want to go back, you'd add them here
        Schema::table('brands', function (Blueprint $table) { $table->string('slug')->nullable(); });
        Schema::table('categories', function (Blueprint $table) { $table->string('slug')->nullable(); });
        Schema::table('products', function (Blueprint $table) { $table->string('slug')->nullable(); });
    }
};