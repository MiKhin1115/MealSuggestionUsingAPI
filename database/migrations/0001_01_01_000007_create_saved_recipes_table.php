<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saved_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->date('saved_date');
            $table->timestamps();
            
            // Prevent duplicate saves for the same recipe on the same day
            $table->unique(['user_id', 'recipe_id', 'saved_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('saved_recipes');
    }
}; 