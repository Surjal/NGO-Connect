<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the recommendation_logs table.
 *
 * Used for debugging and demonstrating the AI recommendation engine.
 * Stores each recommendation with its computed score and human-readable reason.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('recommendable_type')->comment('Model class (App\\Models\\Ngo or App\\Models\\Event)');
            $table->unsignedBigInteger('recommendable_id')->comment('ID of the recommended NGO or Event');
            $table->float('score')->default(0)->comment('Computed recommendation score');
            $table->text('reason')->nullable()->comment('Human-readable explanation for this recommendation');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_logs');
    }
};
