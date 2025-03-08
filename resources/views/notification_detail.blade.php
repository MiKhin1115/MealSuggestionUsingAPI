<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Notification Details - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-4 md:px-16 py-8">
            <div class="mb-6">
                <a href="{{ route('notifications') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Back to Notifications</span>
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div id="notification-header" class="p-6 border-b border-gray-200">
                    <!-- Notification header will be populated by JavaScript -->
                    <div class="flex items-center">
                        <div id="notification-icon" class="text-3xl mr-4">
                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                        </div>
                        <div>
                            <h1 id="notification-title" class="text-2xl font-bold text-gray-800">Loading notification...</h1>
                            <p id="notification-date" class="text-sm text-gray-500 mt-1">Please wait</p>
                        </div>
                    </div>
                </div>
                
                <div id="notification-content" class="p-6">
                    <!-- Notification content will be populated by JavaScript -->
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-full mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6 mb-4"></div>
                    </div>
                </div>
                
                <div id="detailed-advice" class="p-6 bg-gray-50 border-t border-gray-200">
                    <!-- Detailed advice will be populated by JavaScript -->
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Personalized Advice</h2>
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-full mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                    </div>
                </div>
                
                <div id="action-buttons" class="p-6 border-t border-gray-200 flex justify-between">
                    <a href="{{ route('personalized.recommendation') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors flex items-center">
                        <i class="fas fa-utensils mr-2"></i>
                        <span>View Recipe Recommendations</span>
                    </a>
                    <button id="mark-as-read-btn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        <span>Mark as Read</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Include the notification system JavaScript -->
        <script src="{{ asset('js/notification-system.js') }}"></script>
        
        <!-- Notification Detail JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get notification ID from URL
                const urlParams = new URLSearchParams(window.location.search);
                const notificationId = urlParams.get('id');
                
                if (!notificationId) {
                    showError('No notification ID provided');
                    return;
                }
                
                // Get notification from storage
                const notifications = window.notificationSystem.getNotifications();
                const notification = notifications.find(n => n.id === notificationId);
                
                if (!notification) {
                    showError('Notification not found');
                    return;
                }
                
                // Mark notification as read
                const markAsReadBtn = document.getElementById('mark-as-read-btn');
                if (markAsReadBtn) {
                    if (notification.read) {
                        markAsReadBtn.classList.add('hidden');
                    } else {
                        markAsReadBtn.addEventListener('click', function() {
                            window.notificationSystem.markNotificationAsRead(notificationId);
                            markAsReadBtn.classList.add('hidden');
                        });
                    }
                }
                
                // Display notification details
                displayNotificationDetails(notification);
            });
            
            function displayNotificationDetails(notification) {
                // Set notification header
                const titleElement = document.getElementById('notification-title');
                const dateElement = document.getElementById('notification-date');
                const iconElement = document.getElementById('notification-icon');
                
                if (titleElement) titleElement.textContent = notification.title;
                
                if (dateElement) {
                    const date = new Date(notification.timestamp);
                    dateElement.textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                }
                
                if (iconElement) {
                    let iconClass = '';
                    let iconColor = '';
                    
                    switch (notification.type) {
                        case 'success':
                            iconClass = 'fa-check-circle';
                            iconColor = 'text-green-500';
                            break;
                        case 'warning':
                            iconClass = 'fa-exclamation-triangle';
                            iconColor = 'text-yellow-500';
                            break;
                        case 'error':
                            iconClass = 'fa-times-circle';
                            iconColor = 'text-red-500';
                            break;
                        default: // info
                            iconClass = 'fa-info-circle';
                            iconColor = 'text-blue-500';
                    }
                    
                    iconElement.innerHTML = `<i class="fas ${iconClass} ${iconColor}"></i>`;
                }
                
                // Set notification content
                const contentElement = document.getElementById('notification-content');
                if (contentElement) {
                    contentElement.innerHTML = `
                        <p class="text-gray-700">${notification.message}</p>
                    `;
                }
                
                // Set detailed advice based on notification type and title
                const adviceElement = document.getElementById('detailed-advice');
                if (adviceElement) {
                    let adviceContent = '';
                    
                    // Check if this is a calorie comparison notification
                    if (notification.title === 'Calorie Alert') {
                        // Exceeding calories advice
                        adviceContent = `
                            <h2 class="text-xl font-semibold text-yellow-700 mb-4">Managing Your Calorie Intake</h2>
                            
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 mb-6">
                                <h3 class="font-medium text-yellow-800 mb-2">Why This Matters</h3>
                                <p class="text-sm text-yellow-700 mb-4">
                                    Consistently exceeding your daily calorie needs can lead to weight gain over time. Each 3,500 excess calories 
                                    can potentially add about 1 pound of body weight.
                                </p>
                            </div>
                            
                            <h3 class="font-medium text-gray-700 mb-3">Recommended Actions</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-utensils text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Adjust Your Portions</h4>
                                        <p class="text-sm text-gray-600">
                                            Try reducing portion sizes by 20-25% to bring your calorie intake within your daily limit.
                                            Use smaller plates and bowls to help control portions visually.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-running text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Increase Physical Activity</h4>
                                        <p class="text-sm text-gray-600">
                                            Add 30-45 minutes of moderate exercise to burn extra calories. Walking, cycling, 
                                            or swimming are excellent options that don't require special equipment.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-carrot text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Make Ingredient Substitutions</h4>
                                        <p class="text-sm text-gray-600">
                                            Replace high-calorie ingredients with lower-calorie alternatives:
                                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                                <li>Use Greek yogurt instead of sour cream</li>
                                                <li>Choose leaner cuts of meat</li>
                                                <li>Replace cream with milk or plant-based alternatives</li>
                                                <li>Use less oil when cooking (try an oil spray)</li>
                                            </ul>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-balance-scale text-red-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Balance Over Time</h4>
                                        <p class="text-sm text-gray-600">
                                            If you exceed your calories today, reduce your intake slightly over the next few days 
                                            to maintain balance. Remember, it's your overall pattern that matters most.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else if (notification.title === 'Within Calorie Limits') {
                        // Within calories advice
                        adviceContent = `
                            <h2 class="text-xl font-semibold text-green-700 mb-4">Maintaining Your Healthy Habits</h2>
                            
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-6">
                                <h3 class="font-medium text-green-800 mb-2">Great Job!</h3>
                                <p class="text-sm text-green-700 mb-4">
                                    You're doing well staying within your calorie limits. This balanced approach helps maintain 
                                    your weight and supports your overall health goals.
                                </p>
                            </div>
                            
                            <h3 class="font-medium text-gray-700 mb-3">Tips to Continue Your Success</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-apple-alt text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Focus on Nutrient Density</h4>
                                        <p class="text-sm text-gray-600">
                                            While staying within your calorie limits, prioritize foods that provide more nutrients 
                                            per calorie. Include plenty of vegetables, fruits, lean proteins, and whole grains.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-glass-water text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Stay Hydrated</h4>
                                        <p class="text-sm text-gray-600">
                                            Drinking enough water helps maintain energy levels and can prevent mistaking thirst for hunger. 
                                            Aim for 8 glasses (about 2 liters) of water daily.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-dumbbell text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Maintain Regular Activity</h4>
                                        <p class="text-sm text-gray-600">
                                            Regular physical activity complements your healthy eating habits. Aim for at least 
                                            150 minutes of moderate activity per week to maintain your fitness level.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-utensils text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">Meal Planning</h4>
                                        <p class="text-sm text-gray-600">
                                            Continue planning your meals in advance to maintain your calorie balance. 
                                            This helps prevent impulsive food choices that might not align with your goals.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        // Default advice for other notification types
                        adviceContent = `
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">General Health Advice</h2>
                            <p class="text-gray-600">
                                Maintaining a balanced diet and regular physical activity are key components of a healthy lifestyle.
                                Remember to stay hydrated, get enough sleep, and manage stress for optimal wellbeing.
                            </p>
                        `;
                    }
                    
                    adviceElement.innerHTML = adviceContent;
                }
            }
            
            function showError(message) {
                const headerElement = document.getElementById('notification-header');
                const contentElement = document.getElementById('notification-content');
                const adviceElement = document.getElementById('detailed-advice');
                const actionButtonsElement = document.getElementById('action-buttons');
                
                if (headerElement) {
                    headerElement.innerHTML = `
                        <div class="flex items-center">
                            <div class="text-3xl mr-4">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Error</h1>
                            </div>
                        </div>
                    `;
                }
                
                if (contentElement) {
                    contentElement.innerHTML = `
                        <p class="text-red-600">${message}</p>
                        <p class="text-gray-600 mt-4">
                            Please return to the <a href="{{ route('notifications') }}" class="text-blue-600 hover:text-blue-800">notifications page</a>.
                        </p>
                    `;
                }
                
                if (adviceElement) adviceElement.classList.add('hidden');
                if (actionButtonsElement) actionButtonsElement.classList.add('hidden');
            }
        </script>
        
        <x-footer />
    </body>
</html> 