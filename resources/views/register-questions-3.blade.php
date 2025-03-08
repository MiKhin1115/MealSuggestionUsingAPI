<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Health Details - Food App</title>
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
                    <span class="text-sm font-medium text-green-600">Step 3 of 3</span>
                    <span class="text-sm font-medium text-gray-600">Final Details</span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full">
                    <div class="w-full h-3 bg-green-600 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Question Form -->
        <div class="max-w-4xl mx-auto px-4 pb-16">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Final Health & Preference Details</h2>
                
                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm text-red-700">
                                {{ session('error') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('register.questions3.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ session()->getId() }}">

                    <!-- No Allergies -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="no_allergies" name="no_allergies" type="checkbox" value="1"
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="no_allergies" class="font-medium text-gray-700">I don't have any food allergies</label>
                            <p class="text-gray-500">Check this if you don't have any food allergies or dietary restrictions</p>
                        </div>
                    </div>

                    <!-- Medical Conditions -->
                    <div>
                        <label for="medical_conditions" class="block text-sm font-medium text-gray-700">Medical Conditions</label>
                        <textarea id="medical_conditions" name="medical_conditions" rows="2"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="List any medical conditions that affect your diet (optional)"></textarea>
                    </div>

                    <!-- Health Goal -->
                    <div>
                        <label for="health_goal" class="block text-sm font-medium text-gray-700">Health Goal</label>
                        <select id="health_goal" name="health_goal" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="">Select your goal</option>
                            <option value="weight_loss">Weight Loss</option>
                            <option value="weight_gain">Weight Gain</option>
                            <option value="maintain_weight">Maintain Current Weight</option>
                        </select>
                    </div>

                    <!-- Favorite Snacks -->
                    <div>
                        <label for="favorite_snacks" class="block text-sm font-medium text-gray-700">Favorite Snacks</label>
                        <textarea id="favorite_snacks" name="favorite_snacks" rows="2"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="List your favorite snacks, separated by commas"></textarea>
                    </div>

                    <!-- Cooking Skill -->
                    <div>
                        <label for="cooking_skill" class="block text-sm font-medium text-gray-700">Cooking Skill Level</label>
                        <select id="cooking_skill" name="cooking_skill" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="beginner">Beginner - I'm learning to cook</option>
                            <option value="intermediate">Intermediate - I can follow recipes</option>
                            <option value="advanced">Advanced - I can cook without recipes</option>
                            <option value="expert">Expert - I can create my own recipes</option>
                        </select>
                    </div>

                    <!-- Cooking Time -->
                    <div>
                        <label for="cooking_time" class="block text-sm font-medium text-gray-700">Preferred Cooking Time (per meal)</label>
                        <select id="cooking_time" name="cooking_time" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="15">15 minutes or less</option>
                            <option value="30">15-30 minutes</option>
                            <option value="60">30-60 minutes</option>
                            <option value="90">More than 60 minutes</option>
                        </select>
                    </div>

                    <!-- Meal Budget -->
                    <div>
                        <label for="meal_budget" class="block text-sm font-medium text-gray-700">Daily Meal Budget</label>
                        <select id="meal_budget" name="meal_budget" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <option value="budget">Budget-friendly (Under 10000 kyats/day)</option>
                            <option value="moderate">Moderate (10000-20000 kyats/day)</option>
                            <option value="expensive">Expensive (Over 20000 kyats/day)</option>
                        </select>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <a href="/register-questions-2" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous Step
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Complete Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html> 