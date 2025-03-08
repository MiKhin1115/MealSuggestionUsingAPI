<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'instructions',
        'cooking_time',
        'calories',
        'protein',
        'carbs',
        'fats',
        'diet_type',
        'meal_type',
        'image_url',
        'source_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'cooking_time' => 'integer',
        'calories' => 'integer',
        'protein' => 'float',
        'carbs' => 'float',
        'fats' => 'float',
    ];

    /**
     * Get the users who have favorited this recipe.
     */
    public function favoriteByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'recipe_id', 'user_id');
    }
    
    /**
     * Alias for favoriteByUsers() - added for compatibility
     */
    public function favorites()
    {
        return $this->favoriteByUsers();
    }

    /**
     * Get the users who have saved this recipe.
     */
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_recipes', 'recipe_id', 'user_id');
    }
} 