<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('recipe_id')->constrained('meal_suggestion_recipes')->onDelete('cascade'); // Foreign key to meal_suggestion_recipes table
            $table->timestamps();

            // Add unique constraint to prevent duplicate favorites
            $table->unique(['user_id', 'recipe_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_favorites');
    }
};