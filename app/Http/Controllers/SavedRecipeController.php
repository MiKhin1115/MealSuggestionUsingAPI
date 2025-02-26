<?php

namespace App\Http\Controllers;

use App\Models\SavedRecipe;
use Illuminate\Http\Request;

class SavedRecipeController extends Controller
{
    public function store(Request $request)
    {
        try {
            SavedRecipe::create([
                'user_id' => auth()->id(),
                'recipe_id' => $request->recipe_id,
                'saved_date' => $request->saved_date
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recipe saved successfully'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry
            if ($e->getCode() === '23000') { // Unique constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'You have already saved this recipe today'
                ], 422);
            }
            throw $e;
        }
    }
} 