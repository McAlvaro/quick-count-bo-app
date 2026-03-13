<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, modifying enum columns is tricky as it's just TEXT.
        // For MySQL/MariaDB, we need to alter the ENUM definition.
        if (config('database.default') !== 'sqlite') {
            DB::statement("ALTER TABLE candidates MODIFY COLUMN type ENUM('PRESIDENTE', 'DIPUTADO', 'DIPUTADO_ESPECIAL', 'GOBERNADOR', 'ALCALDE')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') !== 'sqlite') {
            DB::statement("ALTER TABLE candidates MODIFY COLUMN type ENUM('PRESIDENTE', 'DIPUTADO', 'DIPUTADO_ESPECIAL')");
        }
    }
};
