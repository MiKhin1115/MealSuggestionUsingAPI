<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class MealSuggestionRecipeSeeder extends Seeder
{
    public function run()
    {
        $recipes = [
            [
                'title' => 'Mohinga',
                'description' => 'Traditional Burmese fish soup with rice noodles, a beloved breakfast dish',
                'image_url' => 'https://www.themyanmartimes.com/wp-content/uploads/2019/04/mohinga.jpg',
                'meal_type' => 'Breakfast',
                'cooking_time' => '45 mins',
                'difficulty' => 'Medium',
                'ingredients' => [
                    'Rice noodles',
                    'Fish (preferably catfish)',
                    'Banana stem',
                    'Lemongrass',
                    'Ginger',
                    'Garlic',
                    'Fish sauce',
                    'Crispy fritters',
                    'Boiled eggs'
                ],
                'instructions' => [
                    'Prepare fish broth with lemongrass and ginger',
                    'Cook rice noodles until tender',
                    'Slice banana stem and prepare garnishes',
                    'Mix all ingredients in a bowl',
                    'Top with crispy fritters and boiled eggs'
                ],
                'nutrition_facts' => [
                    'calories' => '450 kcal',
                    'protein' => '22g',
                    'carbs' => '65g',
                    'fat' => '12g'
                ]
            ],
            [
                'title' => 'Shan Noodles',
                'description' => 'Popular Shan-style rice noodles with chicken and tomato sauce',
                'image_url' => 'https://www.yangonmyanmar.com/wp-content/uploads/2019/08/shan-noodles.jpg',
                'meal_type' => 'Lunch',
                'cooking_time' => '30 mins',
                'difficulty' => 'Easy',
                'ingredients' => [
                    'Rice noodles',
                    'Chicken',
                    'Tomatoes',
                    'Peanuts',
                    'Garlic oil',
                    'Chili',
                    'Spring onions'
                ],
                'instructions' => [
                    'Cook rice noodles until al dente',
                    'Prepare chicken and tomato sauce',
                    'Mix noodles with sauce',
                    'Top with crushed peanuts',
                    'Garnish with spring onions'
                ],
                'nutrition_facts' => [
                    'calories' => '380 kcal',
                    'protein' => '25g',
                    'carbs' => '48g',
                    'fat' => '15g'
                ]
            ],
            [
                'title' => 'Tea Leaf Salad',
                'description' => 'Traditional Burmese fermented tea leaf salad with crunchy nuts',
                'image_url' => 'https://www.myanmarfoodie.com/wp-content/uploads/2020/01/tea-leaf-salad.jpg',
                'meal_type' => 'Dinner',
                'cooking_time' => '15 mins',
                'difficulty' => 'Easy',
                'ingredients' => [
                    'Fermented tea leaves',
                    'Peanuts',
                    'Fried yellow peas',
                    'Fried garlic',
                    'Tomatoes',
                    'Dried shrimp',
                    'Sesame seeds',
                    'Lime juice'
                ],
                'instructions' => [
                    'Mix tea leaves with lime juice',
                    'Add all crunchy ingredients',
                    'Add chopped tomatoes and dried shrimp',
                    'Toss well to combine',
                    'Serve immediately while crunchy'
                ],
                'nutrition_facts' => [
                    'calories' => '280 kcal',
                    'protein' => '12g',
                    'carbs' => '25g',
                    'fat' => '18g'
                ]
            ],
            [
                'title' => 'Ohn No Khao Swe',
                'description' => 'Creamy coconut chicken noodle soup with egg noodles',
                'image_url' => 'https://www.myanmarcooking.com/wp-content/uploads/2019/06/ohn-no-khao-swe.jpg',
                'meal_type' => 'Dinner',
                'cooking_time' => '40 mins',
                'difficulty' => 'Medium',
                'ingredients' => [
                    'Egg noodles',
                    'Chicken',
                    'Coconut milk',
                    'Chickpea flour',
                    'Fish sauce',
                    'Onions',
                    'Hard-boiled eggs',
                    'Crispy noodles'
                ],
                'instructions' => [
                    'Cook chicken in coconut milk',
                    'Prepare chickpea flour soup base',
                    'Cook egg noodles',
                    'Combine noodles with coconut broth',
                    'Top with garnishes and crispy noodles'
                ],
                'nutrition_facts' => [
                    'calories' => '420 kcal',
                    'protein' => '28g',
                    'carbs' => '45g',
                    'fat' => '22g'
                ]
            ],
            [
                'title' => 'Htamin Jin',
                'description' => 'Tangy rice salad with flaked fish and fresh herbs',
                'image_url' => 'https://www.myanmarfoodrecipes.com/wp-content/uploads/2019/03/htamin-jin.jpg',
                'meal_type' => 'Lunch',
                'cooking_time' => '30 mins',
                'difficulty' => 'Medium',
                'ingredients' => [
                    'Cooked rice',
                    'Flaked fish',
                    'Onions',
                    'Tomatoes',
                    'Crispy fried garlic',
                    'Fish sauce',
                    'Lime juice',
                    'Fresh herbs'
                ],
                'instructions' => [
                    'Mix rice with flaked fish',
                    'Add chopped vegetables',
                    'Season with fish sauce and lime',
                    'Add crispy garlic',
                    'Garnish with fresh herbs'
                ],
                'nutrition_facts' => [
                    'calories' => '350 kcal',
                    'protein' => '20g',
                    'carbs' => '52g',
                    'fat' => '8g'
                ]
            ]
        ];

        foreach ($recipes as $recipe) {
            Recipe::create([
                'title' => $recipe['title'],
                'description' => $recipe['description'],
                'image_url' => $recipe['image_url'],
                'meal_type' => $recipe['meal_type'],
                'cooking_time' => $recipe['cooking_time'],
                'difficulty' => $recipe['difficulty'],
                'ingredients' => json_encode($recipe['ingredients']),
                'instructions' => json_encode($recipe['instructions']),
                'nutrition_facts' => json_encode($recipe['nutrition_facts'])
            ]);
        }
    }
} 