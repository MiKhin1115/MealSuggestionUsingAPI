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
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Questions1;
use App\Models\Questions2;
use App\Models\Questions3;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GetRecipeController;
use App\Http\Controllers\CalorieCalculatorController;
use App\Http\Controllers\NotificationController;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/register', function (Request $request) {
    // Validate the form data
    $validated = $request->validate([
        'email' => [
            'required',
            'email',
            'unique:users',
            function ($attribute, $value, $fail) {
                if (str_ends_with(strtolower($value), '@example.com')) {
                    $fail('Registration with example.com email addresses is not allowed.');
                }
            },
        ],
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

    $user = User::create([
        'name' => '', // Name is set to null
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']), // Hash password
        'email_verified_at' => null, // You may update this after email verification
        'remember_token' => null, // Can be null unless using remember me feature
        'age' => null, // Age is set to null
    ]);
    
    // Store just the user ID in session instead of the entire model
    session(['registration_user_id' => $user->id]);
    
    // Log user creation and session data
    \Log::info('User created during registration', [
        'user_id' => $user->id, 
        'email' => $user->email,
        'session_id' => session()->getId(),
        'has_registration_user_id' => session()->has('registration_user_id'),
        'registration_user_id' => session('registration_user_id')
    ]);
    
    // Redirect to the questions page
    return redirect()->route('register.questions');
})->name('register.store');

Route::get('/register-questions', function () {
    return view('register-questions');
})->name('register.questions');

Route::post('/register-questions', function (Request $request) {
    // Log the incoming request data
    \Log::info('Questions1 form submission received', [
        'all_request_data' => $request->all(),
        'session_data' => session()->all(),
        'session_id' => session()->getId(),
        'has_registration_user_id' => session()->has('registration_user_id'),
        'registration_user_id' => session('registration_user_id')
    ]);

    // Get user ID from session
    $userId = session('registration_user_id');
    
    if (!$userId) {
        \Log::error('User ID not found in session during register-questions step');
        return redirect()->route('register')->with('error', 'Session expired. Please register again.');
    }
    
    // Retrieve the user directly from database
    $user = User::find($userId);
    
    if (!$user) {
        \Log::error('User not found in database during register-questions step', ['user_id' => $userId]);
        return redirect()->route('register')->with('error', 'User not found. Please register again.');
    }
    
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-Z0-9\s.,!?()\'\\-_]+$/'
        ],  // Name is required and should be a string with no special characters
        'gender' => 'required|in:male,female',  // Gender must be either male or female
        'age' => 'required|integer|min:13',   // Age should be an integer and at least 13
        'height' => 'required|numeric|min:70', // Height should be numeric and at least 1 cm
        'weight' => 'required|numeric|min:20', // Weight should be numeric and at least 1 kg
        'diet_type' => 'required|in:omnivore,vegetarian,vegan', // Diet type must be one of these options
    ], [
        'name.required' => 'Full name is required.',
        'name.regex' => 'Full name contains invalid special characters.',
        'gender.required' => 'Gender is required.',
        'gender.in' => 'Gender must be either male or female.',
        'age.required' => 'Age is required.',
        'age.integer' => 'Age must be a number.',
        'age.min' => 'Age must be at least 13.',
        'height.required' => 'Height is required.',
        'height.numeric' => 'Height must be a number.',
        'height.min' => 'Height must be at least 70 cm.',
        'weight.required' => 'Weight is required.',
        'weight.numeric' => 'Weight must be a number.',
        'weight.min' => 'Weight must be at least 5 kg.',
        'diet_type.required' => 'Diet type is required.',
        'diet_type.in' => 'Diet type must be one of the following: omnivore, vegetarian, vegan.',
    ]);

    // Use a database transaction to ensure data consistency
    try {
        \DB::beginTransaction();
        
        // Update the user record directly
        $user->name = $validated['name'];
        $user->age = (int)$validated['age'];
        $user->gender = $validated['gender'];
        $user->save();
        
        // Log user update
        \Log::info('User updated during registration questions', [
            'user_id' => $user->id, 
            'name' => $user->name, 
            'age' => $user->age,
            'gender' => $user->gender
        ]);
        
        // Create the questions record
        $question1 = Questions1::create([
            'user_id' => $user->id,
            'height' => $validated['height'],
            'weight' => $validated['weight'],
            'diet_type' => $validated['diet_type'],
        ]);
        
        // Log question1 creation
        \Log::info('Questions1 record created', [
            'user_id' => $user->id,
            'question1_id' => $question1->id
        ]);
        
        \DB::commit();
        
        return redirect()->route('register.questions2')->with('success', 'Information saved successfully!');
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Error updating user profile: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'exception' => $e
        ]);
        return redirect()->back()->with('error', 'An error occurred while saving your information. Please try again. Error: ' . $e->getMessage());
    }
})->name('register.questions.store');

