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
        Schema::table('questions3', function (Blueprint $table) {
            // Check if the column doesn't exist before adding it
            if (!Schema::hasColumn('questions3', 'allergies')) {
                $table->text('allergies')->nullable()->comment('User allergies, comma-separated');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions3', function (Blueprint $table) {
            // Only drop the column if it exists
            if (Schema::hasColumn('questions3', 'allergies')) {
                $table->dropColumn('allergies');
            }
        });
    }
};
