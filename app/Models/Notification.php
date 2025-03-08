<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'link',
        'read',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'read' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the notification as read.
     *
     * @return $this
     */
    public function markAsRead()
    {
        $this->read = true;
        $this->save();

        return $this;
    }

    /**
     * Create a calorie comparison notification.
     *
     * @param  int  $userId
     * @param  bool  $isExceeding
     * @param  float  $totalRecipeCalories
     * @param  float  $dailyNeeds
     * @param  float  $difference
     * @param  string|null  $healthGoal
     * @return \App\Models\Notification
     */
    public static function createCalorieComparison($userId, $isExceeding, $totalRecipeCalories, $dailyNeeds, $difference, $healthGoal = null)
    {
        $percentage = round(($totalRecipeCalories / $dailyNeeds) * 100);
        
        if ($isExceeding) {
            $title = 'Calorie Alert';
            $message = "Your selected recipes total {$totalRecipeCalories} calories, which exceeds your estimated daily need of {$dailyNeeds} calories by {$difference} calories ({$percentage}% of your daily needs).";
            $type = 'warning';
            
            // Add specific advice for weight loss users
            if ($healthGoal === 'weight_loss') {
                $message .= " This may slow down your weight loss progress. Consider reducing portion sizes or adding exercise to compensate.";
            }
        } else {
            $title = 'Within Calorie Limits';
            $message = "Your selected recipes total {$totalRecipeCalories} calories, which is within your estimated daily need of {$dailyNeeds} calories. You have {$difference} calories remaining ({$percentage}% of your daily needs).";
            $type = 'success';
            
            // Add encouragement for weight loss users
            if ($healthGoal === 'weight_loss') {
                $message .= " You're on track with your weight loss goals!";
            }
        }

        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => '/notification-detail',
            'data' => [
                'recipeCalories' => $totalRecipeCalories,
                'dailyNeeds' => $dailyNeeds,
                'difference' => $difference,
                'percentage' => $percentage,
                'healthGoal' => $healthGoal,
            ],
        ]);
    }
}
