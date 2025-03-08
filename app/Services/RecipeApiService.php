<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\ApiRateLimiter;
use GuzzleHttp\Exception\RequestException;

class RecipeApiService
{
    protected $apiKey;
    protected $baseUrl;
    protected $rateLimiter;
    protected $client;

    public function __construct(ApiRateLimiter $rateLimiter)
    {
        $this->apiKey = config('services.spoonacular.key');
        $this->baseUrl = 'https://api.spoonacular.com';
        $this->rateLimiter = $rateLimiter;
        $this->client = Http::baseUrl($this->baseUrl);
    }

    /**
     * Optimize recipe data to reduce its size before caching
     * 
     * @param array $recipe The recipe data to optimize
     * @return array The optimized recipe data
     */
    protected function optimizeRecipeData(array $recipe): array
    {
        // Remove unnecessary fields that take up a lot of space
        $fieldsToRemove = [
            'analyzedInstructions', // We'll extract just the steps
            'summary', // Often contains HTML which is large
            'winePairing',
            'occasions',
            'originalId',
            'spoonacularSourceUrl',
            'license',
            'creditsText',
            'sourceName',
            'sourceUrl',
        ];
        
        foreach ($fieldsToRemove as $field) {
            if (isset($recipe[$field])) {
                unset($recipe[$field]);
            }
        }
        
        // Extract just the steps from analyzedInstructions
        if (isset($recipe['analyzedInstructions']) && !empty($recipe['analyzedInstructions'])) {
            $steps = [];
            foreach ($recipe['analyzedInstructions'] as $instruction) {
                if (isset($instruction['steps'])) {
                    foreach ($instruction['steps'] as $step) {
                        $steps[] = $step['step'];
                    }
                }
            }
            $recipe['steps'] = $steps;
            Log::debug('Recipe instructions extracted from analyzedInstructions', [
                'recipe_id' => $recipe['id'] ?? 'unknown',
                'steps_count' => count($steps)
            ]);
            unset($recipe['analyzedInstructions']);
        } else if (isset($recipe['instructions']) && !empty($recipe['instructions'])) {
            // If we have plain text instructions but no analyzedInstructions
            // Split by periods or line breaks to create steps
            $instructionText = $recipe['instructions'];
            $steps = preg_split('/\.\s+|\n+/', $instructionText, -1, PREG_SPLIT_NO_EMPTY);
            $steps = array_map('trim', $steps);
            // Filter out empty steps and add periods if missing
            $steps = array_filter($steps, function($step) {
                return !empty($step);
            });
            $steps = array_map(function($step) {
                return rtrim($step, '.') . '.';
            }, $steps);
            $recipe['steps'] = $steps;
            Log::debug('Recipe instructions extracted from plain text', [
                'recipe_id' => $recipe['id'] ?? 'unknown',
                'steps_count' => count($steps)
            ]);
        } else {
            Log::warning('No recipe instructions found', [
                'recipe_id' => $recipe['id'] ?? 'unknown',
                'has_analyzedInstructions' => isset($recipe['analyzedInstructions']),
                'has_instructions' => isset($recipe['instructions'])
            ]);
        }
        
        // Optimize extended ingredients
        if (isset($recipe['extendedIngredients'])) {
            $optimizedIngredients = [];
            foreach ($recipe['extendedIngredients'] as $ingredient) {
                $optimizedIngredients[] = [
                    'id' => $ingredient['id'] ?? null,
                    'name' => $ingredient['name'] ?? '',
                    'amount' => $ingredient['amount'] ?? 0,
                    'unit' => $ingredient['unit'] ?? '',
                    'image' => $ingredient['image'] ?? null,
                ];
            }
            $recipe['extendedIngredients'] = $optimizedIngredients;
        }
        
        // Optimize nutrition data
        if (isset($recipe['nutrition']) && isset($recipe['nutrition']['nutrients'])) {
            $importantNutrients = ['Calories', 'Protein', 'Carbohydrates', 'Fat', 'Fiber', 'Sugar'];
            $optimizedNutrients = [];
            
            foreach ($recipe['nutrition']['nutrients'] as $nutrient) {
                if (in_array($nutrient['name'], $importantNutrients)) {
                    $optimizedNutrients[] = [
                        'name' => $nutrient['name'],
                        'amount' => $nutrient['amount'],
                        'unit' => $nutrient['unit'],
                    ];
                }
            }
            
            $recipe['nutrition']['nutrients'] = $optimizedNutrients;
            
            // Remove other nutrition data we don't need
            $nutritionFieldsToRemove = ['properties', 'flavonoids', 'ingredients', 'caloricBreakdown', 'weightPerServing'];
            foreach ($nutritionFieldsToRemove as $field) {
                if (isset($recipe['nutrition'][$field])) {
                    unset($recipe['nutrition'][$field]);
                }
            }
        }
        
        return $recipe;
    }
    
