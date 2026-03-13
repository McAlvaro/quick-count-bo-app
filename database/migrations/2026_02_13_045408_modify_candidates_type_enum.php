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
        $driver = config('database.default');

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE candidates MODIFY COLUMN type ENUM('PRESIDENTE', 'DIPUTADO', 'DIPUTADO_ESPECIAL', 'GOBERNADOR', 'ALCALDE')");
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: drop constraint if exists, then add new one
            DB::statement('ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_type_check');
            DB::statement('ALTER TABLE candidates ALTER COLUMN type TYPE VARCHAR(50)');
            DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_type_check CHECK (type IN ('PRESIDENTE', 'DIPUTADO', 'DIPUTADO_ESPECIAL', 'GOBERNADOR', 'ALCALDE'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = config('database.default');

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE candidates MODIFY COLUMN type ENUM('PRESIDENTE', 'DIPUTADO', 'DIPUTADO_ESPECIAL')");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_type_check');
            DB::statement('ALTER TABLE candidates ALTER COLUMN type TYPE VARCHAR(50)');
        }
    }
};
