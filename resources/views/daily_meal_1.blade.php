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
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-16 py-8">
    <!-- Page Header -->
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">Daily Meal Planner</h1>

    <!-- Warning Header (Centered) -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 px-16 py-6 mb-6 max-w-4xl mx-auto flex justify-center">
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
            <form id="ingredients-form" action="{{ route('daily.meal.2') }}" method="get">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
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

                    <!-- Condiments -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-pepper-hot mr-2 text-green-500"></i>
                            Condiments
                        </h3>
                        <div class="space-y-4">
                            <div class="relative">
                                <input type="text" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                       placeholder="e.g., Ketchup, Soy Sauce, Mustard...">
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
                <div class="flex flex-col items-center mt-8">
                    <div id="validation-message" class="text-red-500 mb-2 hidden">Please enter at least one ingredient.</div>
                    <button id="submit-button" type="button" class="bg-green-500 text-white text-lg mb-10 px-8 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200">
                        Get Meal Suggestions
                    </button>
                </div>
            </form>
        </div>

        <script>
            // Validation and form submission
            document.getElementById('submit-button').addEventListener('click', function() {
                const inputs = document.querySelectorAll('input[type="text"]');
                let hasValue = false;
                let allIngredients = [];
                
                // Check if at least one input has value and collect ingredients
                inputs.forEach(input => {
                    const value = input.value.trim();
                    if (value !== '') {
                        hasValue = true;
                        // Split the input value by commas to handle multiple ingredients
                        const values = value.split(',').map(v => v.trim()).filter(v => v !== '');
                        allIngredients.push(...values);
                    }
                });
                
                if (hasValue) {
                    // For GET requests, we'll add individual ingredient parameters to the form
                    allIngredients.forEach((ingredient, index) => {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `ingredient[]`;
                        hiddenInput.value = ingredient;
                        document.getElementById('ingredients-form').appendChild(hiddenInput);
                    });
                    
                    // Submit the form
                    document.getElementById('ingredients-form').submit();
                } else {
                    // No ingredients entered, show validation message
                    document.getElementById('validation-message').classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('validation-message').classList.add('hidden');
                    }, 3000);
                }
            });
            
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
        <x-footer />
    </body>
</html> 