Route::get('/register-questions-2', function () {
    // Check if user ID exists in session
    $userId = session('registration_user_id');
    
    if (!$userId) {
        return redirect()->route('register')->with('error', 'Session expired. Please register again.');
    }
    
    return view('register-questions-2');
})->name('register.questions2');

Route::post('/register-questions-2', function (Request $request) {
    // Get user ID from session
    $userId = session('registration_user_id');

    if (!$userId) {
        \Log::error('User ID not found in session during register-questions-2 step');
        return redirect()->route('register')->with('error', 'Session expired. Please register again.');
    }

    // Retrieve user directly from database
    $user = User::find($userId);
    
    if (!$user) {
        \Log::error('User not found in database during register-questions-2 step', ['user_id' => $userId]);
        return redirect()->route('register')->with('error', 'User not found. Please register again.');
    }

    // Validate input
    $validated = $request->validate([
        'favorite_meals' => [
            'required',
            'string',
            'regex:/^[a-zA-Z0-9\s.,!?()\'\\-_]+$/'
        ],
        'disliked_ingredients' => [
            'nullable',
            'string',
            'regex:/^[a-zA-Z0-9\s.,!?()\'\\-_]+$/'
        ],
    ], [
        'favorite_meals.required' => 'Please list your favorite meals.',
        'favorite_meals.regex' => 'Favorite meals contains invalid special characters.',
        'disliked_ingredients.regex' => 'Disliked ingredients contains invalid special characters.',
    ]);

    try {
        // Store data in questions2 table
        $question2 = Questions2::create([
            'user_id' => $user->id,
            'favorite_meals' => $validated['favorite_meals'],
            'disliked_ingredients' => $validated['disliked_ingredients'] ?? null,
        ]);
        
        // Log question2 creation
        \Log::info('Questions2 record created', [
            'user_id' => $user->id,
            'question2_id' => $question2->id
        ]);

        return redirect()->route('register.questions3')->with('success', 'Information saved successfully!');
    } catch (\Exception $e) {
        \Log::error('Error creating Questions2 record: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'exception' => $e
        ]);
        return redirect()->back()->with('error', 'An error occurred while saving your information. Please try again.');
    }
})->name('register.questions2.store');

Route::get('/register-questions-3', function () {
    // Check if user ID exists in session
    $userId = session('registration_user_id');
    
    if (!$userId) {
        return redirect()->route('register')->with('error', 'Session expired. Please register again.');
    }
    
    return view('register-questions-3');
})->name('register.questions3');

