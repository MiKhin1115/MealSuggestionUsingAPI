<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CalorieCalculatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalorieCalculatorController extends Controller
{
    protected $calorieCalculatorService;

    public function __construct(CalorieCalculatorService $calorieCalculatorService)
    {
        $this->calorieCalculatorService = $calorieCalculatorService;
    }

    /**
     * Display the calorie calculator page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's saved recipes
        $recipes = $user->favoriteRecipes()->get();
        
        // Get user's profile data for personalized calculations
        $questions1 = $user->questions1;
        $questions2 = $user->questions2;
        $questions3 = $user->questions3;
        
        return view('calorie_calculator', [
            'recipes' => $recipes,
            'user' => $user,
            'questions1' => $questions1,
            'questions2' => $questions2,
            'questions3' => $questions3
        ]);
    }

    /**
     * Calculate the user's daily calorie needs based on their profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDailyNeeds(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get user profile data
            $questions1 = $user->questions1;
            
            if (!$questions1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User profile data is incomplete. Please complete your profile.'
                ], 400);
            }
            
            // Get user health goal from questions3
            $questions3 = $user->questions3;
            $healthGoal = $questions3 ? $questions3->health_goal : 'maintain';
            
            // Get activity level from request
            $activityLevel = $request->input('activity_level', 'moderate');
            
            Log::info('Calculating daily needs', [
                'user_id' => $user->id,
                'activity_level' => $activityLevel,
                'health_goal' => $healthGoal
            ]);
            
            // Get all necessary user stats for BMR calculation
            $userStats = [
                'weight' => $questions1->weight,
                'height' => $questions1->height,
                'gender' => $user->gender,
                'activity_level' => $activityLevel,
                'health_goal' => $healthGoal
            ];
            
            // Log the user stats for debugging
            Log::info('User stats for calculation', $userStats);
            
            // Calculate daily calorie needs (which will calculate BMR internally)
            $calorieNeeds = $this->calorieCalculatorService->calculateDailyCalorieNeeds(
                $user,
                $userStats
            );
            
            // If there was a calculation error
            if (!isset($calorieNeeds['recommended_calories']) || $calorieNeeds['is_default']) {
                Log::warning('Using default calorie recommendation', [
                    'user_id' => $user->id,
                    'reason' => $calorieNeeds['message'] ?? 'Unknown error in calculation'
                ]);
            }
            
            // Get total calories
            $totalCalories = $calorieNeeds['recommended_calories'] ?? 0;
            
            // Prepare response data
            $responseData = [
                'bmr' => $calorieNeeds['bmr'] ?? 0,
                'total_calories' => $totalCalories,
                'activity_level' => $activityLevel,
                'health_goal' => $healthGoal,
                'is_default' => $calorieNeeds['is_default'] ?? false,
                'activity_multiplier' => $calorieNeeds['activity_multiplier'] ?? 1.0,
                'goal_adjustment' => $calorieNeeds['goal_adjustment'] ?? 0
            ];
            
            Log::info('Daily needs calculation complete', [
                'total_calories' => $responseData['total_calories'],
                'activity_level_used' => $calorieNeeds['activity_level_used'] ?? $activityLevel,
                'activity_multiplier' => $calorieNeeds['activity_multiplier'] ?? 1.0
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => $responseData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error calculating daily calorie needs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to calculate daily calorie needs: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Calculate total calories from selected recipes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateRecipeCalories(Request $request)
    {
        try {
            // Get recipe IDs from request
            $recipeIds = $request->input('recipe_ids', []);
            
            Log::info('Calculating recipe calories', [
                'recipe_ids' => $recipeIds,
                'count' => count($recipeIds)
            ]);
            
            if (empty($recipeIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No recipes selected'
                ], 400);
            }
            
            // Get the actual recipe objects to access all nutritional data
            $recipes = \App\Models\Recipe::whereIn('id', $recipeIds)->get();
            
            // Calculate totals and prepare response data
            $totalCalories = 0;
            $totalProtein = 0;
            $totalCarbs = 0;
            $totalFats = 0;
            $recipeDetails = [];
            
            foreach ($recipes as $recipe) {
                $calories = $recipe->calories ?? 0;
                $protein = $recipe->protein ?? 0;
                $carbs = $recipe->carbs ?? 0;
                $fats = $recipe->fats ?? 0;
                
                $totalCalories += $calories;
                $totalProtein += $protein;
                $totalCarbs += $carbs;
                $totalFats += $fats;
                
                $recipeDetails[] = [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'calories' => $calories,
                    'protein' => $protein,
                    'carbs' => $carbs,
                    'fats' => $fats
                ];
            }
            
            $responseData = [
                'total' => $totalCalories,
                'total_protein' => $totalProtein,
                'total_carbs' => $totalCarbs,
                'total_fats' => $totalFats,
                'recipes' => $recipeDetails
            ];
            
            Log::info('Recipe calorie calculation complete', [
                'total_calories' => $totalCalories,
                'recipe_count' => count($recipeDetails)
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => $responseData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error calculating recipe calories', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'recipe_ids' => $request->input('recipe_ids', [])
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to calculate recipe calories: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check if selected recipes exceed daily calorie limit
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCalorieExceedance(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get recipe IDs from request
            $recipeIds = $request->input('recipe_ids', []);
            
            if (empty($recipeIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No recipes selected'
                ], 400);
            }
            
            // Get user profile data
            $questions1 = $user->questions1;
            
            if (!$questions1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User profile data is incomplete. Please complete your profile.'
                ], 400);
            }
            
            // Get user health goal from questions3
            $questions3 = $user->questions3;
            $healthGoal = $questions3 ? $questions3->health_goal : 'maintain_weight';
            
            Log::info('Checking calorie exceedance', [
                'user_id' => $user->id,
                'health_goal' => $healthGoal,
                'recipe_count' => count($recipeIds)
            ]);
            
            // Use user-specified activity level or default to moderate
            $activityLevel = $request->input('activity_level', 'moderate');
            
            // Get all user stats for calculations
            $userStats = [
                'weight' => $questions1->weight,
                'height' => $questions1->height,
                'gender' => $user->gender,
                'activity_level' => $activityLevel,
                'health_goal' => $healthGoal
            ];
            
            // Calculate daily calorie needs 
            $calorieNeeds = $this->calorieCalculatorService->calculateDailyCalorieNeeds(
                $user,
                $userStats
            );
            
            // Calculate total calories from recipes
            $recipeCalories = $this->calorieCalculatorService->calculateTotalRecipeCalories($recipeIds);
            
            // Check if recipes exceed daily calorie limit and provide health-goal specific advice
            $exceedanceData = $this->calorieCalculatorService->checkCalorieExceedance(
                $recipeCalories['total_calories'],
                $calorieNeeds['recommended_calories'],
                $healthGoal
            );
            
            Log::info('Calorie exceedance check completed', [
                'user_id' => $user->id,
                'health_goal' => $healthGoal,
                'exceeds_limit' => $exceedanceData['exceeds_limit'],
                'difference' => $exceedanceData['difference'],
                'deficit_recovery_days' => $exceedanceData['deficit_recovery_days'] ?? 0
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'daily_needs' => $calorieNeeds,
                    'recipe_calories' => $recipeCalories,
                    'exceedance' => $exceedanceData
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking calorie exceedance', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'recipe_ids' => $request->input('recipe_ids', [])
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to check calorie exceedance: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get nutrition data from API for a food query
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNutritionData(Request $request)
    {
        try {
            $query = $request->input('query');
            
            if (empty($query)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Food query is required'
                ], 400);
            }
            
            $nutritionData = $this->calorieCalculatorService->getNutritionDataFromAPI($query);
            
            return response()->json([
                'status' => 'success',
                'data' => $nutritionData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting nutrition data', [
                'error' => $e->getMessage(),
                'query' => $request->input('query')
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get nutrition data: ' . $e->getMessage()
            ], 500);
        }
    }
} 