<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeAdminController extends Controller
{
    public function create()
    {
        return view('admin.recipes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|url',
            'meal_type' => 'required|in:Breakfast,Lunch,Dinner,Supper',
            'cooking_time' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'ingredients' => 'required|array',
            'instructions' => 'required|array',
            'nutrition_facts' => 'required|array'
        ]);

        Recipe::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'],
            'meal_type' => $validated['meal_type'],
            'cooking_time' => $validated['cooking_time'],
            'difficulty' => $validated['difficulty'],
            'ingredients' => json_encode($validated['ingredients']),
            'instructions' => json_encode($validated['instructions']),
            'nutrition_facts' => json_encode($validated['nutrition_facts'])
        ]);

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recipe added successfully');
    }
} 