<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
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

        // Insert some sample data
        DB::table('recipes')->insert([
            [
                'title' => 'Classic Eggs Benedict',
                'description' => 'A delicious breakfast classic with perfectly poached eggs and hollandaise sauce',
                'image_url' => 'https://images.unsplash.com/photo-1608039829572-78524f79c4c7',
                'meal_type' => 'Breakfast',
                'cooking_time' => '30 mins',
                'difficulty' => 'Medium',
                'ingredients' => json_encode([
                    '4 English muffins',
                    '8 eggs',
                    '8 slices of ham',
                    'Hollandaise sauce',
                    'Fresh parsley'
                ]),
                'instructions' => json_encode([
                    'Toast the English muffins',
                    'Poach the eggs',
                    'Prepare hollandaise sauce',
                    'Assemble: muffin, ham, egg, sauce',
                    'Garnish with parsley'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '540 kcal',
                    'protein' => '25g',
                    'carbs' => '35g',
                    'fat' => '32g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Grilled Chicken Salad',
                'description' => 'Fresh and healthy lunch option with grilled chicken breast',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c',
                'meal_type' => 'Lunch',
                'cooking_time' => '20 mins',
                'difficulty' => 'Easy',
                'ingredients' => json_encode([
                    'Chicken breast',
                    'Mixed greens',
                    'Cherry tomatoes',
                    'Cucumber',
                    'Balsamic dressing'
                ]),
                'instructions' => json_encode([
                    'Grill the chicken',
                    'Chop vegetables',
                    'Mix salad ingredients',
                    'Slice chicken and add to salad',
                    'Drizzle with dressing'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '320 kcal',
                    'protein' => '28g',
                    'carbs' => '15g',
                    'fat' => '18g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Grilled Salmon',
                'description' => 'Perfectly grilled salmon with seasonal vegetables',
                'image_url' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288',
                'meal_type' => 'Dinner',
                'cooking_time' => '25 mins',
                'difficulty' => 'Medium',
                'ingredients' => json_encode([
                    'Salmon fillet',
                    'Asparagus',
                    'Lemon',
                    'Olive oil',
                    'Herbs'
                ]),
                'instructions' => json_encode([
                    'Preheat grill',
                    'Season salmon',
                    'Grill salmon and vegetables',
                    'Add lemon and herbs',
                    'Serve hot'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '450 kcal',
                    'protein' => '46g',
                    'carbs' => '12g',
                    'fat' => '28g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Light Quinoa Bowl',
                'description' => 'Nutritious and light evening meal',
                'image_url' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929',
                'meal_type' => 'Supper',
                'cooking_time' => '20 mins',
                'difficulty' => 'Easy',
                'ingredients' => json_encode([
                    'Quinoa',
                    'Avocado',
                    'Cherry tomatoes',
                    'Chickpeas',
                    'Olive oil'
                ]),
                'instructions' => json_encode([
                    'Cook quinoa',
                    'Prepare vegetables',
                    'Mix ingredients',
                    'Add dressing',
                    'Serve immediately'
                ]),
                'nutrition_facts' => json_encode([
                    'calories' => '380 kcal',
                    'protein' => '12g',
                    'carbs' => '48g',
                    'fat' => '18g'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}; 