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
        Schema::create('donation_has_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donation_id');
            $table->enum('payment_method', ['esewa', 'khalti', 'cash', 'cheque']);
            $table->json('payment_response')->nullable(); // to store gateway response or details
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('donation_id')->references('id')->on('donations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_has_payments');
    }
};
