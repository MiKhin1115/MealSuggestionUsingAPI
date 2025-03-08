<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meal_suggestion_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_url');
            $table->string('meal_type');  // Breakfast, Lunch, Dinner, Supper
            $table->string('cooking_time');
            $table->string('difficulty');  // Easy, Medium, Hard
            $table->json('ingredients');
            $table->json('instructions');
            $table->json('nutrition_facts');
            $table->timestamps();
        });

        // Insert sample data
        DB::table('meal_suggestion_recipes')->insert([
            [
                'title' => 'Mohinga',
                'description' => 'Traditional Burmese fish soup with rice noodles',
                'image_url' => 'https://images.unsplash.com/photo-1555126634-323283e090fa',
                'meal_type' => 'Breakfast',
                'cooking_time' => '45 mins',
                'difficulty' => 'Medium',
                'ingredients' => json_encode([
                    'Rice noodles',
                    'Fish',
                    'Banana stem',
                    'Lemongrass',
                    'Ginger',
                    'Garlic',
                    'Fish sauce'
                ]),
                'instructions' => json_encode([
                    'Prepare fish broth',
                    'Cook rice noodles',
                    'Prepare banana stem',
                    'Mix ingredients',
                    'Add garnishes'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '450 kcal',
                    'protein' => '22g',
                    'carbs' => '65g',
                    'fat' => '12g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Shan Noodles',
                'description' => 'Popular Shan-style rice noodles with chicken',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c',
                'meal_type' => 'Lunch',
                'cooking_time' => '30 mins',
                'difficulty' => 'Easy',
                'ingredients' => json_encode([
                    'Rice noodles',
                    'Chicken',
                    'Tomatoes',
                    'Peanuts',
                    'Garlic oil',
                    'Chili'
                ]),
                'instructions' => json_encode([
                    'Cook noodles',
                    'Prepare chicken',
                    'Make sauce',
                    'Mix all ingredients',
                    'Add garnishes'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '380 kcal',
                    'protein' => '25g',
                    'carbs' => '48g',
                    'fat' => '15g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Tea Leaf Salad',
                'description' => 'Traditional Burmese fermented tea leaf salad',
                'image_url' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288',
                'meal_type' => 'Dinner',
                'cooking_time' => '15 mins',
                'difficulty' => 'Easy',
                'ingredients' => json_encode([
                    'Fermented tea leaves',
                    'Peanuts',
                    'Fried garlic',
                    'Tomatoes',
                    'Dried shrimp',
                    'Sesame seeds'
                ]),
                'instructions' => json_encode([
                    'Mix tea leaves',
                    'Add crunchy ingredients',
                    'Add vegetables',
                    'Toss well',
                    'Serve immediately'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '280 kcal',
                    'protein' => '12g',
                    'carbs' => '25g',
                    'fat' => '18g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Coconut Noodles',
                'description' => 'Creamy coconut chicken noodle soup',
                'image_url' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929',
                'meal_type' => 'Supper',
                'cooking_time' => '35 mins',
                'difficulty' => 'Medium',
                'ingredients' => json_encode([
                    'Egg noodles',
                    'Chicken',
                    'Coconut milk',
                    'Fish sauce',
                    'Onions',
                    'Eggs'
                ]),
                'instructions' => json_encode([
                    'Cook noodles',
                    'Prepare chicken',
                    'Make coconut broth',
                    'Combine ingredients',
                    'Add toppings'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '420 kcal',
                    'protein' => '28g',
                    'carbs' => '45g',
                    'fat' => '22g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('meal_suggestion_recipes');
    }
}; 