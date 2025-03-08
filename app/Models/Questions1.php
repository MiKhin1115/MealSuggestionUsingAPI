<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions1 extends Model
{
    use HasFactory;

    protected $table = 'questions1';

    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'diet_type',
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
