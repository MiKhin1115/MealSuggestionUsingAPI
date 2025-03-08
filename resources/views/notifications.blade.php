<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Notifications - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Main Content -->
        <div class="container mx-auto px-4 md:px-16 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
                <button id="clear-all-notifications" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition-colors flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i>
                    <span>Clear All</span>
                </button>
            </div>
            
            <p class="text-gray-600 mb-8">
                Stay updated with important information about your meal plans and calorie tracking.
            </p>
            
            <!-- Notifications Container -->
            <div id="notifications-container" class="space-y-4">
                <!-- Notifications will be loaded here by JavaScript -->
                <div class="text-center py-8">
                    <div class="text-5xl text-gray-300 mb-4">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-500">Loading Notifications...</h3>
                </div>
            </div>
        </div>
        
        <!-- Include the notification system JavaScript -->
        <script src="{{ asset('js/notification-system.js') }}"></script>
        <x-footer />
    </body>
</html> 