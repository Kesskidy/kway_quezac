<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La veste est l'unique produit : elle est écrite en dur dans la page, plus besoin de table `products`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });

        Schema::dropIfExists('products');
    }

    public function down(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->unsignedInteger('price_cents');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('id')->constrained();
        });
    }
};
