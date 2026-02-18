<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $user) {
            $user->id();
            $user->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $user->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $user->text('content');
            $user->timestamp('read_at')->nullable();
            $user->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
