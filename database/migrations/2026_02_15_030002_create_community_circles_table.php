<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circle_threads', function (Blueprint $user) {
            $user->id();
            $user->foreignId('ngo_id')->constrained('users')->onDelete('cascade');
            $user->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $user->string('title');
            $user->text('content');
            $user->timestamps();
        });

        Schema::create('circle_replies', function (Blueprint $user) {
            $user->id();
            $user->foreignId('thread_id')->constrained('circle_threads')->onDelete('cascade');
            $user->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $user->text('content');
            $user->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circle_replies');
        Schema::dropIfExists('circle_threads');
    }
};
