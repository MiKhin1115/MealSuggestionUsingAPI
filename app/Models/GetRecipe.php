<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetRecipe extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'get_recipes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recipe_id',
        'notes',
    ];

    /**
     * Get the user that owns this recipe.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipe associated with this entry.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
} 