<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Saved Recipes - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Add Axios for API requests -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // Set up Axios with CSRF token
            window.axios = axios;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        </script>
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-16 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Your Saved Recipes</h1>
            
            <!-- Tabs for different types of saved items -->
            <div class="mb-8 border-b border-gray-200">
                <div class="flex justify-center">
                    <button class="tab-button active px-6 py-3 text-lg font-medium border-b-2 border-green-600 text-green-600" data-tab="favorites">
                        <i class="fas fa-heart mr-2"></i>Favorites
                    </button>
                    <button class="tab-button px-6 py-3 text-lg font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="saved">
                        <i class="fas fa-bookmark mr-2"></i>Saved for Later
                    </button>
                </div>
            </div>
            
            <!-- Favorites Tab Content -->
            <div id="favorites-content" class="tab-content block">
                @if($favoriteRecipes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favoriteRecipes as $recipe)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 recipe-card cursor-pointer" data-recipe-id="{{ $recipe->id }}">
                        <div class="relative">
                            <img src="{{ $recipe->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c' }}" 
                                 alt="{{ $recipe->title }}" 
                                 class="w-full h-48 object-cover">
                            <div class="absolute top-4 right-4 flex space-x-3">
                                <button class="text-red-500 hover:text-gray-100 transition-colors duration-200 favorite-btn" data-recipe-id="{{ $recipe->id }}">
                                    <i class="fas fa-heart text-2xl"></i>
                                </button>
                                <button class="text-red-600 hover:text-white bg-white hover:bg-red-600 rounded-full p-1 transition-colors duration-200 remove-favorite-btn-x" data-recipe-id="{{ $recipe->id }}">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $recipe->title }}</h2>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $recipe->meal_type }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($recipe->description, 100) }}</p>
                            
                            <div class="flex justify-between text-sm text-gray-500 mb-4">
                                <div>
                                    <span class="font-medium">{{ $recipe->cooking_time ?? '30' }} min</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $recipe->calories ?? '400' }} kcal</span>
                                </div>
                                <div>
                                    <span class="capitalize">{{ $recipe->diet_type ?? 'omnivore' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button class="view-recipe-btn px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors" data-recipe-id="{{ $recipe->id }}">View Recipe</button>
                                <button type="button" class="remove-favorite-btn px-3 py-1 border border-gray-300 text-gray-600 text-sm rounded hover:bg-gray-100 transition-colors" data-recipe-id="{{ $recipe->id }}">Remove</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-heart-broken text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Favorite Recipes Yet</h3>
                    <p class="text-gray-500 mb-6">Click the heart icon on any recipe to add it to your favorites.</p>
                    <a href="{{ route('meal.suggestions') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Browse Recipes
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Saved For Later Tab Content -->
            <div id="saved-content" class="tab-content hidden">
                @if($savedRecipes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($savedRecipes as $savedRecipe)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 recipe-card cursor-pointer" data-recipe-id="{{ $savedRecipe->recipe->id }}">
                        <div class="relative">
                            <img src="{{ $savedRecipe->recipe->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c' }}" 
                                 alt="{{ $savedRecipe->recipe->title }}" 
                                 class="w-full h-48 object-cover">
                            <div class="absolute top-4 right-4 px-2 py-1 bg-green-600 text-white text-xs rounded">
                                Saved on {{ $savedRecipe->saved_date->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $savedRecipe->recipe->title }}</h2>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $savedRecipe->recipe->meal_type }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($savedRecipe->recipe->description, 100) }}</p>
                            
                            <div class="flex justify-between text-sm text-gray-500 mb-4">
                                <div>
                                    <span class="font-medium">{{ $savedRecipe->recipe->cooking_time ?? '30' }} min</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $savedRecipe->recipe->calories ?? '400' }} kcal</span>
                                </div>
                                <div>
                                    <span class="capitalize">{{ $savedRecipe->recipe->diet_type ?? 'omnivore' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button class="view-recipe-btn px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors" data-recipe-id="{{ $savedRecipe->recipe->id }}">View Recipe</button>
                                <button type="button" class="remove-saved-btn px-3 py-1 border border-gray-300 text-gray-600 text-sm rounded hover:bg-gray-100 transition-colors" data-saved-id="{{ $savedRecipe->id }}">Remove</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-bookmark text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No Saved Recipes Yet</h3>
                    <p class="text-gray-500 mb-6">Save recipes to access them later for your meal planning.</p>
                    <a href="{{ route('meal.suggestions') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Browse Recipes
                    </a>
                </div>
                @endif
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
                                    <button id="modal-favorite-btn" class="text-red-500 hover:text-red-600 transition-colors duration-200">
                                        <i class="fas fa-heart text-xl"></i>
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
            // DOM elements
            const modal = document.getElementById('recipe-modal');

            // Tab functionality
            document.addEventListener('DOMContentLoaded', function() {
                const tabButtons = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');
                
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove active class from all buttons
                        tabButtons.forEach(btn => {
                            btn.classList.remove('active', 'border-green-600', 'text-green-600');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });
                        
                        // Add active class to clicked button
                        this.classList.add('active', 'border-green-600', 'text-green-600');
                        this.classList.remove('border-transparent', 'text-gray-500');
                        
                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                            content.classList.remove('block');
                        });
                        
                        // Show corresponding tab content
                        const targetTab = this.getAttribute('data-tab');
                        document.getElementById(`${targetTab}-content`).classList.add('block');
                        document.getElementById(`${targetTab}-content`).classList.remove('hidden');
                    });
                });
                
                // Add event listeners for "View Recipe" buttons
                document.querySelectorAll('.view-recipe-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const recipeId = this.getAttribute('data-recipe-id');
                        getRecipeDetails(recipeId);
                    });
                });
                
                // Remove favorite functionality
                document.querySelectorAll('.remove-favorite-btn, .remove-favorite-btn-x').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const recipeId = this.getAttribute('data-recipe-id');
                        if(confirm('Are you sure you want to remove this recipe from your favorites?')) {
                            // Send AJAX request to remove from favorites
                            fetch('/api/favorites/remove', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    recipe_id: recipeId
                                })
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                return response.json();
                            })
                            .then(data => {
                                console.log('Response data:', data);
                                
                                if(data.status === 'removed') {
                                    // Remove the recipe card from the page
                                    this.closest('.recipe-card').remove();
                                    
                                    // Show success notification
                                    showToast('Recipe removed from favorites successfully!', 'success');
                                    
                                    // Check if there are any recipes left
                                    if(document.querySelectorAll('#favorites-content .recipe-card').length === 0) {
                                        location.reload(); // Reload to show empty state
                                    }
                                } else {
                                    showToast('Could not remove recipe. ' + (data.message || 'Please try again.'), 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('An error occurred while removing the recipe.', 'error');
                            });
                        }
                    });
                });
                
                // Add event listener for the heart icon button
                document.querySelectorAll('.favorite-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const recipeId = this.getAttribute('data-recipe-id');
                        
                        // Since we're already in favorites, clicking the heart should remove from favorites
                        if(confirm('Are you sure you want to remove this recipe from your favorites?')) {
                            // Send AJAX request to remove from favorites
                            fetch('/api/favorites/remove', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    recipe_id: recipeId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.status === 'removed') {
                                    // Remove the recipe card from the page
                                    this.closest('.recipe-card').remove();
                                    
                                    // Show success notification
                                    showToast('Recipe removed from favorites successfully!', 'success');
                                    
                                    // Check if there are any recipes left
                                    if(document.querySelectorAll('#favorites-content .recipe-card').length === 0) {
                                        location.reload(); // Reload to show empty state
                                    }
                                } else {
                                    showToast('Could not remove recipe. ' + (data.message || 'Please try again.'), 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('An error occurred while removing the recipe.', 'error');
                            });
                        }
                    });
                });
                
                // Remove saved recipe functionality
                document.querySelectorAll('.remove-saved-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const savedId = this.getAttribute('data-saved-id');
                        if(confirm('Are you sure you want to remove this saved recipe?')) {
                            // Send AJAX request to remove saved recipe
                            fetch('/saved-recipes/remove', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    saved_id: savedId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    // Remove the recipe card from the page
                                    this.closest('.recipe-card').remove();
                                    
                                    // Check if there are any recipes left
                                    if(document.querySelectorAll('#saved-content .recipe-card').length === 0) {
                                        location.reload(); // Reload to show empty state
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        }
                    });
                });
                
                // Toast notification function
                function showToast(message, type = 'success') {
                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    } shadow-lg z-50 transform transition-all duration-300 translate-y-0 opacity-100`;
                    
                    toast.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                            <span>${message}</span>
                        </div>
                    `;
                    
                    // Add to DOM
                    document.body.appendChild(toast);
                    
                    // Remove after 3 seconds
                    setTimeout(() => {
                        toast.style.transform = 'translateY(20px)';
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }
            });

            // Get recipe details by ID
            function getRecipeDetails(id) {
                if (!id) {
                    showToast('Error: Invalid recipe ID', 'error');
                    return;
                }
                
                // Show loading or spinner if desired
                
                axios.get(`/api/recipes/${id}`)
                    .then(response => {
                        // Check if there's an error message in the response
                        if (response.data && response.data.error) {
                            console.error('API returned error:', response.data.error);
                            showToast(response.data.error, 'error');
                            return;
                        }
                        
                        if (response.data) {
                            openModal(formatRecipeForModal(response.data));
                        } else {
                            showToast('Recipe details not found.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching recipe details:', error);
                        showToast('Failed to load recipe details. Please try again later.', 'error');
                    });
            }

            // Format API recipe data for modal display
            function formatRecipeForModal(recipe) {
                // Extract ingredients
                const ingredients = recipe.extendedIngredients?.map(ing => 
                    `${ing.amount} ${ing.unit} ${ing.name}`
                ) || [];
                
                // Extract steps - handle all possible formats
                let steps = [];
                if (recipe.steps && Array.isArray(recipe.steps)) {
                    // Optimized format where steps are already extracted
                    steps = recipe.steps;
                } else if (recipe.analyzedInstructions && recipe.analyzedInstructions.length > 0) {
                    // Original format where steps are nested in analyzedInstructions
                    steps = recipe.analyzedInstructions[0]?.steps?.map(step => step.step) || [];
                } else if (recipe.instructions) {
                    // Plain text instructions format
                    // Split by periods or line breaks
                    steps = recipe.instructions
                        .split(/\.\s+|\n+/)
                        .map(step => step.trim())
                        .filter(step => step.length > 0)
                        .map(step => step.endsWith('.') ? step : step + '.');
                } else if (recipe.preparation_steps) {
                    // Fallback for database-stored recipes
                    if (typeof recipe.preparation_steps === 'string') {
                        try {
                            steps = JSON.parse(recipe.preparation_steps);
                        } catch (e) {
                            steps = [recipe.preparation_steps];
                        }
                    } else if (Array.isArray(recipe.preparation_steps)) {
                        steps = recipe.preparation_steps;
                    }
                }
                
                // If still no steps, provide a fallback message
                if (steps.length === 0) {
                    steps = ["No detailed cooking instructions available for this recipe."];
                }
                
                // Extract nutrition - handle both formats
                const nutrition = {};
                if (recipe.nutrition && recipe.nutrition.nutrients) {
                    const importantNutrients = ['Calories', 'Protein', 'Carbohydrates', 'Fat', 'Fiber', 'Sugar'];
                    recipe.nutrition.nutrients.forEach(nutrient => {
                        if (importantNutrients.includes(nutrient.name)) {
                            nutrition[nutrient.name] = `${nutrient.amount} ${nutrient.unit}`;
                        }
                    });
                } else {
                    // Fallback for database-stored recipes
                    if (recipe.calories) nutrition['Calories'] = `${recipe.calories} kcal`;
                    if (recipe.protein) nutrition['Protein'] = `${recipe.protein} g`;
                    if (recipe.carbs) nutrition['Carbohydrates'] = `${recipe.carbs} g`;
                    if (recipe.fats) nutrition['Fat'] = `${recipe.fats} g`;
                }
                
                // Check if ingredients need to be parsed from JSON
                if (ingredients.length === 0 && recipe.ingredients) {
                    let parsedIngredients = [];
                    if (typeof recipe.ingredients === 'string') {
                        try {
                            parsedIngredients = JSON.parse(recipe.ingredients);
                        } catch (e) {
                            parsedIngredients = [recipe.ingredients];
                        }
                    } else if (Array.isArray(recipe.ingredients)) {
                        parsedIngredients = recipe.ingredients;
                    }
                    
                    return {
                        id: recipe.id ? recipe.id.toString() : null,
                        title: recipe.title || 'Recipe',
                        image: recipe.image_url || recipe.image || 'https://via.placeholder.com/300x200?text=No+Image+Available',
                        time: recipe.cooking_time ? `${recipe.cooking_time} min` : 'Unknown',
                        ingredients: parsedIngredients,
                        steps: steps,
                        nutrition: nutrition
                    };
                }
                
                return {
                    id: recipe.id ? recipe.id.toString() : null,
                    title: recipe.title || 'Recipe',
                    image: recipe.image_url || recipe.image || 'https://via.placeholder.com/300x200?text=No+Image+Available',
                    time: recipe.cooking_time ? `${recipe.cooking_time} min` : (recipe.readyInMinutes ? `${recipe.readyInMinutes} mins` : 'Unknown'),
                    ingredients: ingredients,
                    steps: steps,
                    nutrition: nutrition
                };
            }

            // Open modal function
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
            
            // Close modal function
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
            
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
        <x-footer />
    </body>
</html> 