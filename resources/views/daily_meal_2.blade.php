<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daily Meal Suggestions - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-16 py-8">
            <!-- Greeting Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">{{ $greeting }}, {{ $user->name }}!</h1>
                <p class="text-gray-600 text-lg">{{ $currentTime->format('g:i A') }}</p>
            </div>

            <!-- Ingredients Used -->
            @if(isset($ingredients) && count($ingredients) > 0)
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-leaf text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            <span class="font-medium">Ingredients used for recipes:</span>
                            {{ implode(', ', $ingredients) }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Meal Categories -->
            @php
                $currentHour = (int)$currentTime->format('H');
                $mealTypes = [];
                
                if ($currentHour >= 5 && $currentHour < 11) {
                    $mealTypes = ['Breakfast', 'Lunch', 'Dinner', 'Supper'];
                } elseif ($currentHour >= 11 && $currentHour < 15) {
                    $mealTypes = ['Lunch', 'Dinner', 'Supper'];
                } elseif ($currentHour >= 15 && $currentHour < 21) {
                    $mealTypes = ['Dinner', 'Supper'];
                } else {
                    $mealTypes = ['Supper'];
                }
            @endphp

            @foreach($mealTypes as $mealType)
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ $mealType }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($recipes[$mealType] ?? [] as $recipe)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer" 
                         data-recipe-id="{{ $recipe->id }}"
                         data-title="{{ $recipe->title }}"
                         data-description="{{ Str::limit(strip_tags($recipe->description), 150) }}"
                         data-ingredients="{{ json_encode($recipe->ingredients ?? []) }}"
                         data-instructions="{{ $recipe->instructions ?? '' }}"
                         data-cooking-time="{{ $recipe->cooking_time }}"
                         data-cooking-time-display="{{ $recipe->cooking_time_display ?? ($recipe->cooking_time . ' mins') }}"
                         data-calories="{{ $recipe->calories ?? 0 }}"
                         data-protein="{{ $recipe->protein ?? 0 }}"
                         data-carbs="{{ $recipe->carbs ?? 0 }}"
                         data-fats="{{ $recipe->fats ?? 0 }}"
                         data-diet-type="{{ $recipe->diet_type ?? 'omnivore' }}"
                         data-image="{{ $recipe->image_url }}"
                         data-source-url="{{ $recipe->spoonacular_source_url ?? '' }}">
                        <div class="relative">
                            <img src="{{ $recipe->image_url }}" 
                                 alt="{{ $recipe->title }}" 
                                 class="w-full h-48 object-cover">
                            <button class="absolute top-4 right-4 text-white hover:text-red-500 transition-colors duration-200 favorite-btn">
                                <i class="far fa-heart text-2xl {{ $recipe->is_favorite ? 'fas text-red-500' : '' }}"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $recipe->title }}</h3>
                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <i class="far fa-clock mr-2"></i>
                                <span>{{ $recipe->cooking_time_display }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $recipe->difficulty }}</span>
                            </div>
                            <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($recipe->description), 150) }}</p>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-2 mt-3">
                                <button class="view-recipe-btn flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> View Recipe
                                </button>
                                <button class="get-recipe-btn flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md transition-colors duration-200">
                                    <i class="fas fa-utensils mr-1"></i> Get This Recipe
                                </button>
                                <button class="favorite-card-btn p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-md transition-colors duration-200">
                                    <i class="far fa-heart text-xl {{ $recipe->is_favorite ? 'fas text-red-500' : '' }}"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-600">No recipes found for {{ $mealType }} with your ingredients.</p>
                        <div class="mt-2 p-4 bg-yellow-50 rounded-lg border border-yellow-200 inline-block">
                            <p class="text-yellow-700"><i class="fas fa-exclamation-circle mr-2"></i>We couldn't find recipes from our database for these ingredients.</p>
                            <p class="text-gray-500 mt-2">Try different ingredients or check that the Spoonacular API is working.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach

            <!-- Add a message when outside of meal times -->
            @if(empty($mealTypes))
            <div class="text-center py-12">
                <h2 class="text-2xl font-semibold text-gray-800">No meal suggestions available for this time</h2>
                <p class="text-gray-600 mt-2">Please check back during meal times</p>
            </div>
            @endif
        </div>

        <!-- Recipe Modal -->
        <div id="recipe-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
            <div class="min-h-screen px-4 py-6 flex items-center justify-center">
                <div class="bg-white rounded-lg max-w-3xl w-full mx-auto relative">
                    <!-- Modal Header -->
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
                                        <i class="far fa-heart text-xl"></i>
                                    </button>
                                </div>
                                <button id="save-recipe-btn" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors duration-200 mb-4">
                                    Get This Recipe
                                </button>
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
            // Toast notification system
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} z-50 transition-opacity duration-300`;
                toast.innerHTML = message;
                document.body.appendChild(toast);
                
                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 3000);
            }
            
            // Modal functionality
            const modal = document.getElementById('recipe-modal');
            
            function openModal(recipeData) {
                document.getElementById('modal-title').textContent = recipeData.title;
                document.getElementById('modal-image').src = recipeData.image;
                
                // Use time_display for showing cooking time if available
                document.getElementById('modal-time').textContent = recipeData.time_display || recipeData.time;
                
                const ingredientsList = document.getElementById('modal-ingredients');
                ingredientsList.innerHTML = recipeData.ingredients.map(ingredient => 
                    `<li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        ${ingredient}
                    </li>`
                ).join('');
                
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
                
                const nutritionDiv = document.getElementById('modal-nutrition');
                nutritionDiv.innerHTML = Object.entries(recipeData.nutrition).map(([key, value]) =>
                    `<div class="bg-gray-100 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">${key}</div>
                        <div class="font-semibold">${value}</div>
                    </div>`
                ).join('');
                
                // Add source link if available
                const saveButton = document.getElementById('save-recipe-btn');
                
                // Remove any existing source links first to prevent duplicates
                const existingSourceLinks = document.querySelectorAll('.original-recipe-link');
                existingSourceLinks.forEach(link => link.remove());
                
                if (recipeData.sourceUrl) {
                    saveButton.insertAdjacentHTML('afterend', 
                        `<a href="${recipeData.sourceUrl}" target="_blank" class="original-recipe-link block text-center text-green-600 hover:text-green-700 mt-2">
                            <i class="fas fa-external-link-alt mr-1"></i> View Original Recipe
                        </a>`
                    );
                }
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
            
            // Function to get recipe data from a card
            function getRecipeDataFromCard(card) {
                // Log the raw data for debugging
                console.log('Processing recipe card:', {
                    title: card.dataset.title,
                    recipeId: card.dataset.recipeId,
                    rawInstructions: card.dataset.instructions
                });
                
                // Parse instructions to ensure they're valid JSON array
                let parsedInstructions;
                try {
                    // Try to parse as JSON if it's already in JSON format
                    parsedInstructions = JSON.parse(card.dataset.instructions || '[]');
                    console.log('Successfully parsed instructions JSON:', parsedInstructions);
                } catch (e) {
                    console.log('Failed to parse instructions as JSON:', e.message);
                    
                    // If we have HTML instructions, use our extraction function
                    if (card.dataset.instructions && 
                        (card.dataset.instructions.includes('<li>') || 
                         card.dataset.instructions.includes('<ol>') ||
                         card.dataset.instructions.includes('<p>'))) {
                        
                        console.log('Detected HTML in instructions, extracting steps');
                        parsedInstructions = extractStepsFromInstructions(card.dataset.instructions);
                    }
                    // If it's not HTML but still has content
                    else if (card.dataset.instructions && card.dataset.instructions.trim() !== '') {
                        // Skip the default message if that's what we have
                        if (card.dataset.instructions.includes('No detailed instructions available')) {
                            console.log('Found default instruction message, attempting to extract steps anyway');
                            const possibleSteps = card.dataset.instructions
                                .split(/\.\s+|\n/)
                                .filter(step => 
                                    step.trim().length > 10 && 
                                    !step.includes('No detailed instructions available')
                                );
                                
                            if (possibleSteps.length > 0) {
                                console.log('Found actual steps in the instruction text:', possibleSteps);
                                parsedInstructions = possibleSteps;
                            } else {
                                // If we only have the default message with no actual steps
                                console.log('No actual steps found, using generic cooking steps');
                                parsedInstructions = generateCookingSteps(card.dataset.title || '', card.dataset.description || '');
                            }
                        } else {
                            // If it's a plain text instruction
                            console.log('Using plain text instruction as a step');
                            parsedInstructions = [card.dataset.instructions.trim()];
                        }
                    } else {
                        // Default instructions if none provided
                        console.log('No instructions provided, using generic cooking steps');
                        parsedInstructions = generateCookingSteps(card.dataset.title || '', card.dataset.description || '');
                    }
                }
                
                // Make sure parsedInstructions is always an array with at least one item
                if (!Array.isArray(parsedInstructions) || parsedInstructions.length === 0) {
                    console.log('Instructions not in array format or empty, generating generic steps');
                    parsedInstructions = generateCookingSteps(card.dataset.title || '', card.dataset.description || '');
                }

                // Extract recipe data from the card's dataset
                const recipeData = {
                    recipe_id: card.dataset.recipeId,
                    title: card.dataset.title || 'Untitled Recipe',
                    description: card.dataset.description || '',
                    ingredients: JSON.parse(card.dataset.ingredients || '[]'),
                    instructions: parsedInstructions, // Use our properly formatted instructions
                    image: card.dataset.image || '',
                    cooking_time: extractCookingTime(card.dataset.cookingTime),
                    calories: parseInt(card.dataset.calories) || 0,
                    protein: parseFloat(card.dataset.protein) || 0,
                    carbs: parseFloat(card.dataset.carbs) || 0,
                    fats: parseFloat(card.dataset.fats) || 0,
                    servings: parseInt(card.dataset.servings) || 0,
                    diet_type: card.dataset.dietType || 'omnivore',
                    source_url: card.dataset.sourceUrl || ''
                };
                
                console.log('Final processed recipe data:', {
                    id: recipeData.recipe_id,
                    title: recipeData.title,
                    instructions: recipeData.instructions
                });
                
                return recipeData;
            }
            
            // Handle recipe card clicks - now only opening the card (not the modal)
            document.querySelectorAll('.recipe-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Only open modal if the click wasn't on a button
                    if (!e.target.closest('button')) {
                        const recipeData = getRecipeDataFromCard(this);
                        openModal(recipeData);
                    }
                });
            });
            
            // Handle View Recipe button clicks with improved functionality
            document.querySelectorAll('.view-recipe-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent card click
                    
                    const recipeCard = this.closest('.recipe-card');
                    const recipeId = recipeCard.dataset.recipeId;
                    
                    // Add logging to debug
                    console.log('View Recipe clicked - Recipe ID:', recipeId);
                    
                    // Add loading state
                    const originalText = this.textContent.trim();
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';
                    this.disabled = true;
                    
                    // Log before making API request
                    console.log('About to make API request to:', `/api/recipes/${recipeId}`);
                    
                    // Try to get recipe details from the API first
                    fetch(`/api/recipes/${recipeId}`)
                        .then(response => {
                            console.log('API Response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`Recipe details not available. Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Log API response for debugging
                            console.log('Recipe API response:', data);
                            console.log('API instructions available:', !!data.instructions);
                            console.log('API analyzedInstructions available:', !!(data.analyzedInstructions && data.analyzedInstructions.length));
                        
                            // Handle instructions from API response
                            let recipeSteps = [];
                            
                            // First try to use analyzedInstructions (most structured format)
                            if (data.analyzedInstructions && data.analyzedInstructions.length > 0 && 
                                data.analyzedInstructions[0].steps && data.analyzedInstructions[0].steps.length > 0) {
                                console.log('Using analyzed instructions from API');
                                recipeSteps = data.analyzedInstructions[0].steps.map(step => step.step);
                            } 
                            // Then try to use raw instructions text
                            else if (data.instructions && data.instructions.trim() !== '') {
                                console.log('Using raw instructions text from API');
                                // Strip HTML tags
                                const plainText = data.instructions.replace(/<[^>]*>/g, '');
                                // Split by periods or line breaks into steps
                                recipeSteps = plainText
                                    .split(/\.\s+|\n+/)
                                    .map(step => step.trim())
                                    .filter(step => step.length > 5);
                                
                                if (recipeSteps.length === 0) {
                                    // If splitting didn't work, use the whole text as one step
                                    recipeSteps = [plainText.trim()];
                                }
                            } 
                            // Fall back to card data if available
                            else if (recipeCard.dataset.instructions) {
                                console.log('Falling back to card data instructions');
                                recipeSteps = extractStepsFromInstructions(recipeCard.dataset.instructions);
                            } 
                            // Last resort: generate generic steps
                            else {
                                console.log('No instructions found, generating generic steps');
                                recipeSteps = generateCookingSteps(data.title || recipeCard.dataset.title, 
                                                                 data.summary || recipeCard.dataset.description);
                            }
                            
                            // Open modal with API data
                            openModal({
                                id: recipeId,
                                title: data.title || recipeCard.dataset.title,
                                image: data.image || recipeCard.dataset.image,
                                time: data.readyInMinutes || parseInt(recipeCard.dataset.cookingTime) || 0,
                                time_display: `${data.readyInMinutes || parseInt(recipeCard.dataset.cookingTime) || 0} mins`,
                                ingredients: data.extendedIngredients 
                                    ? data.extendedIngredients.map(ing => `${ing.amount || ''} ${ing.unit || ''} ${ing.name || ''}`)
                                    : JSON.parse(recipeCard.dataset.ingredients || '[]'),
                                steps: recipeSteps,
                                nutrition: data.nutrition?.nutrients 
                                    ? formatNutrition(data.nutrition.nutrients)
                                    : generateNutritionFacts(),
                                sourceUrl: data.sourceUrl || recipeCard.dataset.sourceUrl
                            });
                        })
                        .catch(error => {
                            console.warn('Falling back to card data:', error);
                            
                            // Ensure we log the error in detail
                            console.error('Error details:', error.message);
                            
                            // Get recipe data from the card
                            const recipeData = getRecipeDataFromCard(recipeCard);
                            
                            // Ensure the recipe data is complete for display
                            recipeData.id = recipeId;
                            recipeData.title = recipeData.title || recipeCard.dataset.title || 'Recipe Details';
                            recipeData.image = recipeData.image || recipeCard.dataset.image || '/images/default-recipe.jpg';
                            recipeData.time = recipeData.cooking_time || 0;
                            recipeData.time_display = recipeCard.dataset.cookingTimeDisplay || `${recipeData.cooking_time || 0} mins`;
                            
                            // Ensure ingredients are in the expected format
                            if (!recipeData.ingredients || !Array.isArray(recipeData.ingredients) || recipeData.ingredients.length === 0) {
                                recipeData.ingredients = ['Ingredients information not available'];
                            }
                            
                            // Ensure steps are in the expected format
                            if (!recipeData.steps || !Array.isArray(recipeData.steps) || recipeData.steps.length === 0) {
                                const instructions = recipeCard.dataset.instructions;
                                if (instructions && instructions.trim() !== '') {
                                    recipeData.steps = extractStepsFromInstructions(instructions);
                                } else {
                                    recipeData.steps = ['Recipe instructions not available'];
                                }
                            }
                            
                            // Ensure nutrition information is available
                            if (!recipeData.nutrition || Object.keys(recipeData.nutrition).length === 0) {
                                recipeData.nutrition = generateNutritionFacts();
                            }
                            
                            openModal(recipeData);
                            // Display a toast notification that we're using local data
                            showToast('Using locally stored recipe data', 'success');
                        })
                        .finally(() => {
                            // Restore button state
                            this.innerHTML = originalText;
                            this.disabled = false;
                        });
                });
            });
            
            // Handle Get This Recipe button clicks
            document.querySelectorAll('.get-recipe-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent card click
                    
                    const card = this.closest('.recipe-card');
                    if (!card) {
                        console.error('Recipe card not found!');
                        showToast('Error: Could not identify recipe', 'error');
                        return;
                    }
                    
                    const recipeId = card.dataset.recipeId;
                    if (!recipeId) {
                        showToast('Error: Invalid recipe ID', 'error');
                        return;
                    }
                    
                    // Add loading state
                    const originalText = this.textContent.trim();
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';
                    this.disabled = true;
                    
                    // Use the enhanced function to get API instructions
                    saveRecipeWithApiInstructions(recipeId)
                        .then(result => {
                            showToast('Recipe with complete instructions added to your collection!', 'success');
                        })
                        .catch(error => {
                            console.error('Error adding to collection:', error);
                            // Try the original method as fallback
                            console.log('Falling back to original save method');
                            return addToGetRecipes(recipeId);
                        })
                        .then(result => {
                            if (result) {
                                showToast('Recipe added to your collection!', 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Even fallback save failed:', error);
                            showToast(error.message || 'Failed to add recipe to your collection', 'error');
                        })
                        .finally(() => {
                            // Restore button state
                            this.innerHTML = originalText;
                            this.disabled = false;
                        });
                });
            });
            
            // Handle favorite button clicks in cards
            document.querySelectorAll('.favorite-card-btn, .favorite-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent card click or modal from opening
                    
                    // Visual feedback first
                    const heart = this.querySelector('i');
                    heart.classList.toggle('far');
                    heart.classList.toggle('fas');
                    heart.classList.toggle('text-red-500');
                    
                    const recipeCard = this.closest('.recipe-card');
                    const recipeId = recipeCard.dataset.recipeId;
                    
                    // Get recipe data using the same function we use for saving recipes
                    // This ensures consistent processing of instructions as JSON
                    const recipeData = getRecipeDataFromCard(recipeCard);
                    
                    fetch('/favorites/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            recipe_id: recipeId,
                            recipe_data: recipeData
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'added') {
                            // Update all heart icons in this card
                            recipeCard.querySelectorAll('.fa-heart').forEach(icon => {
                                icon.classList.remove('far');
                                icon.classList.add('fas', 'text-red-500');
                            });
                            showToast('Recipe added to favorites', 'success');
                        } else {
                            // Update all heart icons in this card
                            recipeCard.querySelectorAll('.fa-heart').forEach(icon => {
                                icon.classList.remove('fas', 'text-red-500');
                                icon.classList.add('far');
                            });
                            showToast('Recipe removed from favorites', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Failed to update favorites', 'error');
                        // Revert heart icon state
                        heart.classList.toggle('far');
                        heart.classList.toggle('fas');
                        heart.classList.toggle('text-red-500');
                    });
                });
            });
            
            // Handle modal favorite button
            document.getElementById('modal-favorite-btn').addEventListener('click', function() {
                // Get the current recipe ID
                const recipeTitle = document.getElementById('modal-title').textContent;
                const recipeCard = document.querySelector(`.recipe-card[data-title="${recipeTitle}"]`);
                
                if (!recipeCard) {
                    console.error('Could not find recipe card for:', recipeTitle);
                    showToast('Error: Could not identify recipe', 'error');
                    return;
                }
                
                const recipeId = recipeCard.dataset.recipeId;
                
                // Visual feedback first
                const heart = this.querySelector('i');
                heart.classList.toggle('far');
                heart.classList.toggle('fas');
                heart.classList.toggle('text-red-500');
                
                // Get recipe data using the same function used elsewhere for consistency
                const recipeData = getRecipeDataFromCard(recipeCard);
                
                // Use the same endpoint as the card favorite
                fetch('/favorites/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        recipe_id: recipeId,
                        recipe_data: recipeData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Also update the corresponding card if it exists
                    const recipeCard = document.querySelector(`.recipe-card[data-recipe-id="${recipeId}"]`);
                    if (recipeCard) {
                        recipeCard.querySelectorAll('.fa-heart').forEach(icon => {
                            if (data.status === 'added') {
                                icon.classList.remove('far');
                                icon.classList.add('fas', 'text-red-500');
                            } else {
                                icon.classList.remove('fas', 'text-red-500');
                                icon.classList.add('far');
                            }
                        });
                    }
                    
                    showToast(data.status === 'added' ? 'Recipe added to favorites' : 'Recipe removed from favorites', 'success');
                })
                .catch(error => {
                    console.error('Error toggling favorite:', error);
                    showToast('Failed to update favorites', 'error');
                    // Revert heart icon state
                    heart.classList.toggle('far');
                    heart.classList.toggle('fas');
                    heart.classList.toggle('text-red-500');
                });
            });
            
            // Handle "Get This Recipe" button in modal
            document.getElementById('save-recipe-btn').addEventListener('click', function() {
                // Get the current recipe ID
                const recipeTitle = document.getElementById('modal-title').textContent;
                const recipeCard = document.querySelector(`.recipe-card[data-title="${recipeTitle}"]`);
                
                if (!recipeCard) {
                    console.error('Could not find recipe card for:', recipeTitle);
                    // Try a fuzzy match as a fallback
                    const allCards = document.querySelectorAll('.recipe-card');
                    for (const card of allCards) {
                        if (card.dataset.title && 
                            (card.dataset.title.includes(recipeTitle) || 
                             recipeTitle.includes(card.dataset.title))) {
                            console.log('Found fuzzy match for recipe:', card.dataset.title);
                            const recipeId = card.dataset.recipeId;
                            console.log('Modal save button clicked for recipe ID:', recipeId);
                            console.log('Recipe card instructions:', card.dataset.instructions);
                            
                            saveRecipeWithApiInstructions(recipeId);
                            return;
                        }
                    }
                    showToast('Error: Could not identify recipe', 'error');
                    return;
                }
                
                const recipeId = recipeCard.dataset.recipeId;
                console.log('Modal save button clicked for recipe ID:', recipeId);
                console.log('Recipe card instructions:', recipeCard.dataset.instructions);
                
                saveRecipeWithApiInstructions(recipeId);
            });
            
            // Function to save a recipe with complete instructions from API
            async function saveRecipeWithApiInstructions(recipeId) {
                try {
                    // First fetch the detailed recipe information from API
                    const response = await fetch(`/api/recipes/${recipeId}`);
                    if (!response.ok) {
                        throw new Error('Could not fetch recipe details');
                    }
                    
                    const data = await response.json();
                    console.log('Fetched API data for recipe save:', data);
                    
                    // Find the recipe card for basic info
                    const card = document.querySelector(`.recipe-card[data-recipe-id="${recipeId}"]`);
                    if (!card) {
                        throw new Error('Recipe card not found');
                    }
                    
                    // Get basic recipe data from card
                    const recipeData = getRecipeDataFromCard(card);
                    
                    // Format cooking time - extract just the number
                    if (typeof recipeData.cooking_time === 'string' && recipeData.cooking_time.includes('mins')) {
                        // Extract just the number from "30 mins" format
                        const match = recipeData.cooking_time.match(/(\d+)/);
                        if (match && match[1]) {
                            recipeData.cooking_time = parseInt(match[1]);
                            console.log('Extracted cooking time value:', recipeData.cooking_time);
                        }
                    }
                    
                    // Enhance with API instructions if available
                    if (data.analyzedInstructions && data.analyzedInstructions.length > 0 && 
                        data.analyzedInstructions[0].steps && data.analyzedInstructions[0].steps.length > 0) {
                        
                        console.log('Using analyzed instructions from API for saving');
                        recipeData.instructions = data.analyzedInstructions[0].steps.map(step => step.step);
                    } 
                    else if (data.instructions && data.instructions.trim() !== '') {
                        console.log('Using raw instructions text from API for saving');
                        // Strip HTML tags and split into steps
                        const plainText = data.instructions.replace(/<[^>]*>/g, '');
                        const steps = plainText
                            .split(/\.\s+|\n+/)
                            .map(step => step.trim())
                            .filter(step => step.length > 5);
                        
                        if (steps.length > 0) {
                            recipeData.instructions = steps;
                        } else {
                            recipeData.instructions = [plainText.trim()];
                        }
                    }
                    
                    console.log('Saving recipe with enhanced instructions:', recipeData);
                    
                    // Send to server
                    const saveResponse = await fetch('/api/get-recipe/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(recipeData)
                    });
                    
                    if (!saveResponse.ok) {
                        if (saveResponse.status === 401) {
                            window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                            throw new Error('Please log in to save recipes to your collection');
                        }
                        const errorData = await saveResponse.json();
                        throw new Error(errorData.error || `Server error: ${saveResponse.statusText}`);
                    }
                    
                    const result = await saveResponse.json();
                    console.log('API save response:', result);
                    
                    // Show success notification
                    showToast('Recipe with API instructions saved to your collection!', 'success');
                    
                    return result;
                } catch (error) {
                    console.error('Error saving recipe with API instructions:', error);
                    throw error;
                }
            }
            
            // Function to generate cooking steps from recipe title and description
            function generateCookingSteps(title, description) {
                // Generate 6-8 cooking steps based on the recipe
                return [
                    "Prepare all ingredients as listed above.",
                    "Wash and chop all vegetables into appropriate sizes.",
                    "In a large pan, heat 2 tablespoons of oil over medium heat.",
                    "Add aromatics (garlic, onions) and sauté until fragrant, about 2 minutes.",
                    "Add main ingredients and stir well to combine.",
                    "Season with salt, pepper, and spices to taste.",
                    "Cook for 15-20 minutes, stirring occasionally.",
                    "Serve hot and enjoy your meal!"
                ];
            }
            
            // Function to generate random nutrition facts
            function generateNutritionFacts() {
                return {
                    "Calories": Math.floor(Math.random() * 400 + 200) + " kcal",
                    "Protein": Math.floor(Math.random() * 20 + 5) + "g",
                    "Carbs": Math.floor(Math.random() * 50 + 20) + "g",
                    "Fat": Math.floor(Math.random() * 15 + 5) + "g",
                    "Fiber": Math.floor(Math.random() * 5 + 1) + "g",
                    "Sugar": Math.floor(Math.random() * 10 + 1) + "g"
                };
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

            // Helper function to extract steps from HTML instructions
            function extractStepsFromInstructions(instructions) {
                console.log('Extracting steps from instructions:', instructions);
                
                if (!instructions) {
                    console.log('No instructions provided, using generated steps');
                    return generateCookingSteps('', '');
                }
                
                // If instructions is a JSON string, try to parse it
                try {
                    const parsedInstructions = JSON.parse(instructions);
                    if (Array.isArray(parsedInstructions) && parsedInstructions.length > 0) {
                        console.log('Successfully parsed JSON instructions array:', parsedInstructions);
                        return parsedInstructions;
                    }
                } catch (e) {
                    console.log('Instructions is not a valid JSON string:', e);
                    // Not JSON, continue with normal processing
                }
                
                // Try to extract steps from HTML instructions
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = instructions;
                const listItems = tempDiv.querySelectorAll('li');
                
                if (listItems.length > 0) {
                    // Extract steps from list items
                    const steps = Array.from(listItems).map(li => li.textContent.trim());
                    console.log('Extracted steps from HTML list:', steps);
                    return steps;
                } else {
                    // Split by periods or line breaks
                    const steps = instructions
                        .split(/\.\s+|\n/)
                        .map(step => step.trim())
                        .filter(step => step.length > 10); // Only keep substantial steps
                    
                    if (steps.length > 0) {
                        console.log('Extracted steps by splitting text:', steps);
                        return steps;
                    } else {
                        // If the instruction is just a single string that's not empty 
                        // and doesn't look like our default message
                        if (instructions.trim() !== '' && 
                            !instructions.includes('No detailed instructions available')) {
                            console.log('Using single instruction as a step:', instructions);
                            return [instructions.trim()];
                        }
                        
                        console.log('Could not extract valid steps, using generated steps');
                        return generateCookingSteps('', '');
                    }
                }
            }
            
            // Function to format nutrition data
            function formatNutrition(nutrients) {
                const result = {};
                const importantNutrients = ['Calories', 'Protein', 'Carbohydrates', 'Fat', 'Fiber', 'Sugar'];
                
                importantNutrients.forEach(nutrientName => {
                    const nutrient = nutrients.find(n => n.name === nutrientName);
                    if (nutrient) {
                        // Ensure calories are displayed as kcal
                        if (nutrientName === 'Calories') {
                            result[nutrientName] = `${Math.round(nutrient.amount)} kcal`;
                        } else {
                            result[nutrientName] = `${Math.round(nutrient.amount)} ${nutrient.unit}`;
                        }
                    }
                });
                
                return Object.keys(result).length > 0 ? result : generateNutritionFacts();
            }
            
            // Add recipe to Get Recipe collection
            async function addToGetRecipes(recipeId) {
                return new Promise((resolve, reject) => {
                    // Check if we have a valid recipeId
                    if (!recipeId) {
                        reject(new Error('Invalid recipe ID'));
                        return;
                    }
                    
                    // Get the recipe card to extract recipe data
                    const card = document.querySelector(`.recipe-card[data-recipe-id="${recipeId}"]`);
                    if (!card) {
                        reject(new Error('Recipe card not found'));
                        return;
                    }
                    
                    // Add debug logging for the card's dataset
                    console.log('Card dataset:', card.dataset);
                    
                    // Get recipe data using our helper function
                    const recipeData = getRecipeDataFromCard(card);
                    
                    // Enhanced debugging for nutritional values
                    console.log('Recipe data to be saved:', {
                        recipe_id: recipeData.recipe_id,
                        title: recipeData.title,
                        cooking_time: recipeData.cooking_time,
                        calories: recipeData.calories,
                        protein: recipeData.protein,
                        carbs: recipeData.carbs,
                        fats: recipeData.fats,
                        instructions: recipeData.instructions
                    });
                    
                    // Call the API to add to get recipes
                    fetch('/api/get-recipe/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(recipeData)
                    })
                    .then(response => {
                        // Check if the response is ok before parsing
                        if (!response.ok) {
                            console.error('Server returned error:', response.status, response.statusText);
                            // Handle authentication errors specifically
                            if (response.status === 401) {
                                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                                throw new Error('Please log in to save recipes to your collection');
                            }
                            return response.json().then(errorData => {
                                throw new Error(errorData.error || `Server error: ${response.statusText}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('API response:', data);
                        if (data.success || data.status === 'success' || data.status === 'exists') {
                            resolve(data);
                        } else {
                            reject(new Error(data.message || data.error || 'You have already saved this recipe.'));
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to collection:', error);
                        reject(new Error(error.message || 'Failed to add recipe to your collection'));
                    });
                });
            }

            // Helper function to extract cooking time as number
            function extractCookingTime(timeString) {
                if (!timeString) return 0;
                
                // If it's already a number, return it
                if (!isNaN(parseInt(timeString))) {
                    return parseInt(timeString);
                }
                
                // Try to extract a number from strings like "30 mins"
                const match = String(timeString).match(/(\d+)/);
                if (match && match[1]) {
                    return parseInt(match[1]);
                }
                
                return 0;
            }
        </script>
        <x-footer />
    </body>
</html> 