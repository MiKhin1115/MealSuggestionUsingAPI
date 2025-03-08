/**
 * Weight Loss Warnings Enhancement
 * 
 * This script enhances the calorie calculator with specific warnings and guidance
 * for users on weight loss plans who exceed their daily calorie targets.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize userHealthGoal global variable
    window.userHealthGoal = null;
    
    // Try to get the health goal from the data attribute
    const calorieCalculator = document.getElementById('calorie-calculator');
    if (calorieCalculator && calorieCalculator.dataset.healthGoal) {
        window.userHealthGoal = calorieCalculator.dataset.healthGoal;
    }
    
    // Alternative method: Check for health goal in URL or form
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('health_goal')) {
        window.userHealthGoal = urlParams.get('health_goal');
    }
    
    // Check for health goal in any form with health_goal selector
    const healthGoalSelect = document.querySelector('select[name="health_goal"]');
    if (healthGoalSelect) {
        window.userHealthGoal = healthGoalSelect.value;
        
        // Update the goal when the selection changes
        healthGoalSelect.addEventListener('change', function() {
            window.userHealthGoal = this.value;
        });
    }
    
    // Format the warning message with appropriate styling based on severity
    function formatWarningMessage(message, isWeightLoss, exceedsLimit) {
        if (!message) return '';
        
        // Use different styling for weight loss warnings
        if (isWeightLoss && exceedsLimit) {
            if (message.startsWith('WARNING:')) {
                return `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-3">
                    <div class="font-bold">Critical Warning</div>
                    <p>${message}</p>
                </div>`;
            } else if (message.startsWith('CAUTION:')) {
                return `<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-3">
                    <div class="font-bold">Caution</div>
                    <p>${message}</p>
                </div>`;
            } else if (message.startsWith('NOTE:')) {
                return `<div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-3">
                    <div class="font-bold">Note</div>
                    <p>${message}</p>
                </div>`;
            }
        }
        
        // Default formatting
        return `<p class="mb-2">${message}</p>`;
    }
    
    // Create and inject the notification icon into the header
    function createNotificationIcon() {
        // Find the header section
        const header = document.querySelector('header');
        if (!header) return;
        
        // Check if notification container already exists
        let notificationContainer = header.querySelector('.notification-container');
        if (!notificationContainer) {
            // Create notification container
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container relative ml-4';
            
            // Create notification icon
            notificationContainer.innerHTML = `
                <button id="calorie-notification-icon" class="relative p-1 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="calorie-notification-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">!</span>
                </button>
                <div id="calorie-notification-tooltip" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-md p-3 text-sm z-50 border border-gray-200">
                    <p id="calorie-notification-message" class="text-gray-700"></p>
                </div>
            `;
            
            // Find nav links container and add the notification before the last item (usually account/logout)
            const navLinksContainer = header.querySelector('nav ul') || header.querySelector('nav');
            if (navLinksContainer) {
                const items = navLinksContainer.children;
                if (items.length > 0) {
                    // Insert before the last item (usually profile/account)
                    navLinksContainer.insertBefore(notificationContainer, items[items.length - 1]);
                } else {
                    navLinksContainer.appendChild(notificationContainer);
                }
            } else {
                // Fall back to appending to the header directly
                header.appendChild(notificationContainer);
            }
        }
        
        return notificationContainer;
    }
    
    // Show notification when calorie limit is exceeded
    function showCalorieNotification(isExceeded, difference, healthGoal) {
        const notificationContainer = createNotificationIcon();
        if (!notificationContainer) return;
        
        const badge = notificationContainer.querySelector('#calorie-notification-badge');
        const tooltip = notificationContainer.querySelector('#calorie-notification-tooltip');
        const message = notificationContainer.querySelector('#calorie-notification-message');
        
        if (!badge || !tooltip || !message) return;
        
        // Only show notification if calories are exceeded
        if (isExceeded) {
            badge.classList.remove('hidden');
            
            let notificationText = `You're exceeding your daily calorie target by ${Math.round(difference)} calories.`;
            
            // Add specific text for weight loss goal with exercise and diet advice
            if (healthGoal === 'weight_loss') {
                notificationText = `<strong>Weight Loss Alert:</strong> You're exceeding your daily calorie target by ${Math.round(difference)} calories. This will slow your weight loss progress.<br><br>
                <strong>Recommendations:</strong><br>
                • Add ${Math.round(difference/100)} minutes of cardio exercise<br>
                • Choose lower-calorie alternatives for your next meals<br>
                • Increase protein and fiber intake to feel fuller longer`;
                
                message.innerHTML = notificationText;
            } else {
                message.textContent = notificationText;
            }
            
            // Add click event to toggle tooltip
            const icon = notificationContainer.querySelector('#calorie-notification-icon');
            if (icon) {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    tooltip.classList.toggle('hidden');
                });
                
                // Close tooltip when clicking elsewhere
                document.addEventListener('click', function(e) {
                    if (!notificationContainer.contains(e.target)) {
                        tooltip.classList.add('hidden');
                    }
                });
            }
        } else {
            badge.classList.add('hidden');
        }
    }
    
    // Hook into the existing calorie exceedance check
    const originalCheckCalorieExceedance = window.checkCalorieExceedance;

    // Replace the original function with our enhanced version
    window.checkCalorieExceedance = function(totalCalories, recommendedCalories) {
        // Call the existing function to get its result
        fetch('/api/check-calorie-exceedance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                total_calories: totalCalories,
                recommended_calories: recommendedCalories,
                health_goal: window.userHealthGoal || null // Use the global health goal if available
            })
        })
        .then(response => response.json())
        .then(data => {
            // Display the enhanced warning if applicable
            displayEnhancedExceedanceWarning(data);
            
            // Show notification icon if calories are exceeded
            if (data.hasOwnProperty('exceeds_limit')) {
                showCalorieNotification(
                    data.exceeds_limit, 
                    data.difference, 
                    window.userHealthGoal
                );
            }
        })
        .catch(error => {
            console.error('Error checking calorie exceedance:', error);
        });
    };

    // Display enhanced warnings for weight loss users
    function displayEnhancedExceedanceWarning(data) {
        const resultDiv = document.getElementById('calorie-exceedance-result');
        if (!resultDiv) return;
        
        // Display standard warning
        if (data.message) {
            // Format the message with appropriate styling
            const isWeightLoss = window.userHealthGoal === 'weight_loss';
            const formattedMessage = formatWarningMessage(data.message, isWeightLoss, data.exceeds_limit);
            resultDiv.innerHTML = formattedMessage;
            
            // Only display recovery plan for weight loss goals when calories are exceeded
            if (isWeightLoss && data.exceeds_limit) {
                // Get the difference rounded to nearest calorie
                const difference = Math.round(data.difference);
                const percentOfDaily = data.percent_of_daily.toFixed(1);
                
                let recoveryPlan = '';
                
                // Severe exceedance (more than 20%)
                if (percentOfDaily > 20) {
                    recoveryPlan = `
                        <div class="bg-red-50 p-4 rounded-md mb-4">
                            <h3 class="font-bold text-red-800 mb-2">Recovery Plan</h3>
                            <p class="text-red-700 mb-2">You've exceeded your calorie target by ${difference} calories (${percentOfDaily}% over).</p>
                            <ul class="list-disc pl-5 text-red-700">
                                <li>Consider reducing your calories by ${Math.round(difference/3)} calories per day for the next 3 days to get back on track.</li>
                                <li>Add 30-45 minutes of moderate exercise daily this week to offset the excess.</li>
                                <li>Increase water intake and focus on high-fiber, low-calorie foods for the next few meals.</li>
                            </ul>
                        </div>
                    `;
                }
                // Moderate exceedance (between 10% and 20%)
                else if (percentOfDaily > 10) {
                    recoveryPlan = `
                        <div class="bg-yellow-50 p-4 rounded-md mb-4">
                            <h3 class="font-bold text-yellow-800 mb-2">Recovery Plan</h3>
                            <p class="text-yellow-700 mb-2">You've exceeded your calorie target by ${difference} calories (${percentOfDaily}% over).</p>
                            <ul class="list-disc pl-5 text-yellow-700">
                                <li>Consider reducing your calories by ${Math.round(difference/2)} calories per day for the next 2 days.</li>
                                <li>Add 20-30 minutes of exercise tomorrow to help offset the excess.</li>
                                <li>Plan your next meals to be rich in vegetables and lean proteins.</li>
                            </ul>
                        </div>
                    `;
                }
                // Slight exceedance (less than 10%)
                else {
                    recoveryPlan = `
                        <div class="bg-blue-50 p-4 rounded-md mb-4">
                            <h3 class="font-bold text-blue-800 mb-2">Recovery Plan</h3>
                            <p class="text-blue-700 mb-2">You've slightly exceeded your calorie target by ${difference} calories (${percentOfDaily}% over).</p>
                            <ul class="list-disc pl-5 text-blue-700">
                                <li>Try to eat about ${difference} fewer calories tomorrow to balance out.</li>
                                <li>Consider a 15-minute walk to help offset some of the excess calories.</li>
                                <li>This small deviation won't significantly impact your progress if you get back on track.</li>
                            </ul>
                        </div>
                    `;
                }
                
                // Add the recovery plan to the results
                resultDiv.innerHTML += recoveryPlan;
            }
        }
    }
}); 