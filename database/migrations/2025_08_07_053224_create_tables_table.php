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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->integer(column: 'number');
            $table->foreignId(column: 'precinct_id')->constrained('precincts')->onDelete('cascade');
            $table->integer(column: 'null_votes')->default(value: 0);
            $table->integer(column: 'blank_votes')->default(value: 0);
            $table->string(column: 'act_path')->nullable();
            $table->foreignId(column: 'registered_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('registered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
