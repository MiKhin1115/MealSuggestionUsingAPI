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
            // Remove allergies columns
            if (Schema::hasColumn('questions3', 'allergies')) {
                $table->dropColumn('allergies');
            }
            
            if (Schema::hasColumn('questions3', 'no_allergies')) {
                $table->dropColumn('no_allergies');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions3', function (Blueprint $table) {
            // Add the columns back if needed
            if (!Schema::hasColumn('questions3', 'no_allergies')) {
                $table->boolean('no_allergies')->default(false)->comment('True if the user has no allergies');
            }
            
            if (!Schema::hasColumn('questions3', 'allergies')) {
                $table->text('allergies')->nullable()->comment('User allergies, comma-separated');
            }
        });
    }
};
