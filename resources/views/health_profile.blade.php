<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Health Profile - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Display Age and BMI information right after the header section -->
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-8">Health Profile</h1>
                
                <!-- Add Age and BMI Information Card -->
                <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Health Summary</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-r border-gray-200">
                            <p class="text-gray-600 mb-1">Age</p>
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-gray-800">{{ $user->age ?? 'N/A' }}</span>
                                <span class="ml-1 text-gray-600">years</span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">BMI (Body Mass Index) <a href="#" id="bmi-info-link" class="text-green-600 hover:text-green-800 text-sm ml-1"><i class="fas fa-info-circle"></i> Learn more</a></p>
                            @if(isset($questions1) && $questions1->height && $questions1->weight)
                                @php
                                    $heightInMeters = $questions1->height / 100; // Convert cm to meters
                                    $bmi = $questions1->weight / ($heightInMeters * $heightInMeters);
                                    $bmiCategory = '';
                                    
                                    if ($bmi < 18.5) {
                                        $bmiCategory = 'Underweight';
                                        $bmiColor = 'text-blue-600';
                                    } elseif ($bmi >= 18.5 && $bmi < 25) {
                                        $bmiCategory = 'Healthy Weight';
                                        $bmiColor = 'text-green-600';
                                    } elseif ($bmi >= 25 && $bmi < 30) {
                                        $bmiCategory = 'Overweight';
                                        $bmiColor = 'text-yellow-600';
                                    } else {
                                        $bmiCategory = 'Obese';
                                        $bmiColor = 'text-red-600';
                                    }
                                @endphp
                                <div class="flex items-end">
                                    <span class="text-2xl font-bold text-gray-800">{{ number_format($bmi, 1) }}</span>
                                    <span class="ml-2 {{ $bmiColor }} font-medium">{{ $bmiCategory }}</span>
                                </div>
                            @else
                                <span class="text-gray-500">Complete your height and weight to see BMI</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- BMI Information Modal -->
                <div id="bmi-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-lg max-w-3xl w-full mx-4 p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Understanding BMI (Body Mass Index)</h3>
                            <button id="close-bmi-modal" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <p class="mb-4">BMI is a measurement that uses your height and weight to determine if you are at a healthy weight. While BMI is useful as a quick assessment tool, it has limitations and doesn't account for factors like muscle mass, bone density, or body composition.</p>
                        
                        <div class="bg-gray-100 p-4 rounded-lg mb-6">
                            <h4 class="font-semibold mb-2">BMI Categories:</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                    <strong class="text-blue-600 mr-2">Underweight:</strong> 
                                    <span>Less than 18.5</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                    <strong class="text-green-600 mr-2">Healthy weight:</strong> 
                                    <span>18.5 to 24.9</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                    <strong class="text-yellow-600 mr-2">Overweight:</strong> 
                                    <span>25 to 29.9</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                    <strong class="text-red-600 mr-2">Obesity:</strong> 
                                    <span>30 or higher</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Visual BMI Scale:</h4>
                            <div class="relative h-8 bg-gray-200 rounded-lg overflow-hidden flex mb-2">
                                <div class="h-full bg-blue-500 w-[18.5%]"></div>
                                <div class="h-full bg-green-500 w-[25%]"></div>
                                <div class="h-full bg-yellow-500 w-[25%]"></div>
                                <div class="h-full bg-red-500 w-[31.5%]"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>16</span>
                                <span>18.5</span>
                                <span>25</span>
                                <span>30</span>
                                <span>40</span>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Important note:</strong> BMI is just one of many factors to consider when assessing health. It doesn't account for muscle mass, bone density, body composition, or ethnic differences. Always consult with healthcare professionals for personalized health advice.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button id="close-bmi-modal-btn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Close</button>
                        </div>
                    </div>
                </div>
                
                <!-- Form section continues below -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-medium">Error:</p>
                        <p>{{ session('error') }}</p>
                        @if(session('error_details'))
                            <div class="mt-2 text-sm">
                                <p class="font-medium">Details:</p>
                                <pre class="mt-1 bg-red-50 p-2 rounded overflow-x-auto">{{ session('error_details') }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Debug information -->
                @if(config('app.debug') && session('debug_info'))
                    <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4 mb-6">
                        <p class="font-medium">Debug Information:</p>
                        <pre class="mt-1 bg-gray-50 p-2 rounded overflow-x-auto text-xs">{{ print_r(session('debug_info'), true) }}</pre>
                    </div>
                @endif
                
                <form action="{{ route('health.profile.update') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
                    @csrf
                    
                    @if($errors->any())
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <p class="font-medium">Please correct the following errors:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Basic Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Birthday -->
                            <div>
                                <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">Birthday</label>
                                <input type="date" id="birthday" name="birthday" 
                                       value="{{ old('birthday', $user->birthday ?? '') }}" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Used to calculate your age and nutritional needs</p>
                            </div>
                            
                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <div class="flex space-x-4 mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="gender" value="male" class="form-radio text-green-600" 
                                               {{ (old('gender', $user->gender ?? '') == 'male') ? 'checked' : '' }}>
                                        <span class="ml-2">Male</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="gender" value="female" class="form-radio text-green-600" 
                                               {{ (old('gender', $user->gender ?? '') == 'female') ? 'checked' : '' }}>
                                        <span class="ml-2">Female</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="gender" value="other" class="form-radio text-green-600" 
                                               {{ (old('gender', $user->gender ?? '') == 'other') ? 'checked' : '' }}>
                                        <span class="ml-2">Other</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Height -->
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                                <input type="number" id="height" name="height" min="70" max="250" step="0.1"
                                       value="{{ old('height', $questions1->height ?? '') }}" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            
                            <!-- Weight -->
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                <input type="number" id="weight" name="weight" min="20" max="300" step="0.1"
                                       value="{{ old('weight', $questions1->weight ?? '') }}" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dietary Preferences Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Dietary Preferences</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Diet Type -->
                            <div>
                                <label for="diet_type" class="block text-sm font-medium text-gray-700 mb-1">Diet Type</label>
                                <select id="diet_type" name="diet_type" 
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="omnivore" {{ (old('diet_type', $questions1->diet_type ?? '') == 'omnivore') ? 'selected' : '' }}>Omnivore (Eat Everything)</option>
                                    <option value="vegetarian" {{ (old('diet_type', $questions1->diet_type ?? '') == 'vegetarian') ? 'selected' : '' }}>Vegetarian (No Meat)</option>
                                    <option value="vegan" {{ (old('diet_type', $questions1->diet_type ?? '') == 'vegan') ? 'selected' : '' }}>Vegan (No Animal Products)</option>
                                </select>
                            </div>
                            
                            <!-- Health Goal -->
                            <div>
                                <label for="health_goal" class="block text-sm font-medium text-gray-700 mb-1">Health Goal</label>
                                <select id="health_goal" name="health_goal" 
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="weight_loss" {{ (old('health_goal', $questions3->health_goal ?? '') == 'weight_loss') ? 'selected' : '' }}>Weight Loss</option>
                                    <option value="weight_gain" {{ (old('health_goal', $questions3->health_goal ?? '') == 'weight_gain') ? 'selected' : '' }}>Weight Gain</option>
                                    <option value="maintain_weight" {{ (old('health_goal', $questions3->health_goal ?? '') == 'maintain_weight') ? 'selected' : '' }}>Maintain Weight</option>
                                </select>
                            </div>
                            
                            <!-- Disliked Ingredients -->
                            <div class="md:col-span-2">
                                <label for="disliked_ingredients" class="block text-sm font-medium text-gray-700 mb-1">Disliked Ingredients</label>
                                <textarea id="disliked_ingredients" name="disliked_ingredients" rows="2" placeholder="List ingredients you don't like separated by commas (e.g., cilantro, olives, mushrooms)"
                                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('disliked_ingredients', $questions2->disliked_ingredients ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medical Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Medical Information</h2>
                        
                        <div class="mb-4">
                            <label for="medical_conditions" class="block text-sm font-medium text-gray-700 mb-1">Medical Conditions</label>
                            <textarea id="medical_conditions" name="medical_conditions" rows="2" placeholder="List any medical conditions that affect your diet (e.g., diabetes, hypertension, celiac disease)"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('medical_conditions', $questions3->medical_conditions ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">This information helps us tailor meal suggestions to your health needs</p>
                        </div>
                    </div>
                    
                    <!-- Cooking Preferences Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Cooking Preferences</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cooking Skill -->
                            <div>
                                <label for="cooking_skill" class="block text-sm font-medium text-gray-700 mb-1">Cooking Skill Level</label>
                                <select id="cooking_skill" name="cooking_skill" 
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="beginner" {{ (old('cooking_skill', $questions3->cooking_skill ?? '') == 'beginner') ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ (old('cooking_skill', $questions3->cooking_skill ?? '') == 'intermediate') ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ (old('cooking_skill', $questions3->cooking_skill ?? '') == 'advanced') ? 'selected' : '' }}>Advanced</option>
                                    <option value="expert" {{ (old('cooking_skill', $questions3->cooking_skill ?? '') == 'expert') ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>
                            
                            <!-- Cooking Time -->
                            <div>
                                <label for="cooking_time" class="block text-sm font-medium text-gray-700 mb-1">Preferred Cooking Time (minutes)</label>
                                <input type="number" id="cooking_time" name="cooking_time" min="5" max="180"
                                       value="{{ old('cooking_time', $questions3->cooking_time ?? 30) }}" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Maximum time you want to spend cooking a meal</p>
                            </div>
                            
                            <!-- Meal Budget -->
                            <div>
                                <label for="meal_budget" class="block text-sm font-medium text-gray-700 mb-1">Meal Budget</label>
                                <select id="meal_budget" name="meal_budget" 
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="budget" {{ (old('meal_budget', $questions3->meal_budget ?? '') == 'budget') ? 'selected' : '' }}>Budget-friendly</option>
                                    <option value="moderate" {{ (old('meal_budget', $questions3->meal_budget ?? '') == 'moderate') ? 'selected' : '' }}>Moderate</option>
                                    <option value="expensive" {{ (old('meal_budget', $questions3->meal_budget ?? '') == 'expensive') ? 'selected' : '' }}>Premium</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">How much you typically spend on ingredients per meal</p>
                            </div>
                            
                            <!-- Favorite Snacks -->
                            <div>
                                <label for="favorite_snacks" class="block text-sm font-medium text-gray-700 mb-1">Favorite Snacks</label>
                                <input type="text" id="favorite_snacks" name="favorite_snacks" 
                                       value="{{ old('favorite_snacks', $questions3->favorite_snacks ?? '') }}" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="e.g., nuts, fruits, yogurt, chips">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" id="save-btn" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <span>Save Changes</span>
                            <span id="spinner" class="ml-2 hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Form submission loading state
                const form = document.querySelector('form');
                const saveBtn = document.getElementById('save-btn');
                const spinner = document.getElementById('spinner');
                
                if (form && saveBtn && spinner) {
                    form.addEventListener('submit', function() {
                        // Disable the button and show spinner
                        saveBtn.disabled = true;
                        spinner.classList.remove('hidden');
                        saveBtn.querySelector('span:first-child').textContent = 'Saving...';
                        saveBtn.classList.add('opacity-75');
                        
                        // Allow form submission to continue
                        return true;
                    });
                }
                
                // BMI Information Modal
                const bmiInfoLink = document.getElementById('bmi-info-link');
                const bmiModal = document.getElementById('bmi-modal');
                const closeBmiModal = document.getElementById('close-bmi-modal');
                const closeBmiModalBtn = document.getElementById('close-bmi-modal-btn');
                
                if (bmiInfoLink && bmiModal) {
                    // Open modal
                    bmiInfoLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        bmiModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden'; // Prevent scrolling
                    });
                    
                    // Close modal functions
                    const closeModal = function() {
                        bmiModal.classList.add('hidden');
                        document.body.style.overflow = ''; // Restore scrolling
                    };
                    
                    if (closeBmiModal) {
                        closeBmiModal.addEventListener('click', closeModal);
                    }
                    
                    if (closeBmiModalBtn) {
                        closeBmiModalBtn.addEventListener('click', closeModal);
                    }
                    
                    // Close on outside click
                    bmiModal.addEventListener('click', function(e) {
                        if (e.target === bmiModal) {
                            closeModal();
                        }
                    });
                    
                    // Close on ESC key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && !bmiModal.classList.contains('hidden')) {
                            closeModal();
                        }
                    });
                }
            });
        </script>
        <x-footer />
    </body>
</html> 