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
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden recipe-card cursor-pointer" data-recipe-id="{{ $recipe->id }}">
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
                                <span>{{ $recipe->cooking_time }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $recipe->difficulty }}</span>
                            </div>
                            <p class="text-gray-600 mb-4">{{ $recipe->description }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-600">No recipes available for {{ $mealType }} at the moment.</p>
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
                                    <button class="text-red-500 hover:text-red-600">
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
            // Modal functionality
            const modal = document.getElementById('recipe-modal');
            
            function openModal(recipeData) {
                document.getElementById('modal-title').textContent = recipeData.title;
                document.getElementById('modal-image').src = recipeData.image;
                document.getElementById('modal-time').textContent = recipeData.time;
                
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
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
            
            // Add click handlers to recipe cards
            document.querySelectorAll('.recipe-card').forEach(card => {
                card.addEventListener('click', function() {
                    const recipeData = {
                        title: "Sample Recipe",
                        image: this.querySelector('img').src,
                        time: "30 mins",
                        ingredients: [
                            "2 cups flour",
                            "3 eggs",
                            "1 cup milk",
                            "2 tbsp butter",
                            "1 tsp salt"
                        ],
                        steps: [
                            "Preheat the oven to 350°F (175°C)",
                            "Mix dry ingredients in a large bowl",
                            "Whisk wet ingredients in a separate bowl",
                            "Combine wet and dry ingredients",
                            "Pour into prepared pan",
                            "Bake for 25-30 minutes"
                        ],
                        nutrition: {
                            "Calories": "350 kcal",
                            "Protein": "8g",
                            "Carbs": "45g",
                            "Fat": "12g",
                            "Fiber": "3g",
                            "Sugar": "2g"
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

            // Add favorite functionality
            document.querySelectorAll('.favorite-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent modal from opening
                    const recipeCard = this.closest('.recipe-card');
                    const recipeId = recipeCard.dataset.recipeId;
                    const heartIcon = this.querySelector('i');

                    fetch('/favorites/toggle', {
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
                        if (data.status === 'added') {
                            heartIcon.classList.remove('far');
                            heartIcon.classList.add('fas', 'text-red-500');
                        } else {
                            heartIcon.classList.remove('fas', 'text-red-500');
                            heartIcon.classList.add('far');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });

            // Save Recipe functionality
            document.getElementById('save-recipe-btn').addEventListener('click', function() {
                const recipeCard = document.querySelector('.recipe-card[data-recipe-id]');
                const recipeId = recipeCard.dataset.recipeId;

                fetch('/save-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        recipe_id: recipeId,
                        saved_date: new Date().toISOString().split('T')[0]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Recipe saved successfully!');
                    } else {
                        alert('You have already saved this recipe today.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error saving recipe');
                });
            });
        </script>
        <x-footer />
    </body>
</html> 