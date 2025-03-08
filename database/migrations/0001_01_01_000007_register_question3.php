<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('questions3', function (Blueprint $table) {
            $table->boolean('no_allergies')->default(false)->comment('True if the user has no allergies');
            $table->text('medical_conditions')->nullable()->comment('User medical conditions, if any');
            $table->integer('cooking_time')->unsigned()->comment('Preferred cooking time in minutes');
            $table->enum('meal_budget', ['budget', 'moderate', 'expensive'])->comment('User meal budget category');
        });
    }

    public function down()
    {
        Schema::table('questions3', function (Blueprint $table) {
            $table->dropColumn(['no_allergies', 'medical_conditions', 'cooking_time', 'meal_budget']);
        });
    }
};
