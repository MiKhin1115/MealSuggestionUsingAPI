<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Calorie Calculator - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Calorie Calculator</h1>
            
            <!-- Introduction Card -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-start">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-calculator text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Your Personalized Nutrition Dashboard</h2>
                        <p class="text-gray-600">
                            Use this toolkit to calculate your daily calorie needs, analyze recipe calories, and make informed dietary choices aligned with your health goals.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- User Stats Overview -->
            @if(isset($questions1) && isset($questions1->height) && isset($questions1->weight))
                <div id="calorie-calculator" 
                    @if(isset($questions2) && isset($questions2->health_goal))
                        data-health-goal="{{ $questions2->health_goal }}"
                    @endif
                >
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                        <!-- User Stats Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-fire text-white mr-3"></i>
                                    <h2 class="text-lg font-semibold text-white">Your Daily Calorie Needs</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">Calculate your recommended daily calorie intake based on your health profile including BMI, diet type, and health goals.</p>
                                
                                @if(isset($user) && isset($questions1) && isset($questions2) && isset($questions3))
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-5">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                            <div>
                                                <h4 class="font-medium text-blue-700 mb-1">Your Profile Information</h4>
                                                <ul class="text-sm text-blue-600 space-y-1">
                                                    @if(isset($questions1->height) && isset($questions1->weight))
                                                        @php
                                                            $heightInMeters = $questions1->height / 100; // Convert cm to meters
                                                            $bmi = $questions1->weight / ($heightInMeters * $heightInMeters);
                                                            $bmiCategory = '';
                                                            
                                                            if ($bmi < 18.5) {
                                                                $bmiCategory = 'Underweight';
                                                            } elseif ($bmi >= 18.5 && $bmi < 25) {
                                                                $bmiCategory = 'Healthy Weight';
                                                            } elseif ($bmi >= 25 && $bmi < 30) {
                                                                $bmiCategory = 'Overweight';
                                                            } else {
                                                                $bmiCategory = 'Obese';
                                                            }
                                                        @endphp
                                                        <li><strong>BMI:</strong> {{ number_format($bmi, 1) }} ({{ $bmiCategory }})</li>
                                                    @endif
                                                    @if(isset($questions1->diet_type))
                                                        <li><strong>Diet Type:</strong> {{ ucfirst($questions1->diet_type) }}</li>
                                                    @endif
                                                    @if(isset($questions3->health_goal))
                                                        <li><strong>Health Goal:</strong> {{ str_replace('_', ' ', ucfirst($questions3->health_goal)) }}</li>
                                                    @endif
                                                    @if(isset($questions2->disliked_ingredients) && !empty($questions2->disliked_ingredients))
                                                        <li><strong>Disliked Ingredients:</strong> {{ $questions2->disliked_ingredients }}</li>
                                                    @endif
                                                    @if(isset($questions3->favorite_snacks) && !empty($questions3->favorite_snacks))
                                                        <li><strong>Favorite Snacks:</strong> {{ $questions3->favorite_snacks }}</li>
                                                    @endif
                                                </ul>
                </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 mb-5">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                                            <div>
                                                <h4 class="font-medium text-yellow-700 mb-1">Profile Information Incomplete</h4>
                                                <p class="text-sm text-yellow-600 mb-2">
                                                    For a personalized calorie calculation, please complete your 
                                                    <a href="{{ route('health.profile') }}" class="text-yellow-700 font-medium underline">health profile</a>.
                                                </p>
                                                <p class="text-sm text-yellow-600">
                                                    We'll use basic calculations in the meantime.
                                                </p>
                </div>
            </div>
        </div>
                                @endif
                                
                                <form id="daily-needs-form" class="mb-5">
                                    <!-- User Stats Inputs -->
                                    <div class="space-y-4 mb-4">
                                        <!-- Gender -->
                                        <div>
                                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                            <select id="gender" name="gender" class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                <option value="male" {{ isset($questions1->gender) && $questions1->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ isset($questions1->gender) && $questions1->gender == 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Age -->
                                        <div>
                                            <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                            <input type="number" id="age" name="age" min="12" max="120" 
                                                value="{{ isset($questions1->age) ? $questions1->age : '' }}" 
                                                class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        
                                        <!-- Height -->
                                        <div>
                                            <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                                            <input type="number" id="height" name="height" min="100" max="250" 
                                                value="{{ isset($questions1->height) ? $questions1->height : '' }}" 
                                                class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        
                                        <!-- Weight -->
                                        <div>
                                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                            <input type="number" id="weight" name="weight" min="30" max="300" 
                                                value="{{ isset($questions1->weight) ? $questions1->weight : '' }}" 
                                                class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        
                                        <!-- Activity Level -->
                                        <div>
                                            <label for="activity_level" class="block text-sm font-medium text-gray-700 mb-1">Activity Level</label>
                                            <select id="activity_level" name="activity_level" class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                <option value="sedentary" {{ isset($questions1->activity_level) && $questions1->activity_level == 'sedentary' ? 'selected' : '' }}>Sedentary (little or no exercise)</option>
                                                <option value="light" {{ isset($questions1->activity_level) && $questions1->activity_level == 'light' ? 'selected' : '' }}>Light (light exercise 1-3 days/week)</option>
                                                <option value="moderate" {{ isset($questions1->activity_level) && $questions1->activity_level == 'moderate' ? 'selected' : '' }}>Moderate (moderate exercise 3-5 days/week)</option>
                                                <option value="active" {{ isset($questions1->activity_level) && $questions1->activity_level == 'active' ? 'selected' : '' }}>Active (hard exercise 6-7 days/week)</option>
                                                <option value="very_active" {{ isset($questions1->activity_level) && $questions1->activity_level == 'very_active' ? 'selected' : '' }}>Very Active (intense exercise daily)</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Health Goal -->
                                        <div>
                                            <label for="health_goal" class="block text-sm font-medium text-gray-700 mb-1">Health Goal</label>
                                            <select id="health_goal" name="health_goal" class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                <option value="maintain_weight" {{ isset($questions2->health_goal) && $questions2->health_goal == 'maintain_weight' ? 'selected' : '' }}>Maintain Weight</option>
                                                <option value="weight_loss" {{ isset($questions2->health_goal) && $questions2->health_goal == 'weight_loss' ? 'selected' : '' }}>Weight Loss</option>
                                                <option value="weight_gain" {{ isset($questions2->health_goal) && $questions2->health_goal == 'weight_gain' ? 'selected' : '' }}>Weight Gain</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button type="button" id="calculate-daily-needs" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors flex items-center justify-center">
                                        <i class="fas fa-calculator mr-2"></i>
                                        <span>Calculate Daily Needs</span>
                                    </button>
                                </form>
                                
                                <div id="daily-needs-result" class="bg-gray-50 rounded-lg p-4 border border-gray-200 hidden">
                                    <!-- Results will be populated by JavaScript -->
                                </div>
                            </div>
                    </div>
                        
                        <!-- Nutrition Data Lookup Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-search text-white mr-3"></i>
                                    <h2 class="text-lg font-semibold text-white">Nutrition Data Lookup</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">Look up detailed nutrition information for any food item.</p>
                                
                                <form id="nutrition-search-form" class="mb-5">
                                    <div class="mb-4">
                                        <label for="nutrition-query" class="block text-sm font-medium text-gray-700 mb-1">Food Item</label>
                                        <div class="relative">
                                            <input type="text" id="nutrition-query" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., apple, chicken breast, rice">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-utensils text-gray-400"></i>
                </div>
            </div>
        </div>
        
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                                        <i class="fas fa-search mr-2"></i>
                                        <span>Get Nutrition Data</span>
                                    </button>
                                </form>
                                
                                <div id="nutrition-data-result" class="bg-gray-50 rounded-lg p-4 border border-gray-200 hidden">
                                    <!-- Results will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Recipe Calorie Calculator Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-utensils text-white mr-3"></i>
                                    <h2 class="text-lg font-semibold text-white">Recipe Calorie Calculator</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">Calculate the total calories from your selected recipes.</p>
                                
                                <div class="mb-5">
                                    <h3 class="font-medium text-gray-700 mb-3">Your Recipes:</h3>
                                    <div class="recipe-list max-h-80 overflow-y-auto pr-2">
                                @if(isset($recipes) && count($recipes) > 0)
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($recipes as $recipe)
                                                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                                    <div class="relative">
                                                        <!-- Recipe image -->
                                                        @if($recipe->image_url)
                                                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-24 object-cover">
                                                        @else
                                                            <div class="w-full h-24 bg-gray-100 flex items-center justify-center">
                                                                <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Checkbox overlay -->
                                                        <div class="absolute top-2 right-2">
                                                            <label class="inline-flex items-center bg-white bg-opacity-90 rounded-full px-2 py-1 shadow-sm cursor-pointer hover:bg-opacity-100 transition-all">
                                                                <input type="checkbox" class="recipe-checkbox h-4 w-4 text-green-600 rounded border-gray-300 focus:ring-green-500" 
                                                                    value="{{ $recipe->id }}" 
                                                                    id="recipe-{{ $recipe->id }}"
                                                                    data-calories="{{ $recipe->calories ?? 0 }}"
                                                                    data-title="{{ $recipe->title }}">
                                                                <span class="ml-1 text-xs font-medium text-gray-700">Select</span>
                                        </label>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <h4 class="font-medium text-gray-800 text-sm truncate">{{ $recipe->title }}</h4>
                                                        <div class="flex justify-between items-center mt-1">
                                                            <span class="text-xs text-gray-500">
                                                                @if(isset($recipe->calories))
                                                                    {{ $recipe->calories }} calories
                                                                @else
                                                                    Calories N/A
                                                                @endif
                                                            </span>
                                                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-800 rounded-full">
                                                                {{ $recipe->diet_type ?? 'General' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                    </div>
                                    @endforeach
                                            </div>
                                @else
                                            <div class="text-center py-6 text-gray-500">
                                                <i class="fas fa-folder-open text-gray-400 text-3xl mb-2"></i>
                                                <p>No recipes found in your collection.</p>
                                                <a href="{{ route('recipe_suggestions') }}" class="text-green-600 hover:underline mt-1 inline-block">Browse recipes to add to your collection</a>
                                            </div>
                                @endif
                                    </div>
                                </div>
                                
                                <button type="button" id="calculate-recipe-calories" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors flex items-center justify-center">
                                    <i class="fas fa-calculator mr-2"></i>
                                    <span>Calculate Recipe Calories</span>
                                </button>
                                
                                <div id="recipe-calories-result" class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200 hidden">
                                    <!-- Results will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calorie Exceedance Check Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-600 to-purple-500 px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-balance-scale text-white mr-3"></i>
                                    <h2 class="text-lg font-semibold text-white">Calorie Balance Check</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">Check if your selected recipes align with your daily calorie needs.</p>
                                
                                <div class="bg-purple-50 rounded-lg p-4 mb-5 border border-purple-100">
                                    <div class="flex items-start">
                                        <i class="fas fa-lightbulb text-purple-500 mt-1 mr-2"></i>
                                        <p class="text-sm text-purple-700">
                                            Select recipes from the list above and click the button below to see if they fit within your recommended daily calorie intake.
                                        </p>
                                    </div>
                                </div>
                                
                                <button type="button" id="check-calorie-exceedance" class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>Check Calorie Balance</span>
                                </button>
                                
                                <div id="calorie-exceedance-result" class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200 hidden">
                                    <!-- Results will be populated by JavaScript -->
                                </div>
                                
                                <!-- New Calorie Comparison Result Section -->
                                <div id="calorie-comparison-result" class="mt-4 hidden">
                                    <!-- Comparison results will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <x-footer />
        
        <!-- JavaScript for Calorie Calculator Functionality -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Sample functionality for UI demo purposes
                // In a real application, you would implement actual API calls and calculations
                
                const calculateDailyNeedsBtn = document.getElementById('calculate-daily-needs');
                const dailyNeedsResult = document.getElementById('daily-needs-result');
                
                const nutritionSearchForm = document.getElementById('nutrition-search-form');
                const nutritionDataResult = document.getElementById('nutrition-data-result');
                
                const calculateRecipeCaloriesBtn = document.getElementById('calculate-recipe-calories');
                const recipeCaloriesResult = document.getElementById('recipe-calories-result');
                
                const checkCalorieExceedanceBtn = document.getElementById('check-calorie-exceedance');
                const calorieExceedanceResult = document.getElementById('calorie-exceedance-result');
                
                // Daily Needs Calculator
                if (calculateDailyNeedsBtn) {
                    calculateDailyNeedsBtn.addEventListener('click', function() {
                        // Show the results div with a loading indicator
                        dailyNeedsResult.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-green-500"></div></div>';
                        dailyNeedsResult.classList.remove('hidden');
                        
                        // Set default activity level
                        const activityLevel = 'moderate';
                        
                        // Simulate API call delay
                        setTimeout(function() {
                            // In a real app, you would make an AJAX call to the server to calculate
                            // based on the user's complete profile and selected activity level
                            
                            @if(isset($user) && isset($questions1) && isset($questions1->height) && isset($questions1->weight))
                                @php
                                    // Basic data from profile
                                    $age = isset($user->age) ? $user->age : 30;
                                    $gender = isset($user->gender) ? $user->gender : 'male';
                                    $height = $questions1->height;
                                    $weight = $questions1->weight;
                                    $dietType = isset($questions1->diet_type) ? $questions1->diet_type : 'omnivore';
                                    $healthGoal = isset($questions3->health_goal) ? $questions3->health_goal : 'maintain_weight';
                                    
                                    // BMI calculation
                                    $heightInMeters = $height / 100;
                                    $bmi = $weight / ($heightInMeters * $heightInMeters);
                                @endphp
                                
                                // Calculate BMR (Basal Metabolic Rate) using the Mifflin-St Jeor Equation
                                let bmr = 0;
                                @if($gender == 'male')
                                    bmr = (10 * {{ $weight }}) + (6.25 * {{ $height }}) - (5 * {{ $age }}) + 5;
                                @else
                                    bmr = (10 * {{ $weight }}) + (6.25 * {{ $height }}) - (5 * {{ $age }}) - 161;
                                @endif
                                
                                // Apply activity multiplier
                                let activityMultiplier = 1.2; // Default to sedentary
                                if (activityLevel === 'light') {
                                    activityMultiplier = 1.375;
                                } else if (activityLevel === 'moderate') {
                                    activityMultiplier = 1.55;
                                } else if (activityLevel === 'active') {
                                    activityMultiplier = 1.725;
                                } else if (activityLevel === 'very_active') {
                                    activityMultiplier = 1.9;
                                }
                                
                                let tdee = Math.round(bmr * activityMultiplier);
                                
                                // Adjust for health goal
                                let goalAdjustedCalories = tdee;
                                let goalDescription = '';
                                
                                @if($healthGoal == 'weight_loss')
                                    goalAdjustedCalories = Math.round(tdee * 0.8); // 20% deficit for weight loss
                                    goalDescription = 'For weight loss, we've applied a 20% calorie deficit.';
                                @elseif($healthGoal == 'weight_gain')
                                    goalAdjustedCalories = Math.round(tdee * 1.15); // 15% surplus for weight gain
                                    goalDescription = 'For weight gain, we've applied a 15% calorie surplus.';
                                @else
                                    goalDescription = 'For weight maintenance, we've calculated your exact maintenance calories.';
                                @endif
                                
                                // Adjust for diet type
                                let dietAdjustment = '';
                                @if($dietType == 'vegetarian')
                                    dietAdjustment = 'As a vegetarian, ensure you're getting enough plant-based protein (beans, tofu, dairy).';
                                @elseif($dietType == 'vegan')
                                    dietAdjustment = 'As a vegan, pay special attention to vitamin B12, iron, and plant-based protein sources.';
                                @endif
                                
                                // Create the result HTML
                                const resultHTML = `
                                    <div class="space-y-4">
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-medium text-gray-700">Your daily calorie needs:</span>
                                                <span class="text-2xl font-bold text-green-600">${goalAdjustedCalories} calories</span>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <p>Based on your BMI ({{ number_format($bmi, 1) }}) and health goal.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                            <h4 class="font-medium text-blue-800 mb-1">Personalized Insights</h4>
                                            <ul class="text-sm text-blue-700 space-y-1 list-disc pl-5">
                                                <li>Your base metabolic rate (BMR) is ${Math.round(bmr)} calories.</li>
                                                <li>Based on your profile, you burn approximately ${tdee} calories daily.</li>
                                                <li>${goalDescription}</li>
                                                ${dietAdjustment ? `<li>${dietAdjustment}</li>` : ''}
                                            </ul>
    </div>
    
                                        <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                            <h4 class="font-medium text-green-800 mb-1">Macronutrient Recommendations</h4>
                                            <div class="grid grid-cols-3 gap-2 text-center mt-2">
                                                <div>
                                                    <div class="text-xl font-bold text-green-600">${Math.round(goalAdjustedCalories * 0.3 / 4)}g</div>
                                                    <div class="text-xs text-green-700">Protein (30%)</div>
                                                </div>
                                                <div>
                                                    <div class="text-xl font-bold text-green-600">${Math.round(goalAdjustedCalories * 0.4 / 4)}g</div>
                                                    <div class="text-xs text-green-700">Carbs (40%)</div>
                                                </div>
                                                <div>
                                                    <div class="text-xl font-bold text-green-600">${Math.round(goalAdjustedCalories * 0.3 / 9)}g</div>
                                                    <div class="text-xs text-green-700">Fats (30%)</div>
                                                </div>
                                            </div>
                                        </div>
                </div>
                                `;
                            @else
                                // Basic calculation without profile data
                                // Using default values since profile data is incomplete
                                let baseCalories = 2100;
                                
                                // Apply activity multiplier
                                let activityMultiplier = 1.2; // Default to sedentary
                                if (activityLevel === 'light') {
                                    activityMultiplier = 1.375;
                                } else if (activityLevel === 'moderate') {
                                    activityMultiplier = 1.55;
                                } else if (activityLevel === 'active') {
                                    activityMultiplier = 1.725;
                                } else if (activityLevel === 'very_active') {
                                    activityMultiplier = 1.9;
                                }
                                
                                const estimatedCalories = Math.round(baseCalories * activityMultiplier);
                                
                                // Create the result HTML with a note about incomplete profile
                                const resultHTML = `
                                    <div class="space-y-4">
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-medium text-gray-700">Estimated daily needs:</span>
                                                <span class="text-2xl font-bold text-green-600">${estimatedCalories} calories</span>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <p>This is a basic estimate based on limited information and your selected activity level.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                                            <div class="flex items-start">
                                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2"></i>
                                                <div>
                                                    <h4 class="font-medium text-yellow-800 mb-1">Limited Accuracy</h4>
                                                    <p class="text-sm text-yellow-700">
                                                        For a more accurate calculation based on your BMI, diet type, health goals and more, please 
                                                        <a href="{{ route('health.profile') }}" class="font-medium underline">complete your health profile</a>.
                                                    </p>
                </div>
            </div>
                                        </div>
                                    </div>
                                `;
                            @endif
                            
                            dailyNeedsResult.innerHTML = resultHTML;
                        }, 800);
                    });
                }
                
                // Nutrition Search
                if (nutritionSearchForm) {
                    nutritionSearchForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const searchQuery = document.getElementById('nutrition-query').value;
                        
                        if (!searchQuery.trim()) {
                            alert('Please enter a food item to search for');
                            return;
                        }
                        
                        // Show the results div with a loading indicator
                        nutritionDataResult.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div></div>';
                        nutritionDataResult.classList.remove('hidden');
                        
                        // Simulate API call delay
                        setTimeout(function() {
                            // In a real app, you would fetch nutrition data for the searched food
                            nutritionDataResult.innerHTML = `
                                <h3 class="font-medium text-gray-700 mb-2">${searchQuery.charAt(0).toUpperCase() + searchQuery.slice(1)} (100g)</h3>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Calories:</span>
                                        <span class="font-medium">150 kcal</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Protein:</span>
                                        <span class="font-medium">5g</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Carbs:</span>
                                        <span class="font-medium">15g</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fat:</span>
                                        <span class="font-medium">8g</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fiber:</span>
                                        <span class="font-medium">3g</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sugar:</span>
                                        <span class="font-medium">2g</span>
                                    </div>
                                </div>
                            `;
                        }, 1000);
                    });
                }
                
                // Recipe Calorie Calculator
                if (calculateRecipeCaloriesBtn) {
                    calculateRecipeCaloriesBtn.addEventListener('click', function() {
                        const selectedRecipes = document.querySelectorAll('.recipe-checkbox:checked');
                        
                        if (selectedRecipes.length === 0) {
                            alert('Please select at least one recipe');
                            return;
                        }
                        
                        // Show the results div with a loading indicator
                        recipeCaloriesResult.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-yellow-500"></div></div>';
                        recipeCaloriesResult.classList.remove('hidden');
                        
                        // Simulate API call delay
                        setTimeout(function() {
                            // Calculate total calories from selected recipes
                            let totalCalories = 0;
                            let recipesList = '';
                            
                            selectedRecipes.forEach(recipe => {
                                const calories = parseInt(recipe.getAttribute('data-calories')) || 0;
                                const title = recipe.getAttribute('data-title');
                                
                                totalCalories += calories;
                                recipesList += `<li>${title} - ${calories} calories</li>`;
                            });
                            
                            recipeCaloriesResult.innerHTML = `
                                <div class="flex items-center justify-between mb-3">
                                    <span class="font-medium text-gray-700">Total calories:</span>
                                    <span class="text-xl font-bold text-yellow-600">${totalCalories} calories</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Selected recipes:</h4>
                                    <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
                                        ${recipesList}
                                    </ul>
                                </div>
                            `;
                        }, 800);
                    });
                }
                
                // Calorie Exceedance Check
                if (checkCalorieExceedanceBtn) {
                    checkCalorieExceedanceBtn.addEventListener('click', function() {
                        const selectedRecipes = document.querySelectorAll('.recipe-checkbox:checked');
                        
                        if (selectedRecipes.length === 0) {
                            alert('Please select at least one recipe');
                            return;
                        }
                        
                        if (dailyNeedsResult.classList.contains('hidden')) {
                            alert('Please calculate your daily needs first');
                            return;
                        }
                        
                        // Show the results div with a loading indicator
                        calorieExceedanceResult.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-purple-500"></div></div>';
                        calorieExceedanceResult.classList.remove('hidden');
                        
                        // Set default activity level (same as in daily needs calculator)
                        const activityLevel = 'moderate';
                        
                        // Simulate API call delay
                        setTimeout(function() {
                            // Calculate total calories from selected recipes
                            let totalCalories = 0;
                            
                            selectedRecipes.forEach(recipe => {
                                const calories = parseInt(recipe.getAttribute('data-calories')) || 0;
                                totalCalories += calories;
                            });
                            
                            // Get the daily calorie needs from the daily needs result
                            const dailyNeedsText = document.querySelector('#daily-needs-result .text-2xl').innerText;
                            const dailyCalories = parseInt(dailyNeedsText.replace(/[^0-9]/g, '')) || 2000;
                            
                            // Calculate difference
                            const difference = dailyCalories - totalCalories;
                            const percentOfDaily = Math.round((totalCalories / dailyCalories) * 100);
                            
                            let resultHTML = '';
                            
                            if (difference >= 0) {
                                // Good - within calorie budget
                                resultHTML = `
                                    <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                        <div class="flex items-start">
                                            <i class="fas fa-check-circle text-green-600 text-xl mt-1 mr-3"></i>
                                            <div>
                                                <h4 class="font-medium text-green-800 mb-1">Within Calorie Budget!</h4>
                                                <p class="text-sm text-green-700">
                                                    These recipes account for ${percentOfDaily}% of your daily calorie needs with ${difference} calories to spare.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            } else {
                                // Warning - exceeds calorie budget
                                resultHTML = `
                                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
                                            <div>
                                                <h4 class="font-medium text-yellow-800 mb-1">Exceeds Calorie Budget</h4>
                                                <p class="text-sm text-yellow-700">
                                                    These recipes exceed your daily calorie needs by ${Math.abs(difference)} calories (${percentOfDaily}% of daily needs).
                                                </p>
        </div>
    </div>
</div>
                                `;
                            }
                            
                            calorieExceedanceResult.innerHTML = resultHTML;
                        }, 800);
                    });
                }
            });
        </script>
        <script src="{{ asset('js/weight-loss-warnings.js') }}"></script>
    </body>
</html> 