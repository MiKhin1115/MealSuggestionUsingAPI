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
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/dashboard" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                    </div>
                    <div class="flex items-center space-x-6">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                        </div>

                        <!-- User Profile -->
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-medium text-gray-900">Hlaing Htet Htet Htun</span>
                                <span class="text-xs text-gray-500">19 years old</span>
                            </div>
                            <div class="h-10 w-10 rounded-full overflow-hidden">
                                <img src="https://i.pinimg.com/736x/d6/78/3c/d6783c10250b38ba628db8006f69c204.jpg" 
                                     alt="User avatar" 
                                     class="h-full w-full object-cover">
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Welcome to Your Dashboard</h1>
            
            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Meal Suggestions Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Meal Suggestions" 
                             class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Meal Suggestions</h3>
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
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1547592180-85f173990554" 
                             alt="Daily Meal" 
                             class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Daily Meal</h3>
                        <p class="text-gray-600 mb-4">Plan and track your daily meals with our easy-to-use planner.</p>
                        <a href="/daily-meal" 
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
                    <div class="h-48 overflow-hidden">
                        <img src="https://i.pinimg.com/736x/1e/16/f9/1e16f9b2f4e200da1f3def37f8d8633a.jpg" 
                             alt="Weekly Plan" 
                             class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Weekly Plan</h3>
                        <p class="text-gray-600 mb-4">Organize your meals for the entire week in advance.</p>
                        <a href="/weekly-plan" 
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
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352" 
                             alt="Health Profile" 
                             class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                    </div>
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
    </body>
</html> 