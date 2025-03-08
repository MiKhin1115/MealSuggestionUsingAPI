<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Complete Your Profile - Food App</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/form-validation.js') }}"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                        <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign In</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto pt-8 px-4">
            <div class="mb-8">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-green-600">Step 1 of 3</span>
                    <span class="text-sm font-medium text-gray-600">Profile Setup</span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full">
                    <div class="w-1/3 h-3 bg-green-600 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Question Form -->
        <div class="max-w-4xl mx-auto px-4 pb-16">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Tell us about yourself</h2>
                
                <form action="{{ route('register.questions.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <!-- Age -->
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" id="age" name="age" min="1" max="120" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Height and Weight Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                            <input type="number" id="height" name="height" min="1" max="300" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700">Current Weight (kg)</label>
                            <input type="number" id="weight" name="weight" min="1" max="500" step="0.1" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <!-- Diet Type -->
                    <div>
                        <label for="diet_type" class="block text-sm font-medium text-gray-700">Diet Type</label>
                        <select id="diet_type" name="diet_type" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="">Select a diet type</option>
                            <option value="omnivore">omnivore</option>
                            <option value="vegetarian">vegetarian</option>
                            <option value="vegan">vegan</option>
                        </select>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html> 