Route::post('/register-questions-3', function (Request $request) {
    try {
        // Get user ID from session
        $userId = session('registration_user_id');

        // Log the incoming request data
        \Log::info('Questions3 form submission received', [
            'user_id' => $userId,
            'all_request_data' => $request->all(),
            'session_data' => session()->all()
        ]);

        if (!$userId) {
            \Log::error('User ID not found in session during register-questions-3 step');
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        // Retrieve user directly from database
        $user = User::find($userId);
        
        if (!$user) {
            \Log::error('User not found in database during register-questions-3 step', ['user_id' => $userId]);
            return redirect()->route('register')->with('error', 'User not found. Please register again.');
        }

        // Log the incoming request data
        \Log::info('Questions3 form submission', [
            'user_id' => $userId,
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'request_headers' => $request->headers->all()
        ]);

        // Validate input with more descriptive error messages
        $validated = $request->validate([
            'no_allergies' => 'nullable', // Changed to be truly optional
            'medical_conditions' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[a-zA-Z0-9\s.,!?()\'\\-_]+$/'
            ],
            'health_goal' => 'required|in:weight_loss,weight_gain,maintenance,maintain_weight,muscle_gain,general_health',  // Updated to match form values
            'favorite_snacks' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s.,!?()\'\\-_]+$/'
            ],
            'cooking_skill' => 'required|in:beginner,intermediate,advanced,expert',
            'cooking_time' => 'required|in:15,30,60,90',  // Updated to match form values
            'meal_budget' => 'required|in:budget,moderate,premium,luxury',
        ], [
            'health_goal.required' => 'Please select your health goal.',
            'health_goal.in' => 'Please select a valid health goal.',
            'cooking_skill.required' => 'Please select your cooking skill level.',
            'cooking_skill.in' => 'Please select a valid cooking skill level.',
            'cooking_time.required' => 'Please specify your preferred cooking time.',
            'cooking_time.in' => 'Please select a valid cooking time option.',
            'meal_budget.required' => 'Please select your meal budget preference.',
            'meal_budget.in' => 'Please select a valid meal budget option.',
            'medical_conditions.max' => 'Medical conditions cannot exceed 500 characters.',
            'medical_conditions.regex' => 'Medical conditions contains invalid special characters.',
            'favorite_snacks.max' => 'Favorite snacks cannot exceed 255 characters.',
            'favorite_snacks.regex' => 'Favorite snacks contains invalid special characters.',
        ]);

        \Log::info('Questions3 validation passed', [
            'user_id' => $userId,
            'validated_data' => $validated,
            'session_data' => session()->all()
        ]);

        try {
            \DB::beginTransaction();
            
            // Default no_allergies to 0 if it doesn't exist in the request
            $noAllergies = isset($validated['no_allergies']) ? 1 : 0;
            
            $question3 = Questions3::create([
                'user_id' => $user->id,
                'no_allergies' => $noAllergies,
                'medical_conditions' => $validated['medical_conditions'] ?? null,
                'health_goal' => $validated['health_goal'],
                'favorite_snacks' => $validated['favorite_snacks'] ?? null,
                'cooking_skill' => $validated['cooking_skill'],
                'cooking_time' => $validated['cooking_time'],
                'meal_budget' => $validated['meal_budget'],
            ]);
            
            // Log question3 creation
            \Log::info('Questions3 record created', [
                'user_id' => $user->id,
                'question3_id' => $question3->id,
                'session_data' => session()->all()
            ]);
            
            // Verification of user data before final authentication
            \Log::info('User registration complete', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'age' => $user->age,
                'session_data' => session()->all()
            ]);
            
            // Clear the session data
            session()->forget('registration_user_id');
            
            // Store user in session for registration success page
            session(['registration_complete_user' => $user->id]);
            
            \DB::commit();
            
            \Log::info('Redirecting to registration success', [
                'user_id' => $user->id,
                'session_data' => session()->all()
            ]);
            
            return redirect()->route('registration.success')->with('success', 'Registration completed successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error completing registration: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while saving your information. Please try again. If the problem persists, contact support.');
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Questions3 validation failed', [
            'user_id' => $userId,
            'errors' => $e->errors()
        ]);
        
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
    } catch (\Illuminate\Session\TokenMismatchException $e) {
        \Log::error('CSRF token mismatch during registration step 3', [
            'user_id' => $userId,
            'error' => $e->getMessage()
        ]);
        
        // Regenerate the token
        session()->regenerateToken();
        
        return redirect()->back()
            ->with('error', 'Your session has expired. Please try submitting the form again.')
            ->withInput($request->except('_token'));
    } catch (\Exception $e) {
        \Log::error('Unexpected error during registration step 3', [
            'user_id' => $userId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'An unexpected error occurred. Please try again later.')
            ->withInput();
    }
})->name('register.questions3.store');

