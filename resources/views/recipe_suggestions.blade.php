<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Recipe Suggestions - Meal Suggestion</title>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Recipe Suggestions</h1>
            
            <!-- Search and Filter Section -->
            <div class="max-w-6xl mx-auto mb-8">
                <!-- Search Bar with Auto-suggestions -->
                <div class="relative mb-6">
                    <input type="text" 
                           id="recipe-search"
                           placeholder="Search for recipes..." 
                           class="w-full px-4 py-3 pl-12 pr-4 text-gray-700 bg-white border rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <button class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-green-500 rounded-r-full hover:bg-green-600">
                        <span class="text-white">Search</span>
                    </button>
                    
                    <!-- Auto-suggestions dropdown -->
                    <div id="search-suggestions" class="absolute w-full mt-1 bg-white rounded-lg shadow-lg hidden">
                        <!-- Suggestions will be populated here -->
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                    <!-- Cuisine Filter -->
                    <select class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Cuisine Type</option>
                        <option value="italian">Italian</option>
                        <option value="chinese">Chinese</option>
                        <option value="indian">Indian</option>
                        <option value="mexican">Mexican</option>
                    </select>

                    <!-- Meal Type Filter -->
                    <select class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Meal Type</option>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="snack">Snack</option>
                    </select>

                    <!-- Cooking Time Filter -->
                    <select class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Cooking Time</option>
                        <option value="15">Under 15 mins</option>
                        <option value="30">Under 30 mins</option>
                        <option value="60">Under 1 hour</option>
                    </select>

                    <!-- Dietary Preferences Filter -->
                    <select class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Dietary Preferences</option>
                        <option value="vegetarian">Vegetarian</option>
                        <option value="vegan">Vegan</option>
                        <option value="gluten-free">Gluten Free</option>
                        <option value="keto">Keto</option>
                    </select>

                    <!-- Ingredients Filter -->
                    <select class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Main Ingredient</option>
                        <option value="chicken">Chicken</option>
                        <option value="beef">Beef</option>
                        <option value="fish">Fish</option>
                        <option value="vegetables">Vegetables</option>
                    </select>

                    <!-- Random Recipe Button -->
                    <button class="bg-green-500 text-white rounded-lg px-4 py-2 hover:bg-green-600 transition-colors duration-200">
                        <i class="fas fa-random mr-2"></i>Surprise Me!
                    </button>
                </div>
            </div>

            <!-- Recipe Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Sample Recipe Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Recipe" 
                             class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200">
                            <i class="far fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delicious Pasta</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-heart mr-2"></i>
                            <i class="far fa-clock mr-2"></i>
                            <span>30 mins</span>
                            <span class="mx-2">•</span>
                            <span>Italian</span>
                        </div>
                        <p class="text-gray-600 mb-4">A classic Italian pasta dish with fresh ingredients...</p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium">View Recipe →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Recipe" 
                             class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200">
                            <i class="far fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delicious Pasta</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-heart mr-2"></i>
                            <i class="far fa-clock mr-2"></i>
                            <span>30 mins</span>
                            <span class="mx-2">•</span>
                            <span>Italian</span>
                        </div>
                        <p class="text-gray-600 mb-4">A classic Italian pasta dish with fresh ingredients...</p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium">View Recipe →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Recipe" 
                             class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200">
                            <i class="far fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delicious Pasta</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-heart mr-2"></i>
                            <i class="far fa-clock mr-2"></i>
                            <span>30 mins</span>
                            <span class="mx-2">•</span>
                            <span>Italian</span>
                        </div>
                        <p class="text-gray-600 mb-4">A classic Italian pasta dish with fresh ingredients...</p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium">View Recipe →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Recipe" 
                             class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200">
                            <i class="far fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delicious Pasta</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-heart mr-2"></i>
                            <i class="far fa-clock mr-2"></i>
                            <span>30 mins</span>
                            <span class="mx-2">•</span>
                            <span>Italian</span>
                        </div>
                        <p class="text-gray-600 mb-4">A classic Italian pasta dish with fresh ingredients...</p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium">View Recipe →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Recipe" 
                             class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200">
                            <i class="far fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delicious Pasta</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-heart mr-2"></i>
                            <i class="far fa-clock mr-2"></i>
                            <span>30 mins</span>
                            <span class="mx-2">•</span>
                            <span>Italian</span>
                        </div>
                        <p class="text-gray-600 mb-4">A classic Italian pasta dish with fresh ingredients...</p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium">View Recipe →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" 
                             alt="Recipe" 
                             class="w-full h-48 object-cover">
                        <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200">
                            <i class="far fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delicious Pasta</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-heart mr-2"></i>
                            <i class="far fa-clock mr-2"></i>
                            <span>30 mins</span>
                            <span class="mx-2">•</span>
                            <span>Italian</span>
                        </div>
                        <p class="text-gray-600 mb-4">A classic Italian pasta dish with fresh ingredients...</p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium">View Recipe →</a>
                    </div>
                </div>
                <!-- More recipe cards will be added dynamically -->
            </div>
        </div>

        <!-- Recipe Modal -->
        <div id="recipe-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
            <div class="min-h-screen px-4 py-6 flex items-center justify-center">
                <div class="bg-white rounded-lg max-w-3xl w-full mx-auto relative">
                    <!-- Modal Header with close button -->
                    <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
                        <h2 id="modal-title" class="text-2xl font-bold text-gray-800">Recipe Title</h2>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Modal Content -->
                    <div class="p-6 max-h-[calc(100vh-8rem)] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left side - Image and basic info -->
                            <div>
                                <img id="modal-image" src="" alt="Recipe" class="w-full h-64 object-cover rounded-lg mb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <i class="far fa-clock"></i>
                                        <span id="modal-time">30 mins</span>
                                    </div>
                                    <button class="text-red-500 hover:text-red-600">
                                        <i class="far fa-heart text-xl"></i>
                                    </button>
                                </div>
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-2">Ingredients</h3>
                                    <ul id="modal-ingredients" class="space-y-2 text-gray-600">
                                        <!-- Ingredients will be populated here -->
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Right side - Cooking Steps and Nutrition -->
                            <div>
                                <!-- Cooking Steps Section -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-3">Cooking Instructions</h3>
                                    <ol id="modal-steps" class="space-y-4 text-gray-600">
                                        <!-- Steps will be populated here -->
                                    </ol>
                                </div>
                                
                                <!-- Nutrition Facts Section -->
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Nutrition Facts</h3>
                                    <div id="modal-nutrition" class="grid grid-cols-2 gap-4">
                                        <!-- Nutrition facts will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Search suggestions functionality
            const searchInput = document.getElementById('recipe-search');
            const suggestionsBox = document.getElementById('search-suggestions');
            
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value;
                if (searchTerm.length > 2) {
                    // Show suggestions box
                    suggestionsBox.classList.remove('hidden');
                    
                    // Simulate API call for suggestions
                    // Replace this with actual API call
                    const suggestions = [
                        'Italian Pasta',
                        'Italian Pizza',
                        'Italian Risotto'
                    ].filter(item => item.toLowerCase().includes(searchTerm.toLowerCase()));
                    
                    // Display suggestions
                    suggestionsBox.innerHTML = suggestions.map(suggestion => `
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            ${suggestion}
                        </div>
                    `).join('');
                } else {
                    suggestionsBox.classList.add('hidden');
                }
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target)) {
                    suggestionsBox.classList.add('hidden');
                }
            });

            // Heart icon toggle functionality
            document.querySelectorAll('.fa-heart').forEach(heart => {
                heart.addEventListener('click', function() {
                    this.classList.toggle('fas');
                    this.classList.toggle('far');
                    this.classList.toggle('text-red-500');
                });
            });

            // Modal functionality
            const modal = document.getElementById('recipe-modal');
            
            function openModal(recipeData) {
                // Update existing modal content
                document.getElementById('modal-title').textContent = recipeData.title;
                document.getElementById('modal-image').src = recipeData.image;
                document.getElementById('modal-time').textContent = recipeData.time;
                
                // Populate ingredients
                const ingredientsList = document.getElementById('modal-ingredients');
                ingredientsList.innerHTML = recipeData.ingredients.map(ingredient => 
                    `<li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        ${ingredient}
                    </li>`
                ).join('');
                
                // Populate cooking steps
                const stepsList = document.getElementById('modal-steps');
                stepsList.innerHTML = recipeData.steps.map((step, index) => 
                    `<li class="flex">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-green-100 text-green-500 rounded-full mr-3 font-semibold">
                            ${index + 1}
                        </span>
                        <div class="flex-grow">
                            <p>${step}</p>
                        </div>
                    </li>`
                ).join('');
                
                // Populate nutrition facts
                const nutritionDiv = document.getElementById('modal-nutrition');
                nutritionDiv.innerHTML = Object.entries(recipeData.nutrition).map(([key, value]) =>
                    `<div class="bg-gray-100 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">${key}</div>
                        <div class="font-semibold">${value}</div>
                    </div>`
                ).join('');
                
                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
            
            // Update the sample recipe data to include cooking steps
            document.querySelectorAll('.recipe-card').forEach(card => {
                card.addEventListener('click', function() {
                    const recipeData = {
                        title: "Delicious Pasta",
                        image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c",
                        time: "30 mins",
                        ingredients: [
                            "200g pasta",
                            "2 tbsp olive oil",
                            "3 cloves garlic",
                            "Fresh basil",
                            "Parmesan cheese"
                        ],
                        steps: [
                            "Bring a large pot of salted water to boil.",
                            "Cook pasta according to package instructions until al dente.",
                            "Meanwhile, heat olive oil in a large pan over medium heat.",
                            "Add minced garlic and sauté until fragrant, about 1 minute.",
                            "Drain pasta, reserving 1/2 cup of pasta water.",
                            "Add pasta to the pan with garlic and oil, toss well.",
                            "Add fresh basil and grated Parmesan cheese.",
                            "If needed, add some reserved pasta water to create a silky sauce.",
                            "Season with salt and pepper to taste.",
                            "Serve hot with extra Parmesan cheese on top."
                        ],
                        nutrition: {
                            "Calories": "420 kcal",
                            "Protein": "12g",
                            "Carbs": "65g",
                            "Fat": "14g",
                            "Fiber": "4g",
                            "Sugar": "3g"
                        }
                    };
                    openModal(recipeData);
                });
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        </script>
    </body>
</html> 