<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFavorite;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserFavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $recipeApiId = $request->recipe_id;
            $recipeData = $request->recipe_data;

            Log::info('Toggle favorite request received', [
                'user_id' => $user->id,
                'recipe_api_id' => $recipeApiId,
                'recipe_data' => $recipeData
            ]);

            // Validate required data
            if (!$recipeApiId) {
                throw new \Exception('Recipe ID is required');
            }

            if (!$recipeData || !isset($recipeData['title'])) {
                throw new \Exception('Invalid recipe data provided');
            }

            // Enhanced recipe existence check - multiple strategies
            $recipe = null;
            
            // Strategy 1: Check by ID (if the API ID is numeric and might be a direct ID)
            if (is_numeric($recipeApiId)) {
                $recipe = Recipe::find($recipeApiId);
            }
            
            // Strategy 2: Check by source_url containing API ID
            if (!$recipe) {
                $recipe = Recipe::where('source_url', 'LIKE', "%$recipeApiId%")->first();
            }
            
            // Strategy 3: Check by title (additional fallback)
            if (!$recipe && isset($recipeData['title'])) {
                $recipe = Recipe::where('title', $recipeData['title'])->first();
            }
            
            // If recipe still doesn't exist, create it
            if (!$recipe) {
                Log::info('Creating new recipe', [
                    'recipe_api_id' => $recipeApiId,
                    'recipe_data' => $recipeData
                ]);

                try {
                    // Create new recipe if it doesn't exist
                    // Store the API ID in the source_url field temporarily
                    $sourceUrl = $recipeData['sourceUrl'] ?? '';
                    $sourceUrl = $sourceUrl ? $sourceUrl : "api_id:$recipeApiId";
                    
                    $recipe = Recipe::create([
                        'title' => $recipeData['title'],
                        'description' => $recipeData['description'] ?? null,
                        'ingredients' => is_array($recipeData['ingredients']) ? $recipeData['ingredients'] : [],
                        'instructions' => $this->formatInstructions($recipeData['instructions'] ?? []),
                        'cooking_time' => intval($recipeData['cooking_time'] ?? $recipeData['readyInMinutes'] ?? 0),
                        'calories' => isset($recipeData['calories']) ? intval($recipeData['calories']) : null,
                        'protein' => isset($recipeData['protein']) ? floatval($recipeData['protein']) : null,
                        'carbs' => isset($recipeData['carbs']) ? floatval($recipeData['carbs']) : null,
                        'fats' => isset($recipeData['fats']) ? floatval($recipeData['fats']) : null,
                        'diet_type' => $this->mapDietType($recipeData['diet_type'] ?? $recipeData['dietType'] ?? null),
                        'image_url' => $recipeData['image'] ?? '',
                        'source_url' => $sourceUrl
                    ]);

                    Log::info('Recipe created successfully', ['recipe_id' => $recipe->id, 'api_id' => $recipeApiId]);
                } catch (\Exception $e) {
                    Log::error('Failed to create recipe', [
                        'error' => $e->getMessage(),
                        'recipe_data' => $recipeData
                    ]);
                    throw new \Exception('Failed to create recipe: ' . $e->getMessage());
                }
            }

            // Check if favorite exists - use the database recipe ID
            $favorite = UserFavorite::where('user_id', $user->id)
                ->where('recipe_id', $recipe->id)
                ->first();

            if ($favorite) {
                $favorite->delete();
                $status = 'removed';
                Log::info('Favorite removed', [
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                    'api_id' => $recipeApiId
                ]);
            } else {
                try {
                    UserFavorite::create([
                        'user_id' => $user->id,
                        'recipe_id' => $recipe->id
                    ]);
                    $status = 'added';
                    Log::info('Favorite added', [
                        'user_id' => $user->id,
                        'recipe_id' => $recipe->id,
                        'api_id' => $recipeApiId
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create favorite', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'recipe_id' => $recipe->id,
                        'api_id' => $recipeApiId
                    ]);
                    throw new \Exception('Failed to create favorite: ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'status' => $status,
                'message' => $status === 'added' ? 'Recipe added to favorites' : 'Recipe removed from favorites'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in toggle favorite', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update favorites: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $user = Auth::user();
        
        Log::info('Loading favorites for user', ['user_id' => $user->id]);
        
        $favorites = $user->favoriteRecipes()
            ->orderBy('user_favorites.created_at', 'desc')
            ->paginate(12);

        Log::info('Favorites loaded', [
            'user_id' => $user->id,
            'count' => $favorites->count()
        ]);

        return view('favorites.index', [
            'favorites' => $favorites
        ]);
    }

    /**
     * Map external diet types to our database enum values
     * 
     * @param string|null $dietType
     * @return string
     */
    private function mapDietType($dietType)
    {
        if (!$dietType) {
            return 'omnivore'; // Default value
        }
        
        $dietType = strtolower($dietType);
        
        // Map to our allowed enum values
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
     * Remove a recipe from favorites directly
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        try {
            $user = Auth::user();
            $recipeId = $request->recipe_id;
            
            Log::info('Remove favorite request received', [
                'user_id' => $user->id,
                'recipe_id' => $recipeId
            ]);
            
            // Validate input
            if (!$recipeId) {
                throw new \Exception('Recipe ID is required');
            }
            
            // Find and delete the favorite
            $deleted = UserFavorite::where('user_id', $user->id)
                ->where('recipe_id', $recipeId)
                ->delete();
                
            if ($deleted) {
                Log::info('Favorite removed successfully', [
                    'user_id' => $user->id,
                    'recipe_id' => $recipeId
                ]);
                
                return response()->json([
                    'status' => 'removed',
                    'message' => 'Recipe removed from favorites'
                ]);
            } else {
                Log::warning('Favorite not found for removal', [
                    'user_id' => $user->id,
                    'recipe_id' => $recipeId
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Favorite not found'
                ], 404);
            }
            
        } catch (\Exception $e) {
            Log::error('Error removing favorite', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'recipe_id' => $request->recipe_id ?? null
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to remove from favorites: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format instructions to ensure it's always a valid array
     * 
     * @param mixed $instructions
     * @return array
     */
    private function formatInstructions($instructions)
    {
        // If it's already an array with content, return it
        if (is_array($instructions) && count($instructions) > 0) {
            return $instructions;
        }
        
        // If it's a string, convert it to an array item
        if (is_string($instructions) && strlen(trim($instructions)) > 0) {
            return [trim($instructions)];
        }
        
        // Default fallback
        return ["No detailed instructions available for this recipe."];
    }
} 