// Update the registration success route
Route::get('/registration-success', function () {
    // Get user ID from session
    $userId = session('registration_complete_user');
    
    if (!$userId) {
        return redirect()->route('register')->with('error', 'Registration session expired. Please register again.');
    }
    
    // Retrieve user from database
    $user = User::find($userId);
    
    if (!$user) {
        return redirect()->route('register')->with('error', 'User not found. Please register again.');
    }
    
    // Authenticate the user
    Auth::login($user);
    
    // Regenerate session for security
    session()->regenerate();
    
    // Clear the registration session data
    session()->forget('registration_complete_user');
    
    // Log successful registration completion
    \Log::info('User completed registration process', [
        'user_id' => $user->id,
        'email' => $user->email
    ]);
    
    return view('registration-success', [
        'user' => $user
    ]);
})->name('registration.success');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/about', function () {
    return view('about');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');



Route::post('/login', function (Request $request) {
    // Validate the form data
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt to authenticate the user
    if (Auth::attempt($validated)) {
        // Authentication passed - regenerate session
        $request->session()->regenerate();
        
        // Get the authenticated user and log basic info
        $authenticatedUser = Auth::user();
        \Log::info('User logged in successfully', [
            'id' => $authenticatedUser->id,
            'name' => $authenticatedUser->name, // Check if name is populated
            'email' => $authenticatedUser->email,
            'age' => $authenticatedUser->age    // Check if age is populated
        ]);
        
        return redirect()->intended(route('dashboard'));
    }

    // Log authentication failure
    \Log::warning('Failed login attempt', ['email' => $validated['email']]);

    // The credentials are incorrect
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login');

Route::get('/auth/required', function() {
    return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
})->name('auth.required');

// Add a diagnostic route to check the current user's data
Route::get('/check-my-user', function () {
    if (Auth::check()) {
        $user = Auth::user();
        
        // Get raw data from the database
        $rawUserData = \DB::table('users')->where('id', $user->id)->first();
        
        return response()->json([
            'authenticated' => true,
            'user_from_auth' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'age' => $user->age
            ],
            'raw_user_data' => $rawUserData
        ]);
    }
    
    return response()->json([
        'authenticated' => false,
        'message' => 'Not logged in'
    ]);
})->name('check.user');

// User Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    
    Route::post('/profile/update', function (Request $request) {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image|max:1024', // Max 1MB
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable|required_with:password',
        ]);
        
        // Update name
        $user->name = $validated['name'];
        
        // Handle password update if provided
        if (isset($validated['password'])) {
            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
            
            $user->password = Hash::make($validated['password']);
        }
        
        // Handle profile picture upload if provided
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            
            // Store in public/profile-pictures directory
            $picture->storeAs('profile-pictures', $filename, 'public');
            
            // Save the path to the database (removing the leading slash for better compatibility)
            $user->picture = 'storage/profile-pictures/' . $filename;
            
            // Log the image path for debugging
            \Log::info('Profile picture updated', [
                'user_id' => $user->id,
                'picture_path' => $user->picture
            ]);
        }
        
        $user->save();
        
        // Redirect to dashboard with success message instead of back to the profile page
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    })->name('profile.update');
});

