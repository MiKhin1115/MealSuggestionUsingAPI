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
        // Check if recipes table exists before creating it
        if (!Schema::hasTable('recipes')) {
            Schema::create('recipes', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('ingredients');
                $table->json('instructions');
                $table->integer('cooking_time')->comment('in minutes');
                $table->integer('calories')->nullable();
                $table->float('protein')->nullable()->comment('in grams');
                $table->float('carbs')->nullable()->comment('in grams');
                $table->float('fats')->nullable()->comment('in grams');
                $table->enum('diet_type', ['omnivore', 'vegetarian', 'vegan'])->default('omnivore');
                $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack'])->nullable();
                $table->string('image_url')->nullable();
                $table->string('source_url')->nullable();
                $table->timestamps();
            });
        }

        // Check if user_favorites table exists before creating it
        if (!Schema::hasTable('user_favorites')) {
            Schema::create('user_favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['user_id', 'recipe_id']);
            });
        }

        // Check if saved_recipes table exists before creating it
        if (!Schema::hasTable('saved_recipes')) {
            Schema::create('saved_recipes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['user_id', 'recipe_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_recipes');
        Schema::dropIfExists('user_favorites');
        Schema::dropIfExists('recipes');
    }
}; 