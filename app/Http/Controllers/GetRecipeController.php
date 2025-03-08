<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\GetRecipe;
use App\Models\Recipe;

class GetRecipeController extends Controller
{
    /**
     * Display a listing of the user's get recipes.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your recipes.');
        }
        
        $getRecipes = $user->getRecipes()
            ->with('recipe')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('get_recipes.index', [
            'getRecipes' => $getRecipes
        ]);
    }
    
    /**
     * Add a recipe to the user's get recipes.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipe(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log the request
            Log::info('Get recipe request', [
                'user_id' => $user->id,
                'data' => $request->all()
            ]);
            
            // Validate request data
            $validated = $request->validate([
                'recipe_id' => 'required',
                'title' => 'required|string|max:255',
            ]);
            
            // Enhanced recipe existence check
            // First, try to find by direct ID
            $recipe = Recipe::find($validated['recipe_id']);
            
            // If not found by ID, check if we have a recipe with this API ID in source_url
            if (!$recipe && is_numeric($validated['recipe_id'])) {
                $recipe = Recipe::where('source_url', 'LIKE', "%{$validated['recipe_id']}%")->first();
            }
            
            // If still not found, check by title (as an additional fallback)
            if (!$recipe) {
                $recipe = Recipe::where('title', $validated['title'])->first();
            }
            
            // If recipe still doesn't exist, create it
            if (!$recipe) {
                // Recipe doesn't exist in our database, so we need to create it from the data
                Log::info('Creating new recipe', [
                    'recipe_id' => $validated['recipe_id'],
                    'title' => $validated['title']
                ]);
                
                // Log the request with detailed recipe data for debugging
                Log::info('Get recipe request data', [
                    'user_id' => $user->id,
                    'title' => $request->input('title'),
                    'instructions_type' => gettype($request->input('instructions')),
                    'instructions_sample' => is_array($request->input('instructions')) 
                        ? json_encode(array_slice($request->input('instructions'), 0, 2)) 
                        : substr($request->input('instructions', ''), 0, 100)
                ]);
                
                // Make sure instructions is properly formatted as a JSON array
                $instructions = $request->input('instructions');
                if (empty($instructions)) {
                    $instructions = ["No detailed instructions available for this recipe."];
                } elseif (is_string($instructions)) {
                    // Try to decode if it's a JSON string
                    $decoded = json_decode($instructions, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $instructions = $decoded;
                    } else {
                        // If it's a plain string, make it the first step
                        $instructions = [$instructions];
                    }
                } elseif (!is_array($instructions)) {
                    // If it's not a string or array, use default
                    $instructions = ["No detailed instructions available for this recipe."];
                }
                
                // Process cooking time to ensure it's an integer
                $cookingTime = $request->input('cooking_time');
                if (is_string($cookingTime) && preg_match('/(\d+)\s*mins?/i', $cookingTime, $matches)) {
                    $cookingTime = (int)$matches[1];
                    Log::info('Extracted cooking time from string', [
                        'original' => $request->input('cooking_time'),
                        'extracted' => $cookingTime
                    ]);
                } else {
                    $cookingTime = (int)$cookingTime;
                }
                
                // Create recipe with properly formatted instructions
                $recipe = Recipe::create([
                    'title' => $request->input('title'),
                    'description' => $request->input('description', ''),
                    'ingredients' => $request->input('ingredients', []),
                    'instructions' => $instructions,
                    'cooking_time' => $cookingTime,
                    'calories' => $request->input('calories', 0),
                    'protein' => $request->input('protein', 0),
                    'carbs' => $request->input('carbs', 0),
                    'fats' => $request->input('fats', 0),
                    'diet_type' => $this->mapDietType($request->input('diet_type', 'omnivore')),
                    'meal_type' => $request->input('meal_type', 'dinner'),
                    'image_url' => $request->input('image', ''),
                    'source_url' => $request->input('source_url', '') ?: "api_id:{$validated['recipe_id']}"
                ]);
                
                Log::info('Created new recipe for get recipe', [
                    'recipe_id' => $recipe->id,
                    'title' => $recipe->title
                ]);
            } else {
                Log::info('Found existing recipe', [
                    'recipe_id' => $recipe->id,
                    'title' => $recipe->title
                ]);
            }
            
            // Check if this recipe is already in the user's get recipes
            $existingGetRecipe = GetRecipe::where('user_id', $user->id)
                ->where('recipe_id', $recipe->id)
                ->first();
                
            if ($existingGetRecipe) {
                // Recipe is already in their get recipes
                Log::info('Recipe already in get recipes', [
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id
                ]);
                
                return response()->json([
                    'status' => 'exists',
                    'message' => 'Recipe is already in your collection',
                    'get_recipe' => $existingGetRecipe
                ]);
            } else {
                // Add to get recipes
                $getRecipe = GetRecipe::create([
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                    'notes' => $request->input('notes', '')
                ]);
                
                Log::info('Added recipe to get recipes', [
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                    'get_recipe_id' => $getRecipe->id
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Recipe added to your collection',
                    'get_recipe' => $getRecipe
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error adding recipe to get recipes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to add recipe to your collection: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove a recipe from the user's collection
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'You must be logged in to remove recipes from your collection'
                ], 401);
            }
            
            $getRecipeId = $request->input('id');
            
            if (!$getRecipeId) {
                return response()->json([
                    'error' => 'No recipe ID provided'
                ], 400);
            }
            
            // Log the request
            Log::info('Remove recipe from collection request', [
                'user_id' => $user->id,
                'get_recipe_id' => $getRecipeId
            ]);
            
            // Find the get recipe entry
            $getRecipe = GetRecipe::where('id', $getRecipeId)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$getRecipe) {
                return response()->json([
                    'error' => 'Recipe not found in your collection'
                ], 404);
            }
            
            // Delete the entry
            $getRecipe->delete();
            
            Log::info('Recipe removed from collection', [
                'user_id' => $user->id,
                'get_recipe_id' => $getRecipeId
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Recipe removed from your collection'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error removing recipe from collection', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to remove recipe from your collection: ' . $e->getMessage()
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
} 