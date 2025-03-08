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
        if (!Schema::hasTable('get_recipes')) {
            Schema::create('get_recipes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                // Create a unique index to prevent duplicate entries
                $table->unique(['user_id', 'recipe_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('get_recipes');
    }
}; 