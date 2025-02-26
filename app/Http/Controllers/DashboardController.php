<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            // Create a guest user object with default values
            $user = (object)[
                'name' => 'Guest User',
                'email' => null,
                'picture' => null,
                'age' => null
            ];
        }
        return view('dashboard', compact('user'));
    }
}
