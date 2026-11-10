<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->ulid('token')->unique();
            $table->foreignId('promo_code_id')->unique()->constrained();
            $table->json('course');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->json('inputs')->nullable();
            $table->unsignedTinyInteger('fill_percent')->nullable();
            $table->unsignedInteger('duration_ticks')->nullable();
            $table->boolean('wheel_allowed')->default(false);
            $table->timestamp('spun_at')->nullable();
            $table->unsignedTinyInteger('segment')->nullable();
            $table->boolean('won')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
