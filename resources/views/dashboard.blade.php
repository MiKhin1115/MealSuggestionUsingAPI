<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard - Meal Suggestion</title>
        @vite('resources/css/app.css')
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        
        <x-header />
        
        <!-- Main Content -->
        <div class="container mx-auto px-16 py-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <h1 class="text-3xl font-bold text-gray-800 mb-8">Welcome to Your Dashboard</h1>
            
            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meal Suggestions Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="/meal-suggestions" class="block">
                        <div class="h-48 overflow-hidden cursor-pointer">
                            <img src="{{ asset('images/dashboard/recipe_suggestion.jpeg') }}"
                                 alt="Meal Suggestions" 
                                 class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Recipe Suggestions</h3>
                        <p class="text-gray-600 mb-4">Discover personalized recipe recommendations based on your preferences.</p>
                        <a href="/meal-suggestions" 
                           class="inline-flex items-center text-green-600 hover:text-green-700">
                            Explore recipes
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Daily Meal Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="/daily-meal-1" class="block">
                        <div class="h-48 overflow-hidden cursor-pointer">
                            <img src="{{ asset('images/dashboard/daily_meal.jpeg') }}"
                                 alt="Daily Meal" 
                                 class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Daily Meal</h3>
                        <p class="text-gray-600 mb-4">Plan and track your daily meals with our easy-to-use planner.</p>
                        <a href="/daily-meal-1" 
                           class="inline-flex items-center text-green-600 hover:text-green-700">
                            View planner
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Weekly Plan Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="/personalized_recommendation" class="block">
                        <div class="h-48 overflow-hidden cursor-pointer">
                            <img src="{{ asset('images/dashboard/personal_recommedation.jpeg') }}"
                                 alt="Weekly Plan" 
                                 class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Personalized Recommendations</h3>
                        <p class="text-gray-600 mb-4">Organize your meals based on at least 3 of your favorite recipes.</p>
                        <a href="/personalized_recommendation" 
                           class="inline-flex items-center text-green-600 hover:text-green-700">
                            Plan week
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Health Profile Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="/health-profile" class="block">
                        <div class="h-48 overflow-hidden cursor-pointer">
                            <img src="{{ asset('images/dashboard/health_profile.jpeg') }}"
                                 alt="Health Profile" 
                                 class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Health Profile</h3>
                        <p class="text-gray-600 mb-4">Manage your dietary preferences and health goals.</p>
                        <a href="/health-profile" 
                           class="inline-flex items-center text-green-600 hover:text-green-700">
                            View profile
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <x-footer />
    </body>
</html> 