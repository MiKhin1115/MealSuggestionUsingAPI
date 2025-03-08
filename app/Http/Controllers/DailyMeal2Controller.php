<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Models\Recipe;
use Illuminate\Support\Facades\Log;

class DailyMeal2Controller extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            $user = new \App\Models\User();
            $user->name = "Guest User";
            $user->age = "-";
        }
        
        // Set Myanmar timezone
        date_default_timezone_set('Asia/Yangon');
        $currentTime = new DateTime();
        $currentHour = (int)$currentTime->format('H');
        $greeting = $this->getGreeting($currentHour);
        
        // Get meal types based on time of day
        $mealTypes = $this->getMealTypes($currentHour);
        
        // Get ingredients from request
        $ingredients = $request->input('ingredient', []);
        
        // Debug recipe counts
        $debugCounts = [
            'Breakfast' => Recipe::where('meal_type', 'Breakfast')->count(),
            'Lunch' => Recipe::where('meal_type', 'Lunch')->count(),
            'Dinner' => Recipe::where('meal_type', 'Dinner')->count(),
            'Supper' => Recipe::where('meal_type', 'Supper')->count(),
            'Total' => Recipe::count()
        ];
        
        // Get recipes for each meal type based on ingredients
        $recipes = $this->processRecipesForMealTypes($mealTypes, $ingredients);
        
        return view('daily_meal_2', [
            'user' => $user,
            'greeting' => $greeting,
            'currentTime' => $currentTime,
            'mealTypes' => $mealTypes,
            'recipes' => $recipes,
            'ingredients' => $ingredients, // Pass ingredients to view for display
            'debug' => [
                'recipe_counts' => $debugCounts,
                'ingredient_count' => count($ingredients),
                'ingredients' => $ingredients
            ]
        ]);
    }
    
    public function showDailyMeal(Request $request)
    {
        try {
            // Get user's saved ingredients or default to empty array
            $user = auth()->user();
            $userIngredients = $user->ingredients ?? [];
            
            // Get recipes for each meal type
            $recipes = [];
            $mealTypes = ['Breakfast', 'Lunch', 'Dinner', 'Supper'];
            
            // Get the active meal type (default to first one)
            $activeMealType = $request->query('meal_type', $mealTypes[0]);
            
            return view('daily_meal_2', [
                'recipes' => $recipes,
                'mealTypes' => $mealTypes,
                'userIngredients' => $userIngredients,
                'activeMealType' => $activeMealType,
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing daily meal: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    
    private function getMealTypes($hour)
    {
        // Morning (5 AM to 11:59 AM): Show all meals
        if ($hour >= 5 && $hour < 12) {
            return ['Breakfast', 'Lunch', 'Dinner', 'Supper'];
        }
        // Afternoon (12 PM to 4:59 PM): Show lunch, dinner, and supper
        elseif ($hour >= 12 && $hour < 17) {
            return ['Lunch', 'Dinner', 'Supper'];
        }
        // Evening (5 PM to 8:59 PM): Show dinner and supper
        elseif ($hour >= 17 && $hour < 21) {
            return ['Dinner', 'Supper'];
        }
        // Night (9 PM to 4:59 AM): Show only supper
        else {
            return ['Supper'];
        }
    }
    
    private function processRecipesForMealTypes($mealTypes, $ingredients)
    {
        $recipes = [];
        $user = Auth::user();
        
        // If no ingredients provided, use some defaults
        if (empty($ingredients)) {
            $ingredients = ['chicken', 'rice', 'vegetables'];
        }
        
        // Prepare the ingredients string for API
        $ingredientsString = implode(',', $ingredients);
        
        // Process each meal type separately to get different recipes
        foreach ($mealTypes as $mealType) {
            // Get meal-specific recipes from Spoonacular
            $apiRecipes = $this->getRecipesFromSpoonacular($ingredientsString, $mealType);
            
            // Log API response for debugging
            \Log::info("Spoonacular API Response for $mealType", [
                'count' => count($apiRecipes),
                'ingredients' => $ingredients
            ]);
            
            $mealRecipes = collect();
            
            // Process API recipes if available
            if (!empty($apiRecipes)) {
                // Get up to 3 recipes for this meal type
                $recipeCount = min(3, count($apiRecipes));
                
                for ($i = 0; $i < $recipeCount; $i++) {
                    if (isset($apiRecipes[$i])) {
                        $apiRecipe = $apiRecipes[$i];
                        
                        // Create a standardized recipe object
                        $recipe = new \stdClass();
                        $recipe->id = $apiRecipe['id'] ?? rand(1000, 9999);
                        $recipe->title = $apiRecipe['title'] ?? '';
                        $recipe->description = $apiRecipe['summary'] ?? '';
                        $recipe->cooking_time_display = ($apiRecipe['readyInMinutes'] ?? 30) . ' mins'; // For display
                        $recipe->cooking_time = ($apiRecipe['readyInMinutes'] ?? 30); // For database
                        $recipe->difficulty = $this->getDifficultyFromTime($apiRecipe['readyInMinutes'] ?? 30);
                        $recipe->image_url = $apiRecipe['image'] ?? "https://spoonacular.com/recipeImages/{$recipe->id}-556x370.jpg";
                        $recipe->ingredients = $this->getIngredientsFromApi($apiRecipe, $ingredients);
                        $recipe->is_favorite = false;
                        $recipe->meal_type = $mealType;
                        $recipe->spoonacular_source_url = $apiRecipe['sourceUrl'] ?? '';
                        
                        // Handle instructions in a more robust way
                        if (!empty($apiRecipe['analyzedInstructions']) && is_array($apiRecipe['analyzedInstructions'])) {
                            // Extract steps from analyzed instructions
                            $steps = [];
                            foreach ($apiRecipe['analyzedInstructions'] as $instruction) {
                                if (isset($instruction['steps']) && is_array($instruction['steps'])) {
                                    foreach ($instruction['steps'] as $step) {
                                        if (isset($step['step']) && !empty($step['step'])) {
                                            $steps[] = $step['step'];
                                        }
                                    }
                                }
                            }
                            
                            if (!empty($steps)) {
                                // If we have steps, convert them to JSON directly
                                $recipe->instructions = json_encode($steps);
                                \Log::info("Extracted " . count($steps) . " steps from analyzedInstructions for recipe {$recipe->title}");
                            } else if (!empty($apiRecipe['instructions'])) {
                                // If no analyzed steps but we have raw instructions text
                                $instructionText = strip_tags($apiRecipe['instructions']);
                                // Split by periods or newlines to create steps
                                $rawSteps = preg_split('/\.\s+|\n+/', $instructionText);
                                $filteredSteps = array_filter($rawSteps, function($step) {
                                    return strlen(trim($step)) > 5; // Only keep substantial steps
                                });
                                
                                if (!empty($filteredSteps)) {
                                    $recipe->instructions = json_encode(array_values($filteredSteps));
                                    \Log::info("Extracted " . count($filteredSteps) . " steps from raw instructions for recipe {$recipe->title}");
                                } else {
                                    $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                                }
                            } else {
                                $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                            }
                        } else if (!empty($apiRecipe['instructions'])) {
                            // Handle raw instruction text if analyzedInstructions is not available
                            $instructionText = strip_tags($apiRecipe['instructions']);
                            // Split by periods or newlines to create steps
                            $rawSteps = preg_split('/\.\s+|\n+/', $instructionText);
                            $filteredSteps = array_filter($rawSteps, function($step) {
                                return strlen(trim($step)) > 5; // Only keep substantial steps
                            });
                            
                            if (!empty($filteredSteps)) {
                                $recipe->instructions = json_encode(array_values($filteredSteps));
                                \Log::info("Extracted " . count($filteredSteps) . " steps from raw instructions text for recipe {$recipe->title}");
                            } else {
                                $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                            }
                        } else {
                            $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                        }
                        
                        $recipe->from_api = true;
                        
                        // Extract nutritional information if available
                        if (isset($apiRecipe['nutrition']) && isset($apiRecipe['nutrition']['nutrients'])) {
                            $nutrients = collect($apiRecipe['nutrition']['nutrients']);
                            
                            $recipe->calories = $nutrients->firstWhere('name', 'Calories')['amount'] ?? 0;
                            $recipe->protein = $nutrients->firstWhere('name', 'Protein')['amount'] ?? 0;
                            $recipe->carbs = $nutrients->firstWhere('name', 'Carbohydrates')['amount'] ?? 0;
                            $recipe->fats = $nutrients->firstWhere('name', 'Fat')['amount'] ?? 0;
                            
                            \Log::info("Extracted nutrition for {$recipe->title}: Calories: {$recipe->calories}, Protein: {$recipe->protein}, Carbs: {$recipe->carbs}, Fats: {$recipe->fats}");
                        } else {
                            // Set default values or estimate
                            $recipe->calories = $apiRecipe['calories'] ?? $this->estimateCalories($recipe->cooking_time);
                            $recipe->protein = $apiRecipe['protein'] ?? 0;
                            $recipe->carbs = $apiRecipe['carbs'] ?? 0;
                            $recipe->fats = $apiRecipe['fats'] ?? 0;
                            
                            \Log::info("No nutrition data for {$recipe->title}, using defaults");
                        }
                        
                        $mealRecipes->push($recipe);
                    }
                }
            }
            
            // No fallbacks - just use what we got from the API (even if empty)
            $recipes[$mealType] = $mealRecipes;
        }
        
        return $recipes;
    }
    
    /**
     * Call Spoonacular API to get recipes by ingredients and meal type
     */
    private function getRecipesFromSpoonacular($ingredientsString, $mealType)
    {
        try {
            $apiKey = config('services.spoonacular.key');
            $number = 3; // Get 3 recipes per meal type
            
            // Log the attempt
            \Log::info("Attempting Spoonacular API request for $mealType with ingredients: $ingredientsString");
            
            // Base URL for complexSearch
            $url = "https://api.spoonacular.com/recipes/complexSearch";
            $client = new \GuzzleHttp\Client();
            
            // Create unique parameters for each meal type to ensure different results
            switch ($mealType) {
                case 'Breakfast':
                    $queryParams = [
                        'apiKey' => $apiKey,
                        'includeIngredients' => $ingredientsString,
                        'type' => 'breakfast',
                        'number' => $number,
                        'sort' => 'popularity',
                        'instructionsRequired' => true,
                        'fillIngredients' => true,
                        'addRecipeInformation' => true
                    ];
                    break;
                    
                case 'Lunch':
                    $queryParams = [
                        'apiKey' => $apiKey,
                        'includeIngredients' => $ingredientsString,
                        'type' => 'main course',  // Changed from 'lunch' to 'main course'
                        'number' => $number + 1,
                        'sort' => 'time',
                        'maxReadyTime' => 30,
                        'instructionsRequired' => true,
                        'fillIngredients' => true,
                        'addRecipeInformation' => true
                    ];
                    break;
                    
                case 'Dinner':
                    $queryParams = [
                        'apiKey' => $apiKey,
                        'includeIngredients' => $ingredientsString,
                        'type' => 'main course',
                        'number' => $number + 1,
                        'sort' => 'popularity',
                        'instructionsRequired' => true,
                        'fillIngredients' => true,
                        'addRecipeInformation' => true
                    ];
                    break;
                    
                case 'Supper':
                    $queryParams = [
                        'apiKey' => $apiKey,
                        'includeIngredients' => $ingredientsString,
                        'type' => 'main course',  // Changed from 'dinner' to 'main course'
                        'number' => $number + 2,
                        'sort' => 'random',
                        'instructionsRequired' => true,
                        'fillIngredients' => true,
                        'addRecipeInformation' => true
                    ];
                    break;
                    
                default:
                    $queryParams = [
                        'apiKey' => $apiKey,
                        'includeIngredients' => $ingredientsString,
                        'type' => 'main course',
                        'number' => $number,
                        'instructionsRequired' => true,
                        'fillIngredients' => true,
                        'addRecipeInformation' => true
                    ];
            }
            
            // Add detailed instruction flag to all query types
            $queryParams['instructionsRequired'] = true;
            $queryParams['addRecipeInformation'] = true;
            $queryParams['addRecipeNutrition'] = true;
            
            // Ensure required ingredients are actually used in the recipes
            if (stripos($ingredientsString, 'chicken') !== false) {
                // If chicken is one of the ingredients, make sure it's required
                $queryParams['includeIngredients'] = $ingredientsString;
                
                // For chicken specifically, we want to boost recipes with chicken as a main ingredient
                // This helps filter out recipes where chicken is just a minor component
                $queryParams['query'] = 'chicken'; 
                
                \Log::info("Chicken detected in ingredients, boosting chicken recipes");
            }
            
            \Log::info("Spoonacular query for $mealType", [
                'params' => array_diff_key($queryParams, ['apiKey' => ''])
            ]);
            
            // Make the API request
            $response = $client->request('GET', $url, [
                'query' => $queryParams
            ]);
            
            $searchResult = json_decode($response->getBody(), true);
            $recipes = $searchResult['results'] ?? [];
            
            // If no results, try with findByIngredients endpoint instead
            if (empty($recipes)) {
                \Log::info("No results from complexSearch, trying findByIngredients for $mealType");
                
                // Use findByIngredients endpoint which works better for ingredient searches
                $ingredientsUrl = "https://api.spoonacular.com/recipes/findByIngredients";
                $ingredientParams = [
                    'apiKey' => $apiKey,
                    'ingredients' => $ingredientsString,
                    'number' => $number + 2,  // Request more recipes
                    'ranking' => 1,  // Maximize used ingredients
                    'ignorePantry' => false,  // Include pantry items
                    'sort' => 'max-used-ingredients' // Prioritize recipes that use the most of our ingredients
                ];
                
                // Log the parameters for debugging
                \Log::info("Using findByIngredients with params:", array_diff_key($ingredientParams, ['apiKey' => '']));
                
                $response = $client->request('GET', $ingredientsUrl, [
                    'query' => $ingredientParams
                ]);
                
                $recipes = json_decode($response->getBody(), true) ?? [];
                
                // If we got results, fetch full recipe information for each one
                if (!empty($recipes)) {
                    foreach ($recipes as $index => $basicRecipe) {
                        $recipeId = $basicRecipe['id'];
                        try {
                            // Get detailed recipe information
                            $infoUrl = "https://api.spoonacular.com/recipes/{$recipeId}/information";
                            $infoResponse = $client->request('GET', $infoUrl, [
                                'query' => [
                                    'apiKey' => $apiKey,
                                    'includeNutrition' => true,
                                    'instructionsRequired' => true,
                                    'includeInstruction' => true
                                ]
                            ]);
                            
                            $recipeInfo = json_decode($infoResponse->getBody(), true);
                            \Log::info("Retrieved detailed recipe information for ID $recipeId: " . 
                                       (isset($recipeInfo['instructions']) ? "Has instructions" : "No instructions") . ", " .
                                       (isset($recipeInfo['analyzedInstructions']) ? count($recipeInfo['analyzedInstructions']) . " analyzed instructions" : "No analyzed instructions"));
                            
                            // Merge the detailed info into our recipe
                            $recipes[$index] = array_merge($basicRecipe, $recipeInfo);
                        } catch (\Exception $e) {
                            \Log::warning("Failed to get detailed info for recipe ID $recipeId: " . $e->getMessage());
                            // Keep the basic recipe info if detailed fetch fails
                        }
                    }
                }
            }
            
            // If still no results, try removing restrictions
            if (empty($recipes)) {
                \Log::info("No results from findByIngredients, trying with fewer restrictions for $mealType");
                
                // Try with just a basic search
                $queryParams = [
                    'apiKey' => $apiKey,
                    'query' => implode(' ', explode(',', $ingredientsString)),  // Use the ingredients as a search query
                    'number' => $number,
                    'instructionsRequired' => true,
                    'fillIngredients' => true,
                    'addRecipeInformation' => true
                ];
                
                $response = $client->request('GET', $url, [
                    'query' => $queryParams
                ]);
                
                $searchResult = json_decode($response->getBody(), true);
                $recipes = $searchResult['results'] ?? [];
            }
            
            return $recipes;
        } catch (\Exception $e) {
            \Log::error("Spoonacular API Error for $mealType", ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return [];
        }
    }
    
    /**
     * Extract ingredient list from API response and verify required ingredients are present
     */
    private function getIngredientsFromApi($apiRecipe, $userIngredients)
    {
        $ingredients = [];
        $foundIngredients = [];
        
        // Handle the extendedIngredients property (from complexSearch with addRecipeInformation=true)
        if (isset($apiRecipe['extendedIngredients']) && is_array($apiRecipe['extendedIngredients'])) {
            foreach ($apiRecipe['extendedIngredients'] as $ingredient) {
                $amount = $ingredient['amount'] ?? '';
                $unit = $ingredient['unit'] ?? '';
                $name = strtolower($ingredient['name'] ?? '');
                
                $ingredients[] = "$amount $unit $name";
                $foundIngredients[$name] = true;
            }
        } 
        // Handle the usedIngredients property (from findByIngredients endpoint)
        else if (isset($apiRecipe['usedIngredients']) && is_array($apiRecipe['usedIngredients'])) {
            foreach ($apiRecipe['usedIngredients'] as $ingredient) {
                $amount = $ingredient['amount'] ?? '';
                $unit = $ingredient['unit'] ?? '';
                $name = strtolower($ingredient['name'] ?? '');
                
                $ingredients[] = "$amount $unit $name";
                $foundIngredients[$name] = true;
            }
            
            // Also include missed ingredients
            if (isset($apiRecipe['missedIngredients']) && is_array($apiRecipe['missedIngredients'])) {
                foreach ($apiRecipe['missedIngredients'] as $ingredient) {
                    $amount = $ingredient['amount'] ?? '';
                    $unit = $ingredient['unit'] ?? '';
                    $name = strtolower($ingredient['name'] ?? '');
                    
                    $ingredients[] = "$amount $unit $name";
                    $foundIngredients[$name] = true;
                }
            }
        }
        // For recipes from complexSearch without extended info, use analyzedInstructions for ingredients
        else if (isset($apiRecipe['analyzedInstructions']) && is_array($apiRecipe['analyzedInstructions'])) {
            $ingredientSet = [];
            
            foreach ($apiRecipe['analyzedInstructions'] as $instruction) {
                if (isset($instruction['steps']) && is_array($instruction['steps'])) {
                    foreach ($instruction['steps'] as $step) {
                        if (isset($step['ingredients']) && is_array($step['ingredients'])) {
                            foreach ($step['ingredients'] as $ingredient) {
                                $name = strtolower($ingredient['name'] ?? '');
                                $ingredientSet[$name] = true;
                                $foundIngredients[$name] = true;
                            }
                        }
                    }
                }
            }
            
            foreach (array_keys($ingredientSet) as $name) {
                $ingredients[] = ucfirst($name);
            }
        }
        
        // Verify that chicken is included if it was requested
        if (is_array($userIngredients)) {
            $hasChicken = false;
            foreach ($userIngredients as $userIngredient) {
                if (stripos($userIngredient, 'chicken') !== false) {
                    $hasChicken = true;
                    break;
                }
            }
            
            if ($hasChicken) {
                $foundChicken = false;
                foreach ($foundIngredients as $name => $value) {
                    if (stripos($name, 'chicken') !== false) {
                        $foundChicken = true;
                        break;
                    }
                }
                
                // If chicken was requested but not found in ingredients, check the recipe title
                if (!$foundChicken && isset($apiRecipe['title'])) {
                    if (stripos($apiRecipe['title'], 'chicken') !== false) {
                        $foundChicken = true;
                        // Add chicken to the ingredients if it wasn't explicitly mentioned
                        $ingredients[] = "Chicken";
                        \Log::info("Added chicken to ingredients based on recipe title: " . $apiRecipe['title']);
                    }
                }
                
                if (!$foundChicken) {
                    \Log::warning("Recipe doesn't seem to include chicken despite being requested: " . ($apiRecipe['title'] ?? 'Unknown recipe'));
                }
            }
        }
        
        // If we still don't have ingredients from the API, just use the original user ingredients
        if (empty($ingredients)) {
            // Just use exactly what the user entered, with no additions
            if (!empty($userIngredients)) {
                foreach ($userIngredients as $ing) {
                    $ingredients[] = ucfirst($ing);
                }
            }
        }
        
        // If for some reason we still have no ingredients, add a placeholder
        if (empty($ingredients)) {
            $ingredients[] = "Ingredients not available";
        }
        
        return $ingredients;
    }
    
    /**
     * Convert cooking time to difficulty level
     */
    private function getDifficultyFromTime($minutes)
    {
        if ($minutes <= 20) {
            return 'Easy';
        } elseif ($minutes <= 45) {
            return 'Medium';
        } else {
            return 'Hard';
        }
    }
    
    /**
     * Generate a fallback recipe if API fails
     */
    private function generateFallbackRecipe($mealType, $ingredients)
    {
        $recipe = new \stdClass();
        $recipe->id = rand(1000, 9999);
        
        // Check if chicken is one of the requested ingredients
        $hasChicken = false;
        foreach ($ingredients as $ingredient) {
            if (stripos($ingredient, 'chicken') !== false) {
                $hasChicken = true;
                break;
            }
        }
        
        // Generate recipe title based on ingredients, prioritizing chicken if requested
        if ($hasChicken) {
            // Generate a chicken-focused recipe title
            $recipe->title = $this->generateChickenRecipeTitle($mealType);
        } else {
            $recipe->title = $this->generateRecipeTitle($mealType, $ingredients);
        }
        
        $recipe->description = $this->generateRecipeDescription($mealType, $ingredients);
        $recipe->cooking_time_display = rand(15, 45) . ' mins';
        $recipe->cooking_time = rand(15, 45);
        $recipe->difficulty = $this->getRandomDifficulty();
        $recipe->image_url = $this->getRandomImageUrl($mealType);
        $recipe->ingredients = $this->generateIngredientsList($ingredients);
        $recipe->is_favorite = false;
        $recipe->meal_type = $mealType;
        $recipe->instructions = json_encode([
            "Heat oil in a large pan over medium heat.",
            "Add your main ingredients and cook until golden brown.",
            "Season with salt, pepper, and your favorite spices.",
            "Add any liquids or sauces and simmer until everything is well cooked.",
            "Serve hot and enjoy your meal!"
        ]);
        $recipe->from_api = false;
        
        return $recipe;
    }
    
    /**
     * Generate a recipe title based on meal type and ingredients
     */
    private function generateRecipeTitle($mealType, $ingredients)
    {
        $mainIngredient = $ingredients[array_rand($ingredients)];
        $adjectives = ['Delicious', 'Quick', 'Easy', 'Homemade', 'Savory', 'Tasty', 'Healthy', 'Flavorful'];
        $adjective = $adjectives[array_rand($adjectives)];
        
        $formats = [
            "$adjective $mainIngredient " . ucfirst($mealType),
            ucfirst($mainIngredient) . " " . ucfirst($mealType) . " Delight",
            "$mealType $adjective $mainIngredient Bowl",
            "Easy $mainIngredient " . ucfirst($mealType),
            ucfirst($mealType) . " $mainIngredient Special"
        ];
        
        return $formats[array_rand($formats)];
    }
    
    /**
     * Generate a description for the recipe
     */
    private function generateRecipeDescription($mealType, $ingredients)
    {
        $mainIngredient = $ingredients[array_rand($ingredients)];
        $secondIngredient = $ingredients[array_rand($ingredients)];
        
        while ($secondIngredient === $mainIngredient && count($ingredients) > 1) {
            $secondIngredient = $ingredients[array_rand($ingredients)];
        }
        
        $descriptions = [
            "A delightful $mealType featuring $mainIngredient and $secondIngredient. Perfect for any day of the week.",
            "This $mealType dish combines the flavors of $mainIngredient with a hint of $secondIngredient for a satisfying meal.",
            "Enjoy this easy-to-make $mealType that showcases the best of $mainIngredient in every bite.",
            "A nutritious and tasty $mealType option using fresh $mainIngredient and $secondIngredient.",
            "This $mealType recipe transforms simple $mainIngredient into something extraordinary."
        ];
        
        return $descriptions[array_rand($descriptions)];
    }
    
    /**
     * Generate a list of ingredients for a fallback recipe
     */
    private function generateIngredientsList($userIngredients)
    {
        $ingredients = [];
        
        // Always include user-provided ingredients
        foreach ($userIngredients as $ingredient) {
            $ingredients[] = ucfirst($ingredient);
        }
        
        // Check if chicken is one of the requested ingredients
        $hasChicken = false;
        foreach ($userIngredients as $ingredient) {
            if (stripos($ingredient, 'chicken') !== false) {
                $hasChicken = true;
                break;
            }
        }
        
        // For chicken recipes, add chicken-specific ingredients
        if ($hasChicken) {
            // Add chicken if not already in the list (with different formats)
            $chickenAdded = false;
            foreach ($ingredients as $ingredient) {
                if (stripos($ingredient, 'chicken') !== false) {
                    $chickenAdded = true;
                    break;
                }
            }
            
            if (!$chickenAdded) {
                $chickenTypes = [
                    'Chicken breast',
                    'Chicken thighs',
                    'Whole chicken',
                    'Chicken drumsticks',
                    'Chicken wings',
                    'Ground chicken'
                ];
                
                $ingredients[] = $chickenTypes[array_rand($chickenTypes)];
            }
            
            // Add common ingredients used with chicken
            $commonWithChicken = [
                'Garlic',
                'Onion',
                'Olive oil',
                'Salt',
                'Pepper',
                'Lemon',
                'Paprika',
                'Herbs'
            ];
            
            // Add some common ingredients
            $numToAdd = min(4, count($commonWithChicken));
            $keys = array_rand($commonWithChicken, $numToAdd);
            if (!is_array($keys)) {
                $keys = [$keys];
            }
            
            foreach ($keys as $key) {
                $ingredients[] = $commonWithChicken[$key];
            }
        }
        // For non-chicken recipes, add some generic ingredients
        else {
            // Add some common ingredients
            $commonIngredients = [
                'Salt',
                'Pepper',
                'Olive oil',
                'Garlic',
                'Onion',
                'Butter',
                'Flour',
                'Sugar',
                'Milk'
            ];
            
            // Add a few common ingredients
            $numToAdd = min(3, count($commonIngredients));
            $keys = array_rand($commonIngredients, $numToAdd);
            if (!is_array($keys)) {
                $keys = [$keys];
            }
            
            foreach ($keys as $key) {
                $ingredients[] = $commonIngredients[$key];
            }
        }
        
        return array_unique($ingredients);
    }
    
    /**
     * Get a random measurement unit
     */
    private function getRandomUnit()
    {
        $units = ['cup', 'tablespoon', 'teaspoon', 'ounce', 'pound', 'gram'];
        return $units[array_rand($units)];
    }
    
    /**
     * Get a random difficulty level
     */
    private function getRandomDifficulty()
    {
        $difficulties = ['Easy', 'Medium', 'Hard'];
        $weights = [70, 25, 5]; // 70% Easy, 25% Medium, 5% Hard
        
        $rand = rand(1, 100);
        
        if ($rand <= $weights[0]) {
            return $difficulties[0];
        } elseif ($rand <= $weights[0] + $weights[1]) {
            return $difficulties[1];
        } else {
            return $difficulties[2];
        }
    }
    
    /**
     * Get a random image URL based on meal type
     */
    private function getRandomImageUrl($mealType)
    {
        $baseUrl = 'https://source.unsplash.com/featured/?food,';
        
        switch ($mealType) {
            case 'Breakfast':
                return $baseUrl . 'breakfast';
            case 'Lunch':
                return $baseUrl . 'lunch';
            case 'Dinner':
                return $baseUrl . 'dinner';
            case 'Supper':
                return $baseUrl . 'supper';
            default:
                return $baseUrl . 'meal';
        }
    }
    
    private function getGreeting($hour)
    {
        if ($hour >= 5 && $hour < 12) {
            return 'Good Morning';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'Good Afternoon';
        } elseif ($hour >= 17 && $hour < 22) {
            return 'Good Evening';
        } else {
            return 'Good Night';
        }
    }

    public function getRecipes(Request $request)
    {
        try {
            // Get the selected meal type from the request
            $mealType = $request->input('meal_type', 'Breakfast');
            
            // Get user's saved ingredients
            $user = auth()->user();
            $userIngredients = json_decode($request->input('ingredients', '[]'));
            
            // Prepare ingredients string
            $ingredientsString = implode(',', $userIngredients);
            
            // Get recipes from Spoonacular API - fix parameter order
            $apiRecipes = $this->getRecipesFromSpoonacular($ingredientsString, $mealType);
            
            // Convert API recipes to a format for the frontend
            $recipes = [];
            if (!empty($apiRecipes)) {
                foreach ($apiRecipes as $apiRecipe) {
                    $recipe = new \stdClass();
                    $recipe->id = $apiRecipe['id'] ?? rand(1000, 9999);
                    $recipe->title = $apiRecipe['title'] ?? '';
                    $recipe->description = $apiRecipe['summary'] ?? '';
                    $recipe->cooking_time_display = ($apiRecipe['readyInMinutes'] ?? 30) . ' mins'; // For display
                    $recipe->cooking_time = ($apiRecipe['readyInMinutes'] ?? 30); // For database
                    $recipe->difficulty = $this->getDifficultyFromTime($apiRecipe['readyInMinutes'] ?? 30);
                    $recipe->image_url = $apiRecipe['image'] ?? '';
                    $recipe->ingredients = $this->getIngredientsFromApi($apiRecipe, $userIngredients);
                    $recipe->is_favorite = false;
                    $recipe->meal_type = $mealType;
                    $recipe->spoonacular_source_url = $apiRecipe['sourceUrl'] ?? '';
                    
                    // Handle instructions in a more robust way
                    if (!empty($apiRecipe['analyzedInstructions']) && is_array($apiRecipe['analyzedInstructions'])) {
                        // Extract steps from analyzed instructions
                        $steps = [];
                        foreach ($apiRecipe['analyzedInstructions'] as $instruction) {
                            if (isset($instruction['steps']) && is_array($instruction['steps'])) {
                                foreach ($instruction['steps'] as $step) {
                                    if (isset($step['step']) && !empty($step['step'])) {
                                        $steps[] = $step['step'];
                                    }
                                }
                            }
                        }
                        
                        if (!empty($steps)) {
                            // If we have steps, convert them to JSON directly
                            $recipe->instructions = json_encode($steps);
                            \Log::info("Extracted " . count($steps) . " steps from analyzedInstructions for recipe {$recipe->title}");
                        } else if (!empty($apiRecipe['instructions'])) {
                            // If no analyzed steps but we have raw instructions text
                            $instructionText = strip_tags($apiRecipe['instructions']);
                            // Split by periods or newlines to create steps
                            $rawSteps = preg_split('/\.\s+|\n+/', $instructionText);
                            $filteredSteps = array_filter($rawSteps, function($step) {
                                return strlen(trim($step)) > 5; // Only keep substantial steps
                            });
                            
                            if (!empty($filteredSteps)) {
                                $recipe->instructions = json_encode(array_values($filteredSteps));
                                \Log::info("Extracted " . count($filteredSteps) . " steps from raw instructions for recipe {$recipe->title}");
                            } else {
                                $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                            }
                        } else {
                            $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                        }
                    } else if (!empty($apiRecipe['instructions'])) {
                        // Handle raw instruction text if analyzedInstructions is not available
                        $instructionText = strip_tags($apiRecipe['instructions']);
                        // Split by periods or newlines to create steps
                        $rawSteps = preg_split('/\.\s+|\n+/', $instructionText);
                        $filteredSteps = array_filter($rawSteps, function($step) {
                            return strlen(trim($step)) > 5; // Only keep substantial steps
                        });
                        
                        if (!empty($filteredSteps)) {
                            $recipe->instructions = json_encode(array_values($filteredSteps));
                            \Log::info("Extracted " . count($filteredSteps) . " steps from raw instructions text for recipe {$recipe->title}");
                        } else {
                            $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                        }
                    } else {
                        $recipe->instructions = json_encode(["No detailed instructions available for this recipe."]);
                    }
                    
                    $recipe->from_api = true;
                    
                    // Extract nutritional information if available
                    if (isset($apiRecipe['nutrition']) && isset($apiRecipe['nutrition']['nutrients'])) {
                        $nutrients = collect($apiRecipe['nutrition']['nutrients']);
                        
                        $recipe->calories = $nutrients->firstWhere('name', 'Calories')['amount'] ?? 0;
                        $recipe->protein = $nutrients->firstWhere('name', 'Protein')['amount'] ?? 0;
                        $recipe->carbs = $nutrients->firstWhere('name', 'Carbohydrates')['amount'] ?? 0;
                        $recipe->fats = $nutrients->firstWhere('name', 'Fat')['amount'] ?? 0;
                        
                        \Log::info("Extracted nutrition for {$recipe->title}: Calories: {$recipe->calories}, Protein: {$recipe->protein}, Carbs: {$recipe->carbs}, Fats: {$recipe->fats}");
                    } else {
                        // Set default values or estimate
                        $recipe->calories = $apiRecipe['calories'] ?? $this->estimateCalories($recipe->cooking_time);
                        $recipe->protein = $apiRecipe['protein'] ?? 0;
                        $recipe->carbs = $apiRecipe['carbs'] ?? 0;
                        $recipe->fats = $apiRecipe['fats'] ?? 0;
                        
                        \Log::info("No nutrition data for {$recipe->title}, using defaults");
                    }
                    
                    $recipes[] = $recipe;
                }
            }
            
            // Return recipes as a JSON response
            return response()->json([
                'success' => true,
                'recipes' => $recipes,
                'activeMealType' => $mealType,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting recipes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ]);
        }
    }

    /**
     * Estimate calories based on cooking time and meal type
     * If no nutrient data is available, this provides a reasonable default
     */
    private function estimateCalories($cookingTime)
    {
        // Extract minutes from cooking time string (e.g., "30 mins" -> 30)
        if (is_string($cookingTime)) {
            preg_match('/(\d+)/', $cookingTime, $matches);
            $minutes = isset($matches[1]) ? (int)$matches[1] : 30;
        } else {
            $minutes = is_numeric($cookingTime) ? (int)$cookingTime : 30;
        }
        
        // Basic estimation: longer cooking times generally means more calories
        // This is a very rough estimate!
        if ($minutes <= 15) {
            return rand(200, 400); // Quick snack or light meal
        } elseif ($minutes <= 30) {
            return rand(350, 600); // Average meal
        } elseif ($minutes <= 60) {
            return rand(500, 800); // Substantial meal
        } else {
            return rand(700, 1200); // Complex/heavy meal
        }
    }

    /**
     * Generate a chicken-focused recipe title
     */
    private function generateChickenRecipeTitle($mealType)
    {
        $chickenRecipes = [
            'Breakfast' => [
                'Chicken and Egg Breakfast Skillet',
                'Breakfast Chicken and Waffles',
                'Chicken Breakfast Burrito',
                'Chicken Frittata',
                'Chicken Hash Browns'
            ],
            'Lunch' => [
                'Grilled Chicken Salad',
                'Chicken Sandwich',
                'Chicken Wrap',
                'Chicken Noodle Soup',
                'Chicken Stir Fry',
                'Chicken Caesar Salad',
                'Buffalo Chicken Wrap'
            ],
            'Dinner' => [
                'Roast Chicken with Vegetables',
                'Chicken Parmesan',
                'Chicken Alfredo Pasta',
                'Lemon Garlic Roasted Chicken',
                'Chicken Curry',
                'Honey Garlic Chicken',
                'BBQ Chicken',
                'Chicken Cacciatore'
            ],
            'Supper' => [
                'Chicken Soup',
                'Chicken Quesadilla',
                'Chicken Pot Pie',
                'Chicken and Rice Bake',
                'Light Chicken Salad'
            ]
        ];
        
        // Get recipes for the specified meal type, or fall back to dinner recipes
        $recipes = $chickenRecipes[$mealType] ?? $chickenRecipes['Dinner'];
        
        // Return a random recipe title
        return $recipes[array_rand($recipes)];
    }
} 