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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->text('requirements');
            $table->string('location', 255);
            $table->enum('type', [0, 1]); // online or offline
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('cover_image_path_name', 255)->nullable();
            $table->string('capacity', 255);
            $table->boolean('is_volunteers_required');
            $table->foreignId('user_id')->unsigned()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
