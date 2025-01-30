<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Food Preferences - Food App</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation Bar (Same as previous page) -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                        <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign In</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto pt-8 px-4">
            <div class="mb-8">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-green-600">Step 2 of 3</span>
                    <span class="text-sm font-medium text-gray-600">Food Preferences</span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full">
                    <div class="w-2/3 h-3 bg-green-600 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Question Form -->
        <div class="max-w-4xl mx-auto px-4 pb-16">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Food Preferences</h2>
                
                <form action="/register-questions-3" method="POST" class="space-y-6">
                    @csrf

                    <!-- Favorite Meals -->
                    <div>
                        <label for="favorite_meals" class="block text-sm font-medium text-gray-700">Favorite Meals</label>
                        <p class="text-sm text-gray-500 mb-2">Enter your favorite meals, separated by commas</p>
                        <textarea id="favorite_meals" name="favorite_meals" rows="3" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                  placeholder="e.g., Spaghetti Bolognese, Chicken Curry, Greek Salad"></textarea>
                    </div>

                    <!-- Disliked Ingredients -->
                    <div>
                        <label for="disliked_ingredients" class="block text-sm font-medium text-gray-700">Disliked Ingredients</label>
                        <p class="text-sm text-gray-500 mb-2">Enter ingredients you don't like, separated by commas</p>
                        <textarea id="disliked_ingredients" name="disliked_ingredients" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                  placeholder="e.g., Mushrooms, Olives, Cilantro"></textarea>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <a href="/register-questions" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous Step
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html> 