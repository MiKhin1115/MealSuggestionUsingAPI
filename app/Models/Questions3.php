<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions3 extends Model
{
    use HasFactory;

    protected $table = 'questions3';

    protected $fillable = [
        'user_id',
        'medical_conditions',
        'health_goal',
        'favorite_snacks',
        'cooking_skill',
        'cooking_time',
        'meal_budget',
        'daily_budget',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


