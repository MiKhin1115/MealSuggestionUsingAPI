/**
 * Calorie Calculator JavaScript
 * Handles client-side calorie calculations and API interactions
 */

class CalorieCalculator {
    constructor() {
        // API endpoints
        this.apiEndpoints = {
            dailyNeeds: '/api/calories/daily-needs',
            recipeCalories: '/api/calories/recipe-calories',
            calorieExceedance: '/api/calories/check-exceedance',
            nutritionData: '/api/nutrition/data'
        };
        
        // Initialize the calculator
        this.init();
    }
    
    /**
     * Initialize the calorie calculator
     */
    init() {
        // Add event listeners when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
            this.setupEventListeners();
        });
    }
    
    /**
     * Set up event listeners for calorie calculation features
     */
    setupEventListeners() {
        // Daily Needs Calculator
        const dailyNeedsButton = document.getElementById('calculate-daily-needs');
        if (dailyNeedsButton) {
            dailyNeedsButton.addEventListener('click', this.handleCalculateDailyNeeds.bind(this));
        }
        
        // Add event listener for activity level changes to update calories in real-time
        const activityLevelSelect = document.getElementById('activity_level');
        if (activityLevelSelect) {
            activityLevelSelect.addEventListener('change', this.handleCalculateDailyNeeds.bind(this));
        }
        
        // Recipe Calorie Calculator
        const recipeCaloriesButton = document.getElementById('calculate-recipe-calories');
        if (recipeCaloriesButton) {
            recipeCaloriesButton.addEventListener('click', this.handleCalculateRecipeCalories.bind(this));
        }
        
        // Calorie Exceedance Check
        const calorieExceedanceButton = document.getElementById('check-calorie-exceedance');
        if (calorieExceedanceButton) {
            calorieExceedanceButton.addEventListener('click', this.handleCheckCalorieExceedance.bind(this));
        }
        
        // Nutrition Data Search
        const nutritionSearchForm = document.getElementById('nutrition-search-form');
        if (nutritionSearchForm) {
            nutritionSearchForm.addEventListener('submit', this.handleNutritionSearch.bind(this));
        }
        
        // Recipe checkboxes for selection
        const recipeCheckboxes = document.querySelectorAll('.recipe-checkbox');
        recipeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selectedCount = document.querySelectorAll('.recipe-checkbox:checked').length;
                
                // Update buttons to show selected count
                const recipeCaloriesButton = document.getElementById('calculate-recipe-calories');
                const calorieExceedanceButton = document.getElementById('check-calorie-exceedance');
                
                if (recipeCaloriesButton) {
                    if (selectedCount > 0) {
                        recipeCaloriesButton.innerHTML = `<i class="fas fa-utensils mr-2"></i><span>Calculate Calories (${selectedCount} selected)</span>`;
                        recipeCaloriesButton.disabled = false;
                        recipeCaloriesButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        recipeCaloriesButton.innerHTML = `<i class="fas fa-utensils mr-2"></i><span>Calculate Selected Recipe Calories</span>`;
                        recipeCaloriesButton.disabled = false;
                    }
                }
                
                if (calorieExceedanceButton) {
                    if (selectedCount > 0) {
                        calorieExceedanceButton.innerHTML = `<i class="fas fa-check-circle mr-2"></i><span>Check Calorie Balance (${selectedCount} selected)</span>`;
                        calorieExceedanceButton.disabled = false;
                        calorieExceedanceButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        calorieExceedanceButton.innerHTML = `<i class="fas fa-check-circle mr-2"></i><span>Check Calorie Balance</span>`;
                        calorieExceedanceButton.disabled = false;
                    }
                }
            });
        });
    }
    
    /**
     * Handle daily calorie needs calculation
     * @param {Event} event - The click event
     */
    async handleCalculateDailyNeeds(event) {
        event.preventDefault();
        
        // Clear any previous errors
        const errorElement = document.getElementById('daily-needs-error');
        if (errorElement) errorElement.remove();
        
        // Validate form data first
        const gender = document.getElementById('gender')?.value;
        const age = document.getElementById('age')?.value;
        const height = document.getElementById('height')?.value;
        const weight = document.getElementById('weight')?.value;
        const activityLevel = document.getElementById('activity_level')?.value;
        const healthGoal = document.getElementById('health_goal')?.value || 'maintain_weight';
        
        // Print the values for debugging
        console.log("Form values:", {
            gender, age, height, weight, activityLevel, healthGoal
        });
        
        // Validate required fields
        if (!gender || !age || !height || !weight) {
            const missingFields = [];
            if (!gender) missingFields.push('gender');
            if (!age) missingFields.push('age');
            if (!height) missingFields.push('height');
            if (!weight) missingFields.push('weight');
            
            const errorMessage = `Please fill in all required fields: ${missingFields.join(', ')}`;
            this.showError('daily-needs-result', errorMessage);
            return;
        }
        
        // Validate numeric fields
        if (isNaN(age) || isNaN(height) || isNaN(weight)) {
            const invalidFields = [];
            if (isNaN(age)) invalidFields.push('age');
            if (isNaN(height)) invalidFields.push('height');
            if (isNaN(weight)) invalidFields.push('weight');
            
            const errorMessage = `Please enter valid numbers for: ${invalidFields.join(', ')}`;
            this.showError('daily-needs-result', errorMessage);
            return;
        }
        
        this.showLoading('daily-needs-result');
        document.getElementById('daily-needs-result').classList.remove('hidden');
        
        try {
            // Ensure activity level is valid, default to 'moderate' if not
            const validActivityLevels = ['sedentary', 'light', 'moderate', 'active', 'very_active'];
            const validatedActivityLevel = validActivityLevels.includes(activityLevel) ? 
                activityLevel : 'moderate';
            
            // Log the data being sent for debugging
            const requestData = {
                gender: gender,
                age: age,
                height: height,
                weight: weight,
                activity_level: validatedActivityLevel,
                health_goal: healthGoal
            };
            
            console.log('Sending daily needs calculation request:', requestData);
            
            // Get CSRF token and verify it exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error('CSRF token not found');
                throw new Error('CSRF token not found. Please refresh the page and try again.');
            }
            
            const response = await fetch(this.apiEndpoints.dailyNeeds, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(requestData)
            });
            
            console.log('API response status:', response.status, response.statusText);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('API error response:', errorText);
                throw new Error(`Server responded with status ${response.status}: ${errorText || response.statusText}`);
            }
            
            const data = await response.json();
            console.log('Daily needs calculation result:', data);
            
            if (data.status === 'success') {
                // Ensure data has activity level description
                if (!data.data.activity_level_description) {
                    // Map the activity level to a description
                    const activityDescriptions = {
                        'sedentary': 'Sedentary (little or no exercise)',
                        'light': 'Light (light exercise 1-3 days/week)',
                        'moderate': 'Moderate (moderate exercise 3-5 days/week)',
                        'active': 'Active (hard exercise 6-7 days/week)',
                        'very_active': 'Very Active (very hard exercise & physical job)'
                    };
                    
                    data.data.activity_level_description = activityDescriptions[validatedActivityLevel] || 
                        `${validatedActivityLevel.charAt(0).toUpperCase() + validatedActivityLevel.slice(1)} activity`;
                }
                
                this.displayDailyNeeds(data.data);
            } else {
                this.hideLoading('daily-needs-result');
                this.showError('daily-needs-result', data.message || 'Failed to calculate daily needs');
            }
        } catch (error) {
            console.error('Detailed error calculating daily needs:', error);
            
            // Create a more detailed error message with debugging info
            let errorMessage = 'An error occurred while calculating your daily needs';
            
            if (error.message) {
                errorMessage += `. Error details: ${error.message}`;
            }
            
            this.hideLoading('daily-needs-result');
            this.showError('daily-needs-result', errorMessage);
            
            // Add a debug link to show more information (hidden in production)
            const resultContainer = document.getElementById('daily-needs-result');
            if (resultContainer) {
                const debugElement = document.createElement('div');
                debugElement.id = 'daily-needs-error';
                debugElement.className = 'mt-3 p-3 bg-red-50 text-xs border border-red-200 rounded';
                debugElement.innerHTML = `
                    <details>
                        <summary class="cursor-pointer text-red-700 font-medium">Debug Information</summary>
                        <div class="mt-2 text-red-600 whitespace-pre-wrap">${JSON.stringify({
                            endpoint: this.apiEndpoints.dailyNeeds,
                            formData: {
                                gender: gender,
                                age: age, 
                                height: height,
                                weight: weight,
                                activityLevel: activityLevel,
                                healthGoal: healthGoal
                            },
                            errorMessage: error.message,
                            errorStack: error.stack
                        }, null, 2)}</div>
                    </details>
                `;
                resultContainer.appendChild(debugElement);
            }
        }
    }
    
    /**
     * Handle recipe calorie calculation
     * @param {Event} event - The click event
     */
    async handleCalculateRecipeCalories(event) {
        event.preventDefault();
        
            const recipeIds = this.getSelectedRecipeIds();
            
            if (recipeIds.length === 0) {
            alert('Please select at least one recipe to calculate calories');
                return;
            }
            
            this.showLoading('recipe-calories-result');
        document.getElementById('recipe-calories-result').classList.remove('hidden');
            
        try {
            const response = await fetch('/api/calories/recipe-calories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    recipe_ids: recipeIds
                })
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.displayRecipeCalories(data.data);
            } else {
                this.hideLoading('recipe-calories-result');
                this.showError('recipe-calories-result', data.message || 'Failed to calculate recipe calories');
            }
        } catch (error) {
            console.error('Error calculating recipe calories:', error);
            this.hideLoading('recipe-calories-result');
            this.showError('recipe-calories-result', 'An error occurred while calculating recipe calories');
        }
    }
    
    /**
     * Handle calorie exceedance check
     * @param {Event} event - The click event
     */
    async handleCheckCalorieExceedance(event) {
        event.preventDefault();
        
            const recipeIds = this.getSelectedRecipeIds();
            
            if (recipeIds.length === 0) {
            alert('Please select at least one recipe to check calorie balance');
                return;
            }
            
            // Show loading state
            this.showLoading('calorie-exceedance-result');
        document.getElementById('calorie-exceedance-result').classList.remove('hidden');
            
        try {
            // Call API to check calorie exceedance
            const response = await fetch(this.apiEndpoints.calorieExceedance, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    recipe_ids: recipeIds
                })
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.hideLoading('calorie-exceedance-result');
                this.displayCalorieExceedance(data.data);
            } else {
                this.hideLoading('calorie-exceedance-result');
                this.showError('calorie-exceedance-result', data.message || 'Failed to check calorie balance');
            }
        } catch (error) {
            console.error('Error checking calorie exceedance:', error);
            this.hideLoading('calorie-exceedance-result');
            this.showError('calorie-exceedance-result', 'An error occurred while checking calorie balance');
        }
    }
    
    /**
     * Handle nutrition data search
     * @param {Event} event - The form submit event
     */
    async handleNutritionSearch(event) {
        event.preventDefault();
        
        try {
            // Get food query from form
            const query = document.getElementById('nutrition-query')?.value;
            
            if (!query) {
                this.showError('nutrition-data-result', 'Please enter a food to search for');
                return;
            }
            
            // Show loading state
            this.showLoading('nutrition-data-result');
            
            // Call API to get nutrition data
            const response = await fetch(this.apiEndpoints.nutritionData, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ query })
            });
            
            if (!response.ok) {
                throw new Error('Failed to get nutrition data');
            }
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.displayNutritionData(data.data);
            } else {
                this.showError('nutrition-data-result', data.message || 'Failed to get nutrition data');
            }
        } catch (error) {
            console.error('Error getting nutrition data:', error);
            this.showError('nutrition-data-result', error.message);
        }
    }
    
    /**
     * Get selected recipe IDs from the page
     * @returns {Array} Array of recipe IDs
     */
    getSelectedRecipeIds() {
        // Get all checked recipe checkboxes
        const checkedRecipes = document.querySelectorAll('.recipe-checkbox:checked');
        console.log('Found checked recipe checkboxes:', checkedRecipes.length);
        
        // Debug available checkboxes
        const allCheckboxes = document.querySelectorAll('.recipe-checkbox');
        console.log('Total recipe checkboxes on page:', allCheckboxes.length);
        
        // Log checkbox elements for debugging
        if (allCheckboxes.length > 0) {
            console.log('First checkbox properties:', {
                id: allCheckboxes[0].id,
                value: allCheckboxes[0].value,
                dataAttributes: {
                    calories: allCheckboxes[0].dataset.calories,
                    title: allCheckboxes[0].dataset.title
                }
            });
        }
        
        // Extract recipe IDs
        return Array.from(checkedRecipes).map(checkbox => checkbox.value);
    }
    
    /**
     * Display daily calorie needs in the result container
     * @param {Object} data - Daily calorie needs data
     */
    displayDailyNeeds(data) {
        const resultDiv = document.getElementById('daily-needs-result');
        if (!resultDiv) return;
        
        this.hideLoading('daily-needs-result');
        
        // Ensure we have valid data
        const bmr = data.bmr || 0;
        const recommendedCalories = data.recommended_calories || 0;
        
        // Make sure we have a valid activity level description
        let activityLevel = data.activity_level_description || '';
        
        // If activity level description is missing or undefined, generate it from activity_level
        if (!activityLevel || activityLevel === 'undefined') {
            const activityMap = {
                'sedentary': 'Sedentary (little or no exercise)',
                'light': 'Light (light exercise 1-3 days/week)',
                'moderate': 'Moderate (moderate exercise 3-5 days/week)',
                'active': 'Active (hard exercise 6-7 days/week)',
                'very_active': 'Very Active (very hard exercise & physical job)'
            };
            
            activityLevel = activityMap[data.activity_level] || 'Moderate activity';
        }
        
        // Calculate activity multiplier based on activity level
        let activityMultiplier = 1.0;
        switch (data.activity_level) {
            case 'sedentary':
                activityMultiplier = 1.2;
                break;
            case 'light':
                activityMultiplier = 1.375;
                break;
            case 'moderate':
                activityMultiplier = 1.55;
                break;
            case 'active':
                activityMultiplier = 1.725;
                break;
            case 'very_active':
                activityMultiplier = 1.9;
                break;
            default:
                activityMultiplier = 1.55; // Default to moderate
        }
        
        // Validate numerical values before displaying
        if (isNaN(recommendedCalories) || recommendedCalories <= 0) {
            // If recommended calories is invalid, provide a range based on the BMR and activity level
            const recommendedRange = {
                min: Math.round(bmr * activityMultiplier * 0.9),
                max: Math.round(bmr * activityMultiplier * 1.1)
            };
            
            resultDiv.innerHTML = `
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h4 class="font-medium text-yellow-800 mb-3">Estimated Daily Calorie Needs</h4>
                    
                    <div class="bg-white rounded-lg p-4 mb-3 text-center">
                        <span class="text-2xl font-bold text-yellow-600">${Math.round(bmr * activityMultiplier)} calories</span>
                        <span class="text-sm text-yellow-500">(estimated average)</span>
                    </div>
                    <div class="text-xs text-yellow-600">
                        <p class="mb-1"><strong>Basal Metabolic Rate (BMR):</strong> ${Math.round(bmr)} calories</p>
                        <p><strong>Activity Level:</strong> ${activityLevel}</p>
                    </div>
                </div>
            `;
        } else {
            // Store the recommended calories in a data attribute for later use
            resultDiv.dataset.recommendedCalories = recommendedCalories;
            
            // Store the health goal if provided
            if (data.health_goal) {
                const calcElement = document.getElementById('calorie-calculator');
                if (calcElement) {
                    calcElement.dataset.healthGoal = data.health_goal;
                }
                // Set global variable for the weight loss warning system
                window.userHealthGoal = data.health_goal;
            }
            
            // Normal display with valid data - showing how activity affects calories
            resultDiv.innerHTML = `
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-medium text-blue-800 mb-3">Your Daily Calorie Needs</h4>
                    <p class="text-sm text-blue-700 mb-3">
                        Based on your profile and activity level, your daily recommended calorie intake is:
                    </p>
                    <div class="bg-white rounded-lg p-4 mb-3 text-center">
                        <span class="text-2xl font-bold text-blue-900">${Math.round(recommendedCalories)} calories</span>
                    </div>
                    
                    <div class="bg-blue-100 p-3 rounded-lg mb-3">
                        <p class="text-sm text-blue-800 mb-2"><strong>How Your Activity Level Affects Calories:</strong></p>
                        <p class="text-xs text-blue-700 mb-1">Your BMR (calories burned at rest): ${Math.round(bmr)} calories</p>
                        <p class="text-xs text-blue-700 mb-1">Activity multiplier: ${activityMultiplier.toFixed(2)}x (${activityLevel})</p>
                        <p class="text-xs text-blue-700">BMR × Activity multiplier = ${Math.round(bmr)} × ${activityMultiplier.toFixed(2)} = ${Math.round(bmr * activityMultiplier)} calories</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-3">
                        <div class="bg-white p-2 rounded text-center ${data.activity_level === 'sedentary' ? 'ring-2 ring-blue-500' : ''}">
                            <div class="text-xs font-medium mb-1">Sedentary</div>
                            <div class="text-sm">${Math.round(bmr * 1.2)}</div>
                        </div>
                        <div class="bg-white p-2 rounded text-center ${data.activity_level === 'light' ? 'ring-2 ring-blue-500' : ''}">
                            <div class="text-xs font-medium mb-1">Light</div>
                            <div class="text-sm">${Math.round(bmr * 1.375)}</div>
                        </div>
                        <div class="bg-white p-2 rounded text-center ${data.activity_level === 'moderate' ? 'ring-2 ring-blue-500' : ''}">
                            <div class="text-xs font-medium mb-1">Moderate</div>
                            <div class="text-sm">${Math.round(bmr * 1.55)}</div>
                        </div>
                        <div class="bg-white p-2 rounded text-center ${data.activity_level === 'active' ? 'ring-2 ring-blue-500' : ''}">
                            <div class="text-xs font-medium mb-1">Active</div>
                            <div class="text-sm">${Math.round(bmr * 1.725)}</div>
                        </div>
                        <div class="bg-white p-2 rounded text-center ${data.activity_level === 'very_active' ? 'ring-2 ring-blue-500' : ''}">
                            <div class="text-xs font-medium mb-1">Very Active</div>
                            <div class="text-sm">${Math.round(bmr * 1.9)}</div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-blue-600 italic">
                        <i class="fas fa-info-circle mr-1"></i> Try changing your activity level to see how it affects your daily calorie needs.
                    </p>
            </div>
        `;
        }
        
        resultDiv.classList.remove('hidden');
    }
    
    /**
     * Display recipe calories in the result container
     * @param {Object} data - Recipe calories data
     */
    displayRecipeCalories(data) {
        const resultDiv = document.getElementById('recipe-calories-result');
        if (!resultDiv) return;
        
        this.hideLoading('recipe-calories-result');
        
        const totalCalories = data.total;
        const recipes = data.recipes;
        
        // Store the total calories in a data attribute for later use
        resultDiv.dataset.totalRecipeCalories = totalCalories;
        
        let html = `
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-medium text-green-800 mb-3">Recipe Calorie Summary</h4>
                <p class="text-sm text-green-700 mb-3">
                    Total Calories: <span class="font-bold">${totalCalories}</span>
                </p>
        `;
        
        if (recipes && recipes.length > 0) {
            html += `
                <div class="bg-white rounded-md shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipe</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Calories</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
            `;
            
            recipes.forEach(recipe => {
                html += `
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${recipe.title}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right">${recipe.calories}</td>
                    </tr>
                `;
            });
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        html += `</div>`;
        
        resultDiv.innerHTML = html;
        resultDiv.classList.remove('hidden');
        
        // After displaying recipe calories, compare with daily needs
        setTimeout(() => {
            // Get all recipe calories as an array
            const recipeCalories = recipes.map(recipe => recipe.calories || 0);
            // Call the comparison function with the total and individual recipe calories
            this.compareCaloriesWithDailyNeeds(totalCalories, recipeCalories);
        }, 500); // Short delay to ensure UI updates first
    }
    
    /**
     * Display calorie exceedance check results
     * @param {Object} data - Calorie exceedance data
     */
    displayCalorieExceedance(data) {
        const resultContainer = document.getElementById('calorie-exceedance-result');
        if (!resultContainer) return;
        
        // Determine background and border colors based on exceedance
        const bgClass = data.exceedance.exceeds ? 'bg-red-50' : 'bg-green-50';
        const borderClass = data.exceedance.exceeds ? 'border-red-200' : 'border-green-200';
        const textClass = data.exceedance.exceeds ? 'text-red-800' : 'text-green-800';
        
        // Create result HTML without macronutrient information
        const html = `
            <div class="${bgClass} p-4 rounded-lg border ${borderClass}">
                <h4 class="font-semibold ${textClass} mb-3">Calorie Exceedance Check</h4>
                <p class="mb-2 font-medium ${textClass}">${data.exceedance.message}</p>
                <p class="mb-1">Your daily calorie needs: ${Math.round(data.daily_needs.total_calories)} calories</p>
                <p class="mb-1">Selected recipes total: ${data.recipe_calories.total} calories</p>
                <p class="mb-1">Difference: ${data.exceedance.difference} calories</p>
                <p class="mb-1">Percentage of daily needs: ${data.exceedance.percentage}%</p>
            </div>
        `;
        
        resultContainer.innerHTML = html;
        resultContainer.classList.remove('hidden');
    }
    
    /**
     * Display nutrition data in the result container
     * @param {Object} data - Nutrition data
     */
    displayNutritionData(data) {
        const resultContainer = document.getElementById('nutrition-data-result');
        if (!resultContainer) return;
        
        // Create result HTML
        const html = `
            <div class="alert alert-info">
                <h4>Nutrition Information for "${data.name}"</h4>
                <p><strong>Serving Size:</strong> ${data.serving_size_g}g</p>
                <p><strong>Calories:</strong> ${data.calories} kcal</p>
                <p><strong>Macronutrients:</strong></p>
                <ul>
                    <li>Protein: ${data.protein_g}g</li>
                    <li>Carbohydrates: ${data.carbohydrates_total_g}g</li>
                    <li>Fat: ${data.fat_total_g}g</li>
                    <li>Fiber: ${data.fiber_g}g</li>
                    <li>Sugar: ${data.sugar_g}g</li>
                </ul>
                <p><strong>Other Nutrients:</strong></p>
                <ul>
                    <li>Sodium: ${data.sodium_mg}mg</li>
                    <li>Potassium: ${data.potassium_mg}mg</li>
                    <li>Cholesterol: ${data.cholesterol_mg}mg</li>
                    <li>Saturated Fat: ${data.fat_saturated_g}g</li>
                </ul>
            </div>
        `;
        
        resultContainer.innerHTML = html;
    }
    
    /**
     * Show loading state in the result container
     * @param {string} containerId - ID of the result container
     */
    showLoading(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = `
            <div class="flex justify-center items-center p-4">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                <span class="ml-2 text-gray-600">Loading...</span>
            </div>
        `;
            container.classList.remove('hidden');
    }
    
    /**
     * Hide loading state in the result container
     * @param {string} containerId - ID of the result container
     */
    hideLoading(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Find and remove loading indicator if it exists
        const loadingElement = container.querySelector('.animate-spin');
        if (loadingElement && loadingElement.parentElement) {
            loadingElement.parentElement.remove();
        }
    }
    
    /**
     * Show an error message in the specified container
     * @param {string} containerId - ID of the container to show the error in
     * @param {string} message - Error message to display
     */
    showError(containerId, message) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Ensure container is visible
            container.classList.remove('hidden');
        
        // Format the error message with icon and styling
        container.innerHTML = `
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-red-800 mb-1">Error</h4>
                        <p class="text-sm text-red-700">${message}</p>
                    </div>
                </div>
            </div>
        `;
        
        // Scroll to error message
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Log error to console for debugging
        console.error(`Error in ${containerId}:`, message);
    }
    
    // After calculating recipe calories and daily needs, compare them and show warnings
    compareCaloriesWithDailyNeeds(totalRecipeCalories, recipeCaloriesArray) {
        // Get the recommended daily calories from the page
        let dailyNeeds = 0;
        
        // First try to get it from the DOM element with data attribute
        const dailyNeedsElement = document.querySelector('[data-recommended-calories]');
        if (dailyNeedsElement && dailyNeedsElement.dataset.recommendedCalories) {
            dailyNeeds = parseFloat(dailyNeedsElement.dataset.recommendedCalories);
        }
        
        // If not found, try to get it from the displayed daily needs result
        if (!dailyNeeds) {
            const dailyNeedsResult = document.getElementById('daily-needs-result');
            if (dailyNeedsResult) {
                // First try to find the estimated average calories (the bold number in the center)
                const boldCalorieMatch = dailyNeedsResult.querySelector('.text-2xl.font-bold');
                if (boldCalorieMatch) {
                    const calorieText = boldCalorieMatch.textContent;
                    const calorieMatch = calorieText.match(/(\d+,?\d*)/);
                    if (calorieMatch && calorieMatch[1]) {
                        dailyNeeds = parseFloat(calorieMatch[1].replace(',', ''));
                    }
                }
                
                // If still not found, try to extract from the text content
                if (!dailyNeeds) {
                    const calorieMatch = dailyNeedsResult.textContent.match(/(\d+,?\d*) calories/);
                    if (calorieMatch && calorieMatch[1]) {
                        dailyNeeds = parseFloat(calorieMatch[1].replace(',', ''));
                    }
                }
            }
        }
        
        // Only proceed if we have both valid values
        if (totalRecipeCalories > 0 && dailyNeeds > 0) {
            // Get health goal from the page if available
            let healthGoal = null;
            const healthGoalElement = document.querySelector('[data-health-goal]');
            if (healthGoalElement && healthGoalElement.dataset.healthGoal) {
                healthGoal = healthGoalElement.dataset.healthGoal;
            }
            
            // Set global variable for the notification system
            window.userHealthGoal = healthGoal;
            
            // Check if calories exceed daily needs
            const isExceeding = totalRecipeCalories > dailyNeeds;
            const difference = totalRecipeCalories - dailyNeeds;
            const percentOfDaily = (totalRecipeCalories / dailyNeeds) * 100;
            
            // Add notification using the notification system
            if (window.notificationSystem) {
                window.notificationSystem.addCalorieComparisonNotification(
                    isExceeding, 
                    totalRecipeCalories, 
                    dailyNeeds, 
                    difference, 
                    healthGoal
                );
            }
            
            // Call the exceedance check function with valid numbers
            if (typeof window.checkCalorieExceedance === 'function') {
                window.checkCalorieExceedance(totalRecipeCalories, dailyNeeds);
            }
            
            // Create popup content
            let popupContent = '';
            
            if (isExceeding) {
                // Warning message for exceeding calories
                popupContent = `
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-yellow-800 mb-1">Calorie Alert</h4>
                                <p class="text-sm text-yellow-700">
                                    Your selected recipes total <strong>${Math.round(totalRecipeCalories)}</strong> calories, which exceeds your estimated daily need of <strong>${Math.round(dailyNeeds)}</strong> calories by <strong>${Math.round(difference)}</strong> calories (<strong>${Math.round(percentOfDaily)}%</strong> of your daily needs).
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add specific advice for weight loss users
                if (healthGoal === 'weight_loss') {
                    popupContent += `
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <div class="flex items-start">
                                <i class="fas fa-weight text-red-600 text-xl mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-red-800 mb-1">Weight Loss Impact</h4>
                                    <p class="text-sm text-red-700 mb-2">
                                        Consuming these recipes will slow down your weight loss progress. Consider the following adjustments:
                                    </p>
                                    <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                                        <li>Add <strong>${Math.round(difference/100)}</strong> minutes of cardio exercise to burn the excess calories</li>
                                        <li>Reduce portion sizes by approximately <strong>${Math.round((difference/totalRecipeCalories)*100)}%</strong></li>
                                        <li>Substitute higher-calorie ingredients with lower-calorie alternatives</li>
                                        <li>Balance your calorie intake over the next few days by reducing <strong>${Math.round(difference/2)}</strong> calories per day</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;
                }
            } else {
                // Success message for staying within calories
                popupContent = `
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 text-xl mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-green-800 mb-1">Within Calorie Limits</h4>
                                <p class="text-sm text-green-700">
                                    Your selected recipes total <strong>${Math.round(totalRecipeCalories)}</strong> calories, which is within your estimated daily need of <strong>${Math.round(dailyNeeds)}</strong> calories. You have <strong>${Math.round(Math.abs(difference))}</strong> calories remaining.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add encouragement for weight loss users
                if (healthGoal === 'weight_loss') {
                    popupContent += `
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mt-4">
                            <div class="flex items-start">
                                <i class="fas fa-thumbs-up text-blue-600 text-xl mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-blue-800 mb-1">Great Job!</h4>
                                    <p class="text-sm text-blue-700">
                                        You're on track with your weight loss goals. These meals support your calorie deficit while providing nutrition.
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            // Show popup in the middle of the screen
            this.showCenteredPopup(popupContent);
            
            // Also update the comparison result section if it exists
            const comparisonResultElement = document.getElementById('calorie-comparison-result');
            if (comparisonResultElement) {
                comparisonResultElement.innerHTML = popupContent;
                comparisonResultElement.classList.remove('hidden');
            }
        }
    }
    
    // Create a new method for displaying centered popups
    showCenteredPopup(content) {
        // Remove any existing popup
        const existingPopup = document.getElementById('centered-calorie-popup');
        if (existingPopup) {
            existingPopup.remove();
        }
        
        // Create popup container
        const popup = document.createElement('div');
        popup.id = 'centered-calorie-popup';
        popup.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50';
        popup.style.animation = 'fadeIn 0.3s ease-out';
        
        // Create popup content
        popup.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[80vh] overflow-y-auto p-6" style="animation: scaleIn 0.3s ease-out">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Calorie Comparison Results</h3>
                    <button id="close-popup" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="popup-content">
                    ${content}
                </div>
            </div>
        `;
        
        // Add to document
        document.body.appendChild(popup);
        
        // Add event listeners
        document.getElementById('close-popup').addEventListener('click', () => {
            popup.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                popup.remove();
            }, 280);
        });
        
        // Close when clicking outside
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    popup.remove();
                }, 280);
            }
        });
    }
}

// Initialize the calorie calculator
const calorieCalculator = new CalorieCalculator(); 