<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    protected $fillable = [
        'user_id',
        'recipe_id',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Recipe
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id');
    }
} 