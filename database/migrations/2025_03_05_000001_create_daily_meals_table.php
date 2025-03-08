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
        // Check if daily_meals table exists before creating it
        if (!Schema::hasTable('daily_meals')) {
            Schema::create('daily_meals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack'])->default('dinner');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                // Create a unique index to prevent duplicate meal types on the same day for a user
                $table->unique(['user_id', 'date', 'meal_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_meals');
    }
}; 