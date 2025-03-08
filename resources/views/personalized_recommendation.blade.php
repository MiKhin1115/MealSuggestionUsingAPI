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
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <!-- Left side - Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/dashboard" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                    </div>

                    <!-- Right side - Icons and Profile -->
                    <div class="flex items-center space-x-6">
                        <!-- Heart Icon -->
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-heart text-xl"></i>
                        </button>

                        <!-- Notification Bell -->
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                        </div>

                        <!-- User Profile -->
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                <span class="text-xs text-gray-500">{{ $user->age }} years old</span>
                            </div>
                            <div class="h-10 w-10 rounded-full overflow-hidden">
                                <img src="https://i.pinimg.com/736x/d6/78/3c/d6783c10250b38ba628db8006f69c204.jpg" 
                                     alt="User avatar" 
                                     class="h-full w-full object-cover">
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Your Personalized Recommendations</h1>
            
            <!-- Recommendations Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($recommendations as $recipe)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative">
                        <img src="{{ $recipe->image_url }}" 
                             alt="{{ $recipe->title }}" 
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $recipe->title }}</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="far fa-clock mr-2"></i>
                            <span>{{ $recipe->cooking_time }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $recipe->difficulty }}</span>
                        </div>
                        <p class="text-gray-600 mb-4">{{ $recipe->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Saved on: {{ $recipe->saved_date }}</span>
                            <button class="text-green-600 hover:text-green-700">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600">No recommendations available yet. Start saving recipes to get personalized suggestions!</p>
                </div>
                @endforelse
            </div>
        </div>
        <x-footer />
    </body>
</html> 