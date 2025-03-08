<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecipeApiService;

class RecipeSuggestionController extends Controller
{
    protected $recipeApiService;

    public function __construct(RecipeApiService $recipeApiService)
    {
        $this->recipeApiService = $recipeApiService;
    }

    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            $user = new \App\Models\User();
            $user->name = "Guest User";
            $user->age = "-";
        }
        
        // Get initial random recipes
        $recipes = $this->recipeApiService->getRandomRecipes();
        
        return view('recipe_suggestions', [
            'user' => $user,
            'recipes' => $recipes['recipes'] ?? []
        ]);
    }

    /**
     * Search for recipes based on user preferences
     */
    public function search(Request $request)
    {
        try {
            $preferences = [
                'query' => $request->input('query'),
                'cuisine' => $request->input('cuisine'),
                'mealType' => $request->input('mealType'),
                'cookingTime' => $request->input('cookingTime'),
                'diet' => $request->input('diet'),
                'ingredients' => $request->input('ingredients'),
            ];

            // Filter out empty preferences
            $preferences = array_filter($preferences, function($value) {
                return !is_null($value) && $value !== '';
            });

            // Log the search request
            \Log::info('Recipe search request', [
                'user_id' => Auth::id() ?? 'guest',
                'preferences' => $preferences,
                'ip' => $request->ip()
            ]);

            // If no preferences are provided, return an error
            if (empty($preferences)) {
                return response()->json([
                    'results' => [],
                    'error' => 'No search criteria provided. Please specify at least one search parameter.'
                ]);
            }

            $results = $this->recipeApiService->searchRecipes($preferences);
            
            // Log the search results
            \Log::info('Recipe search results', [
                'user_id' => Auth::id() ?? 'guest',
                'count' => count($results['results'] ?? []),
                'total' => $results['totalResults'] ?? 0
            ]);
            
            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Error in recipe search', [
                'user_id' => Auth::id() ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'results' => [],
                'error' => 'An error occurred while searching for recipes. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get recipe details by ID
     */
    public function getRecipeDetails($id, Request $request)
    {
        try {
            // Validate the ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'error' => 'Invalid recipe ID'
                ], 400);
            }
            
            // Log the recipe details request
            \Log::info('Recipe details request', [
                'user_id' => Auth::id() ?? 'guest',
                'recipe_id' => $id,
                'source' => $request->input('source')
            ]);

            // Check if we should fetch from database
            if ($request->input('source') === 'database') {
                // Fetch from the database directly
                $recipe = \App\Models\Recipe::find($id);
                
                if (!$recipe) {
                    return response()->json([
                        'error' => 'Recipe not found in database'
                    ], 404);
                }
                
                return response()->json($recipe);
            }
            
            // Otherwise fetch from API
            $recipe = $this->recipeApiService->getRecipeById($id);
            
            // Check if the recipe was found
            if (isset($recipe['error'])) {
                return response()->json($recipe, 404);
            }
            
            return response()->json($recipe);
        } catch (\Exception $e) {
            \Log::error('Error getting recipe details', [
                'user_id' => Auth::id() ?? 'guest',
                'recipe_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'An error occurred while fetching recipe details. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get random recipe suggestions
     */
    public function getRandomRecipes(Request $request)
    {
        try {
            $preferences = [
                'cuisine' => $request->input('cuisine'),
                'mealType' => $request->input('mealType'),
                'diet' => $request->input('diet'),
                'cookingTime' => $request->input('cookingTime'),
                'ingredients' => $request->input('ingredients'),
            ];

            // Filter out empty preferences
            $preferences = array_filter($preferences, function($value) {
                return !is_null($value) && $value !== '';
            });

            // Log the random recipes request
            \Log::info('Random recipes request', [
                'user_id' => Auth::id() ?? 'guest',
                'preferences' => $preferences,
                'ip' => $request->ip()
            ]);

            $recipes = $this->recipeApiService->getRandomRecipes($preferences);
            
            // Log the random recipes results
            \Log::info('Random recipes results', [
                'user_id' => Auth::id() ?? 'guest',
                'count' => count($recipes['recipes'] ?? [])
            ]);
            
            return response()->json($recipes);
        } catch (\Exception $e) {
            \Log::error('Error getting random recipes', [
                'user_id' => Auth::id() ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'recipes' => [],
                'error' => 'An error occurred while fetching random recipes. Please try again later.'
            ], 500);
        }
    }
} 