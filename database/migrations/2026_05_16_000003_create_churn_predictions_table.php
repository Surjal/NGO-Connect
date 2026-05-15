<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('churn_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ngo_id')->constrained('users')->cascadeOnDelete();
            $table->float('risk_score');
            $table->string('risk_level', 20);
            $table->json('feature_snapshot');
            $table->timestamp('predicted_at');
            $table->timestamps();

            $table->unique(['volunteer_id', 'ngo_id']);
            $table->index(['ngo_id', 'risk_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('churn_predictions');
    }
};
