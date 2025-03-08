<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About Us - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Top Navigation Bar -->
        <x-header />

        <!-- Hero Section with Video Background -->
        <div class="relative overflow-hidden bg-cover bg-center h-96" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative container mx-auto px-6 flex flex-col items-center justify-center h-full text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About Meal Suggestion</h1>
                <p class="text-xl text-white max-w-2xl">Your journey to healthier and tastier meals starts here. Discover personalized recipes, nutrition advice, and culinary inspiration.</p>
                <a href="#our-mission" class="mt-8 animate-bounce">
                    <i class="fas fa-chevron-down text-white text-2xl"></i>
                </a>
            </div>
        </div>

        <!-- Our Mission Section -->
        <div id="our-mission" class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto text-center">
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">Our Mission</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Empowering Healthy Eating Habits</h2>
                    <p class="text-lg text-gray-600 mb-8">
                        At Meal Suggestion, we believe that eating well shouldn't be complicated. Our mission is to simplify healthy eating by providing personalized recipe suggestions, nutrition guidance, and meal planning tools that fit your lifestyle and preferences.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 transform hover:-translate-y-1">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-utensils text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Personalized Recipes</h3>
                            <p class="text-gray-600">Discover recipes tailored to your dietary needs, preferences, and health goals.</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 transform hover:-translate-y-1">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-apple-alt text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nutrition Guidance</h3>
                            <p class="text-gray-600">Get expert advice on balanced nutrition, portion control, and making healthier food choices.</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 transform hover:-translate-y-1">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-alt text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Meal Planning</h3>
                            <p class="text-gray-600">Simplify your week with easy-to-use meal planners that save time and reduce food waste.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Food & Health Articles Section -->
        <div class="py-16 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">Knowledge Center</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Food & Health Articles</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Explore our collection of expert-written articles on nutrition, cooking techniques, and developing healthier eating habits.
                    </p>
                </div>
                
                <!-- Article Categories -->
                <div class="flex flex-wrap justify-center gap-3 mb-10">
                    <button class="category-btn active px-4 py-2 rounded-full bg-green-600 text-white hover:bg-green-700 transition-colors" data-category="all">All Articles</button>
                    <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-category="nutrition">Nutrition</button>
                    <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-category="cooking">Cooking Tips</button>
                    <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-category="diet">Healthy Diets</button>
                    <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-category="wellness">Wellness</button>
                </div>
                
                <!-- Featured Articles -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="articles-container">
                    <!-- Article 1 -->
                    <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="nutrition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                 alt="Understanding Macronutrients" class="w-full h-48 object-cover">
                            <div class="absolute top-0 left-0 m-3 px-2 py-1 bg-green-500 text-white text-xs font-bold rounded">Popular</div>
                        </div>
                        <div class="p-6">
                            <span class="inline-block py-1 px-2 rounded-full bg-green-100 text-green-800 text-xs font-medium mb-2">Nutrition</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Understanding Macronutrients</h3>
                            <p class="text-gray-600 mb-4">Learn how proteins, carbohydrates, and fats fuel your body and why balanced intake matters.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 5 min read</span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Article 2 -->
                    <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="cooking">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                 alt="Meal Prep 101" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-6">
                            <span class="inline-block py-1 px-2 rounded-full bg-blue-100 text-blue-800 text-xs font-medium mb-2">Cooking Tips</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Meal Prep 101: Save Time & Eat Well</h3>
                            <p class="text-gray-600 mb-4">Discover effective strategies to prepare your meals for the week in just a few hours.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 8 min read</span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Article 3 -->
                    <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="diet">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                 alt="Mediterranean Diet" class="w-full h-48 object-cover">
                            <div class="absolute top-0 left-0 m-3 px-2 py-1 bg-blue-500 text-white text-xs font-bold rounded">New</div>
                        </div>
                        <div class="p-6">
                            <span class="inline-block py-1 px-2 rounded-full bg-purple-100 text-purple-800 text-xs font-medium mb-2">Healthy Diets</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">The Mediterranean Diet Explained</h3>
                            <p class="text-gray-600 mb-4">Explore why the Mediterranean diet is consistently rated as one of the healthiest eating patterns.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 6 min read</span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- New Article 4 -->
                    <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="nutrition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1576402187878-974f70c890a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                 alt="Super Foods" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-6">
                            <span class="inline-block py-1 px-2 rounded-full bg-green-100 text-green-800 text-xs font-medium mb-2">Nutrition</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Superfoods: Facts vs. Fiction</h3>
                            <p class="text-gray-600 mb-4">Separating marketing hype from nutritional reality about today's trendy superfoods.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 7 min read</span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- New Article 5 -->
                    <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="wellness">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                 alt="Mindful Eating" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-6">
                            <span class="inline-block py-1 px-2 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium mb-2">Wellness</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">The Practice of Mindful Eating</h3>
                            <p class="text-gray-600 mb-4">How being present and mindful during meals can transform your relationship with food.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 4 min read</span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- New Article 6 -->
                    <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="cooking">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1556911220-e15b29be8c8f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                 alt="Healthy Cooking Methods" class="w-full h-48 object-cover">
                            <div class="absolute top-0 left-0 m-3 px-2 py-1 bg-green-500 text-white text-xs font-bold rounded">Popular</div>
                        </div>
                        <div class="p-6">
                            <span class="inline-block py-1 px-2 rounded-full bg-blue-100 text-blue-800 text-xs font-medium mb-2">Cooking Tips</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Healthiest Cooking Methods Explained</h3>
                            <p class="text-gray-600 mb-4">A guide to cooking techniques that preserve nutrients and enhance flavor without excess fat.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 9 min read</span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- View All Articles Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                        View All Articles
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
                
                <!-- Newsletter Subscription -->
                <div class="mt-16 bg-green-50 p-8 rounded-xl shadow-md">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="text-left">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Subscribe to Our Newsletter</h3>
                            <p class="text-gray-600">Get the latest articles, recipes, and health tips delivered to your inbox every week.</p>
                        </div>
                        <div class="w-full md:w-1/2">
                            <form class="flex flex-col sm:flex-row gap-3">
                                <input type="email" placeholder="Your email address" class="flex-grow px-4 py-3 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                                    Subscribe
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-2">
                                We respect your privacy. No spam, just valuable content.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for Article Filtering -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categoryButtons = document.querySelectorAll('.category-btn');
                const articleCards = document.querySelectorAll('.article-card');
                
                // Add click event listeners to category buttons
                categoryButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Remove active class from all buttons
                        categoryButtons.forEach(btn => btn.classList.remove('active', 'bg-green-600', 'text-white'));
                        categoryButtons.forEach(btn => btn.classList.add('bg-gray-200', 'text-gray-700'));
                        
                        // Add active class to clicked button
                        button.classList.add('active', 'bg-green-600', 'text-white');
                        button.classList.remove('bg-gray-200', 'text-gray-700');
                        
                        const category = button.getAttribute('data-category');
                        
                        // Show/hide articles based on category
                        articleCards.forEach(card => {
                            if (category === 'all' || card.getAttribute('data-category') === category) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                });
            });
        </script>

        <!-- Contact Section -->
        <div class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">Get in Touch</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Contact Us</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Have questions, feedback, or suggestions? We'd love to hear from you. Reach out to our team using any of the methods below.
                    </p>
                </div>
                
                <!-- Contact Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <!-- Location -->
                    <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-md transform transition-transform hover:-translate-y-2 hover:shadow-lg">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-map-marker-alt text-2xl text-green-500"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Our Location</h3>
                        <p class="mt-2 text-center text-gray-500">123 Healthy Street</p>
                        <p class="mt-1 text-center text-gray-500">Yangon, Myanmar 11001</p>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-md transform transition-transform hover:-translate-y-2 hover:shadow-lg">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-phone text-2xl text-green-500"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Call Us</h3>
                        <p class="mt-2 text-center text-gray-500">+95 9 777 444 695</p>
                        <p class="mt-1 text-center text-gray-500">Monday - Friday: 9am - 5pm</p>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-md transform transition-transform hover:-translate-y-2 hover:shadow-lg">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-envelope text-2xl text-green-500"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Email Us</h3>
                        <p class="mt-2 text-center text-gray-500">
                            <a href="mailto:support@mealsuggestion.com" class="text-green-500 hover:underline">support@mealsuggestion.com</a>
                        </p>
                        <p class="mt-1 text-center text-gray-500">We'll respond as soon as possible</p>
                    </div>
                </div>
            </div>
        </div>

        
        
        
        <!-- Testimonials Section -->
        <div class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">User Stories</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">What Our Users Say</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Don't just take our word for it. Hear from people who have transformed their eating habits with Meal Suggestion.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <!-- Testimonial 1 -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <div class="text-yellow-400 flex">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 italic mb-4">"The personalized recipes have completely changed how I eat. I've lost 15 pounds and have so much more energy throughout the day."</p>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">S</div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Sarah Johnson</h4>
                                <p class="text-xs text-gray-500">Member since 2022</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <div class="text-yellow-400 flex">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 italic mb-4">"As a busy professional, meal planning was always a challenge. This platform has saved me so much time and reduced my food waste considerably."</p>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">M</div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Michael Chen</h4>
                                <p class="text-xs text-gray-500">Member since 2021</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <div class="text-yellow-400 flex">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <p class="text-gray-600 italic mb-4">"The nutrition guidance has been eye-opening. I now understand how to balance my meals properly and feel so much better for it!"</p>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">A</div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Amara Williams</h4>
                                <p class="text-xs text-gray-500">Member since 2023</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <x-footer />

        <!-- Optional: Back to top button -->
        <button id="back-to-top" class="fixed bottom-8 right-8 p-2 rounded-full bg-green-600 text-white shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-green-700">
            <i class="fas fa-chevron-up text-xl"></i>
        </button>

        <!-- JavaScript for Back to Top button -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const backToTopButton = document.getElementById('back-to-top');
                
                // Show/hide the button based on scroll position
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTopButton.classList.remove('opacity-0', 'invisible');
                        backToTopButton.classList.add('opacity-100', 'visible');
                    } else {
                        backToTopButton.classList.remove('opacity-100', 'visible');
                        backToTopButton.classList.add('opacity-0', 'invisible');
                    }
                });
                
                // Scroll to top when clicked
                backToTopButton.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        </script>
    </body>
</html> 