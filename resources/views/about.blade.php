<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About Us - Meal Suggestion</title>
        @vite('resources/css/app.css')
    </head>
    <body class="antialiased">
        <!-- Navigation Bar -->
        <x-header />

        <!-- Hero Section -->
        <div class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:pb-28 lg:w-full">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">Smart Recipe Suggestion System</span>
                                <span class="block text-green-600">Your Personalized Meal Planner</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Helping you find the best recipes based on your ingredients, dietary needs, and preferences!
                            </p>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" 
                     src="https://images.unsplash.com/photo-1556911220-bff31c812dba" 
                     alt="Family cooking">
            </div>
        </div>

        <!-- Our Mission -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Our Mission</h2>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        We believe that eating healthy and delicious meals should be simple. Our system helps you discover personalized meal plans while minimizing food waste and saving time!
                    </p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-center text-gray-900">How It Works</h2>
                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="text-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white mx-auto">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Personalized Meal Suggestions</h3>
                        </div>
                        <!-- Add other features similarly -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-12">What Our Users Say</h2>
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white shadow-lg rounded-lg p-8">
                        <p class="text-gray-600 italic">"I love how this app helps me plan meals with what I already have in my fridge!"</p>
                        <p class="mt-4 font-medium text-gray-900">- Sarah L.</p>
                    </div>
                </div>
            </div>
        </div>
        <x-footer />
    </body>
</html> 