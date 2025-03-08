<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions3', function (Blueprint $table) {
            // Drop the existing health_goal column and recreate it with the new enum values
            DB::statement("ALTER TABLE questions3 MODIFY COLUMN health_goal ENUM('weight_loss', 'weight_gain', 'maintain_weight') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions3', function (Blueprint $table) {
            // Revert back to the original enum values
            DB::statement("ALTER TABLE questions3 MODIFY COLUMN health_goal ENUM('weight_loss', 'weight_gain', 'maintenance') NOT NULL");
        });
    }
};
