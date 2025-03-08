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
        Schema::table('recipes', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('recipes', 'calories')) {
                $table->integer('calories')->nullable()->after('cooking_time');
            }
            
            if (!Schema::hasColumn('recipes', 'protein')) {
                $table->float('protein')->nullable()->comment('in grams')->after('calories');
            }
            
            if (!Schema::hasColumn('recipes', 'carbs')) {
                $table->float('carbs')->nullable()->comment('in grams')->after('protein');
            }
            
            if (!Schema::hasColumn('recipes', 'fats')) {
                $table->float('fats')->nullable()->comment('in grams')->after('carbs');
            }
            
            // Make sure cooking_time exists as integer
            if (!Schema::hasColumn('recipes', 'cooking_time')) {
                $table->integer('cooking_time')->comment('in minutes')->after('instructions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Remove columns added by this migration
            $columns = ['calories', 'protein', 'carbs', 'fats'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('recipes', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // We don't drop cooking_time in down() since it might be a core column
        });
    }
}; 