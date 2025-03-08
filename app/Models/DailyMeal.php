<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recipe_id',
        'date',
        'meal_type',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the user that owns the daily meal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipe associated with the daily meal.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
} 