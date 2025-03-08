<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions2', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->text('favorite_meals')->comment('Favorite meals, stored as comma-separated values');
            $table->text('disliked_ingredients')->nullable()->comment('Disliked ingredients, stored as comma-separated values');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions2');
    }
};

