<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Recipe Suggestions - Meal Suggestion</title>
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
                    <select id="cuisine-filter" class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Cuisine Type</option>
                        <option value="italian">Italian</option>
                        <option value="chinese">Chinese</option>
                        <option value="indian">Indian</option>
                        <option value="mexican">Mexican</option>
                        <option value="thai">Thai</option>
                        <option value="japanese">Japanese</option>
                        <option value="mediterranean">Mediterranean</option>
                        <option value="french">French</option>
                    </select>

                    <!-- Meal Type Filter -->
                    <select id="meal-type-filter" class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Meal Type</option>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="snack">Snack</option>
                        <option value="appetizer">Appetizer</option>
                        <option value="dessert">Dessert</option>
                        <option value="salad">Salad</option>
                        <option value="soup">Soup</option>
                    </select>

                    <!-- Cooking Time Filter -->
                    <select id="cooking-time-filter" class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Cooking Time</option>
                        <option value="15">Under 15 mins</option>
                        <option value="30">Under 30 mins</option>
                        <option value="60">Under 1 hour</option>
                        <option value="120">Under 2 hours</option>
                    </select>

                    <!-- Dietary Preferences Filter -->
                    <select id="diet-filter" class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Dietary Preferences</option>
                        <option value="vegetarian">Vegetarian</option>
                        <option value="vegan">Vegan</option>
                        <option value="gluten-free">Gluten Free</option>
                        <option value="keto">Keto</option>
                        <option value="paleo">Paleo</option>
                        <option value="low-carb">Low Carb</option>
                        <option value="dairy-free">Dairy Free</option>
                    </select>

                    <!-- Ingredients Filter -->
                    <select id="ingredients-filter" class="rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        <option value="">Main Ingredient</option>
                        <option value="chicken">Chicken</option>
                        <option value="beef">Beef</option>
                        <option value="fish">Fish</option>
                        <option value="vegetables">Vegetables</option>
                        <option value="pasta">Pasta</option>
                        <option value="rice">Rice</option>
                        <option value="tofu">Tofu</option>
                        <option value="eggs">Eggs</option>
                    </select>

                    <!-- Random Recipe Button -->
                    <button id="surprise-me-btn" class="bg-green-500 text-white rounded-lg px-4 py-2 hover:bg-green-600 transition-colors duration-200">
                        <i class="fas fa-random mr-2"></i>Surprise Me!
                    </button>
                </div>
                
                <!-- Active Filters Display -->
                <div id="active-filters" class="flex flex-wrap gap-2 mb-6 hidden">
                    <!-- Active filters will be displayed here -->
                </div>
            </div>

            <!-- Recipe Cards Grid -->
            <div id="recipe-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Recipe cards will be populated dynamically via JavaScript -->
                <div id="loading-indicator" class="col-span-3 text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-green-500"></i>
                    <p class="mt-2 text-gray-600">Loading recipes...</p>
                </div>
            </div>
            
            <!-- Load More Button Container -->
            <div id="load-more-container" class="text-center mt-8 hidden">
                <div class="flex justify-center items-center space-x-4 mb-4">
                    <button id="prev-page-btn" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-colors duration-200 hidden">
                        <i class="fas fa-chevron-left mr-2"></i>Previous
                    </button>
                    <span id="page-info" class="text-gray-600"></span>
                    <button id="next-page-btn" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-colors duration-200 hidden">
                        Next<i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Recipe Modal -->
        <div id="recipe-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
            <div class="min-h-screen px-4 py-6 flex items-center justify-center">
                <div class="bg-white rounded-lg max-w-3xl w-full mx-auto relative">
                    <!-- Modal Header with close button -->
                    <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
                        <h2 id="modal-title" class="text-2xl font-bold text-gray-800">Recipe Title</h2>
                        <div class="flex items-center space-x-2">
                            <button id="modal-save-recipe-btn" class="text-green-500 hover:text-green-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-bookmark text-xl mr-1"></i>
                                <span class="text-sm font-medium">Get Recipe</span>
                            </button>
                            <button id="modal-favorite-btn" class="text-red-500 hover:text-red-600 transition-colors duration-200">
                                <i class="far fa-heart text-xl"></i>
                            </button>
                            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
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
            const searchInput = document.getElementById('recipe-search');
            const suggestionsBox = document.getElementById('search-suggestions');
            const recipeCardsContainer = document.getElementById('recipe-cards-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const modal = document.getElementById('recipe-modal');
            
            // State
            let currentRecipes = [];
            const RECIPES_PER_API_CALL = 100; // Maximum recipes to fetch from API
            const RECIPES_PER_PAGE = 12; // Number of recipes to display per page
            let currentPage = 1;
            let totalResults = 0;
            let lastSearchPreferences = {};
            let isLoading = false;
            let allRecipes = []; // Store all fetched recipes
            
            // Add debounce helper at the top of script
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Add request cache
            const requestCache = new Map();
            const CACHE_DURATION = 1000 * 60 * 5; // 5 minutes
            
            // Load initial recipes when page loads
            document.addEventListener('DOMContentLoaded', function() {
                // Show initial message instead of loading random recipes
                showInitialMessage();
                
                // Initialize active filters display
                updateActiveFiltersDisplay(getCurrentFilterPreferences());
            });
            
            // Add new function to show initial message
            function showInitialMessage() {
                recipeCardsContainer.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-600 text-lg">Welcome to Recipe Suggestions!</p>
                        <p class="text-gray-500 mt-2">Select a filter option or click "Surprise Me" to discover recipes.</p>
                    </div>
                `;
                
                // Hide pagination container
                document.getElementById('load-more-container').classList.add('hidden');
            }
            
            // Load random recipes
            function loadRandomRecipes(preferences = {}, isFresh = false) {
                showLoading();
                
                // Generate cache key from preferences
                const cacheKey = JSON.stringify(preferences);
                
                // Check cache first (but skip if isFresh is true)
                if (!isFresh) {
                    const cachedData = requestCache.get(cacheKey);
                    if (cachedData && (Date.now() - cachedData.timestamp < CACHE_DURATION)) {
                        console.log('Using cached random recipes');
                        handleRandomRecipesResponse(cachedData.data);
                        hideLoading();
                        return;
                    }
                }
                
                // Log the preferences being used for debugging
                console.log('Loading random recipes with preferences:', preferences);
                
                // Set number of random recipes to 3
                const params = {
                    ...preferences,
                    number: 3 // Always fetch 3 random recipes
                };
                
                // If isFresh is true, always add a timestamp to ensure we get new recipes
                if (isFresh) {
                    params._timestamp = Date.now();
                } else {
                    // Remove the _timestamp from the API request if not fresh
                    if (params._timestamp) delete params._timestamp;
                }
                
                axios.get('/api/recipes/random', { 
                    params: params,
                    timeout: 20000
                })
                    .then(response => {
                        // Cache the successful response
                        requestCache.set(cacheKey, {
                            data: response.data,
                            timestamp: Date.now()
                        });
                        
                        handleRandomRecipesResponse(response.data);
                    })
                    .catch(error => {
                        console.error('Error fetching random recipes:', error);
                        handleRandomRecipesError(error);
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }
            
            // Separate response handling logic
            function handleRandomRecipesResponse(data) {
                if (data && data.error) {
                    console.error('API returned error:', data.error);
                    showError(data.error);
                    return;
                }
                
                if (data && data.recipes && data.recipes.length > 0) {
                    currentRecipes = data.recipes;
                    renderRecipeCards(currentRecipes);
                    console.log('Rendered', currentRecipes.length, 'random recipe cards');
                    
                    // Hide pagination for random recipes since we only show 3
                    document.getElementById('load-more-container').classList.add('hidden');
                    
                    // Update the results message for random recipes
                    updateResultsCountMessage(currentRecipes.length, null, true);
                } else {
                    showNoRecipesMessage();
                }
            }
            
            // Separate error handling logic
            function handleRandomRecipesError(error) {
                if (error.code === 'ECONNABORTED') {
                    showError('Request timed out. Please try again with simpler preferences.');
                } else if (error.response) {
                    const errorMessage = error.response.data?.error || 
                        `Request failed with status ${error.response.status}. Please try again later.`;
                    showError(errorMessage);
                } else if (error.request) {
                    showError('No response received from server. Please check your internet connection.');
                } else {
                    showError('Failed to load recipes. Please try again later.');
                }
            }
            
            // Helper function to update results count message
            function updateResultsCountMessage(count, total = null, isRandom = false) {
                const existingMessages = document.querySelectorAll('.results-count-message');
                existingMessages.forEach(msg => msg.remove());
                
                const resultsMessage = document.createElement('div');
                resultsMessage.className = 'text-center text-gray-600 mb-4 results-count-message';
                
                if (isRandom) {
                    // For random recipes
                    resultsMessage.textContent = `Showing ${count} suggested recipes based on your preferences`;
                } else if (total !== null) {
                    // For paginated search results
                    const displayTotal = total > 100 ? 100 : total;
                    const startRange = Math.min(((currentPage - 1) * RECIPES_PER_PAGE) + 1, displayTotal);
                    const endRange = Math.min(currentPage * RECIPES_PER_PAGE, displayTotal);
                    resultsMessage.textContent = `Showing ${startRange}-${endRange} of ${displayTotal} recipes`;
                    
                    // Update pagination controls with actual total for proper pagination
                    updatePaginationControls(total);
                } else {
                    // For non-paginated results
                    resultsMessage.textContent = `Showing ${count} recipes`;
                }
                
                recipeCardsContainer.parentNode.insertBefore(resultsMessage, recipeCardsContainer);
            }

            // Update pagination controls
            function updatePaginationControls(total) {
                const totalPages = Math.ceil(total / RECIPES_PER_PAGE);
                const loadMoreContainer = document.getElementById('load-more-container');
                const prevPageBtn = document.getElementById('prev-page-btn');
                const nextPageBtn = document.getElementById('next-page-btn');
                const pageInfo = document.getElementById('page-info');
                
                if (total > 0) {
                    loadMoreContainer.classList.remove('hidden');
                    
                    const displayTotal = total > 100 ? 100 : total;
                    const startRange = Math.min(((currentPage - 1) * RECIPES_PER_PAGE) + 1, displayTotal);
                    const endRange = Math.min(currentPage * RECIPES_PER_PAGE, displayTotal);
                    
                    // Use the same format as the results count message
                    pageInfo.textContent = `Showing ${startRange}-${endRange} of ${displayTotal} recipes`;
                    
                    prevPageBtn.classList.toggle('hidden', currentPage <= 1);
                    nextPageBtn.classList.toggle('hidden', currentPage >= totalPages);
                    
                    prevPageBtn.disabled = currentPage <= 1;
                    nextPageBtn.disabled = currentPage >= totalPages;
                    
                    if (totalPages <= 1) {
                        loadMoreContainer.classList.add('hidden');
                    }
                } else {
                    loadMoreContainer.classList.add('hidden');
                }
            }

            // Add event listeners for pagination buttons
            document.getElementById('prev-page-btn').addEventListener('click', function() {
                if (currentPage > 1 && !isLoading) {
                    currentPage--;
                    
                    // Check if we need to fetch new data from API
                    if ((currentPage - 1) * RECIPES_PER_PAGE % RECIPES_PER_API_CALL === 0) {
                        searchRecipes(lastSearchPreferences);
                    } else {
                        // Use existing data
                        const startIdx = ((currentPage - 1) * RECIPES_PER_PAGE) % RECIPES_PER_API_CALL;
                        const endIdx = Math.min(startIdx + RECIPES_PER_PAGE, allRecipes.length);
                        currentRecipes = allRecipes.slice(startIdx, endIdx);
                        renderRecipeCards(currentRecipes, false);
                        updateResultsCountMessage(currentRecipes.length, totalResults);
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });

            document.getElementById('next-page-btn').addEventListener('click', function() {
                const totalPages = Math.ceil(totalResults / RECIPES_PER_PAGE);
                if (currentPage < totalPages && !isLoading) {
                    currentPage++;
                    
                    // Check if we need to fetch new data from API
                    if ((currentPage - 1) * RECIPES_PER_PAGE % RECIPES_PER_API_CALL === 0) {
                        searchRecipes(lastSearchPreferences);
                    } else {
                        // Use existing data
                        const startIdx = ((currentPage - 1) * RECIPES_PER_PAGE) % RECIPES_PER_API_CALL;
                        const endIdx = Math.min(startIdx + RECIPES_PER_PAGE, allRecipes.length);
                        currentRecipes = allRecipes.slice(startIdx, endIdx);
                        renderRecipeCards(currentRecipes, false);
                        updateResultsCountMessage(currentRecipes.length, totalResults);
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });

            // Search for recipes
            function searchRecipes(preferences = {}) {
                if (isLoading) return;
                
                isLoading = true;
                showLoading();
                
                lastSearchPreferences = { ...preferences };
                
                const searchParams = {
                    ...preferences,
                    pageSize: RECIPES_PER_API_CALL,
                    offset: Math.floor((currentPage - 1) * RECIPES_PER_PAGE / RECIPES_PER_API_CALL) * RECIPES_PER_API_CALL
                };
                
                console.log('Searching recipes with preferences:', searchParams);
                
                if (Object.keys(preferences).length === 0) {
                    console.log('No search preferences provided, loading random recipes instead');
                    loadRandomRecipes();
                    isLoading = false;
                    return;
                }
                
                axios.get('/api/recipes/search', { 
                    params: searchParams,
                    timeout: 30000
                })
                    .then(response => {
                        console.log('Search API response:', response.data);
                        
                        if (response.data && response.data.error) {
                            console.error('API returned error:', response.data.error);
                            showError(response.data.error);
                            return;
                        }
                        
                        if (response.data && response.data.results && response.data.results.length > 0) {
                            totalResults = response.data.totalResults || 0;
                            allRecipes = response.data.results;
                            
                            // Calculate start and end indices for current page
                            const startIdx = ((currentPage - 1) * RECIPES_PER_PAGE) % RECIPES_PER_API_CALL;
                            const endIdx = Math.min(startIdx + RECIPES_PER_PAGE, allRecipes.length);
                            
                            // Get recipes for current page
                            currentRecipes = allRecipes.slice(startIdx, endIdx);
                            
                            renderRecipeCards(currentRecipes, false);
                            console.log('Rendered', currentRecipes.length, 'recipe cards');
                            
                            // Only show results count message if we have recipes
                            updateResultsCountMessage(currentRecipes.length, totalResults);
                            
                            const loadMoreContainer = document.getElementById('load-more-container');
                            if (totalResults > RECIPES_PER_PAGE) {
                                loadMoreContainer.classList.remove('hidden');
                                
                                const prevPageBtn = document.getElementById('prev-page-btn');
                                const nextPageBtn = document.getElementById('next-page-btn');
                                
                                prevPageBtn.classList.toggle('hidden', currentPage <= 1);
                                nextPageBtn.classList.toggle('hidden', currentPage >= Math.ceil(totalResults / RECIPES_PER_PAGE));
                            } else {
                                loadMoreContainer.classList.add('hidden');
                            }
                        } else {
                            console.log('No results found with current preferences');
                            // Clear any existing results count message
                            const existingMessages = document.querySelectorAll('.results-count-message');
                            existingMessages.forEach(msg => msg.remove());
                            showNoRecipesMessage();
                            document.getElementById('load-more-container').classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        handleSearchError(error);
                        document.getElementById('load-more-container').classList.add('hidden');
                    })
                    .finally(() => {
                        isLoading = false;
                        hideLoading();
                    });
            }
            
            // Get recipe details by ID
            function getRecipeDetails(id) {
                showLoading();
                
                axios.get(`/api/recipes/${id}`, {
                    timeout: 20000
                })
                    .then(response => {
                        // Check if there's an error message in the response
                        if (response.data && response.data.error) {
                            console.error('API returned error:', response.data.error);
                            alert(response.data.error);
                            return;
                        }
                        
                        if (response.data) {
                            openModal(formatRecipeForModal(response.data));
                        } else {
                            alert('Recipe details not found.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching recipe details:', error);
                        
                        // Check if it's a timeout error
                        if (error.code === 'ECONNABORTED') {
                            alert('Request timed out. Please try again later.');
                        } else if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.error('Error response data:', error.response.data);
                            console.error('Error response status:', error.response.status);
                            
                            // Use the error message from the API if available
                            const errorMessage = error.response.data && error.response.data.error 
                                ? error.response.data.error 
                                : `Failed to load recipe details (Status: ${error.response.status}). Please try again later.`;
                            
                            alert(errorMessage);
                        } else if (error.request) {
                            // The request was made but no response was received
                            console.error('Error request:', error.request);
                            alert('No response received from server. Please check your internet connection.');
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            alert('Failed to load recipe details. Please try again later.');
                        }
                    })
                    .finally(() => {
                        hideLoading();
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
                }
                
                // If still no steps, provide a fallback message
                if (steps.length === 0) {
                    steps = ["No detailed cooking instructions available for this recipe. You might want to try another recipe or search for cooking instructions online."];
                    console.warn('No cooking instructions found for recipe:', recipe.id || 'unknown');
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
                }
                
                return {
                    id: recipe.id ? recipe.id.toString() : null,
                    title: recipe.title || 'Recipe',
                    image: recipe.image || 'https://via.placeholder.com/300x200?text=No+Image+Available',
                    time: recipe.readyInMinutes ? `${recipe.readyInMinutes} mins` : 'Unknown',
                    ingredients: ingredients,
                    steps: steps,
                    nutrition: nutrition
                };
            }
            
            // Render recipe cards
            function renderRecipeCards(recipes, append = false) {
                if (!append) {
                    recipeCardsContainer.innerHTML = '';
                }
                
                if (!recipes || recipes.length === 0) {
                    if (!append) {
                        showNoRecipesMessage();
                    }
                    return;
                }
                
                console.log(`Rendering ${recipes.length} recipe cards`);
                
                // Create a document fragment to batch DOM operations
                const fragment = document.createDocumentFragment();
                
                // Create cards
                recipes.forEach(recipe => {
                    if (!recipe.id) {
                        console.warn('Recipe missing ID:', recipe);
                        return;
                    }
                    
                    const card = createRecipeCard(recipe);
                    fragment.appendChild(card);
                });
                
                // Batch append all cards at once
                recipeCardsContainer.appendChild(fragment);
                
                // Initialize lazy loading for images
                initializeLazyLoading();
                
                // Add event listeners to the new cards
                addRecipeCardEventListeners();
                
                console.log('Recipe cards rendered successfully');
            }
            
            function createRecipeCard(recipe) {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 recipe-card cursor-pointer';
                
                // Ensure recipe ID is a string and not undefined
                const recipeId = recipe.id ? recipe.id.toString() : null;
                if (!recipeId) {
                    console.error('Recipe missing ID:', recipe);
                    return document.createElement('div'); // Return empty div if recipe has no ID
                }
                
                // Process and format recipe data for storage
                const formattedInstructions = [];
                if (recipe.analyzedInstructions && recipe.analyzedInstructions.length > 0 && recipe.analyzedInstructions[0].steps) {
                    formattedInstructions.push(...recipe.analyzedInstructions[0].steps.map(step => step.step));
                } else if (recipe.instructions) {
                    formattedInstructions.push(...recipe.instructions.split(/\.\s+|\n+/)
                        .map(step => step.trim())
                        .filter(step => step.length > 0)
                        .map(step => step.endsWith('.') ? step : step + '.'));
                }
                
                // Format ingredients for storage
                const formattedIngredients = [];
                if (recipe.extendedIngredients && Array.isArray(recipe.extendedIngredients)) {
                    recipe.extendedIngredients.forEach(ing => {
                        formattedIngredients.push(`${ing.amount || ''} ${ing.unit || ''} ${ing.name || ''}`);
                    });
                }
                
                // Extract nutrition data
                const nutritionData = {};
                if (recipe.nutrition && recipe.nutrition.nutrients) {
                    recipe.nutrition.nutrients.forEach(nutrient => {
                        nutritionData[nutrient.name] = {
                            amount: nutrient.amount,
                            unit: nutrient.unit
                        };
                    });
                }
                
                card.dataset.recipeId = recipeId;
                card.dataset.title = recipe.title || '';
                card.dataset.description = recipe.summary || recipe.description || '';
                card.dataset.ingredients = JSON.stringify(formattedIngredients);
                card.dataset.instructions = JSON.stringify(formattedInstructions);
                card.dataset.cookingTime = recipe.readyInMinutes || '0';
                card.dataset.servings = recipe.servings || '0';
                
                // Enhanced nutrition data
                card.dataset.calories = nutritionData.Calories ? nutritionData.Calories.amount : '0';
                card.dataset.protein = nutritionData.Protein ? nutritionData.Protein.amount : '0';
                card.dataset.carbs = nutritionData.Carbohydrates ? nutritionData.Carbohydrates.amount : '0';
                card.dataset.fats = nutritionData.Fat ? nutritionData.Fat.amount : '0';
                
                card.dataset.dietType = recipe.diets?.[0] || 'omnivore';
                card.dataset.image = recipe.image || 'https://via.placeholder.com/300x200?text=No+Image+Available';
                card.dataset.sourceUrl = recipe.sourceUrl || '';
                
                const imageUrl = recipe.image || 'https://via.placeholder.com/300x200?text=No+Image+Available';
                const title = recipe.title || 'Untitled Recipe';
                const cuisineType = recipe.cuisines?.[0] || recipe.dishTypes?.[0] || 'Various';
                const dietType = recipe.diets?.[0] || 'omnivore';
                const readyTime = recipe.readyInMinutes || '?';
                const calories = getCaloriesString(recipe);
                
                card.innerHTML = `
                    <div class="relative">
                        <img data-src="${imageUrl}" 
                             alt="${title}" 
                             class="w-full h-48 object-cover lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
                        <div class="absolute top-2 right-2">
                            <button class="favorite-btn text-gray-400 hover:text-red-500 transition-colors duration-200">
                                <i class="far fa-heart text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">${title}</h2>
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span class="capitalize">${dietType}</span>
                            <span>${readyTime} mins</span>
                        </div>
                        <div class="text-gray-500 mb-4">${calories}</div>
                        <div class="flex space-x-2 mt-3">
                            <button class="view-recipe-btn bg-green-500 hover:bg-green-600 text-white py-2 px-3 rounded-md text-sm flex-1 transition-colors duration-200">
                                View Recipe
                            </button>
                            <button class="save-recipe-btn bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-md text-sm flex-1 transition-colors duration-200">
                                <i class="fas fa-bookmark mr-1"></i> Save Recipe
                            </button>
                        </div>
                    </div>
                `;
                
                return card;
            }
            
            function getCaloriesString(recipe) {
                if (recipe.nutrition?.nutrients) {
                    const calorieInfo = recipe.nutrition.nutrients.find(n => n.name === 'Calories');
                    if (calorieInfo) {
                        return `${Math.round(calorieInfo.amount)} ${calorieInfo.unit}`;
                    }
                }
                return '';
            }
            
            function initializeLazyLoading() {
                const lazyImages = document.querySelectorAll('img.lazy');
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    });
                });
                
                lazyImages.forEach(img => imageObserver.observe(img));
            }
            
            function showNoRecipesMessage() {
                recipeCardsContainer.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-600">No recipes found. Try different search criteria.</p>
                    </div>
                `;
            }
            
            // Add event listeners to recipe cards
            function addRecipeCardEventListeners() {
                // Add click event to favorite buttons
                document.querySelectorAll('.favorite-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent card click event
                        
                        // Visual feedback first
                        const heart = this.querySelector('i');
                        heart.classList.toggle('far');
                        heart.classList.toggle('fas');
                        heart.classList.toggle('text-red-500');
                        
                        // Get the recipe ID from the parent card
                        const card = this.closest('.recipe-card');
                        if (!card) {
                            console.error('Recipe card not found!');
                            showToast('Error: Could not identify recipe', 'error');
                            return;
                        }
                        
                        const recipeId = card.dataset.recipeId;
                        if (!recipeId || recipeId === 'undefined' || recipeId === 'null') {
                            console.error('Recipe ID not valid!', card);
                            showToast('Error: Invalid recipe ID', 'error');
                            return;
                        }
                        
                        // Call the toggle favorite function with the button for context
                        toggleFavorite(recipeId, this);
                    });
                });
                
                // Add click event to View Recipe buttons
                document.querySelectorAll('.view-recipe-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent card click event
                        
                        // Get the recipe ID from the parent card
                        const card = this.closest('.recipe-card');
                        if (!card) {
                            console.error('Recipe card not found!');
                            showToast('Error: Could not identify recipe', 'error');
                            return;
                        }
                        
                        const recipeId = card.dataset.recipeId;
                        getRecipeDetails(recipeId);
                    });
                });
                
                // Add click event to Save Recipe buttons
                document.querySelectorAll('.save-recipe-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent card click event
                        
                        // Get the recipe ID from the parent card
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
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
                        this.disabled = true;
                        
                        // Add to get recipes collection
                        addToGetRecipes(recipeId)
                            .then(result => {
                                showToast('Recipe saved to your collection!', 'success');
                            })
                            .catch(error => {
                                console.error('Error saving recipe:', error);
                                showToast('Failed to save recipe to your collection', 'error');
                            })
                            .finally(() => {
                                // Restore button state
                                this.innerHTML = originalText;
                                this.disabled = false;
                            });
                    });
                });
                
                // Add click event to recipe cards
                document.querySelectorAll('.recipe-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const recipeId = this.dataset.recipeId;
                        getRecipeDetails(recipeId);
                    });
                });
            }

            // Toggle favorite recipe
            async function toggleFavorite(recipeId, button) {
                try {
                    // Make sure we have a valid recipeId
                    if (!recipeId || recipeId === 'undefined' || recipeId === 'null') {
                        console.error('Invalid recipe ID:', recipeId);
                        throw new Error('Invalid recipe ID');
                    }
                    
                    let recipeData = null;
                    
                    // Check if button is in the modal
                    const isModalButton = button.id === 'modal-favorite-btn' || button.closest('#recipe-detail-modal');
                    
                    if (isModalButton) {
                        // Get recipe data from the modal
                        const title = document.getElementById('modal-title').textContent;
                        if (!title) {
                            console.error('Recipe title not found in modal');
                            throw new Error('Recipe title is required');
                        }
                        
                        // Create recipe data object from modal content
                        recipeData = {
                            title: title,
                            description: '',
                            ingredients: [],
                            instructions: [],
                            readyInMinutes: 0,
                            calories: 0,
                            dietType: 'omnivore',
                            image: document.getElementById('modal-image').src || '',
                            sourceUrl: ''
                        };
                        
                        console.log('Modal favorite toggle for recipe:', recipeId, recipeData);
                    } else {
                        // Get recipe data from the card (original behavior)
                        const card = button.closest('.recipe-card');
                        if (!card) {
                            console.error('Recipe card not found for button:', button);
                            throw new Error('Recipe card not found');
                        }

                        // Ensure we have at least the title
                        const title = card.dataset.title;
                        if (!title) {
                            console.error('Recipe title is missing for card:', card);
                            throw new Error('Recipe title is required');
                        }

                        recipeData = {
                            title: title,
                            description: card.dataset.description || '',
                            ingredients: JSON.parse(card.dataset.ingredients || '[]'),
                            instructions: JSON.parse(card.dataset.instructions || '[]'),
                            readyInMinutes: parseInt(card.dataset.cookingTime || '0'),
                            calories: parseInt(card.dataset.calories || '0'),
                            dietType: card.dataset.dietType || 'omnivore',
                            image: card.dataset.image || '',
                            sourceUrl: card.dataset.sourceUrl || ''
                        };
                        
                        console.log('Card favorite toggle for recipe:', recipeId, recipeData);
                    }

                    // Make sure recipeId is a string
                    const cleanRecipeId = String(recipeId).trim();
                    
                    // Log the exact data being sent to the server
                    console.log('Sending to server:', {
                        recipe_id: cleanRecipeId,
                        recipe_data: recipeData
                    });
                    
                    // Send the data to the server
                    const response = await axios.post('/favorites/toggle', {
                        recipe_id: cleanRecipeId,
                        recipe_data: recipeData
                    });

                    console.log('Server response:', response.data);

                    if (response.data.status === 'added') {
                        showToast('Recipe added to favorites', 'success');
                    } else if (response.data.status === 'removed') {
                        showToast('Recipe removed from favorites', 'success');
                    } else {
                        throw new Error(response.data.message || 'Failed to update favorites');
                    }
                } catch (error) {
                    console.error('Error toggling favorite:', error);
                    showToast(error.message || 'Failed to update favorites', 'error');
                    // Revert the heart icon state
                    const heart = button.querySelector('i');
                    if (heart) {
                        heart.classList.toggle('far');
                        heart.classList.toggle('fas');
                        heart.classList.toggle('text-red-500');
                    }
                }
            }

            // Add recipe to daily meal plan
            async function addToDailyMeal(recipeId) {
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
                    
                    // Extract recipe data from the card's dataset
                    const recipeData = {
                        recipe_id: recipeId,
                        title: card.dataset.title || 'Untitled Recipe',
                        image: card.dataset.image || '',
                        cooking_time: parseInt(card.dataset.cookingTime) || 0,
                        calories: parseInt(card.dataset.calories) || 0,
                        diet_type: card.dataset.dietType || 'omnivore',
                        meal_type: 'dinner', // Default to dinner, can be changed by user
                        date: new Date().toISOString().slice(0, 10) // Today's date in YYYY-MM-DD format
                    };
                    
                    // Call the API to add to daily meal
                    axios.post('/api/meals/add', recipeData)
                        .then(response => {
                            if (response.data.error) {
                                reject(new Error(response.data.error));
                                return;
                            }
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.error('Error adding to meal plan:', error);
                            
                            if (error.response && error.response.status === 401) {
                                // User is not logged in
                                showToast('Please log in to add recipes to your meal plan', 'error');
                                // Redirect to login page after a short delay
                                setTimeout(() => {
                                    window.location.href = '/login';
                                }, 2000);
                                reject(new Error('Authentication required'));
                                return;
                            }
                            
                            const errorMessage = error.response?.data?.message || error.message || 'Failed to add recipe to meal plan';
                            reject(new Error(errorMessage));
                        });
                });
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
                    
                    // Extract recipe data from the card's dataset
                    const recipeData = {
                        recipe_id: recipeId,
                        title: card.dataset.title || 'Untitled Recipe',
                        description: card.dataset.description || '',
                        // Parse JSON strings back to arrays
                        ingredients: JSON.parse(card.dataset.ingredients || '[]'),
                        instructions: JSON.parse(card.dataset.instructions || '[]'),
                        image: card.dataset.image || '',
                        cooking_time: parseCookingTime(card.dataset.cookingTime) || 0,
                        calories: parseNutritionValue(card.dataset.calories) || 0,
                        protein: parseNutritionValue(card.dataset.protein) || 0,
                        carbs: parseNutritionValue(card.dataset.carbs) || 0,
                        fats: parseNutritionValue(card.dataset.fats) || 0,
                        servings: parseInt(card.dataset.servings) || 0,
                        diet_type: card.dataset.dietType || 'omnivore',
                        source_url: card.dataset.sourceUrl || '',
                        notes: '' // Optional notes about the recipe
                    };
                    
                    // Ensure instructions is an array
                    if (!Array.isArray(recipeData.instructions)) {
                        if (typeof recipeData.instructions === 'string') {
                            // Try to parse as JSON if it's a string
                            try {
                                recipeData.instructions = JSON.parse(recipeData.instructions);
                            } catch (e) {
                                // If parsing fails, convert to array with single item
                                recipeData.instructions = [recipeData.instructions];
                            }
                        } else {
                            // Default to empty array if not string or array
                            recipeData.instructions = [];
                        }
                    }
                    
                    // Ensure ingredients is an array
                    if (!Array.isArray(recipeData.ingredients)) {
                        if (typeof recipeData.ingredients === 'string') {
                            // Try to parse as JSON if it's a string
                            try {
                                recipeData.ingredients = JSON.parse(recipeData.ingredients);
                            } catch (e) {
                                // If parsing fails, convert to array with single item
                                recipeData.ingredients = [recipeData.ingredients];
                            }
                        } else {
                            // Default to empty array if not string or array
                            recipeData.ingredients = [];
                        }
                    }
                    
                    console.log('Sending recipe data to server:', recipeData);
                    
                    // Call the API to add to get recipes
                    axios.post('/api/get-recipe/add', recipeData)
                        .then(response => {
                            if (response.data.error) {
                                reject(new Error(response.data.error));
                                return;
                            }
                            
                            // Handle existing recipe response
                            if (response.data.status === 'exists') {
                                showToast('This recipe is already in your collection', 'success');
                            } else {
                                showToast('Recipe saved to your collection', 'success');
                            }
                            
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.error('Error saving recipe:', error);
                            
                            if (error.response && error.response.status === 401) {
                                // User is not logged in
                                showToast('Please log in to save recipes to your collection', 'error');
                                // Redirect to login page after a short delay
                                setTimeout(() => {
                                    window.location.href = '/login';
                                }, 2000);
                                reject(new Error('Authentication required'));
                                return;
                            }
                            
                            const errorMessage = error.response?.data?.message || error.message || 'Failed to save recipe to your collection';
                            reject(new Error(errorMessage));
                        });
                });
            }

            // Show toast notification
            function showToast(message, type = 'success') {
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
                
                document.body.appendChild(toast);
                
                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.style.transform = 'translateY(100%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
            
            // Show loading indicator
            function showLoading() {
                loadingIndicator.classList.remove('hidden');
            }
            
            // Hide loading indicator
            function hideLoading() {
                loadingIndicator.classList.add('hidden');
            }
            
            // Show error message
            function showError(message) {
                recipeCardsContainer.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <p class="text-red-500">${message}</p>
                    </div>
                `;
            }
            
            // Debounced search function
            const debouncedSearch = debounce((preferences) => {
                searchRecipes(preferences);
            }, 300);

            // Helper function to get current filter preferences
            function getCurrentFilterPreferences() {
                const ingredients = document.getElementById('ingredients-filter').value;
                return {
                    cuisine: document.getElementById('cuisine-filter').value,
                    mealType: document.getElementById('meal-type-filter').value,
                    cookingTime: document.getElementById('cooking-time-filter').value,
                    diet: document.getElementById('diet-filter').value,
                    // Use query parameter when ingredient is selected from dropdown
                    query: ingredients || searchInput.value.trim()
                };
            }

            // Modify the filter change handler to trigger search
            const filterChangeHandler = debounce(() => {
                const preferences = getCurrentFilterPreferences();
                
                // Only search if at least one filter is selected or there's a search query
                const hasActiveFilters = Object.values(preferences).some(val => val !== '');
                if (hasActiveFilters) {
                    updateActiveFiltersDisplay(preferences);
                    searchRecipes(preferences);
                } else {
                    showInitialMessage();
                }
            }, 300);

            // Modify search input event listener
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.trim();
                const ingredients = document.getElementById('ingredients-filter').value;
                
                if (searchTerm.length > 2 || ingredients) {
                    // Show suggestions box
                    suggestionsBox.classList.remove('hidden');
                    
                    // Get current filter values
                    const currentPreferences = getCurrentFilterPreferences();
                    
                    // Debounce the search
                    debouncedSearch(currentPreferences);
                } else {
                    suggestionsBox.classList.add('hidden');
                    if (searchTerm.length === 0) {
                        // If search is cleared, check if there are any active filters
                        const filterPreferences = getCurrentFilterPreferences();
                        const hasActiveFilters = Object.values(filterPreferences).some(val => val !== '');
                        
                        if (hasActiveFilters) {
                            debouncedSearch(filterPreferences);
                        } else {
                            showInitialMessage();
                        }
                    }
                }
            });

            // Optimize filter change handling
            document.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', filterChangeHandler);
            });

            // Optimize search button click
            document.querySelector('.bg-green-500.rounded-r-full').addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                const preferences = getCurrentFilterPreferences();
                
                if (searchTerm) {
                    preferences.query = searchTerm;
                }
                
                updateActiveFiltersDisplay(preferences);
                searchRecipes(preferences);
            });
            
            // Update active filters display
            function updateActiveFiltersDisplay(preferences) {
                const activeFiltersContainer = document.getElementById('active-filters');
                let hasActiveFilters = false;
                
                // Clear current filters
                activeFiltersContainer.innerHTML = '';
                
                // Helper function to get display text for a filter value
                function getFilterDisplayText(filterId, value) {
                    const select = document.getElementById(filterId);
                    const option = Array.from(select.options).find(opt => opt.value === value);
                    return option ? option.textContent : value;
                }
                
                // Add filter badges for each active filter
                if (preferences.query) {
                    addFilterBadge('Search', preferences.query);
                    hasActiveFilters = true;
                }
                
                if (preferences.cuisine) {
                    addFilterBadge('Cuisine', getFilterDisplayText('cuisine-filter', preferences.cuisine));
                    hasActiveFilters = true;
                }
                
                if (preferences.mealType) {
                    addFilterBadge('Meal Type', getFilterDisplayText('meal-type-filter', preferences.mealType));
                    hasActiveFilters = true;
                }
                
                if (preferences.cookingTime) {
                    addFilterBadge('Cooking Time', getFilterDisplayText('cooking-time-filter', preferences.cookingTime));
                    hasActiveFilters = true;
                }
                
                if (preferences.diet) {
                    addFilterBadge('Diet', getFilterDisplayText('diet-filter', preferences.diet));
                    hasActiveFilters = true;
                }
                
                if (preferences.ingredients) {
                    addFilterBadge('Ingredient', getFilterDisplayText('ingredients-filter', preferences.ingredients));
                    hasActiveFilters = true;
                }
                
                // Show or hide the container based on whether there are active filters
                if (hasActiveFilters) {
                    activeFiltersContainer.classList.remove('hidden');
                    
                    // Add a "Clear All" button if there are active filters
                    const clearAllBtn = document.createElement('button');
                    clearAllBtn.className = 'px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm flex items-center hover:bg-gray-300';
                    clearAllBtn.innerHTML = 'Clear All <i class="fas fa-times ml-2"></i>';
                    clearAllBtn.addEventListener('click', clearAllFilters);
                    activeFiltersContainer.appendChild(clearAllBtn);
                } else {
                    activeFiltersContainer.classList.add('hidden');
                }
                
                // Helper function to add a filter badge
                function addFilterBadge(label, value) {
                    const badge = document.createElement('div');
                    badge.className = 'px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm flex items-center';
                    badge.innerHTML = `
                        <span class="font-semibold mr-1">${label}:</span> 
                        <span>${value}</span>
                        <button class="ml-2 text-green-600 hover:text-green-800" data-filter="${label.toLowerCase()}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    // Add click event to remove this specific filter
                    const removeBtn = badge.querySelector('button');
                    removeBtn.addEventListener('click', function() {
                        const filterType = this.dataset.filter;
                        clearFilter(filterType);
                    });
                    
                    activeFiltersContainer.appendChild(badge);
                }
            }
            
            // Clear all filters
            function clearAllFilters() {
                currentPage = 1;
                totalResults = 0;
                lastSearchPreferences = {};
                
                // Reset all select elements
                document.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });
                
                // Clear search input
                searchInput.value = '';
                
                // Hide active filters display
                document.getElementById('active-filters').classList.add('hidden');
                
                // Hide pagination container
                document.getElementById('load-more-container').classList.add('hidden');
                
                // Load random recipes without any filters
                loadRandomRecipes();
            }
            
            // Clear a specific filter
            function clearFilter(filterType) {
                currentPage = 1;
                totalResults = 0;
                lastSearchPreferences = {};
                
                switch(filterType) {
                    case 'search':
                        searchInput.value = '';
                        break;
                    case 'cuisine':
                        document.getElementById('cuisine-filter').selectedIndex = 0;
                        break;
                    case 'meal':
                    case 'meal type':
                        document.getElementById('meal-type-filter').selectedIndex = 0;
                        break;
                    case 'cooking':
                    case 'cooking time':
                        document.getElementById('cooking-time-filter').selectedIndex = 0;
                        break;
                    case 'diet':
                    case 'dietary preferences':
                        document.getElementById('diet-filter').selectedIndex = 0;
                        break;
                    case 'ingredient':
                    case 'main ingredient':
                        document.getElementById('ingredients-filter').selectedIndex = 0;
                        break;
                }
                
                // Update recipes with the new filter settings
                const preferences = getCurrentFilterPreferences();
                if (searchInput.value.trim()) {
                    preferences.query = searchInput.value.trim();
                }
                
                // Update active filters display
                updateActiveFiltersDisplay(preferences);
                
                searchRecipes(preferences);
            }
            
            // Random recipe button with consideration for current filters
            document.querySelector('#surprise-me-btn').addEventListener('click', function() {
                // Reset pagination state since we're showing random recipes
                currentPage = 1;
                totalResults = 0;
                allRecipes = [];
                
                // Get current filter preferences to influence random selection
                const currentPreferences = getCurrentFilterPreferences();
                
                // Only include non-empty preferences
                const filteredPreferences = Object.entries(currentPreferences)
                    .filter(([_, value]) => value !== '')
                    .reduce((obj, [key, value]) => {
                        obj[key] = value;
                        return obj;
                    }, {});
                
                // Update active filters display
                updateActiveFiltersDisplay(filteredPreferences);
                
                // Add a timestamp to ensure we get fresh random recipes every time
                filteredPreferences._timestamp = Date.now();
                
                // Always set isFresh to true to bypass cache and get new recipes
                loadRandomRecipes(filteredPreferences, true);
            });

            // Modal functionality
            function openModal(recipeData) {
                // Update existing modal content
                document.getElementById('modal-title').textContent = recipeData.title;
                document.getElementById('modal-image').src = recipeData.image;
                document.getElementById('modal-time').textContent = recipeData.time;
                
                // Add recipe ID to the favorite button
                const favoriteBtn = document.getElementById('modal-favorite-btn');
                
                // Clear previous event listeners to prevent duplicates
                const newFavoriteBtn = favoriteBtn.cloneNode(true);
                favoriteBtn.parentNode.replaceChild(newFavoriteBtn, favoriteBtn);
                
                // Handle Save Recipe button
                const saveRecipeBtn = document.getElementById('modal-save-recipe-btn');
                const newSaveRecipeBtn = saveRecipeBtn.cloneNode(true);
                saveRecipeBtn.parentNode.replaceChild(newSaveRecipeBtn, saveRecipeBtn);
                
                // Check if we have a valid recipe ID
                if (!recipeData.id) {
                    console.error('Modal opened with recipe missing ID:', recipeData);
                    newFavoriteBtn.style.display = 'none'; // Hide favorite button if no ID
                    newSaveRecipeBtn.style.display = 'none'; // Hide save button if no ID
                } else {
                    newFavoriteBtn.dataset.recipeId = recipeData.id;
                    newFavoriteBtn.style.display = ''; // Show the button
                    
                    newSaveRecipeBtn.dataset.recipeId = recipeData.id;
                    newSaveRecipeBtn.style.display = ''; // Show the button
                    
                    // Add click event listener to the favorite button
                    newFavoriteBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        
                        // Visual feedback
                        const heart = this.querySelector('i');
                        heart.classList.toggle('far');
                        heart.classList.toggle('fas');
                        heart.classList.toggle('text-red-500');
                        
                        // Get the recipe ID from the button dataset
                        const recipeId = this.dataset.recipeId;
                        if (!recipeId || recipeId === 'undefined' || recipeId === 'null') {
                            console.error('Invalid recipe ID in modal:', recipeId);
                            showToast('Error: Invalid recipe ID', 'error');
                            return;
                        }
                        
                        // Call toggleFavorite with the recipe ID and button
                        toggleFavorite(recipeId, this);
                    });
                    
                    // Add click event listener to the save recipe button
                    newSaveRecipeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        
                        // Visual feedback
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin text-xl mr-1"></i><span class="text-sm font-medium">Saving...</span>';
                        this.disabled = true;
                        
                        // Get the recipe ID from the button dataset
                        const recipeId = this.dataset.recipeId;
                        if (!recipeId || recipeId === 'undefined' || recipeId === 'null') {
                            console.error('Invalid recipe ID in modal:', recipeId);
                            showToast('Error: Invalid recipe ID', 'error');
                            return;
                        }
                        
                        // Create recipe data to save
                        const recipeToSave = {
                            recipe_id: recipeId,
                            title: recipeData.title,
                            description: '',
                            ingredients: recipeData.ingredients,
                            instructions: recipeData.steps,
                            image: recipeData.image,
                            cooking_time: parseCookingTime(recipeData.time),
                            calories: parseNutritionValue(recipeData.nutrition?.Calories),
                            protein: parseNutritionValue(recipeData.nutrition?.Protein),
                            carbs: parseNutritionValue(recipeData.nutrition?.Carbohydrates),
                            fats: parseNutritionValue(recipeData.nutrition?.Fat),
                            diet_type: 'omnivore',
                            source_url: '',
                            notes: ''
                        };
                        
                        // Ensure instructions is an array
                        if (!Array.isArray(recipeToSave.instructions)) {
                            if (typeof recipeToSave.instructions === 'string') {
                                // Try to parse as JSON if it's a string
                                try {
                                    recipeToSave.instructions = JSON.parse(recipeToSave.instructions);
                                } catch (e) {
                                    // If parsing fails, convert to array with single item
                                    recipeToSave.instructions = [recipeToSave.instructions];
                                }
                            } else {
                                // Default to empty array if not string or array
                                recipeToSave.instructions = [];
                            }
                        }
                        
                        // Ensure ingredients is an array
                        if (!Array.isArray(recipeToSave.ingredients)) {
                            if (typeof recipeToSave.ingredients === 'string') {
                                // Try to parse as JSON if it's a string
                                try {
                                    recipeToSave.ingredients = JSON.parse(recipeToSave.ingredients);
                                } catch (e) {
                                    // If parsing fails, convert to array with single item
                                    recipeToSave.ingredients = [recipeToSave.ingredients];
                                }
                            } else {
                                // Default to empty array if not string or array
                                recipeToSave.ingredients = [];
                            }
                        }
                        
                        console.log('Saving recipe from modal:', recipeToSave);
                        
                        // Call API to save recipe
                        axios.post('/api/get-recipe/add', recipeToSave)
                            .then(response => {
                                if (response.data.error) {
                                    showToast(response.data.error, 'error');
                                    return;
                                }
                                
                                // Handle existing recipe response
                                if (response.data.status === 'exists') {
                                    showToast('This recipe is already in your collection', 'success');
                                } else {
                                    showToast('Recipe successfully saved to your collection', 'success');
                                }
                            })
                            .catch(error => {
                                console.error('Error saving recipe:', error);
                                
                                if (error.response && error.response.status === 401) {
                                    // User is not logged in
                                    showToast('Please log in to save recipes to your collection', 'error');
                                    // Redirect to login page after a short delay
                                    setTimeout(() => {
                                        window.location.href = '/login';
                                    }, 2000);
                                    return;
                                }
                                
                                const errorMessage = error.response?.data?.message || error.message || 'Failed to save recipe';
                                showToast(errorMessage, 'error');
                            })
                            .finally(() => {
                                // Restore button state
                                this.innerHTML = originalText;
                                this.disabled = false;
                            });
                    });
                }
                
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

            // Helper function to simplify preferences when no results are found
            function simplifyPreferences(preferences, keepQuery = false) {
                const result = { ...preferences };
                
                // Priority order for removing constraints (least important first)
                const removalOrder = ['ingredients', 'cookingTime', 'cuisine', 'mealType', 'diet'];
                
                // If we need to keep the query, don't include it in removal candidates
                const candidatesForRemoval = keepQuery ? 
                    removalOrder.filter(key => key !== 'query') : 
                    [...removalOrder, 'query'];
                
                // Remove the first non-empty preference from the list
                for (const key of candidatesForRemoval) {
                    if (result[key] && result[key] !== '') {
                        delete result[key];
                        break;
                    }
                }
                
                return result;
            }

            // Parse cooking time from string (e.g., "30 mins" -> 30)
            function parseCookingTime(timeString) {
                if (!timeString) return 0;
                
                // If it's already a number, return it
                if (typeof timeString === 'number') return timeString;
                
                // Extract number from string (e.g., "30 mins" -> 30)
                const match = timeString.match(/(\d+)/);
                return match ? parseInt(match[1]) : 0;
            }
            
            // Parse nutrition value from string (e.g., "30 g" -> 30)
            function parseNutritionValue(valueString) {
                if (!valueString) return 0;
                
                // If it's already a number, return it
                if (typeof valueString === 'number') return valueString;
                
                // Extract number from string (e.g., "30 g" -> 30)
                const match = valueString.match(/(\d+(\.\d+)?)/);
                return match ? parseFloat(match[1]) : 0;
            }
        </script>
        <x-footer />
    </body>
</html> 