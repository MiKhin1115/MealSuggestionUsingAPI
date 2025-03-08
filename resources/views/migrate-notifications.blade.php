@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Migrate Notifications</h1>
            
            <div class="mb-6">
                <p class="mb-2">We've upgraded our notification system to provide better reliability and cross-device access. This page will help you migrate your existing notifications to the new system.</p>
                <p class="mb-2">Your existing notifications are currently stored in your browser's local storage. By clicking the button below, we'll transfer them to our secure database so you can access them from any device.</p>
            </div>
            
            <div id="migration-status" class="mb-6 p-4 bg-gray-100 rounded-lg hidden">
                <p id="status-message">Preparing to migrate notifications...</p>
                <div id="progress-container" class="mt-2 hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                    <p id="progress-text" class="text-sm text-gray-600 mt-1">0%</p>
                </div>
            </div>
            
            <div id="migration-results" class="mb-6 hidden">
                <div id="success-message" class="p-4 bg-green-100 text-green-800 rounded-lg mb-4 hidden">
                    <h3 class="font-bold">Migration Successful!</h3>
                    <p id="success-details"></p>
                </div>
                
                <div id="error-message" class="p-4 bg-red-100 text-red-800 rounded-lg mb-4 hidden">
                    <h3 class="font-bold">Migration Failed</h3>
                    <p id="error-details"></p>
                </div>
            </div>
            
            <div class="flex justify-center">
                <button id="migrate-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Migrate My Notifications
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const migrateButton = document.getElementById('migrate-button');
    const migrationStatus = document.getElementById('migration-status');
    const statusMessage = document.getElementById('status-message');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const migrationResults = document.getElementById('migration-results');
    const successMessage = document.getElementById('success-message');
    const successDetails = document.getElementById('success-details');
    const errorMessage = document.getElementById('error-message');
    const errorDetails = document.getElementById('error-details');
    
    migrateButton.addEventListener('click', async function() {
        // Disable the button
        migrateButton.disabled = true;
        migrateButton.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Show migration status
        migrationStatus.classList.remove('hidden');
        
        try {
            // Check if notification system is available
            if (!window.notificationSystem) {
                throw new Error('Notification system not found. Please refresh the page and try again.');
            }
            
            // Get notifications from localStorage
            statusMessage.textContent = 'Retrieving notifications from local storage...';
            const notifications = window.notificationSystem.getNotificationsFromLocalStorage();
            
            if (notifications.length === 0) {
                successMessage.classList.remove('hidden');
                successDetails.textContent = 'No notifications found in local storage. Nothing to migrate.';
                migrationResults.classList.remove('hidden');
                statusMessage.textContent = 'Migration complete.';
                return;
            }
            
            // Show progress container
            progressContainer.classList.remove('hidden');
            statusMessage.textContent = 'Migrating notifications to database...';
            
            // Migrate notifications one by one
            let migratedCount = 0;
            let failedCount = 0;
            
            for (let i = 0; i < notifications.length; i++) {
                const notification = notifications[i];
                
                try {
                    // Update progress
                    const progress = Math.round((i / notifications.length) * 100);
                    progressBar.style.width = `${progress}%`;
                    progressText.textContent = `${progress}% (${i}/${notifications.length})`;
                    
                    // Create notification data
                    const notificationData = {
                        title: notification.title || 'Notification',
                        message: notification.message || '',
                        type: notification.type || 'info',
                        link: notification.link || null,
                        read: notification.read || false,
                        data: notification.data || null
                    };
                    
                    // Send to server
                    const response = await fetch('/api/notifications', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(notificationData)
                    });
                    
                    if (!response.ok) {
                        throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                    }
                    
                    migratedCount++;
                } catch (error) {
                    console.error('Error migrating notification:', error);
                    failedCount++;
                }
            }
            
            // Update progress to 100%
            progressBar.style.width = '100%';
            progressText.textContent = '100% (Complete)';
            
            // Show results
            if (migratedCount > 0) {
                successMessage.classList.remove('hidden');
                successDetails.textContent = `Successfully migrated ${migratedCount} notifications to the database.`;
            }
            
            if (failedCount > 0) {
                errorMessage.classList.remove('hidden');
                errorDetails.textContent = `Failed to migrate ${failedCount} notifications. These will remain in local storage.`;
            }
            
            migrationResults.classList.remove('hidden');
            statusMessage.textContent = 'Migration complete.';
            
            // Clear localStorage notifications if all were migrated successfully
            if (failedCount === 0) {
                window.notificationSystem.clearAllNotificationsFromLocalStorage();
            }
            
        } catch (error) {
            console.error('Migration error:', error);
            errorMessage.classList.remove('hidden');
            errorDetails.textContent = error.message || 'An unknown error occurred during migration.';
            migrationResults.classList.remove('hidden');
            statusMessage.textContent = 'Migration failed.';
        }
    });
});
</script>
@endsection 