<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing recipes
        Recipe::truncate();
        
        // Sample recipes
        $recipes = [
            [
                'title' => 'Spinach and Feta Omelette',
                'description' => 'A protein-packed breakfast option that\'s quick to make and delicious.',
                'ingredients' => json_encode([
                    '3 large eggs',
                    '1 cup fresh spinach, chopped',
                    '1/4 cup feta cheese, crumbled',
                    '1 tablespoon olive oil',
                    'Salt and pepper to taste'
                ]),
                'instructions' => json_encode([
                    'Whisk eggs in a bowl with salt and pepper.',
                    'Heat olive oil in a non-stick pan over medium heat.',
                    'Add chopped spinach and cook until wilted, about 1 minute.',
                    'Pour in the eggs and cook until nearly set, about 2 minutes.',
                    'Sprinkle feta cheese on one half of the omelette.',
                    'Fold the omelette in half and cook for another minute.',
                    'Serve hot.'
                ]),
                'cooking_time' => 10,
                'calories' => 320,
                'protein' => 21,
                'carbs' => 4,
                'fats' => 25,
                'diet_type' => 'vegetarian',
                'meal_type' => 'breakfast',
                'image_url' => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?ixlib=rb-4.0.3&auto=format&fit=crop&w=700&q=60',
                'source_url' => null
            ],
            [
                'title' => 'Grilled Chicken Salad',
                'description' => 'A healthy and satisfying lunch option with lean protein and fresh vegetables.',
                'ingredients' => json_encode([
                    '1 chicken breast (about 6 oz), boneless and skinless',
                    '2 cups mixed salad greens',
                    '1/2 cucumber, sliced',
                    '1/2 cup cherry tomatoes, halved',
                    '1/4 red onion, thinly sliced',
                    '2 tablespoons olive oil',
                    '1 tablespoon balsamic vinegar',
                    'Salt and pepper to taste'
                ]),
                'instructions' => json_encode([
                    'Season chicken breast with salt and pepper.',
                    'Grill chicken for 6-7 minutes per side until cooked through.',
                    'Let chicken rest for 5 minutes, then slice.',
                    'In a large bowl, combine salad greens, cucumber, tomatoes, and onion.',
                    'Whisk together olive oil and balsamic vinegar.',
                    'Add sliced chicken to the salad and drizzle with dressing.',
                    'Toss gently and serve immediately.'
                ]),
                'cooking_time' => 20,
                'calories' => 350,
                'protein' => 32,
                'carbs' => 10,
                'fats' => 20,
                'diet_type' => 'omnivore',
                'meal_type' => 'lunch',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&auto=format&fit=crop&w=700&q=60',
                'source_url' => null
            ],
            [
                'title' => 'Vegan Buddha Bowl',
                'description' => 'A colorful, nutrient-dense bowl packed with vegetables, grains, and plant-based protein.',
                'ingredients' => json_encode([
                    '1 cup cooked quinoa',
                    '1 cup roasted sweet potatoes, cubed',
                    '1 cup chickpeas, drained and rinsed',
                    '1 avocado, sliced',
                    '1 cup kale, massaged with lemon juice',
                    '1/4 cup hummus',
                    '2 tablespoons tahini sauce',
                    '1 tablespoon olive oil',
                    'Salt and pepper to taste',
                    'Lemon wedges for serving'
                ]),
                'instructions' => json_encode([
                    'Place quinoa in a bowl as the base.',
                    'Arrange sweet potatoes, chickpeas, avocado, and kale around the bowl.',
                    'Add a dollop of hummus in the center.',
                    'Drizzle with tahini sauce and olive oil.',
                    'Season with salt and pepper.',
                    'Serve with lemon wedges for squeezing over.'
                ]),
                'cooking_time' => 30,
                'calories' => 580,
                'protein' => 18,
                'carbs' => 80,
                'fats' => 22,
                'diet_type' => 'vegan',
                'meal_type' => 'dinner',
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=700&q=60',
                'source_url' => null
            ],
            [
                'title' => 'Classic Eggs Benedict',
                'description' => 'A delicious breakfast classic with perfectly poached eggs and hollandaise sauce',
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
                'cooking_time' => 30,
                'calories' => 540,
                'protein' => 25,
                'carbs' => 35,
                'fats' => 32,
                'diet_type' => 'omnivore',
                'meal_type' => 'breakfast',
                'image_url' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666',
                'source_url' => null
            ]
        ];

        // Insert recipes
        foreach ($recipes as $recipe) {
            Recipe::create($recipe);
        }

        $this->command->info('Sample recipes seeded successfully!');
    }
} 