    /**
     * Optimize a collection of recipes
     * 
     * @param array $recipes Array of recipe data
     * @return array Optimized recipes
     */
    protected function optimizeRecipes(array $recipes): array
    {
        $optimized = [];
        foreach ($recipes as $recipe) {
            $optimized[] = $this->optimizeRecipeData($recipe);
        }
        return $optimized;
    }

    /**
     * Search for recipes based on user preferences
     *
     * @param array $preferences
     * @return array
     */
    public function searchRecipes($preferences = [])
    {
        try {
            // Check rate limiting before making the request
            $endpoint = 'recipes/complexSearch';
            if (!$this->rateLimiter->allowRequest($endpoint)) {
                Log::warning('API rate limit exceeded for recipe search', ['preferences' => $preferences]);
                return [
                    'results' => [],
                    'error' => 'API rate limit exceeded. Please try again in a minute.',
                    'remainingRequests' => $this->rateLimiter->remainingRequests($endpoint),
                    'resetIn' => $this->rateLimiter->timeUntilReset($endpoint)
                ];
            }

            // Build query parameters
            $params = [
                'apiKey' => $this->apiKey,
                'number' => isset($preferences['pageSize']) ? $preferences['pageSize'] : 100, // Maximum allowed by Spoonacular API
                'offset' => isset($preferences['offset']) ? $preferences['offset'] : 0, // Add offset for pagination
                'addRecipeInformation' => 'true',
                'fillIngredients' => 'true',
                'addRecipeNutrition' => 'true',
                'instructionsRequired' => 'true'
            ];

            // Add search query if provided
            if (isset($preferences['query']) && !empty($preferences['query'])) {
                $params['query'] = $preferences['query'];
            }

            // Add cuisine filter if provided
            if (isset($preferences['cuisine']) && !empty($preferences['cuisine'])) {
                $params['cuisine'] = $preferences['cuisine'];
            }

            // Add meal type filter if provided
            if (isset($preferences['mealType']) && !empty($preferences['mealType'])) {
                $params['type'] = $preferences['mealType'];
            }

            // Add max cooking time filter if provided
            if (isset($preferences['cookingTime']) && !empty($preferences['cookingTime'])) {
                $params['maxReadyTime'] = $preferences['cookingTime'];
            }

            // Add diet filter if provided
            if (isset($preferences['diet']) && !empty($preferences['diet'])) {
                $params['diet'] = $preferences['diet'];
            }

            // Add ingredients filter if provided
            if (isset($preferences['ingredients']) && !empty($preferences['ingredients'])) {
                $params['includeIngredients'] = $preferences['ingredients'];
            }

            // Make the API request
            $response = Http::timeout(30)
                ->get("{$this->baseUrl}/{$endpoint}", $params);

            // Log the API response status
            Log::info('Recipe search API response', [
                'status' => $response->status(),
                'success' => $response->successful(),
                'totalResults' => $response->json('totalResults'),
                'offset' => $params['offset']
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json();
                
                // Optimize the recipe data to reduce size
                if (isset($data['results']) && is_array($data['results'])) {
                    $data['results'] = $this->optimizeRecipes($data['results']);
                }
                
                // Add total results count to the response if not present
                if (!isset($data['totalResults'])) {
                    $data['totalResults'] = count($data['results']);
                }

                return $data;
            } else {
                Log::error('Recipe search API error', [
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
                
                return [
                    'results' => [],
                    'error' => 'Failed to search recipes. API returned status: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception in recipe search', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => "An unexpected error occurred: " . $e->getMessage()
            ];
        }
    }

    /**
     * Get detailed recipe information by ID
     *
     * @param int $id
     * @return array
     */
    public function getRecipeById($id)
    {
        // Create a cache key for this recipe ID
        $cacheKey = "recipe_details:{$id}";
        
        // Check if we have cached results - use file cache for large responses
        if (Cache::store('file')->has($cacheKey)) {
            Log::info('Returning cached recipe details', ['id' => $id]);
            return Cache::store('file')->get($cacheKey);
        }
        
        // Check rate limiting before making the request
        $endpoint = 'recipes/' . $id . '/information';
        if (!$this->rateLimiter->allowRequest($endpoint)) {
            Log::warning('API rate limit exceeded for recipe details', ['id' => $id]);
            return [
                'error' => 'API rate limit exceeded. Please try again in a minute.',
                'remainingRequests' => $this->rateLimiter->remainingRequests($endpoint),
                'resetIn' => $this->rateLimiter->timeUntilReset($endpoint)
            ];
        }
        
        // Log the request
        Log::info('Getting recipe details', ['id' => $id]);
        
        try {
            // Make the API request with a timeout
            $response = Http::timeout(15)
                ->get("{$this->baseUrl}/{$endpoint}", [
                    'apiKey' => $this->apiKey,
                    'includeNutrition' => 'true',
                    'instructionsRequired' => 'true'
                ]);
            
            // Log the API response status
            Log::info('Recipe details API response', [
                'id' => $id,
                'status' => $response->status(),
                'success' => $response->successful()
            ]);
            
            // Check if the request was successful
            if ($response->successful()) {
                $recipe = $response->json();
                
                // Optimize the recipe data to reduce size
                $recipe = $this->optimizeRecipeData($recipe);
                
                // Cache the results for 1 hour using file cache
                Cache::store('file')->put($cacheKey, $recipe, now()->addHour());
                
                return $recipe;
            } else {
                // Log the error response
                Log::error('Recipe details API error', [
                    'id' => $id,
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to get recipe details. API returned status: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Exception in getting recipe details', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'An error occurred while getting recipe details: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get random recipes
     *
     * @param array $preferences
     * @return array
     */
    public function getRandomRecipes(array $preferences = [])
    {
        // Create a cache key based on the preferences
        $cacheKey = 'random_recipes:' . md5(json_encode($preferences));
        
        // Check if we have cached results - use file cache for large responses
        if (Cache::store('file')->has($cacheKey)) {
            Log::info('Returning cached random recipes', ['preferences' => $preferences]);
            return Cache::store('file')->get($cacheKey);
        }
        
        // Check rate limiting before making the request
        $endpoint = 'recipes/random';
        if (!$this->rateLimiter->allowRequest($endpoint)) {
            Log::warning('API rate limit exceeded for random recipes', ['preferences' => $preferences]);
            return [
                'recipes' => [],
                'error' => 'API rate limit exceeded. Please try again in a minute.',
                'remainingRequests' => $this->rateLimiter->remainingRequests($endpoint),
                'resetIn' => $this->rateLimiter->timeUntilReset($endpoint)
            ];
        }
        
        // Log the request parameters
        Log::info('Getting random recipes with preferences', ['preferences' => $preferences]);
        
        try {
            // Prepare the API parameters
            $params = [
                'apiKey' => $this->apiKey,
                'number' => 3, // Changed from 6 to 3 for fewer random recipes
                'addRecipeInformation' => 'true',
                'fillIngredients' => 'true',
                'addRecipeNutrition' => 'true',
                'instructionsRequired' => 'true'
            ];
            
            // Add any preferences as filters
            if (isset($preferences['cuisine']) && !empty($preferences['cuisine'])) {
                $params['tags'] = $preferences['cuisine'];
            }
            
            if (isset($preferences['mealType']) && !empty($preferences['mealType'])) {
                $params['tags'] = isset($params['tags']) 
                    ? $params['tags'] . ',' . $preferences['mealType'] 
                    : $preferences['mealType'];
            }
            
            if (isset($preferences['diet']) && !empty($preferences['diet'])) {
                $params['tags'] = isset($params['tags']) 
                    ? $params['tags'] . ',' . $preferences['diet'] 
                    : $preferences['diet'];
            }
            
            // Make the API request with a timeout
            $response = Http::timeout(15)
                ->get("{$this->baseUrl}/{$endpoint}", $params);
            
            // Log the API response status
            Log::info('Random recipes API response', [
                'status' => $response->status(),
                'success' => $response->successful(),
                'count' => count($response->json('recipes', []))
            ]);
            
            // Check if the request was successful
            if ($response->successful()) {
                $results = $response->json();
                
                // Check if we got any recipes back
                if (empty($results['recipes'])) {
                    Log::warning('No random recipes found with the given preferences', [
                        'preferences' => $preferences
                    ]);
                    
                    return [
                        'recipes' => [],
                        'error' => 'No recipes found with the given preferences. Try with fewer filters.'
                    ];
                }
                
                // Optimize the recipe data to reduce size
                if (isset($results['recipes']) && is_array($results['recipes'])) {
                    $results['recipes'] = $this->optimizeRecipes($results['recipes']);
                }
                
                // Cache the results for 15 minutes using file cache
                Cache::store('file')->put($cacheKey, $results, now()->addMinutes(15));
                
                return $results;
            } else {
                // Log the error response
                Log::error('Random recipes API error', [
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
                
                return [
                    'recipes' => [],
                    'error' => 'Failed to get random recipes. API returned status: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Exception in getting random recipes', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'recipes' => [],
                'error' => 'An error occurred while getting random recipes: ' . $e->getMessage()
            ];
        }
    }
} 