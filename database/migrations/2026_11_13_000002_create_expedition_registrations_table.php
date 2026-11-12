<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Inscriptions à l'Expédition RE·SOURCE sur le GR70 (section après les Valeurs). Une adresse = une inscription.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedition_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('email')->unique();
            $table->timestamp('consented_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedition_registrations');
    }
};
