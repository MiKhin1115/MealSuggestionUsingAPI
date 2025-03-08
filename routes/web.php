<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecipeSuggestionController;
use App\Http\Controllers\DailyMealController;
use App\Http\Controllers\DailyMeal2Controller;
use App\Http\Controllers\UserFavoriteController;
use App\Http\Controllers\RecipeAdminController;
use App\Http\Controllers\SavedRecipeController;
use App\Http\Controllers\PersonalizedRecommendationController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/register', function () {
    return view('register');
});

Route::get('/register-questions', function () {
    return view('register-questions');
})->name('register.questions');

Route::get('/register-questions-2', function () {
    return view('register-questions-2');
});

Route::get('/register-questions-3', function () {
    return view('register-questions-3');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    //->middleware(['auth'])
    ->name('dashboard');

Route::get('/about', function () {
    return view('about');
});

Route::get('/registration-success', function () {
    return view('registration-success');
});//->middleware('auth')->name('registration.success');

Route::post('/logout', function () {
    //Auth::logout();
    return redirect('/');
})->name('logout');

Route::post('/register', function (Request $request) {
    // Validate the form data
    $validated = $request->validate([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|same:password_confirmation',
        'password_confirmation' => 'required|min:6'
    ], [
        'password.same' => 'Password and confirm password are not identical.',
        'password.min' => 'Password must be at least 6 characters.',
        'password_confirmation.min' => 'Confirm password must be at least 6 characters.',
        'email.unique' => 'This email is already registered.',
        'email.required' => 'Email is required.',
        'password.required' => 'Password is required.',
        'password_confirmation.required' => 'Password confirmation is required.'
    ]);

    // Store the form data in the session for later use
    session(['registration_data' => [
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']), // Hash the password before storing
    ]]);

    // Redirect to the questions page
    return redirect()->route('register.questions');
})->name('register.store');

Route::post('/login', function (Request $request) {
    // Validate the form data
    $validated = $request->validate([
        'email' => 'required|email|unique:users',
        'password' => 'required',
    ], [
        'email.unique' => 'This email is already registered.',
    ]);

    // Store the form data in the session for later use
    session(['registration_data' => [
        'email' => $validated['email'],
        'password' => $validated['password'],
    ]]);

    // Redirect to the questions page
    return redirect()->route('dashboard');
})->name('login');

Route::get('/meal-suggestions', [RecipeSuggestionController::class, 'index'])
    ->name('recipe.suggestions');

Route::get('/daily-meal-1', [DailyMealController::class, 'index'])
    ->name('daily.meal.1');

Route::get('/daily-meal-2', [DailyMeal2Controller::class, 'index'])
    ->name('daily.meal.2');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/recipes/create', [RecipeAdminController::class, 'create'])->name('admin.recipes.create');
    Route::post('/admin/recipes', [RecipeAdminController::class, 'store'])->name('admin.recipes.store');
});
Route::post('/register-questions', function () {
    return view('register-questions');
})->name('register.questions');

Route::post('/save-recipe', [SavedRecipeController::class, 'store'])
    //->middleware('auth')
    ->name('save.recipe');

Route::get('/personalized-recommendations', [PersonalizedRecommendationController::class, 'index'])
    //->middleware('auth')
    ->name('personalized.recommendations');