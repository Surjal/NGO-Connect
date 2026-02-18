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
        Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id')->comment('Admin who performed the action');
        $table->string('action')->comment('Action performed');
        $table->string('model_type')->comment('Model affected');
        $table->unsignedBigInteger('model_id')->comment('ID of the affected model');
        $table->text('details')->nullable()->comment('Additional details about the action');
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