// Meal Suggestion Routes
Route::middleware('auth')->group(function () {
    // Recipe Suggestions
    Route::get('/meal-suggestions', [RecipeSuggestionController::class, 'index'])
        ->name('meal-suggestions');
    
    // Daily Meal
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
    
    // Personalized Recommendation
    Route::get('/personalized_recommendation', [PersonalizedRecommendationController::class, 'index'])
        ->name('personalized.recommendation');
    
    // User Favorites Routes
    Route::get('/favorites', [UserFavoriteController::class, 'index'])
        ->middleware(['auth'])
        ->name('favorites');
    
    Route::post('/favorites/toggle', [UserFavoriteController::class, 'toggle'])
        ->middleware(['auth'])
        ->name('favorites.toggle');
    
    // Calorie Calculator Routes
    Route::post('/api/calories/daily-needs', [CalorieCalculatorController::class, 'calculateDailyNeeds'])
        ->name('api.calories.daily-needs');
    
    Route::post('/api/calories/recipe-calories', [CalorieCalculatorController::class, 'calculateRecipeCalories'])
        ->name('api.calories.recipe-calories');
    
    Route::post('/api/calories/check-exceedance', [CalorieCalculatorController::class, 'checkCalorieExceedance'])
        ->name('api.calories.check-exceedance');
    
    // New endpoint that matches the URL in our weight-loss-warnings.js
    Route::post('/api/check-calorie-exceedance', [CalorieCalculatorController::class, 'checkCalorieExceedance'])
        ->name('api.check-calorie-exceedance');
    
    Route::post('/api/nutrition/data', [CalorieCalculatorController::class, 'getNutritionData'])
        ->name('api.nutrition.data');
    
    // Calorie Calculator View
    Route::get('/calorie-calculator', [CalorieCalculatorController::class, 'index'])
        ->name('calorie.calculator');
    
    // Health Profile Routes
    Route::get('/health-profile', function () {
        $user = Auth::user();
        $questions1 = $user->questions1()->first();
        $questions2 = $user->questions2()->first();
        $questions3 = $user->questions3()->first();
        
        return view('health_profile', [
            'user' => $user,
            'questions1' => $questions1,
            'questions2' => $questions2,
            'questions3' => $questions3
        ]);
    })->name('health.profile');
    
    Route::post('/health-profile/update', function (Request $request) {
        try {
            // Only validate fields that are actually in the request
            $validationRules = [];
            $fields = [
                // Re-enable birthday validation now that the column exists
                'birthday' => 'nullable|date',
                // Re-enable gender validation now that the column exists
                'gender' => 'nullable|string|in:male,female,other',
                'height' => 'nullable|numeric|min:70|max:250',
                'weight' => 'nullable|numeric|min:20|max:300',
                'diet_type' => 'nullable|string|in:omnivore,vegetarian,vegan',
                'health_goal' => 'nullable|string|in:weight_loss,weight_gain,maintain_weight',
                'no_allergies' => 'nullable|string',
                'allergies' => 'nullable|string|max:500',
                'disliked_ingredients' => 'nullable|string|max:500',
                'medical_conditions' => 'nullable|string|max:500',
                'cooking_skill' => 'nullable|string|in:beginner,intermediate,advanced,expert',
                'cooking_time' => 'nullable|numeric|min:5|max:180',
                'meal_budget' => 'nullable|string|in:budget,moderate,expensive',
                'favorite_snacks' => 'nullable|string|max:255',
            ];
            
            // Only validate fields that are actually present in the request
            foreach ($fields as $field => $rule) {
                if ($request->has($field)) {
                    $validationRules[$field] = $rule;
                }
            }
            
            $validatedData = $request->validate($validationRules);
            
            // Convert checkbox to boolean (1 or 0) instead of string
            $validatedData['no_allergies'] = isset($validatedData['no_allergies']) ? 1 : 0;
            
            // Begin optimized transaction
            DB::beginTransaction();
            
            try {
                $user = Auth::user();
                $userId = $user->id;
                
                // Update user info (only if fields are provided)
                $userUpdated = false;
                
                // Re-enable birthday update now that the column exists
                if (isset($validatedData['birthday'])) {
                    $user->birthday = $validatedData['birthday'];
                    $userUpdated = true;
                }
                
                // Re-enable gender update now that the column exists
                if (isset($validatedData['gender'])) {
                    $user->gender = $validatedData['gender'];
                    $userUpdated = true;
                }
                if ($userUpdated) {
                    $user->save();
                }
                
                // Bulk update Questions1
                $q1Data = array_intersect_key($validatedData, array_flip(['height', 'weight', 'diet_type']));
                if (!empty($q1Data)) {
                    // Get or create Questions1 using firstOrNew which is more efficient
                    $questions1 = \App\Models\Questions1::firstOrNew(['user_id' => $userId]);
                    foreach ($q1Data as $key => $value) {
                        $questions1->$key = $value;
                    }
                    $questions1->save();
                }
                
                // Bulk update Questions2
                if (isset($validatedData['disliked_ingredients'])) {
                    // Get or create Questions2
                    $questions2 = \App\Models\Questions2::firstOrNew(['user_id' => $userId]);
                    $questions2->disliked_ingredients = $validatedData['disliked_ingredients'];
                    $questions2->save();
                }
                
                // Bulk update Questions3
                $q3Keys = ['health_goal', 'allergies', 'medical_conditions', 'cooking_skill', 
                          'cooking_time', 'meal_budget', 'favorite_snacks', 'no_allergies'];
                $q3Data = array_intersect_key($validatedData, array_flip($q3Keys));
                
                if (!empty($q3Data)) {
                    // Get or create Questions3
                    $questions3 = \App\Models\Questions3::firstOrNew(['user_id' => $userId]);
                    foreach ($q3Data as $key => $value) {
                        $questions3->$key = $value;
                    }
                    $questions3->save();
                }
                
                DB::commit();
                
                return redirect()->route('health.profile')
                    ->with('success', 'Your health profile has been updated successfully!');
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Health profile update failed', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
                
                $errorDetail = config('app.debug') ? $e->getMessage() : 'Database error';
                
                return redirect()->route('health.profile')
                    ->with('error', 'An error occurred while saving your information. Please try again.')
                    ->with('error_details', $errorDetail);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('health.profile')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Health profile form error', [
                'user_id' => Auth::id() ?? 'guest',
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('health.profile')
                ->with('error', 'An unexpected error occurred. Please try again later.')
                ->with('error_details', config('app.debug') ? $e->getMessage() : null);
        }
    })->middleware(['auth'])->name('health.profile.update');
});

// Recipe API endpoints
Route::get('/api/recipes/search', [RecipeSuggestionController::class, 'search'])
    ->name('api.recipes.search');
Route::get('/api/recipes/random', [RecipeSuggestionController::class, 'getRandomRecipes'])
    ->name('api.recipes.random');
Route::get('/api/recipes/{id}', [RecipeSuggestionController::class, 'getRecipeDetails'])
    ->name('api.recipes.details');
Route::post('/api/favorites/toggle', [UserFavoriteController::class, 'toggle'])
    ->name('api.favorites.toggle')
    ->middleware('auth');
Route::post('/api/favorites/remove', [UserFavoriteController::class, 'remove'])
    ->name('api.favorites.remove')
    ->middleware('auth');
Route::post('/api/meals/add', [DailyMealController::class, 'addMeal'])
    ->name('api.meals.add')
    ->middleware('auth');
Route::post('/api/get-recipe/add', [GetRecipeController::class, 'getRecipe'])
    ->name('api.get.recipe.add')
    ->middleware('auth');

// Get Recipes route
Route::get('/get-recipes', [GetRecipeController::class, 'index'])
    ->name('get.recipes')
    ->middleware('auth');

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// API Routes
Route::get('/api/recipes/search', [RecipeSuggestionController::class, 'search']);
Route::get('/api/recipes/random', [RecipeSuggestionController::class, 'getRandomRecipes']);
Route::get('/api/recipes/{id}', [RecipeSuggestionController::class, 'getRecipeDetails']);
Route::post('/api/recipes/get', [GetRecipeController::class, 'getRecipe']);
Route::post('/api/recipes/remove', [GetRecipeController::class, 'remove']);
Route::post('/api/favorites/remove', [UserFavoriteController::class, 'remove']);

// Notifications route
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::get('/notification-detail', [NotificationController::class, 'detail'])->name('notification.detail');

// Articles Routes
Route::get('/articles', function () {
    return view('articles.index');
})->name('articles.index');

Route::get('/articles/{slug}', function ($slug) {
    return view('articles.show', ['slug' => $slug]);
})->name('articles.show');
