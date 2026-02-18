<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->boolean('verified')->default(false)->after('suspended');
        });
    }

    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->dropColumn('verified');
        });
    }
};
