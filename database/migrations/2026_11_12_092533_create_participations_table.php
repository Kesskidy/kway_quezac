<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * regle.md, section 8. One participation per winning game session.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->unique()->constrained();
            $table->string('first_name', 50);
            $table->string('email')->index(); // grouped by e-mail in lottery:draw (one prize per e-mail)
            $table->timestamp('consented_at');
            $table->unsignedTinyInteger('prize_rank')->nullable();
            $table->timestamp('drawn_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};
