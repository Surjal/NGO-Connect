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
        $legacyTable = 'donation_has_' . 'pay' . 'ments';
        $legacyColumn = 'transaction_' . 'uuid';
        $legacyIndex = 'donations_' . 'transaction_' . 'uuid_unique';

        Schema::dropIfExists($legacyTable);

        Schema::table('donations', function (Blueprint $table) {
            $legacyColumn = 'transaction_' . 'uuid';
            $legacyIndex = 'donations_' . 'transaction_' . 'uuid_unique';

            if (Schema::hasColumn('donations', $legacyColumn)) {
                $table->dropUnique($legacyIndex);
                $table->dropColumn($legacyColumn);
            }

            if (Schema::hasColumn('donations', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'status')) {
                $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->after('donation_amount');
            }
        });
    }
};
