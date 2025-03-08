<!-- Header -->
<div class="header-wrapper">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<header class="bg-white shadow-lg fixed w-full z-50 top-0 left-0">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Left Side - Logo and Brand -->
            <div class="flex items-center">
                @auth
                    <a href="/dashboard" class="text-2xl font-bold text-green-600 italic font-serif">Meal_Suggestion</a>
                @else
                    <button id="menu-toggle" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <a href="/" class="text-2xl font-bold text-green-600 italic font-serif">Meal_Suggestion</a>
                @endauth
            </div>

            <!-- Right Side - Navigation -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Dashboard Navigation -->
                    <div class="hidden md:flex items-center space-x-6">
                        <!-- Removed About Us link from here -->
                    </div>
                    
                    <!-- Saved Recipes - Heart icon -->
                    <a href="{{ route('favorites') }}" class="text-gray-700 hover:text-red-500 transition-colors duration-200 mr-3" title="Saved Recipes">
                        <i class="fas fa-heart text-xl"></i>
                    </a>
                    
                    <!-- Get Recipes - Utensils icon -->
                    <a href="{{ route('get.recipes') }}" class="text-gray-700 hover:text-green-500 transition-colors duration-200 mr-3 relative" title="My Recipe Collection">
                        <i class="fas fa-utensils text-xl"></i>
                        @if(auth()->user()->getRecipes()->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                {{ auth()->user()->getRecipes()->count() < 10 ? auth()->user()->getRecipes()->count() : '9+' }}
                            </span>
                        @endif
                    </a>
                    
                    <!-- Notifications - Bell icon -->
                    <div class="relative">
                        <button id="notification-bell" class="text-gray-700 hover:text-yellow-500 transition-colors duration-200 mr-3 relative" title="Notifications">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="notification-count absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center hidden"></span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                                <a href="{{ route('notifications') }}" class="text-xs text-blue-600 hover:text-blue-800">View All</a>
                            </div>
                            <div id="dropdown-notifications" class="max-h-96 overflow-y-auto">
                                <!-- Notifications will be loaded here by JavaScript -->
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500">Loading notifications...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</span>
                                <span class="text-sm font-medium text-gray-900">Age - {{ auth()->user()->age }}</span>
                            </div>
                            <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                @if(auth()->user()->picture)
                                    <img src="{{ asset(auth()->user()->picture) }}" alt="User avatar" class="h-full w-full object-cover">
                                @else
                                    <span class="text-green-600 font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                @endif
                            </div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Public Navigation -->
                    <div class="flex items-center space-x-4">
                        <!-- Removed About Us link from here -->
                        <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign In</a>
                        <a href="/register" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu (Dashboard only) -->
        @auth
            <div class="md:hidden border-t border-gray-200">
                <div class="py-2 space-y-1">
                    <!-- Removed About Us link from here -->
                    <!-- Add Saved Recipes link to mobile menu -->
                    <a href="{{ route('favorites') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-heart text-lg mr-2"></i>
                        <span>Saved Recipes</span>
                    </a>
                    <!-- Add Get Recipes link to mobile menu -->
                    <a href="{{ route('get.recipes') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-utensils text-lg mr-2"></i>
                        <span>My Recipe Collection</span>
                    </a>
                    <!-- Add Notifications link to mobile menu -->
                    <div class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50">
                        <button id="mobile-notification-bell" class="flex items-center w-full text-left">
                            <i class="fas fa-bell text-lg mr-2"></i>
                            <span>Notifications</span>
                            <span class="notification-count ml-2 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center hidden"></span>
                        </button>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</header>

<!-- Spacing to prevent content from being hidden under the fixed header -->
<div class="h-20"></div>

<!-- Side Navigation (Only for main site, not dashboard) -->
@guest
<nav id="side-nav" class="fixed left-0 top-16 h-screen w-64 bg-white shadow-lg z-40 overflow-y-auto transform -translate-x-full transition-all duration-300 ease-in-out">
    <div class="sticky top-0 py-6 px-4">
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
                <span>Recipe Suggestions</span>
            </a>
            <a href="#daily-planner" class="flex items-center space-x-3 text-gray-600 hover:text-green-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Daily Meal</span>
            </a>
            <a href="#weekly-planner" class="flex items-center space-x-3 text-gray-600 hover:text-green-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Personalized Recommendation</span>
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
<div class="h-16"></div>
</div>

<style>
    /* Ensure content below header is properly spaced */
    body {
        padding-top: 4rem; /* Add top padding to the body */
        margin: 0;
    }
    
    .header-wrapper {
        width: 100%;
        position: relative;
    }
    
    .header-wrapper + * {
        margin-top: 1rem;
    }

    /* Active navigation link */
    .nav-active {
        color: #047857; /* text-green-600 */
        font-weight: 600;
    }
    
    /* Notification dropdown styles */
    #notification-dropdown {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .dropdown-notification-item {
        transition: background-color 0.2s;
    }
    
    .dropdown-notification-item:last-child {
        border-bottom: none;
    }
    
    .dropdown-delete-notification {
        opacity: 0.7;
        transition: opacity 0.2s, color 0.2s;
    }
    
    .dropdown-delete-notification:hover {
        opacity: 1;
    }
    
    /* Line clamp for notification messages */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- JavaScript for Menu Toggle (Only for main site) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const sideNav = document.getElementById('side-nav');
    const mainContent = document.getElementById('main-content');
    let isMenuOpen = false;

    if (menuToggle && sideNav) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            isMenuOpen = !isMenuOpen;
            
            if (isMenuOpen) {
                sideNav.style.transform = 'translateX(0)';
                mainContent.style.marginLeft = '16rem';
                menuToggle.innerHTML = '<i class="fas fa-times text-xl"></i>';
            } else {
                sideNav.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
                menuToggle.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!sideNav.contains(e.target) && !menuToggle.contains(e.target) && isMenuOpen) {
                isMenuOpen = false;
                sideNav.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
                menuToggle.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768 && isMenuOpen) {
                isMenuOpen = false;
                sideNav.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
                menuToggle.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            }
        });
        
        // Scrollspy functionality
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('#side-nav a');
        
        function highlightActiveSection() {
            let scrollY = window.pageYOffset;
            
            // Find current section
            sections.forEach(section => {
                const sectionHeight = section.offsetHeight;
                const sectionTop = section.offsetTop - 100; // Offset for header
                const sectionId = section.getAttribute('id');
                
                if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    // Remove active class from all links
                    navLinks.forEach(link => {
                        link.classList.remove('text-green-600');
                        link.classList.add('text-gray-600');
                    });
                    
                    // Add active class to current section link
                    const activeLink = document.querySelector(`#side-nav a[href="#${sectionId}"]`);
                    if(activeLink) {
                        activeLink.classList.remove('text-gray-600');
                        activeLink.classList.add('text-green-600');
                    }
                }
            });
        }
        
        // Run on load and scroll
        highlightActiveSection();
        window.addEventListener('scroll', highlightActiveSection);
    }
});
</script>
@endguest
