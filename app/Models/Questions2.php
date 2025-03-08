<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions2 extends Model
{
    use HasFactory;

    protected $table = 'questions2';

    protected $fillable = [
        'user_id',
        'favorite_meals',
        'disliked_ingredients',
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

