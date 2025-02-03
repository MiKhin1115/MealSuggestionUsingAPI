<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daily Meal - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    <!-- Page Header -->
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">Daily Meal Planner</h1>

    <!-- Warning Header (Centered) -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6 max-w-4xl mx-auto flex justify-center">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-md text-yellow-700 text-center font-semibold">
                    To get accurate meal suggestions, enter your ingredients correctly!
                </p>
            </div>
        </div>
    </div>
</div>


            <!-- Ingredients Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                <!-- Pantry Essentials -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-box-open mr-2 text-green-500"></i>
                        Pantry Essentials
                    </h3>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Rice, Pasta, Flour, Oil, Salt...">
                            <div class="suggestions hidden absolute w-full bg-white mt-1 rounded-lg shadow-lg z-10"></div>
                        </div>
                    </div>
                </div>

                <!-- Vegetables & Greens -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-carrot mr-2 text-green-500"></i>
                        Vegetables & Greens
                    </h3>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Tomatoes, Spinach, Broccoli...">
                            <div class="suggestions hidden absolute w-full bg-white mt-1 rounded-lg shadow-lg z-10"></div>
                        </div>
                    </div>
                </div>

                <!-- Fruits & Seeds -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-apple-alt mr-2 text-green-500"></i>
                        Fruits & Seeds
                    </h3>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Apples, Chia Seeds, Almonds...">
                            <div class="suggestions hidden absolute w-full bg-white mt-1 rounded-lg shadow-lg z-10"></div>
                        </div>
                    </div>
                </div>

                <!-- Vegan Ingredients -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-seedling mr-2 text-green-500"></i>
                        Vegan Ingredients
                    </h3>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Tofu, Tempeh, Seitan...">
                            <div class="suggestions hidden absolute w-full bg-white mt-1 rounded-lg shadow-lg z-10"></div>
                        </div>
                    </div>
                </div>

                <!-- Meat & Seafood -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-drumstick-bite mr-2 text-green-500"></i>
                        Meat & Seafood
                    </h3>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Chicken, Fish, Beef...">
                            <div class="suggestions hidden absolute w-full bg-white mt-1 rounded-lg shadow-lg z-10"></div>
                        </div>
                    </div>
                </div>

                <!-- Beverages & Cooking Alcohol -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-wine-bottle mr-2 text-green-500"></i>
                        Beverages & Cooking Alcohol
                    </h3>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   placeholder="e.g., Wine, Beer, Juice...">
                            <div class="suggestions hidden absolute w-full bg-white mt-1 rounded-lg shadow-lg z-10"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8">
                <a href="{{ route('daily.meal.2') }}" >
                    <button class="bg-green-500 text-white text-lg px-8 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200">
                        Get Meal Suggestions
                    </button>
                </a>                
            </div>

        </div>

        <script>
            // Add auto-suggestion functionality
            document.querySelectorAll('input[type="text"]').forEach(input => {
                const suggestionsDiv = input.nextElementSibling;
                
                input.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    if (value.length < 2) {
                        suggestionsDiv.classList.add('hidden');
                        return;
                    }

                    // Sample suggestions - replace with your actual data
                    const suggestions = [
                        'Rice', 'Pasta', 'Flour', 'Sugar', 'Salt',
                        'Tomatoes', 'Lettuce', 'Carrots', 'Onions'
                    ].filter(item => item.toLowerCase().includes(value));

                    if (suggestions.length > 0) {
                        suggestionsDiv.innerHTML = suggestions.map(suggestion => 
                            `<div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">${suggestion}</div>`
                        ).join('');
                        suggestionsDiv.classList.remove('hidden');
                    } else {
                        suggestionsDiv.classList.add('hidden');
                    }
                });

                // Handle clicking outside
                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target)) {
                        suggestionsDiv.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html> 