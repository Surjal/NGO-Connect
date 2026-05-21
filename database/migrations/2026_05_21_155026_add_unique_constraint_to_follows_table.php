<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DELETE f1 FROM follows f1 INNER JOIN follows f2 WHERE f1.id > f2.id AND f1.user_id = f2.user_id AND f1.ngo_id = f2.ngo_id');

        DB::statement('ALTER TABLE follows ADD UNIQUE INDEX follows_user_id_ngo_id_unique (user_id, ngo_id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE follows DROP INDEX follows_user_id_ngo_id_unique');
    }
};
