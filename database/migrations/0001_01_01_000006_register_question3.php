<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions3', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->boolean('allergies')->default(false)->comment('True if the user has allergies, false otherwise');
            $table->enum('health_goal', ['weight_loss', 'weight_gain', 'maintenance']);
            $table->text('favorite_snacks')->nullable()->comment('User\'s favorite snacks, comma-separated');
            $table->enum('cooking_skill', ['beginner', 'intermediate', 'advanced', 'expert'])->comment('User\'s cooking skill level');
            $table->integer('daily_budget')->unsigned()->default(10000)->comment('User\'s daily budget, must be >= 10000');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions3');
    }
};
