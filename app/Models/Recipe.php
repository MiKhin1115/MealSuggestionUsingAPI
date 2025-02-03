<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'meal_suggestion_recipes';

    protected $fillable = [
        'recipe_id',
        'title',
        'description',
        'image_url',
        'meal_type',
        'cooking_time',
        'difficulty',
        'ingredients',
        'instructions',
        'nutrition_facts'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'nutrition_facts' => 'array'
    ];

    public function favorites()
    {
        return $this->hasMany(UserFavorite::class, 'recipe_id');
    }
} 