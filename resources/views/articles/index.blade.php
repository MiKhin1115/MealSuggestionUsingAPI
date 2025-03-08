@extends('layouts.app')

@section('content')
<!-- Hero Header Section -->
<div class="relative overflow-hidden bg-cover bg-center h-72" style="background-image: url('https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative container mx-auto px-6 flex flex-col items-center justify-center h-full text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Food & Health Articles</h1>
        <p class="text-xl text-white max-w-2xl">Discover expert insights, tips, and advice to improve your nutrition knowledge and eating habits.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Browse Our Collection</h2>
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

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" id="articles-container">
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
                <p class="text-gray-600 mb-4 line-clamp-3">Learn how proteins, carbohydrates, and fats fuel your body and why balanced intake matters.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 5 min read</span>
                    <a href="{{ route('articles.show', 'understanding-macronutrients') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
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
                <p class="text-gray-600 mb-4 line-clamp-3">Discover effective strategies to prepare your meals for the week in just a few hours.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 8 min read</span>
                    <a href="{{ route('articles.show', 'meal-prep-101') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
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
                <p class="text-gray-600 mb-4 line-clamp-3">Explore why the Mediterranean diet is consistently rated as one of the healthiest eating patterns.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 6 min read</span>
                    <a href="{{ route('articles.show', 'mediterranean-diet-explained') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Article 4 -->
        <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="nutrition">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1576402187878-974f70c890a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                     alt="Super Foods" class="w-full h-48 object-cover">
            </div>
            <div class="p-6">
                <span class="inline-block py-1 px-2 rounded-full bg-green-100 text-green-800 text-xs font-medium mb-2">Nutrition</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Superfoods: Facts vs. Fiction</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">Separating marketing hype from nutritional reality about today's trendy superfoods.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 7 min read</span>
                    <a href="{{ route('articles.show', 'superfoods-facts-vs-fiction') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Article 5 -->
        <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="wellness">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                     alt="Mindful Eating" class="w-full h-48 object-cover">
            </div>
            <div class="p-6">
                <span class="inline-block py-1 px-2 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium mb-2">Wellness</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">The Practice of Mindful Eating</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">How being present and mindful during meals can transform your relationship with food.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 4 min read</span>
                    <a href="{{ route('articles.show', 'practice-of-mindful-eating') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Article 6 -->
        <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="cooking">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1556911220-e15b29be8c8f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                     alt="Healthy Cooking Methods" class="w-full h-48 object-cover">
                <div class="absolute top-0 left-0 m-3 px-2 py-1 bg-green-500 text-white text-xs font-bold rounded">Popular</div>
            </div>
            <div class="p-6">
                <span class="inline-block py-1 px-2 rounded-full bg-blue-100 text-blue-800 text-xs font-medium mb-2">Cooking Tips</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Healthiest Cooking Methods Explained</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">A guide to cooking techniques that preserve nutrients and enhance flavor without excess fat.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 9 min read</span>
                    <a href="{{ route('articles.show', 'healthiest-cooking-methods') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Article 7 - The new article about calories -->
        <div class="article-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2" data-category="nutrition">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                     alt="Low vs High Calorie Diets" class="w-full h-48 object-cover">
                <div class="absolute top-0 left-0 m-3 px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">Featured</div>
            </div>
            <div class="p-6">
                <span class="inline-block py-1 px-2 rounded-full bg-green-100 text-green-800 text-xs font-medium mb-2">Nutrition</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Is Lower Calories Better Than Higher Calories?</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">Exploring the nuances of caloric intake and why the quality of calories matters as much as the quantity.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500"><i class="far fa-clock mr-1"></i> 7 min read</span>
                    <a href="{{ route('articles.show', 'lower-vs-higher-calories') }}" class="text-green-600 hover:text-green-700 font-medium inline-flex items-center">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
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
</div>
@endsection 