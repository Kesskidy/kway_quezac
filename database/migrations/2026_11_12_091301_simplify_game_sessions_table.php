<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Version 2 (regle.md, section 8): game_sessions only keeps the draw result.
 */
return new class extends Migration
{
    /**
     * Version 1 columns removed by this migration.
     */
    private const REMOVED_COLUMNS = [
        'course',
        'started_at',
        'finished_at',
        'inputs',
        'fill_percent',
        'duration_ticks',
        'wheel_allowed',
        'spun_at',
        'segment',
    ];

    public function up(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn(self::REMOVED_COLUMNS);
        });

        // Version 1 rows have no draw: they count as lost before `won` becomes required.
        DB::table('game_sessions')->whereNull('won')->update(['won' => false]);

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->boolean('won')->nullable(false)->change();
            $table->unsignedTinyInteger('scenario')->default(0)->after('won');
        });
    }

    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn('scenario');
            $table->boolean('won')->nullable()->change();
        });

        // The version 1 data cannot be restored: the columns come back empty.
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->json('course')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->json('inputs')->nullable();
            $table->unsignedTinyInteger('fill_percent')->nullable();
            $table->unsignedInteger('duration_ticks')->nullable();
            $table->boolean('wheel_allowed')->default(false);
            $table->timestamp('spun_at')->nullable();
            $table->unsignedTinyInteger('segment')->nullable();
        });
    }
};
