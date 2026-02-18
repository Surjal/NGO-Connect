<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('location')->nullable()->after('phone')
                ->comment('User district for location-based feed (e.g. Kathmandu)');
            $table->json('preferred_categories')->nullable()->after('location')
                ->comment('JSON array of preferred NGO categories (e.g. ["Education","Health"])');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['location', 'preferred_categories']);
        });
    }
};
