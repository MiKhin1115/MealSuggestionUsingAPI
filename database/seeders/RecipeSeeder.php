<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        Recipe::create([
            'title' => 'Classic Eggs Benedict',
            'description' => 'A delicious breakfast classic with perfectly poached eggs and hollandaise sauce',
            'image_url' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666',
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
            ])
        ]);

        // Add more recipes for different meal types...
    }
} 