<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registration Success - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <meta http-equiv="refresh" content="5;url={{ route('dashboard') }}">
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md w-full mx-auto">
                <div class="bg-white py-8 px-4 shadow-lg rounded-lg sm:px-10">
                    <div class="text-center">
                        <!-- Success Icon -->
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        
                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Registration Successful!</h2>
                        
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">
                                Thank you for joining Meal Suggestion. Your account has been created successfully.
                            </p>
                        </div>

                        <div class="mt-6 space-y-2">
                            <p class="text-sm text-gray-500">You will be redirected to your dashboard in 5 seconds...</p>
                            <div class="relative pt-1">
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-100">
                                    <div class="animate-progress-bar w-full h-full bg-green-500 rounded"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">
                                Not redirected? 
                                <a href="{{ route('dashboard') }}" class="font-medium text-green-600 hover:text-green-500">
                                    Click here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            @keyframes progress {
                0% { width: 0%; }
                100% { width: 100%; }
            }
            .animate-progress-bar {
                animation: progress 5s linear;
            }
        </style>
    </body>
</html> 