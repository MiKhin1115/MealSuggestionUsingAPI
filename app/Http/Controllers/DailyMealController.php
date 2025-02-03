<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyMealController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            $user = new \App\Models\User();
            $user->name = "Guest User";
            $user->age = "-";
        }
        
        return view('daily_meal_1', ['user' => $user]);
    }
} 