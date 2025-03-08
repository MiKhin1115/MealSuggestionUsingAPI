<?php

namespace App\Services;

use App\Models\Questions3;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CalorieCalculatorService
{
    /**
     * Calculate the user's Basal Metabolic Rate (BMR) using the Mifflin-St Jeor Equation
     * BMR is the number of calories your body needs at complete rest to maintain vital functions
     * 
     * Formula:
     * For men: BMR = (10 × weight in kg) + (6.25 × height in cm) - (5 × age in years) + 5
     * For women: BMR = (10 × weight in kg) + (6.25 × height in cm) - (5 × age in years) - 161
     * 
     * @param User $user The user object
     * @param array $userStats Additional user stats if needed
     * @return float|null The calculated BMR or null if insufficient data
     */
    public function calculateBMR($user, array $userStats = [])
    {
        // Check if we have all required data
        if (!isset($user->age) || !$user->age || $user->age <= 0) {
            return null; // Cannot calculate without valid age
        }
        
        // Get weight, height, and gender from parameters or user profile
        if (!isset($userStats['weight']) || !isset($userStats['height']) || !isset($userStats['gender'])) {
            // Try to get data from user profile if available
            $userGoals = Questions3::where('user_id', $user->id)->first();
            
            if (!$userGoals || !isset($userGoals->weight) || !isset($userGoals->height) || !isset($userGoals->gender)) {
                return null; // Not enough data to calculate
            }
            
            $weight = $userGoals->weight;
            $height = $userGoals->height;
            $gender = $userGoals->gender;
        } else {
            $weight = $userStats['weight'];
            $height = $userStats['height'];
            $gender = $userStats['gender'];
        }
        
        // Validate inputs
        if ($weight <= 0 || $height <= 0) {
            return null; // Invalid measurements
        }
        
        $age = $user->age;
        
        // Convert inputs if needed (ensuring inputs are in kg and cm)
        $weightKg = $weight;
        $heightCm = $height;
        
        // Apply the Mifflin-St Jeor Equation
        if (strtolower($gender) === 'male') {
            $bmr = (10 * $weightKg) + (6.25 * $heightCm) - (5 * $age) + 5;
        } else {
            $bmr = (10 * $weightKg) + (6.25 * $heightCm) - (5 * $age) - 161;
        }
        
        // BMR shouldn't be negative or extremely low
        if ($bmr < 500) {
            // Something's wrong with the inputs, but we'll return a minimum reasonable value
            return 500;
        }
        
        return $bmr;
    }
    
    /**
     * Calculate the user's recommended daily calorie intake based on their BMR and activity level
     * This uses the Harris-Benedict equation to estimate Total Daily Energy Expenditure (TDEE)
     * 
     * The process involves:
     * 1. Calculate BMR (basal metabolic rate) - calories needed at complete rest
     * 2. Apply activity multiplier to get TDEE - calories needed with daily activity
     * 3. Adjust based on health goals (weight loss, maintenance, or gain)
     * 
     * @param User $user The user object
     * @param array $userStats Additional user stats if needed
     * @return array The calculated recommended calories and additional information
     */
    public function calculateDailyCalorieNeeds($user, array $userStats = [])
    {
        // First calculate BMR
        $bmr = $this->calculateBMR($user, $userStats);
        
        if (!$bmr) {
            return [
                'recommended_calories' => 2000, // Default value if we can't calculate
                'is_default' => true,
                'message' => 'Using default recommendation. Please update your profile with your height, weight, and gender for a personalized calculation.'
            ];
        }
        
        // Get activity level from passed parameters or fall back to user profile
        $activityLevel = $userStats['activity_level'] ?? null;
        
        // If not provided in parameters, try to get from user profile
        if (!$activityLevel) {
            $userGoals = Questions3::where('user_id', $user->id)->first();
            $activityLevel = $userGoals->activity_level ?? 'moderate';
        }
        
        // Apply activity multiplier based on Harris-Benedict equation
        $activityMultipliers = [
            'sedentary' => 1.2,      // Little or no exercise, desk job
            'light' => 1.375,         // Light exercise 1-3 times/week
            'moderate' => 1.55,       // Moderate exercise 3-5 times/week
            'active' => 1.725,        // Active exercise 6-7 times/week
            'very_active' => 1.9      // Very active exercise, physical job, or 2x training
        ];
        
        // Ensure we have a valid activity level
        if (!isset($activityMultipliers[$activityLevel])) {
            $activityLevel = 'moderate'; // Default if invalid activity level provided
        }
        
        $multiplier = $activityMultipliers[$activityLevel];
        $dailyCalories = $bmr * $multiplier;
        
        // Get health goal from passed parameters or fall back to user profile
        $healthGoal = $userStats['health_goal'] ?? null;
        
        // If not provided in parameters, try to get from user profile
        if (!$healthGoal && isset($userGoals) && isset($userGoals->health_goal)) {
            $healthGoal = $userGoals->health_goal;
        }
        
        // Normalize health goal value
        if ($healthGoal === 'maintain' || $healthGoal === 'maintenance') {
            $healthGoal = 'maintain_weight';
        }
        
        // Adjust based on health goals if available
        $adjustment = 0;
        if ($healthGoal) {
            switch ($healthGoal) {
                case 'weight_loss':
                    // Standard recommendation is 500-1000 calorie deficit for 1-2 lbs weight loss per week
                    // We'll use a safer 500 calorie deficit by default
                    $adjustment = -500;
                    break;
                case 'weight_gain':
                    // For weight gain, a 500 calorie surplus is recommended for about 1 lb per week
                    $adjustment = 500;
                    break;
                case 'maintain_weight':
                default:
                    $adjustment = 0;
                    break;
            }
        }
        
        $recommendedCalories = $dailyCalories + $adjustment;
        
        // Ensure the recommended calories don't go below a safe minimum
        $minSafeCalories = ($user->gender && strtolower($user->gender) === 'male') ? 1500 : 1200;
        if ($recommendedCalories < $minSafeCalories) {
            $recommendedCalories = $minSafeCalories;
            $adjustment = $recommendedCalories - $dailyCalories; // Recalculate the adjustment
        }
        
        return [
            'bmr' => round($bmr),
            'activity_multiplier' => $multiplier,
            'daily_calories_before_adjustment' => round($dailyCalories),
            'goal_adjustment' => $adjustment,
            'recommended_calories' => round($recommendedCalories),
            'is_default' => false,
            'activity_level_used' => $activityLevel,
            'health_goal_used' => $healthGoal ?? 'maintain_weight'
        ];
    }
    
    /**
     * Calculate the total calories from selected recipes
     * 
     * @param array $recipes Array of recipe objects or IDs
     * @return array The calculated total calories and details
     */
    public function calculateTotalRecipeCalories($recipes)
    {
        $totalCalories = 0;
        $recipeDetails = [];
        
        foreach ($recipes as $recipe) {
            if (is_numeric($recipe)) {
                // If recipe is an ID, fetch the recipe
                $recipeObj = Recipe::find($recipe);
                if (!$recipeObj) continue;
            } else {
                $recipeObj = $recipe;
            }
            
            $calories = $recipeObj->calories ?? 0;
            $totalCalories += $calories;
            
            $recipeDetails[] = [
                'id' => $recipeObj->id,
                'title' => $recipeObj->title,
                'calories' => $calories,
                'meal_type' => $recipeObj->meal_type
            ];
        }
        
        return [
            'total_calories' => $totalCalories,
            'recipe_details' => $recipeDetails
        ];
    }
    
    /**
     * Check if the total calories from recipes exceeds the user's daily recommended calories
     * 
     * @param int $totalCalories Total calories from recipes
     * @param int $recommendedCalories Recommended daily calories
     * @param string|null $healthGoal User's health goal (weight_loss, weight_gain, or maintain_weight)
     * @return array The comparison result with details
     */
    public function checkCalorieExceedance($totalCalories, $recommendedCalories, $healthGoal = null)
    {
        $difference = $totalCalories - $recommendedCalories;
        $exceedsLimit = $difference > 0;
        $percentOfDaily = ($totalCalories / $recommendedCalories) * 100;
        
        // Calculate deficit recovery days (how many days it would take to recover from the excess)
        $deficitRecoveryDays = 0;
        if ($exceedsLimit && $healthGoal === 'weight_loss') {
            // Assuming the standard 500 calorie deficit for weight loss
            $deficitRecoveryDays = ceil($difference / 500);
        }
        
        return [
            'exceeds_limit' => $exceedsLimit,
            'difference' => $difference,
            'percent_of_daily' => round($percentOfDaily, 1),
            'health_goal' => $healthGoal,
            'deficit_recovery_days' => $deficitRecoveryDays,
            'message' => $this->getCalorieExceedanceMessage($exceedsLimit, $difference, $percentOfDaily, $healthGoal)
        ];
    }
    
    /**
     * Get a user-friendly message about calorie exceedance
     * 
     * @param bool $exceedsLimit Whether the limit is exceeded
     * @param int $difference Calorie difference
     * @param float $percentOfDaily Percentage of daily recommendation
     * @param string|null $healthGoal User's health goal
     * @return string The message
     */
    private function getCalorieExceedanceMessage($exceedsLimit, $difference, $percentOfDaily, $healthGoal = null)
    {
        // Special messaging for weight loss plans
        if ($healthGoal === 'weight_loss') {
            if ($exceedsLimit) {
                $deficitRecoveryDays = ceil($difference / 500);
                if ($difference > 1000) {
                    return "WARNING: These meals significantly exceed your weight loss calorie target by {$difference} calories. This will delay your weight loss progress by approximately {$deficitRecoveryDays} days. We recommend eating significantly fewer calories tomorrow to stay on track with your weight loss goals.";
                } else if ($difference > 500) {
                    return "CAUTION: These meals exceed your weight loss calorie target by {$difference} calories. To stay on track with your weight loss goals, you should eat approximately {$difference} fewer calories tomorrow.";
                } else {
                    return "NOTE: These meals are slightly above your weight loss calorie target by {$difference} calories. Consider reducing your intake slightly tomorrow to maintain your weight loss schedule.";
                }
            } else {
                if ($percentOfDaily < 70) {
                    return "These meals provide only " . round($percentOfDaily) . "% of your calorie target. While being under your calorie goal helps with weight loss, eating too little may not be sustainable and could affect your nutrition. Consider adding some healthy, nutrient-dense foods.";
                } else {
                    return "Great job! These meals fit within your weight loss calorie target with " . abs($difference) . " calories to spare. This supports your weight loss goals.";
                }
            }
        }
        
        // Default messaging for other health goals
        else {
            if ($exceedsLimit) {
                if ($difference > 500) {
                    return "Warning: These recipes exceed your recommended daily calorie intake by {$difference} calories. Consider lighter options.";
                } else {
                    return "These recipes are slightly above your daily recommended calories by {$difference} calories. You may want to adjust portion sizes.";
                }
            } else {
                if ($percentOfDaily < 70) {
                    return "These recipes provide only " . round($percentOfDaily) . "% of your daily calorie needs. Consider adding more food to meet your nutritional requirements.";
                } else {
                    return "These recipes fit within your daily calorie recommendation with " . abs($difference) . " calories to spare.";
                }
            }
        }
    }
    
    /**
     * Alternatively, use an external API to get calorie data for a food item
     * 
     * @param string $query The food query
     * @return array|null The nutrition data or null if API call fails
     */
    public function getNutritionDataFromAPI($query)
    {
        try {
            $response = Http::withHeaders([
                'X-Api-Key' => config('services.nutrition_api.key', 'BfLp2t0m7rPYofuCdFz06g==kc9oTw6yb2Ay8Tkk')
            ])->get('https://api.api-ninjas.com/v1/nutrition', [
                'query' => $query
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Nutrition API error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Nutrition API exception: ' . $e->getMessage());
            return null;
        }
    }
} 