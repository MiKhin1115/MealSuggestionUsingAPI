/**
 * Notification System
 * Handles storing and displaying notifications across the application
 */

class NotificationSystem {
    constructor() {
        this.storageKey = 'meal_suggestion_notifications';
        this.maxNotifications = 20; // Maximum number of notifications to store
        
        // Initialize the notification system
        this.init();
    }
    
    /**
     * Initialize the notification system
     */
    init() {
        // Add event listeners when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
            this.setupEventListeners();
            this.loadNotifications();
            this.setupNotificationDropdown();
            this.updateNotificationCount();
        });
    }
    
    /**
     * Set up event listeners for notification features
     */
    setupEventListeners() {
        // Clear all notifications button
        const clearAllButton = document.getElementById('clear-all-notifications');
        if (clearAllButton) {
            clearAllButton.addEventListener('click', this.clearAllNotifications.bind(this));
        }
        
        // Mark as read buttons
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('mark-notification-read')) {
                const notificationId = event.target.dataset.notificationId;
                if (notificationId) {
                    this.markNotificationAsRead(notificationId);
                }
            }
        });
        
        // Delete notification buttons
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('delete-notification')) {
                const notificationId = event.target.dataset.notificationId;
                if (notificationId) {
                    this.deleteNotification(notificationId);
                }
            }
        });
    }
    
    /**
     * Set up the notification dropdown functionality
     */
    setupNotificationDropdown() {
        const bellButton = document.getElementById('notification-bell');
        const mobileBellButton = document.getElementById('mobile-notification-bell');
        const dropdown = document.getElementById('notification-dropdown');
        
        if (!bellButton || !dropdown) return;
        
        // Toggle dropdown when bell is clicked
        bellButton.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            
            if (!dropdown.classList.contains('hidden')) {
                this.loadDropdownNotifications();
            }
        });
        
        // Handle mobile bell button click
        if (mobileBellButton) {
            mobileBellButton.addEventListener('click', (e) => {
                e.stopPropagation();
                // Redirect to notifications page on mobile
                window.location.href = '/notifications';
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (dropdown && !dropdown.contains(e.target) && !bellButton.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Handle delete button clicks in dropdown
        document.addEventListener('click', (e) => {
            if (e.target.closest('.dropdown-delete-notification')) {
                e.preventDefault();
                e.stopPropagation();
                
                const button = e.target.closest('.dropdown-delete-notification');
                const notificationId = button.dataset.notificationId;
                
                if (notificationId) {
                    this.deleteNotification(notificationId);
                    
                    // Remove the notification item from the dropdown
                    const item = button.closest('.dropdown-notification-item');
                    if (item) {
                        item.remove();
                        
                        // Check if there are any notifications left
                        const remainingItems = dropdown.querySelectorAll('.dropdown-notification-item');
                        if (remainingItems.length === 0) {
                            document.getElementById('dropdown-notifications').innerHTML = `
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500">No notifications</p>
                                </div>
                            `;
                        }
                    }
                }
            }
        });
    }
    
    /**
     * Add a new notification
     * @param {Object} notification - The notification object to add
     * @param {string} notification.title - The notification title
     * @param {string} notification.message - The notification message
     * @param {string} notification.type - The notification type (success, warning, error, info)
     * @param {string} notification.link - Optional link to navigate to
     */
    addNotification(notification) {
        // Validate notification object
        if (!notification || !notification.title || !notification.message || !notification.type) {
            console.error('Invalid notification object:', notification);
            return;
        }
        
        // Get existing notifications
        const notifications = this.getNotifications();
        
        // Create a new notification object
        const newNotification = {
            id: Date.now().toString(), // Use timestamp as ID
            title: notification.title,
            message: notification.message,
            type: notification.type,
            link: notification.link || null,
            timestamp: new Date().toISOString(),
            read: false
        };
        
        // Add the new notification to the beginning of the array
        notifications.unshift(newNotification);
        
        // Limit the number of notifications
        if (notifications.length > this.maxNotifications) {
            notifications.splice(this.maxNotifications);
        }
        
        // Save the updated notifications
        this.saveNotifications(notifications);
        
        // Update the notification count
        this.updateNotificationCount();
        
        // Update the notification list if on the notifications page
        this.loadNotifications();
        
        return newNotification;
    }
    
    /**
     * Add a calorie comparison notification
     * @param {boolean} isExceeding - Whether the calories exceed the daily limit
     * @param {number} totalRecipeCalories - Total calories from selected recipes
     * @param {number} dailyNeeds - Daily calorie needs
     * @param {number} difference - Difference between total and daily needs
     * @param {string} healthGoal - User's health goal
     */
    addCalorieComparisonNotification(isExceeding, totalRecipeCalories, dailyNeeds, difference, healthGoal) {
        let title, message, type;
        
        if (isExceeding) {
            title = 'Calorie Alert';
            message = `Your selected recipes total ${Math.round(totalRecipeCalories)} calories, which exceeds your estimated daily need of ${Math.round(dailyNeeds)} calories by ${Math.round(difference)} calories.`;
            type = 'warning';
            
            // Add specific advice for weight loss users
            if (healthGoal === 'weight_loss') {
                message += ` This may slow down your weight loss progress. Consider reducing portion sizes or adding exercise to compensate.`;
            }
        } else {
            title = 'Within Calorie Limits';
            message = `Your selected recipes total ${Math.round(totalRecipeCalories)} calories, which is within your estimated daily need of ${Math.round(dailyNeeds)} calories. You have ${Math.round(Math.abs(difference))} calories remaining.`;
            type = 'success';
            
            // Add encouragement for weight loss users
            if (healthGoal === 'weight_loss') {
                message += ` You're on track with your weight loss goals!`;
            }
        }
        
        return this.addNotification({
            title,
            message,
            type,
            link: '/personalized_recommendation'
        });
    }
    
    /**
     * Get all notifications from storage
     * @returns {Array} Array of notification objects
     */
    getNotifications() {
        const notificationsJson = localStorage.getItem(this.storageKey);
        return notificationsJson ? JSON.parse(notificationsJson) : [];
    }
    
    /**
     * Save notifications to storage
     * @param {Array} notifications - Array of notification objects
     */
    saveNotifications(notifications) {
        localStorage.setItem(this.storageKey, JSON.stringify(notifications));
    }
    
    /**
     * Load and display notifications on the page
     */
    loadNotifications() {
        const notificationsContainer = document.getElementById('notifications-container');
        if (!notificationsContainer) return;
        
        const notifications = this.getNotifications();
        
        if (notifications.length === 0) {
            notificationsContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-5xl text-gray-300 mb-4">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-500">No Notifications</h3>
                    <p class="text-gray-400 mt-2">You don't have any notifications at the moment.</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        
        notifications.forEach(notification => {
            const date = new Date(notification.timestamp);
            const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            
            // Determine icon and colors based on notification type
            let iconClass, bgClass, borderClass, textClass;
            
            switch (notification.type) {
                case 'success':
                    iconClass = 'fa-check-circle text-green-500';
                    bgClass = 'bg-green-50';
                    borderClass = 'border-green-100';
                    textClass = 'text-green-800';
                    break;
                case 'warning':
                    iconClass = 'fa-exclamation-triangle text-yellow-500';
                    bgClass = 'bg-yellow-50';
                    borderClass = 'border-yellow-100';
                    textClass = 'text-yellow-800';
                    break;
                case 'error':
                    iconClass = 'fa-times-circle text-red-500';
                    bgClass = 'bg-red-50';
                    borderClass = 'border-red-100';
                    textClass = 'text-red-800';
                    break;
                default: // info
                    iconClass = 'fa-info-circle text-blue-500';
                    bgClass = 'bg-blue-50';
                    borderClass = 'border-blue-100';
                    textClass = 'text-blue-800';
            }
            
            html += `
                <div class="notification-item ${bgClass} border ${borderClass} rounded-lg p-4 mb-4 ${notification.read ? 'opacity-70' : ''}" data-notification-id="${notification.id}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas ${iconClass} text-xl"></i>
                        </div>
                        <div class="ml-3 flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="text-md font-medium ${textClass}">${notification.title}</h4>
                                <div class="flex space-x-2">
                                    ${!notification.read ? `
                                        <button class="mark-notification-read text-gray-400 hover:text-gray-600" data-notification-id="${notification.id}" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    ` : ''}
                                    <button class="delete-notification text-gray-400 hover:text-gray-600" data-notification-id="${notification.id}" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm mt-1">${notification.message}</p>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-xs text-gray-500">${formattedDate}</span>
                                <a href="/notification-detail?id=${notification.id}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        notificationsContainer.innerHTML = html;
    }
    
    /**
     * Mark a notification as read
     * @param {string} notificationId - The ID of the notification to mark as read
     */
    markNotificationAsRead(notificationId) {
        const notifications = this.getNotifications();
        const notification = notifications.find(n => n.id === notificationId);
        
        if (notification) {
            notification.read = true;
            this.saveNotifications(notifications);
            this.updateNotificationCount();
            this.loadNotifications();
        }
    }
    
    /**
     * Delete a notification
     * @param {string} notificationId - The ID of the notification to delete
     */
    deleteNotification(notificationId) {
        let notifications = this.getNotifications();
        notifications = notifications.filter(n => n.id !== notificationId);
        
        this.saveNotifications(notifications);
        this.updateNotificationCount();
        
        // Reload notifications on the notifications page if it exists
        const notificationsContainer = document.getElementById('notifications-container');
        if (notificationsContainer) {
            this.loadNotifications();
        }
        
        // Reload dropdown notifications if the dropdown exists and is visible
        const dropdown = document.getElementById('notification-dropdown');
        if (dropdown && !dropdown.classList.contains('hidden')) {
            this.loadDropdownNotifications();
        }
    }
    
    /**
     * Clear all notifications
     */
    clearAllNotifications() {
        this.saveNotifications([]);
        this.updateNotificationCount();
        this.loadNotifications();
    }
    
    /**
     * Update the notification count in the UI
     */
    updateNotificationCount() {
        const notifications = this.getNotifications();
        const unreadCount = notifications.filter(n => !n.read).length;
        
        // Update all notification count elements
        const countElements = document.querySelectorAll('.notification-count');
        countElements.forEach(element => {
            if (unreadCount > 0) {
                element.textContent = unreadCount;
                element.classList.remove('hidden');
            } else {
                element.textContent = '';
                element.classList.add('hidden');
            }
        });
    }
    
    /**
     * Load notifications into the dropdown
     */
    loadDropdownNotifications() {
        const container = document.getElementById('dropdown-notifications');
        if (!container) return;
        
        const notifications = this.getNotifications();
        
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500">No notifications</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        
        // Show only the most recent 5 notifications
        const recentNotifications = notifications.slice(0, 5);
        
        recentNotifications.forEach(notification => {
            const date = new Date(notification.timestamp);
            const timeAgo = this.getTimeAgo(date);
            
            // Determine icon and colors based on notification type
            let iconClass;
            
            switch (notification.type) {
                case 'success':
                    iconClass = 'fa-check-circle text-green-500';
                    break;
                case 'warning':
                    iconClass = 'fa-exclamation-triangle text-yellow-500';
                    break;
                case 'error':
                    iconClass = 'fa-times-circle text-red-500';
                    break;
                default: // info
                    iconClass = 'fa-info-circle text-blue-500';
            }
            
            html += `
                <div class="dropdown-notification-item px-4 py-3 border-b border-gray-100 hover:bg-gray-50 ${notification.read ? 'opacity-70' : ''}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        <div class="ml-3 flex-grow">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                <button class="dropdown-delete-notification text-gray-400 hover:text-red-500" data-notification-id="${notification.id}" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">${notification.message}</p>
                            <p class="text-xs text-gray-500 mt-1">${timeAgo}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (notifications.length > 5) {
            html += `
                <div class="px-4 py-2 text-center">
                    <a href="/notifications" class="text-xs text-blue-600 hover:text-blue-800">
                        View all ${notifications.length} notifications
                    </a>
                </div>
            `;
        }
        
        container.innerHTML = html;
    }
    
    /**
     * Get time ago string from date
     * @param {Date} date - The date to format
     * @returns {string} - Formatted time ago string
     */
    getTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        
        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) {
            return interval === 1 ? '1 year ago' : `${interval} years ago`;
        }
        
        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) {
            return interval === 1 ? '1 month ago' : `${interval} months ago`;
        }
        
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) {
            return interval === 1 ? '1 day ago' : `${interval} days ago`;
        }
        
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) {
            return interval === 1 ? '1 hour ago' : `${interval} hours ago`;
        }
        
        interval = Math.floor(seconds / 60);
        if (interval >= 1) {
            return interval === 1 ? '1 minute ago' : `${interval} minutes ago`;
        }
        
        return seconds < 10 ? 'just now' : `${seconds} seconds ago`;
    }
}

// Create a global instance of the notification system
window.notificationSystem = new NotificationSystem(); 