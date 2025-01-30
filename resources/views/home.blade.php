<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home - Meal Suggestion</title>
        @vite('resources/css/app.css')
    </head>
    <body class="antialiased">
        <!-- Top Navigation Bar -->
        <nav class="bg-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                        @auth
                            <a href="/dashboard" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900">Logout</button>
                                </form>
                            </div>
                        @else
                            <a href="/login" class="text-gray-600 hover:text-gray-900">Sign In</a>
                            <a href="/register" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Side Navigation -->
        <nav class="fixed left-0 top-16 h-screen w-64 bg-white shadow-lg z-40">
            <div class="py-6 px-4">
                <div class="space-y-8">
                    <a href="#home" class="flex items-center space-x-3 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Home</span>
                    </a>
                    <a href="#meal-suggestions" class="flex items-center space-x-3 text-gray-600 hover:text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Meal Suggestions</span>
                    </a>
                    <a href="#daily-planner" class="flex items-center space-x-3 text-gray-600 hover:text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Daily Planner</span>
                    </a>
                    <a href="#weekly-planner" class="flex items-center space-x-3 text-gray-600 hover:text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Weekly Planner</span>
                    </a>
                    <a href="#health-profile" class="flex items-center space-x-3 text-gray-600 hover:text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Health Profile</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="ml-64 pt-16"> <!-- Margin to account for sidebar -->
            <!-- Home Section -->
            <section id="home" class="min-h-screen bg-white p-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="rounded-lg overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" alt="Delicious meal" class="w-full h-96 object-cover">
                        </div>
                        <div class="space-y-6">
                            <h1 class="text-4xl font-bold text-gray-900">Welcome to Your Culinary Journey</h1>
                            <p class="text-xl text-gray-600">Discover a world of delicious recipes and personalized meal planning.</p>
                            @guest
                                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                                    <h3 class="text-lg font-semibold text-green-800">Start Your Journey Today</h3>
                                    <p class="mt-2 text-green-600">Create an account to access personalized meal suggestions and planning tools.</p>
                                    <div class="mt-4">
                                        <a href="/register" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">Sign Up Now</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>

            <!-- Meal Suggestions Section -->
            <section id="meal-suggestions" class="min-h-screen bg-gray-50 p-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="rounded-lg overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1547592180-85f173990554" alt="Healthy meals" class="w-full h-96 object-cover">
                        </div>
                        <div class="space-y-6">
                            <h2 class="text-4xl font-bold text-gray-900">Smart Recipe Suggestions</h2>
                            <p class="text-xl text-gray-600">Get personalized recipe recommendations based on your preferences and dietary needs.</p>
                            @guest
                                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                                    <h3 class="text-lg font-semibold text-green-800">Unlock Personalized Recipes</h3>
                                    <p class="mt-2 text-green-600">Join now to receive customized recipe suggestions.</p>
                                    <div class="mt-4">
                                        <a href="/register" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">Get Started</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>

            <!-- Daily Planner Section -->
            <section id="daily-planner" class="min-h-screen bg-white p-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="rounded-lg overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1506784365847-bbad939e9335" alt="Daily meal planning" class="w-full h-96 object-cover">
                        </div>
                        <div class="space-y-6">
                            <h2 class="text-4xl font-bold text-gray-900">Daily Meal Planning</h2>
                            <p class="text-xl text-gray-600">Plan your daily meals with ease and maintain a balanced diet throughout the day.</p>
                            @guest
                                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                                    <h3 class="text-lg font-semibold text-green-800">Start Planning Today</h3>
                                    <p class="mt-2 text-green-600">Create your daily meal schedule with our intuitive planner.</p>
                                    <div class="mt-4">
                                        <a href="/register" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">Plan Now</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>

            <!-- Weekly Planner Section -->
            <section id="weekly-planner" class="min-h-screen bg-gray-50 p-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="rounded-lg overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1543352634-a1c51d9f1fa7" alt="Weekly meal planning" class="w-full h-96 object-cover">
                        </div>
                        <div class="space-y-6">
                            <h2 class="text-4xl font-bold text-gray-900">Weekly Meal Planning</h2>
                            <p class="text-xl text-gray-600">Organize your entire week's meals in advance and save time on daily decisions.</p>
                            @guest
                                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                                    <h3 class="text-lg font-semibold text-green-800">Plan Your Week</h3>
                                    <p class="mt-2 text-green-600">Get started with weekly meal planning for better organization.</p>
                                    <div class="mt-4">
                                        <a href="/register" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">Start Planning</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>

            <!-- Health Profile Section -->
            <section id="health-profile" class="min-h-screen bg-white p-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="rounded-lg overflow-hidden shadow-xl">
                            <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352" alt="Health profile" class="w-full h-96 object-cover">
                        </div>
                        <div class="space-y-6">
                            <h2 class="text-4xl font-bold text-gray-900">Health Profile</h2>
                            <p class="text-xl text-gray-600">Customize your dietary preferences and health goals for personalized recommendations.</p>
                            @guest
                                <div class="bg-green-50 p-6 rounded-lg shadow-md">
                                    <h3 class="text-lg font-semibold text-green-800">Create Your Profile</h3>
                                    <p class="mt-2 text-green-600">Set up your health profile for tailored meal suggestions.</p>
                                    <div class="mt-4">
                                        <a href="/register" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">Get Started</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Add this script before closing body tag -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('nav a[href^="#"]');

            function updateActiveLink() {
                let current = '';
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (window.scrollY >= (sectionTop - 300)) {
                        current = '#' + section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('text-green-600');
                    link.classList.add('text-gray-600');
                    if (link.getAttribute('href') === current) {
                        link.classList.remove('text-gray-600');
                        link.classList.add('text-green-600');
                    }
                });
            }

            window.addEventListener('scroll', updateActiveLink);
            updateActiveLink(); // Call once on load
        });
        </script>
    </body>
</html> 