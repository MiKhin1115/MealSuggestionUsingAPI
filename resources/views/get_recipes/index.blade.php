<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>My Recipe Collection - Meal Suggestion</title>
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
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">My Recipe Collection</h1>
            
            @if($getRecipes->isEmpty())
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="text-5xl text-gray-300 mb-4">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-2">No Recipes Yet</h2>
                    <p class="text-gray-500 mb-6">You haven't added any recipes to your collection yet.</p>
                    <a href="{{ route('meal-suggestions') }}" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition-colors">
                        Find Recipes
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($getRecipes as $getRecipe)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden recipe-card" data-recipe-id="{{ $getRecipe->recipe->id }}">
                            <div class="relative">
                                <img src="{{ $getRecipe->recipe->image_url }}" alt="{{ $getRecipe->recipe->title }}" class="w-full h-48 object-cover">
                                <div class="absolute top-0 right-0 p-2">
                                    <button class="remove-recipe-btn bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors shadow-md" data-id="{{ $getRecipe->id }}" title="Remove from collection">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $getRecipe->recipe->title }}</h2>
                                
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
                                    <span class="capitalize">{{ $getRecipe->recipe->diet_type }}</span>
                                    <span>{{ $getRecipe->recipe->cooking_time }} mins</span>
                                </div>
                                
                                @if($getRecipe->notes)
                                    <div class="border-t pt-3 mt-3">
                                        <h3 class="text-sm font-medium text-gray-700">Notes:</h3>
                                        <p class="text-gray-600 text-sm">{{ $getRecipe->notes }}</p>
                                    </div>
                                @endif
                                
                                <div class="flex space-x-2 mt-4">
                                    <button class="view-recipe-btn px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors" data-recipe-id="{{ $getRecipe->recipe->id }}">View Recipe</button>
                                    <button class="edit-notes-btn flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-md text-sm transition-colors duration-200">
                                        Add Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $getRecipes->links() }}
                </div>
            @endif
        </div>
        
        <!-- Notes Modal -->
        <div id="notes-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-md w-full mx-auto">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold">Add Notes</h3>
                </div>
                <div class="p-6">
                    <form id="notes-form">
                        <input type="hidden" id="recipe-id">
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Recipe Notes:</label>
                            <textarea id="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" rows="4" placeholder="Add your notes about this recipe..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" id="cancel-notes" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                                Save Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Recipe Modal (simplified) -->
        <div id="recipe-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-3xl w-full mx-auto max-h-[90vh] overflow-y-auto">
                <div class="p-4 border-b sticky top-0 bg-white">
                    <div class="flex justify-between items-center">
                        <h2 id="modal-title" class="text-2xl font-bold text-gray-800">Recipe Details</h2>
                        <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="modal-content" class="p-6">
                    <!-- Recipe details will be loaded here -->
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <x-footer />

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Set up event listeners for view recipe buttons
                document.querySelectorAll('.view-recipe-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const recipeId = this.getAttribute('data-recipe-id');
                        viewRecipeDetails(recipeId);
                    });
                });
                
                // Set up event listeners for edit notes buttons
                document.querySelectorAll('.edit-notes-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const card = this.closest('.recipe-card');
                        const recipeId = card.dataset.recipeId;
                        const notesSection = card.querySelector('p.text-gray-600.text-sm');
                        const currentNotes = notesSection ? notesSection.textContent : '';
                        
                        openNotesModal(recipeId, currentNotes);
                    });
                });
                
                // Set up event listeners for remove recipe buttons
                document.querySelectorAll('.remove-recipe-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this recipe from your collection?')) {
                            const getRecipeId = this.dataset.id;
                            removeFromCollection(getRecipeId, this);
                        }
                    });
                });
                
                // Modal functionality
                const notesModal = document.getElementById('notes-modal');
                const recipeModal = document.getElementById('recipe-modal');
                
                // Notes modal controls
                document.getElementById('cancel-notes').addEventListener('click', function() {
                    notesModal.classList.add('hidden');
                });
                
                document.getElementById('notes-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveNotes();
                });
                
                // Recipe modal controls
                document.getElementById('close-modal').addEventListener('click', function() {
                    recipeModal.classList.add('hidden');
                });
                
                // Close modals when clicking outside
                window.addEventListener('click', function(e) {
                    if (e.target === notesModal) {
                        notesModal.classList.add('hidden');
                    }
                    if (e.target === recipeModal) {
                        recipeModal.classList.add('hidden');
                    }
                });
                
                // Function to open notes modal
                function openNotesModal(recipeId, notes = '') {
                    document.getElementById('recipe-id').value = recipeId;
                    document.getElementById('notes').value = notes;
                    notesModal.classList.remove('hidden');
                }
                
                // Function to save notes
                function saveNotes() {
                    const recipeId = document.getElementById('recipe-id').value;
                    const notes = document.getElementById('notes').value;
                    
                    // Here you would typically make an AJAX request to save the notes
                    // For now, just show a success message and reload the page
                    alert('Notes saved!');
                    notesModal.classList.add('hidden');
                    window.location.reload();
                }
                
                // Function to view recipe details
                function viewRecipeDetails(recipeId) {
                    if (!recipeId) {
                        alert('Invalid recipe ID');
                        return;
                    }
                    
                    // Log the requested recipe ID for debugging
                    console.log('Fetching recipe details for ID:', recipeId);
                    
                    // Show loading spinner
                    document.getElementById('modal-content').innerHTML = `
                        <div class="flex justify-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                        </div>
                    `;
                    recipeModal.classList.remove('hidden');
                    
                    // Fetch recipe details from API - be explicit about expecting a database recipe
                    axios.get(`/api/recipes/${recipeId}`, {
                        params: {
                            source: 'database'
                        }
                    })
                        .then(response => {
                            if (response.data && response.data.error) {
                                throw new Error(response.data.error);
                            }
                            
                            if (!response.data) {
                                throw new Error('Recipe details not found');
                            }
                            
                            // Log the received recipe data for debugging
                            console.log('Received recipe data:', response.data);
                            
                            // Format and display recipe data
                            displayRecipeInModal(response.data);
                        })
                        .catch(error => {
                            console.error('Error fetching recipe details:', error);
                            document.getElementById('modal-content').innerHTML = `
                                <div class="text-center text-red-600 p-4">
                                    <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                                    <p>Error loading recipe details: ${error.message || 'Please try again later.'}</p>
                                </div>
                            `;
                        });
                }
                
                // Function to display recipe data in modal
                function displayRecipeInModal(recipe) {
                    // Handle the case where the recipe might be nested
                    // In some responses, the actual recipe data might be in recipe.recipe
                    const recipeData = recipe.recipe || recipe;
                    
                    // Log the data structure for debugging
                    console.log('Processing recipe data structure:', recipeData);
                    
                    // Extract ingredients
                    let ingredients = [];
                    if (recipeData.extendedIngredients && Array.isArray(recipeData.extendedIngredients)) {
                        ingredients = recipeData.extendedIngredients.map(ing => `${ing.amount || ''} ${ing.unit || ''} ${ing.name || ''}`);
                    } else if (recipeData.ingredients) {
                        // Handle database-stored recipe
                        try {
                            if (typeof recipeData.ingredients === 'string') {
                                ingredients = JSON.parse(recipeData.ingredients);
                            } else if (Array.isArray(recipeData.ingredients)) {
                                ingredients = recipeData.ingredients;
                            } else {
                                ingredients = [recipeData.ingredients];
                            }
                        } catch (e) {
                            console.warn('Error parsing ingredients JSON:', e);
                            ingredients = Array.isArray(recipeData.ingredients) ? recipeData.ingredients : [recipeData.ingredients];
                        }
                    }
                    
                    // Extract instructions
                    let instructions = [];
                    if (recipeData.analyzedInstructions && recipeData.analyzedInstructions.length > 0) {
                        instructions = recipeData.analyzedInstructions[0].steps.map(step => step.step);
                    } else if (recipeData.instructions) {
                        // Split by periods or line breaks
                        if (typeof recipeData.instructions === 'string') {
                            instructions = recipeData.instructions.split(/\.\s+|\n+/)
                                .map(step => step.trim())
                                .filter(step => step.length > 0)
                                .map(step => step.endsWith('.') ? step : step + '.');
                        } else {
                            instructions = Array.isArray(recipeData.instructions) ? recipeData.instructions : [recipeData.instructions];
                        }
                    } else if (recipeData.preparation_steps) {
                        // Handle database-stored recipe
                        try {
                            if (typeof recipeData.preparation_steps === 'string') {
                                instructions = JSON.parse(recipeData.preparation_steps);
                            } else if (Array.isArray(recipeData.preparation_steps)) {
                                instructions = recipeData.preparation_steps;
                            } else {
                                instructions = [recipeData.preparation_steps];
                            }
                        } catch (e) {
                            console.warn('Error parsing preparation steps JSON:', e);
                            // Fall back to splitting by periods if parsing fails
                            const stepsText = String(recipeData.preparation_steps);
                            instructions = stepsText.split(/\.\s+|\n+/)
                                .map(step => step.trim())
                                .filter(step => step.length > 0);
                        }
                    }
                    
                    // If still no instructions found, provide a placeholder
                    if (instructions.length === 0) {
                        instructions = ["No detailed instructions available for this recipe."];
                    }
                    
                    // Create nutrition facts
                    const nutritionInfo = {};
                    if (recipeData.nutrition && recipeData.nutrition.nutrients) {
                        recipeData.nutrition.nutrients.forEach(nutrient => {
                            if (['Calories', 'Protein', 'Carbohydrates', 'Fat'].includes(nutrient.name)) {
                                nutritionInfo[nutrient.name] = `${nutrient.amount} ${nutrient.unit}`;
                            }
                        });
                    } else {
                        // Use properties from database-stored recipe
                        if (recipeData.calories) nutritionInfo['Calories'] = `${recipeData.calories} kcal`;
                        if (recipeData.protein) nutritionInfo['Protein'] = `${recipeData.protein} g`;
                        if (recipeData.carbs) nutritionInfo['Carbohydrates'] = `${recipeData.carbs} g`;
                        if (recipeData.fats) nutritionInfo['Fat'] = `${recipeData.fats} g`;
                    }
                    
                    // Update modal title
                    document.getElementById('modal-title').textContent = recipeData.title || 'Recipe Details';
                    
                    // Build modal content HTML
                    document.getElementById('modal-content').innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column: Image and Ingredients -->
                            <div>
                                <img src="${recipeData.image_url || recipeData.image || 'https://via.placeholder.com/300x200?text=No+Image'}" 
                                     alt="${recipeData.title}" 
                                     class="w-full h-64 object-cover rounded-md mb-4">
                                     
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <i class="far fa-clock text-green-500 mr-2"></i>
                                            <span>${recipeData.cooking_time || recipeData.readyInMinutes || '30'} mins</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-fire text-orange-500 mr-2"></i>
                                            <span>${recipeData.calories || 'N/A'} cal</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-sm rounded-full capitalize">
                                            ${recipeData.diet_type || recipeData.diets?.[0] || 'Omnivore'}
                                        </span>
                                    </div>
                                </div>
                                
                                <h3 class="text-lg font-semibold mb-2 border-b pb-2">Ingredients</h3>
                                <ul class="space-y-2 mb-6">
                                    ${ingredients.length > 0 ? 
                                    ingredients.map(ingredient => `
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                            <span>${ingredient}</span>
                                        </li>
                                    `).join('') : 
                                    '<li class="text-gray-500">No ingredients information available.</li>'}
                                </ul>
                            </div>
                            
                            <!-- Right Column: Instructions and Nutrition -->
                            <div>
                                <h3 class="text-lg font-semibold mb-2 border-b pb-2">Instructions</h3>
                                <ol class="space-y-4 mb-6">
                                    ${instructions.map((step, index) => `
                                        <li class="flex">
                                            <span class="h-6 w-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center mr-3 flex-shrink-0">
                                                ${index + 1}
                                            </span>
                                            <span>${step}</span>
                                        </li>
                                    `).join('')}
                                </ol>
                                
                                <h3 class="text-lg font-semibold mb-2 border-b pb-2">Nutrition Information</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    ${Object.keys(nutritionInfo).length > 0 ? 
                                    Object.entries(nutritionInfo).map(([name, value]) => `
                                        <div class="bg-gray-100 rounded p-3">
                                            <div class="text-sm text-gray-500">${name}</div>
                                            <div class="font-semibold">${value}</div>
                                        </div>
                                    `).join('') : 
                                    '<div class="col-span-2 text-gray-500">No nutrition information available.</div>'}
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                // Function to remove from collection
                function removeFromCollection(getRecipeId, button) {
                    // Show loading indicator on the button
                    const originalButtonHtml = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.disabled = true;
                    
                    // Make AJAX request to remove the recipe
                    axios.post('/api/recipes/remove', {
                        id: getRecipeId
                    })
                    .then(response => {
                        // Handle successful removal
                        if (response.data.status === 'success') {
                            // Find the recipe card and animate its removal
                            const card = button.closest('.recipe-card');
                            card.style.opacity = '0.5';
                            card.style.pointerEvents = 'none';
                            
                            // Show success message
                            showToast('Recipe removed from your collection', 'success');
                            
                            // Remove card after animation
                            setTimeout(() => {
                                card.remove();
                                
                                // If no more recipes, reload to show empty state
                                if (document.querySelectorAll('.recipe-card').length === 0) {
                                    window.location.reload();
                                }
                            }, 500);
                        } else {
                            // Show error message
                            showToast(response.data.message || 'Failed to remove recipe', 'error');
                            
                            // Reset button
                            button.innerHTML = originalButtonHtml;
                            button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error removing recipe:', error);
                        
                        // Show error message
                        const errorMessage = error.response && error.response.data && error.response.data.error
                            ? error.response.data.error
                            : 'An error occurred. Please try again.';
                        
                        showToast(errorMessage, 'error');
                        
                        // Reset button
                        button.innerHTML = originalButtonHtml;
                        button.disabled = false;
                    });
                }
                
                // Function to show toast notifications
                function showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} shadow-lg z-50 transition-opacity duration-300`;
                    toast.textContent = message;
                    
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => {
                            toast.remove();
                        }, 300);
                    }, 3000);
                }
            });
        </script>
    </body>
</html> 