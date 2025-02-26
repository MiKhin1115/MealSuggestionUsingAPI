<?php

namespace App\Http\Controllers;

use App\Models\SavedRecipe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PersonalizedRecommendationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's saved recipes with related recipe data
        
        if (!$user) {
            $user = new User();
            $user->name = "Guest User";
            $user->age = "-";
            $user->picture = asset('https://i.pinimg.com/736x/fd/e4/c6/fde4c6429138fdb8567dabca85a3305c.jpg');
            $recommendations = SavedRecipe::with('recipe')
            ->where('user_id', $user->id)
            ->orderBy('saved_date', 'desc')
            ->get()
            ->map(function ($savedRecipe) {
                return $savedRecipe->recipe;
            });
        }
        return view('personalized_recommendation', [
            'user' => $user,
            'recommendations' => $recommendations
        ]);
    }
} 