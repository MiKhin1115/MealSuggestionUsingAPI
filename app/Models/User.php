<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'birthday',
        'gender',
        'email_verified_at',
        'remember_token',
        'picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's Questions1 record.
     */
    public function questions1()
    {
        return $this->hasOne(\App\Models\Questions1::class);
    }

    /**
     * Get the user's Questions2 record.
     */
    public function questions2()
    {
        return $this->hasOne(\App\Models\Questions2::class);
    }

    /**
     * Get the user's Questions3 record.
     */
    public function questions3()
    {
        return $this->hasOne(\App\Models\Questions3::class);
    }

    /**
     * Get the recipes that this user has favorited.
     */
    public function favoriteRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'user_favorites', 'user_id', 'recipe_id');
    }

    /**
     * Get the recipes that this user has saved.
     */
    public function savedRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'saved_recipes', 'user_id', 'recipe_id');
    }

    /**
     * Get the recipes that this user wants to get.
     */
    public function getRecipes()
    {
        return $this->hasMany(GetRecipe::class);
    }

    /**
     * Get the notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's age calculated from birthday.
     * 
     * @return int|null
     */
    public function getAgeAttribute($value)
    {
        // If birthday is set, calculate age from birthday
        if ($this->birthday) {
            return \Carbon\Carbon::parse($this->birthday)->age;
        }
        
        // Otherwise return the stored age value
        return $value;
    }

    /**
     * Set the user's birthday and update age automatically.
     * 
     * @param string $value
     * @return void
     */
    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = $value;
        
        // If birthday is set, calculate and store age
        if ($value) {
            $this->attributes['age'] = \Carbon\Carbon::parse($value)->age;
        }
    }
}
