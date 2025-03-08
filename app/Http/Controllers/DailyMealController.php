<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\DailyMeal;
use App\Models\Recipe;

class DailyMealController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            $user = new \App\Models\User();
            $user->name = "Guest User";
            $user->age = "-";
        }
        
        return view('daily_meal_1', ['user' => $user]);
    }
    
    /**
     * Add a recipe to the user's daily meal plan
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMeal(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log the request
            Log::info('Add to meal plan request', [
                'user_id' => $user->id,
                'data' => $request->all()
            ]);
            
            // Validate request data
            $validated = $request->validate([
                'recipe_id' => 'required|numeric',
                'meal_type' => 'required|string|in:breakfast,lunch,dinner,snack',
                'date' => 'required|date_format:Y-m-d',
            ]);
            
            // Find or create recipe in local database
            $recipe = Recipe::find($validated['recipe_id']);
            
            if (!$recipe) {
                // Recipe doesn't exist in our database, so we need to create it from the data
                if (!$request->has('title')) {
                    return response()->json([
                        'error' => 'Recipe details are required when adding a new recipe'
                    ], 400);
                }
                
                // Create a new recipe
                $recipe = Recipe::create([
                    'title' => $request->input('title'),
                    'description' => $request->input('description', ''),
                    'ingredients' => $request->input('ingredients', []),
                    'instructions' => $request->input('instructions', []),
                    'cooking_time' => $request->input('cooking_time', 0),
                    'calories' => $request->input('calories', 0),
                    'diet_type' => $this->mapDietType($request->input('diet_type', 'omnivore')),
                    'image_url' => $request->input('image', ''),
                    'source_url' => ''
                ]);
                
                Log::info('Created new recipe for meal plan', [
                    'recipe_id' => $recipe->id,
                    'title' => $recipe->title
                ]);
            }
            
            // Check if this meal already exists for this date/type
            $existingMeal = DailyMeal::where('user_id', $user->id)
                ->where('date', $validated['date'])
                ->where('meal_type', $validated['meal_type'])
                ->first();
                
            if ($existingMeal) {
                // Update existing meal
                $existingMeal->recipe_id = $recipe->id;
                $existingMeal->save();
                
                Log::info('Updated existing meal', [
                    'meal_id' => $existingMeal->id,
                    'new_recipe_id' => $recipe->id
                ]);
                
                return response()->json([
                    'status' => 'updated',
                    'message' => 'Meal plan updated successfully',
                    'meal' => $existingMeal
                ]);
            } else {
                // Create new meal
                $meal = DailyMeal::create([
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                    'date' => $validated['date'],
                    'meal_type' => $validated['meal_type'],
                    'notes' => $request->input('notes', '')
                ]);
                
                Log::info('Created new meal', [
                    'meal_id' => $meal->id,
                    'recipe_id' => $recipe->id,
                    'meal_type' => $meal->meal_type,
                    'date' => $meal->date
                ]);
                
                return response()->json([
                    'status' => 'created',
                    'message' => 'Recipe added to meal plan',
                    'meal' => $meal
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error adding recipe to meal plan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to add recipe to meal plan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Map diet types to allowed enum values
     * 
     * @param string $dietType
     * @return string
     */
    private function mapDietType($dietType)
    {
        if (!$dietType) {
            return 'omnivore';
        }
        
        $dietType = strtolower($dietType);
        
        $mapping = [
            'vegetarian' => 'vegetarian',
            'vegan' => 'vegan',
            'pescetarian' => 'omnivore',
            'paleo' => 'omnivore',
            'primal' => 'omnivore',
            'gluten free' => 'omnivore',
            'ketogenic' => 'omnivore',
            'whole30' => 'omnivore',
            'dairy free' => 'omnivore',
            'low fodmap' => 'omnivore'
        ];
        
        return $mapping[$dietType] ?? 'omnivore';
    }
    
    /**
     * Generate meal suggestions based on available ingredients
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function generateMeals(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log the request
            Log::info('Generate meals request', [
                'user_id' => $user->id,
                'data' => $request->all()
            ]);
            
            // Process ingredients from the form
            $ingredients = [
                'proteins' => json_decode($request->input('proteins', '[]'), true),
                'vegetables' => json_decode($request->input('vegetables', '[]'), true),
                'fruits' => json_decode($request->input('fruits', '[]'), true),
                'grains' => json_decode($request->input('grains', '[]'), true),
                'dairy' => json_decode($request->input('dairy', '[]'), true),
                'spices' => json_decode($request->input('spices', '[]'), true),
                'beverages' => json_decode($request->input('beverages', '[]'), true),
            ];
            
            // Log the parsed ingredients
            Log::info('Parsed ingredients', [
                'user_id' => $user->id,
                'ingredients' => $ingredients
            ]);
            
            // Generate meal suggestions for each time of day
            $mealSuggestions = $this->generateMealSuggestions($ingredients);
            
            return view('daily_meal_2', [
                'user' => $user,
                'ingredients' => $ingredients,
                'mealSuggestions' => $mealSuggestions
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating meal suggestions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('daily.meal')
                ->with('error', 'An error occurred while generating meal suggestions. Please try again.');
        }
    }
    
    /**
     * Generate meal suggestions based on available ingredients
     * 
     * @param array $ingredients
     * @return array
     */
    private function generateMealSuggestions($ingredients)
    {
        // This is a simplified version - in a real app, you'd use a more sophisticated algorithm
        // or an external API to generate meal suggestions based on available ingredients
        
        // Example data structure
        $meals = [
            'breakfast' => [
                'title' => 'Breakfast Options',
                'recipes' => []
            ],
            'lunch' => [
                'title' => 'Lunch Options',
                'recipes' => []
            ],
            'dinner' => [
                'title' => 'Dinner Options',
                'recipes' => []
            ],
            'snack' => [
                'title' => 'Snacks & Desserts',
                'recipes' => []
            ]
        ];
        
        // Combine all ingredients into a flat list for simple matching
        $allIngredients = [];
        foreach ($ingredients as $category => $items) {
            $allIngredients = array_merge($allIngredients, $items);
        }
        
        // Get recipes from database that might match these ingredients
        // For demo purposes, just get some recipes
        $recipes = Recipe::inRandomOrder()->limit(20)->get();
        
        foreach ($recipes as $recipe) {
            // Determine which meal type this recipe is best suited for
            $mealType = $this->determineMealType($recipe);
            
            // Add to the appropriate meal suggestion
            $meals[$mealType]['recipes'][] = $recipe;
        }
        
        // If we don't have enough recipes for a meal type, duplicate some from other types
        foreach ($meals as $type => &$mealData) {
            if (count($mealData['recipes']) < 3) {
                // Find other meal types with more recipes
                foreach ($meals as $otherType => $otherMealData) {
                    if ($otherType != $type && count($otherMealData['recipes']) > 3) {
                        // Take 1-2 recipes from the other meal type
                        $recipesToMove = array_slice($otherMealData['recipes'], 0, 2);
                        $mealData['recipes'] = array_merge($mealData['recipes'], $recipesToMove);
                        break;
                    }
                }
            }
            
            // Limit to at most 5 recipes per meal type
            $mealData['recipes'] = array_slice($mealData['recipes'], 0, 5);
        }
        
        return $meals;
    }
    
    /**
     * Determine which meal type a recipe is best suited for
     * 
     * @param Recipe $recipe
     * @return string
     */
    private function determineMealType($recipe)
    {
        // This is a simplified algorithm
        // In a real app, you'd use more sophisticated logic based on recipe metadata
        
        $title = strtolower($recipe->title);
        
        if (strpos($title, 'breakfast') !== false || 
            strpos($title, 'pancake') !== false || 
            strpos($title, 'cereal') !== false ||
            strpos($title, 'toast') !== false ||
            strpos($title, 'egg') !== false) {
            return 'breakfast';
        }
        
        if (strpos($title, 'salad') !== false || 
            strpos($title, 'sandwich') !== false || 
            strpos($title, 'soup') !== false) {
            return 'lunch';
        }
        
        if (strpos($title, 'dessert') !== false || 
            strpos($title, 'cookie') !== false || 
            strpos($title, 'snack') !== false || 
            strpos($title, 'cake') !== false) {
            return 'snack';
        }
        
        // Default to dinner for most recipes
        return 'dinner';
    }
} 