<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a 'category' column to the events table.
 *
 * This enables event categorization for the AI recommendation engine.
 * Events inherit their category from the parent NGO, but can also
 * be set independently for more granular recommendations.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('category')->nullable()->after('type')
                  ->comment('Category for recommendation engine (e.g., Education, Health)');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
