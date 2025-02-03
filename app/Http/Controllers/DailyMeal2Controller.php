<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Models\Recipe;

class DailyMeal2Controller extends Controller
{
    public function index()
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
        
        // Get recipes for each meal type
        $recipes = $this->getRecipes($mealTypes);
        
        return view('daily_meal_2', [
            'user' => $user,
            'greeting' => $greeting,
            'currentTime' => $currentTime,
            'mealTypes' => $mealTypes,
            'recipes' => $recipes
        ]);
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
    
    private function getRecipes($mealTypes)
    {
        $recipes = [];
        $user = Auth::user();
        
        foreach ($mealTypes as $mealType) {
            $recipesQuery = Recipe::where('meal_type', $mealType)
                ->inRandomOrder()
                ->take(10);
            
            if ($user) {
                // Add is_favorite attribute to each recipe
                $recipes[$mealType] = $recipesQuery->get()->map(function($recipe) use ($user) {
                    $recipe->is_favorite = $recipe->favorites()
                        ->where('user_id', $user->id)
                        ->exists();
                    return $recipe;
                });
            } else {
                $recipes[$mealType] = $recipesQuery->get();
            }
        }
        return $recipes;
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
} 