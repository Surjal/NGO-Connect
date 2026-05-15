<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->json('recommendations')->nullable();
            $table->timestamp('computed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('ngos', function (Blueprint $table) {
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->dropIndex(['category']);
        });

        Schema::dropIfExists('user_recommendations');
    }
};
