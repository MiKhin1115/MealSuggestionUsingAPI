<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserFavorite;
use Illuminate\Support\Facades\Auth;

class UserFavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $recipeId = $request->recipe_id;

        $favorite = UserFavorite::where('user_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            UserFavorite::create([
                'user_id' => $user->id,
                'recipe_id' => $recipeId
            ]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'message' => $status === 'added' ? 'Recipe added to favorites' : 'Recipe removed from favorites'
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favoriteRecipes()->paginate(12);

        return view('favorites.index', [
            'favorites' => $favorites
        ]);
    }
} 