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
        Schema::create('ngos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->comment('Links to the NGO user in the users table');
            $table->string('ngo_name')->comment('Name of the NGO');
            $table->date('registration_date')->comment('Date of NGO registration');
            $table->string('category')->comment('Primary category of the NGO (e.g., Education, Health, Environment)');
            $table->string('address')->comment('Physical address of the NGO');
            $table->string('phone')->comment('Contact phone number');
            $table->string('registration_number')->comment('DAO registration number');
            $table->string('registration_district')->comment('District of DAO registration');
            $table->date('last_renewal_date')->comment('Date of last registration renewal');
            $table->string('pan_number', 10)->comment('PAN number (10 characters, alphanumeric)');
            $table->string('mission')->nullable()->comment('Mission statement of the NGO');
            $table->text('description')->nullable()->comment('Detailed description of the NGO');
            $table->json('photos')->nullable()->comment('Array of photo file paths (e.g., ["path1.jpg", "path2.jpg"])');
            $table->string('contact_position')->nullable()->comment('Position/role of the contact person in the NGO');
            $table->string('subcategory')->nullable()->comment('Subcategory for more specific classification (e.g., Child Education under Education)');
            $table->string('logo')->nullable()->comment('File path to the NGO logo');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngos');
    }
};
