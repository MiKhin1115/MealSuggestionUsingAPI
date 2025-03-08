<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>My Favorite Recipes - Meal Suggestion</title>
        @vite('resources/css/app.css')
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
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">My Favorite Recipes</h1>

            @if($favorites->isEmpty())
                <div class="text-center py-12">
                    <i class="far fa-heart text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">You haven't saved any recipes yet.</p>
                    <p class="text-gray-500 mt-2">Browse recipes and click the heart icon to save your favorites!</p>
                    <a href="{{ route('meal-suggestions') }}" class="inline-block mt-4 px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        Browse Recipes
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favorites as $recipe)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" data-recipe-id="{{ $recipe->id }}">
                            <div class="relative">
                                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
                                <button class="absolute top-4 right-4 text-red-500 hover:text-red-600 transition-colors duration-200 favorite-btn" data-recipe-id="{{ $recipe->id }}">
                                    <i class="fas fa-heart text-2xl"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $recipe->title }}</h2>
                                <div class="flex justify-between text-sm text-gray-500 mb-4">
                                    <div>
                                        <span class="font-medium">{{ $recipe->cooking_time }} min</span>
                                        @if($recipe->calories)
                                            <span class="mx-1">•</span>
                                            <span>{{ $recipe->calories }} calories</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="capitalize">{{ $recipe->diet_type }}</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="view-recipe-btn px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors" data-recipe-id="{{ $recipe->id }}">
                                        View Recipe
                                    </button>
                                    <button class="get-recipe-btn px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors" data-recipe-id="{{ $recipe->id }}">
                                        Get This Recipe
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $favorites->links() }}
                </div>
            @endif
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

        <!-- Toast Container -->
        <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

        <script>
            // DOM elements
            const modal = document.getElementById('recipe-modal');

            document.addEventListener('DOMContentLoaded', function() {
                // Handle favorite button clicks
                document.querySelectorAll('.favorite-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const recipeId = this.dataset.recipeId;
                        toggleFavorite(recipeId, this);
                    });
                });

                // Handle view recipe button clicks
                document.querySelectorAll('.view-recipe-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const recipeId = this.dataset.recipeId;
                        getRecipeDetails(recipeId);
                    });
                });

                // Handle get recipe button clicks
                document.querySelectorAll('.get-recipe-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const recipeId = this.dataset.recipeId;
                        markRecipeForCooking(recipeId);
                    });
                });
            });

            function showToast(message, type = 'success') {
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `flex items-center p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-y-0 opacity-100 ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                } text-white`;
                
                // Add icon based on type
                const icon = document.createElement('i');
                icon.className = `fas ${
                    type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'
                } mr-3 text-xl`;
                toast.appendChild(icon);
                
                // Add message
                const messageText = document.createElement('span');
                messageText.textContent = message;
                toast.appendChild(messageText);
                
                // Add to container
                const container = document.getElementById('toast-container');
                container.appendChild(toast);
                
                // Animate in
                requestAnimationFrame(() => {
                    toast.style.transform = 'translateY(0)';
                    toast.style.opacity = '1';
                });
                
                // Remove after 3 seconds
                setTimeout(() => {
                    toast.style.transform = 'translateY(100%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            function toggleFavorite(recipeId, button) {
                const recipeCard = button.closest('.bg-white');
                const recipeData = {
                    title: recipeCard.querySelector('h2').textContent,
                    description: '', // We don't store description in the card
                    ingredients: [], // We don't store ingredients in the card
                    instructions: [], // We don't store instructions in the card
                    readyInMinutes: recipeCard.querySelector('.font-medium').textContent.split(' ')[0],
                    calories: recipeCard.querySelector('.text-gray-500').textContent.match(/(\d+) calories/)?.[1] || null,
                    dietType: recipeCard.querySelector('.capitalize').textContent,
                    image: recipeCard.querySelector('img').src,
                    sourceUrl: '' // We don't store source URL in the card
                };

                axios.post('/api/favorites/toggle', {
                    recipe_id: recipeId,
                    recipe_data: recipeData
                })
                .then(response => {
                    if (response.data.status === 'removed') {
                        button.querySelector('i').classList.remove('fas');
                        button.querySelector('i').classList.add('far');
                        showToast('Recipe removed from favorites', 'success');
                        // Remove the recipe card from the view
                        button.closest('.bg-white').remove();
                    } else {
                        button.querySelector('i').classList.remove('far');
                        button.querySelector('i').classList.add('fas');
                        showToast('Recipe added to favorites', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error toggling favorite:', error);
                    showToast('Error updating favorites', 'error');
                });
            }

            function getRecipeDetails(id) {
                if (!id) {
                    showToast('Error: Invalid recipe ID', 'error');
                    return;
                }
                
                // Log the requested recipe ID for debugging
                console.log('Fetching recipe details for ID:', id);
                
                // Show loading or spinner if desired
                
                // Explicitly request a database recipe by adding the source parameter
                axios.get(`/api/recipes/${id}?source=database`)
                    .then(response => {
                        // Check if there's an error message in the response
                        if (response.data && response.data.error) {
                            console.error('API returned error:', response.data.error);
                            showToast(response.data.error, 'error');
                            return;
                        }
                        
                        if (response.data) {
                            // Log the received recipe data for debugging
                            console.log('Received recipe data:', response.data);
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
                // Handle the case where the recipe might be nested
                // In some responses, the actual recipe data might be in recipe.recipe
                const recipeData = recipe.recipe || recipe;
                
                // Log the data structure for debugging
                console.log('Processing recipe data structure:', recipeData);
                
                // Extract ingredients
                let ingredients = [];
                if (recipeData.extendedIngredients && Array.isArray(recipeData.extendedIngredients)) {
                    ingredients = recipeData.extendedIngredients.map(ing => 
                        `${ing.amount || ''} ${ing.unit || ''} ${ing.name || ''}`
                    );
                } else if (recipeData.ingredients) {
                    // Handle database-stored recipe
                    try {
                        ingredients = typeof recipeData.ingredients === 'string' ? 
                            JSON.parse(recipeData.ingredients) : recipeData.ingredients;
                    } catch (e) {
                        console.warn('Error parsing ingredients JSON:', e);
                        ingredients = Array.isArray(recipeData.ingredients) ? 
                            recipeData.ingredients : [recipeData.ingredients];
                    }
                }
                
                // Extract steps - handle all possible formats
                let steps = [];
                if (recipeData.steps && Array.isArray(recipeData.steps)) {
                    // Optimized format where steps are already extracted
                    steps = recipeData.steps;
                } else if (recipeData.analyzedInstructions && recipeData.analyzedInstructions.length > 0) {
                    // Original format where steps are nested in analyzedInstructions
                    steps = recipeData.analyzedInstructions[0]?.steps?.map(step => step.step) || [];
                } else if (recipeData.instructions) {
                    // Plain text instructions format
                    if (typeof recipeData.instructions === 'string') {
                        // Split by periods or line breaks
                        steps = recipeData.instructions
                            .split(/\.\s+|\n+/)
                            .map(step => step.trim())
                            .filter(step => step.length > 0)
                            .map(step => step.endsWith('.') ? step : step + '.');
                    } else {
                        steps = Array.isArray(recipeData.instructions) ? 
                            recipeData.instructions : [recipeData.instructions];
                    }
                } else if (recipeData.preparation_steps) {
                    // Fallback for database-stored recipes
                    if (typeof recipeData.preparation_steps === 'string') {
                        try {
                            steps = JSON.parse(recipeData.preparation_steps);
                        } catch (e) {
                            console.warn('Error parsing preparation steps JSON:', e);
                            steps = [recipeData.preparation_steps];
                        }
                    } else if (Array.isArray(recipeData.preparation_steps)) {
                        steps = recipeData.preparation_steps;
                    } else if (recipeData.preparation_steps) {
                        steps = [recipeData.preparation_steps];
                    }
                }
                
                // If still no steps, provide a fallback message
                if (steps.length === 0) {
                    steps = ["No detailed cooking instructions available for this recipe."];
                }
                
                // Extract nutrition - handle both formats
                const nutrition = {};
                if (recipeData.nutrition && recipeData.nutrition.nutrients) {
                    const importantNutrients = ['Calories', 'Protein', 'Carbohydrates', 'Fat', 'Fiber', 'Sugar'];
                    recipeData.nutrition.nutrients.forEach(nutrient => {
                        if (importantNutrients.includes(nutrient.name)) {
                            nutrition[nutrient.name] = `${nutrient.amount} ${nutrient.unit}`;
                        }
                    });
                } else {
                    // Fallback for database-stored recipes
                    if (recipeData.calories) nutrition['Calories'] = `${recipeData.calories} kcal`;
                    if (recipeData.protein) nutrition['Protein'] = `${recipeData.protein} g`;
                    if (recipeData.carbs) nutrition['Carbohydrates'] = `${recipeData.carbs} g`;
                    if (recipeData.fats) nutrition['Fat'] = `${recipeData.fats} g`;
                }
                
                // Check if ingredients need to be parsed from JSON
                if (ingredients.length === 0 && recipeData.ingredients) {
                    let parsedIngredients = [];
                    if (typeof recipeData.ingredients === 'string') {
                        try {
                            parsedIngredients = JSON.parse(recipeData.ingredients);
                        } catch (e) {
                            console.warn('Error parsing ingredients JSON:', e);
                            parsedIngredients = [recipeData.ingredients];
                        }
                    } else if (Array.isArray(recipeData.ingredients)) {
                        parsedIngredients = recipeData.ingredients;
                    } else if (recipeData.ingredients) {
                        parsedIngredients = [recipeData.ingredients];
                    }
                    
                    return {
                        id: recipeData.id ? recipeData.id.toString() : null,
                        title: recipeData.title || 'Recipe',
                        image: recipeData.image_url || recipeData.image || 'https://via.placeholder.com/300x200?text=No+Image+Available',
                        time: recipeData.cooking_time ? `${recipeData.cooking_time} min` : 'Unknown',
                        ingredients: parsedIngredients,
                        steps: steps,
                        nutrition: nutrition
                    };
                }
                
                return {
                    id: recipeData.id ? recipeData.id.toString() : null,
                    title: recipeData.title || 'Recipe',
                    image: recipeData.image_url || recipeData.image || 'https://via.placeholder.com/300x200?text=No+Image+Available',
                    time: recipeData.cooking_time ? `${recipeData.cooking_time} min` : (recipeData.readyInMinutes ? `${recipeData.readyInMinutes} mins` : 'Unknown'),
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

            function markRecipeForCooking(recipeId) {
                // Here you would typically make an API call to mark the recipe for cooking
                console.log(`Recipe ${recipeId} marked for cooking`);
                showToast('Added to your cooking list!', 'success');
            }
        </script>

        <x-footer />
    </body>
</html> 