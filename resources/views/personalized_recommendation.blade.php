<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Personalized Recommendations - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-16 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Personalized Recipe Recommendations</h1>
            
            <p class="text-gray-600 mb-8">
                Based on your preferences and health goals, we've selected these recipes just for you.
            </p>
            
            <!-- Embedded Calorie Calculator Section -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                    <div class="flex items-center">
                        <i class="fas fa-calculator text-white mr-3"></i>
                        <h2 class="text-lg font-semibold text-white">Calorie Calculator</h2>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Track your calorie intake to see if these recipes fit your daily nutritional needs.
                    </p>
                    
                    @if(isset($user) && isset($questions1) && isset($questions1->height) && isset($questions1->weight))
                        <div id="calorie-calculator" 
                             @if(isset($questions2) && isset($questions2->health_goal))
                                data-health-goal="{{ $questions2->health_goal }}"
                             @endif
                        >
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
                                    <p class="text-sm text-yellow-600">
                                        For a personalized calorie calculation, please complete your 
                                        <a href="{{ route('health.profile') }}" class="text-yellow-700 font-medium underline">health profile</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Daily Calorie Needs -->
                        <div>
                            <h3 class="font-medium text-gray-700 mb-3">Calculate Daily Calorie Needs</h3>
                            <form id="daily-needs-form" class="mb-5">
                                <!-- Hidden fields with user data -->
                                <input type="hidden" id="gender" value="{{ isset($questions1->gender) ? $questions1->gender : 'male' }}">
                                <input type="hidden" id="age" value="{{ isset($questions1->age) ? $questions1->age : '30' }}">
                                <input type="hidden" id="height" value="{{ isset($questions1->height) ? $questions1->height : '170' }}">
                                <input type="hidden" id="weight" value="{{ isset($questions1->weight) ? $questions1->weight : '70' }}">
                                
                                <!-- Health goal hidden field -->
                                <input type="hidden" id="health_goal" value="{{ isset($questions2->health_goal) ? $questions2->health_goal : 'maintain_weight' }}">
                                
                                <div class="mb-4">
                                    <label for="activity_level" class="block text-sm font-medium text-gray-700 mb-1">Activity Level</label>
                                    <select id="activity_level" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="sedentary">Sedentary (little or no exercise)</option>
                                        <option value="light" selected>Light (light exercise 1-3 days/week)</option>
                                        <option value="moderate">Moderate (moderate exercise 3-5 days/week)</option>
                                        <option value="active">Active (hard exercise 6-7 days/week)</option>
                                        <option value="very_active">Very Active (very hard exercise & physical job)</option>
                                    </select>
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
                        
                        <!-- Recipe Calorie Calculator -->
                        <div>
                            <h3 class="font-medium text-gray-700 mb-3">Check Recipe Calories</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Select recipes from your recommendations to calculate total calories.
                            </p>
                            
                            <div id="recipe-calories-form">
                                <button type="button" id="calculate-recipe-calories" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors flex items-center justify-center">
                                    <i class="fas fa-utensils mr-2"></i>
                                    <span>Calculate Selected Recipe Calories</span>
                                </button>
                            </div>
                            
                            <div id="recipe-calories-result" class="bg-gray-50 rounded-lg p-4 border border-gray-200 mt-4 hidden">
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
            
            <!-- Important Reminder Title -->
            <div class="bg-amber-100 border-l-4 border-amber-500 p-4 mb-8 rounded-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-amber-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-amber-800">Important Reminder</h3>
                        <p class="text-amber-700 mt-1">
                            Please only check recipes that you have actually cooked and eaten to ensure accurate calorie tracking.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Recipe Recommendations (Remaining content) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @if(isset($recommendations) && count($recommendations) > 0)
                    @foreach($recommendations as $recipe)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="relative">
                                <!-- Recipe image -->
                                @if(isset($recipe->image_url) && $recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Recipe selection checkbox -->
                                <div class="absolute top-4 right-4">
                                    <label class="inline-flex items-center bg-white p-2 rounded-full shadow-md text-gray-700 hover:bg-green-50 cursor-pointer">
                                        <input type="checkbox" class="recipe-checkbox form-checkbox h-5 w-5 text-green-600" 
                                               value="{{ $recipe->id }}" 
                                               data-calories="{{ $recipe->calories ?? 0 }}"
                                               data-title="{{ $recipe->title }}">
                                    </label>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $recipe->title }}</h3>
                                
                                <!-- Recipe metadata -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if(isset($recipe->diet_type))
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                            {{ ucfirst($recipe->diet_type) }}
                                        </span>
                                    @endif
                                    
                                    @if(isset($recipe->calories))
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded">
                                            {{ $recipe->calories }} kcal
                                        </span>
                                    @endif
                                    
                                    @if(isset($recipe->cooking_time))
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                            {{ $recipe->cooking_time }} min
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Description -->
                                @if(isset($recipe->description))
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $recipe->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- No recipes message -->
                    <div class="col-span-3 bg-white p-8 rounded-lg shadow-md text-center">
                        <div class="text-5xl text-gray-300 mb-4">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-2">No Recipes in Your Collection</h2>
                        <p class="text-gray-500 mb-6">You haven't added any recipes to your collection yet. Visit the meal suggestions page to find and add recipes to your collection.</p>
                        <a href="{{ route('meal-suggestions') }}" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition-colors">
                            Find Recipes to Add
                        </a>
                    </div>
                @endif
            </div>
            
        </div>
        
        <!-- Include the calorie calculator JavaScript -->
        <script src="{{ asset('js/calorie-calculator.js') }}"></script>
        <script src="{{ asset('js/weight-loss-warnings.js') }}"></script>
        <script src="{{ asset('js/notification-system.js') }}"></script>
        <x-footer />
    </body>
</html> 