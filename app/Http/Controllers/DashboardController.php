<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user with all necessary data
        $user = Auth::user();
        
        // If no user is found, create a temporary one for display
        if (!$user) {
            $user = new User();
            $user->name = "Guest User";
            $user->age = "-";
        }
        
        return view('dashboard', ['user' => $user]);
    }
} 