<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Questions1;
use App\Models\Questions2;
use App\Models\Questions3;
use App\Models\GetRecipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Broadcast;


class PersonalizedRecommendationController extends Controller
{
    /**
     * Display personalized recipe recommendations.
     */
    public function index()
    {
        $user = Auth::user();
        $recommendations = [];
        
        try {
            // Get user preferences from Questions tables
            $userPreferences = Questions1::where('user_id', $user->id)->first();
            $userGoals = Questions3::where('user_id', $user->id)->first();
            $userPreferences2 = Questions2::where('user_id', $user->id)->first();
            
            // Log some debugging information
            Log::info('Starting personalized recipe recommendations for user', [
                'user_id' => $user->id,
                'has_preferences' => !is_null($userPreferences),
                'has_goals' => !is_null($userGoals),
                'has_preferences2' => !is_null($userPreferences2),
                'health_goal' => $userGoals->health_goal ?? 'not set'
            ]);
            
            // Get user's recipe collection instead of filtering recipes
            $getRecipes = $user->getRecipes()->with('recipe')->get();
            Log::info('User get_recipes count', ['count' => $getRecipes->count()]);
            
            // If no recipes in user's collection, check total recipe count
            $totalRecipeCount = Recipe::count();
            Log::info('Total recipe count in database', ['count' => $totalRecipeCount]);
            
            // Extract just the recipes from the collection
            if ($getRecipes->isNotEmpty()) {
                Log::info('Using recipes from user collection');
                $recommendations = $getRecipes->map(function($getRecipe) {
                    return $getRecipe->recipe;
                });
            } else {
                // If no recipes in collection, return an empty collection instead of random recipes
                Log::info('User has no recipes in collection, showing empty recommendations');
                $recommendations = collect([]);
            }
            
            // Log the number of recommendations found
            Log::info('Final recommendations count for personalized page', [
                'count' => count($recommendations),
                'diet_types' => $recommendations->pluck('diet_type')->toArray()
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in personalized recommendations: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return an empty collection instead of random recipes
            $recommendations = collect([]);
            Log::info('Using empty recommendations after error');
        }
        
        return view('personalized_recommendation', [
            'recommendations' => $recommendations,
            'user' => $user,
            'questions1' => $userPreferences ?? null,
            'questions2' => $userPreferences2 ?? null,
            'questions3' => $userGoals ?? null
        ]);
    }
} 