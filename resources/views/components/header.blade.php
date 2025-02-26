<!-- Header -->
<div class="header-wrapper">
<header class="bg-white shadow-lg fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Left Side - Logo and Brand -->
            <div class="flex items-center">
                @auth
                    <a href="/dashboard" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                @else
                    <button id="menu-toggle" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <a href="/" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                @endauth
            </div>

            <!-- Right Side - Navigation -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Dashboard Navigation -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="/meal-suggestions" class="text-gray-600 hover:text-gray-900">Recipe Suggestions</a>
                        <a href="/daily-meal-1" class="text-gray-600 hover:text-gray-900">Daily Meal</a>
                        <a href="/personalized-recommendations" class="text-gray-600 hover:text-gray-900">Recommendations</a>
                        <a href="/health-profile" class="text-gray-600 hover:text-gray-900">Health Profile</a>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <div class="hidden md:flex flex-col items-end">
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                            @if(auth()->user()->picture)
                                <img src="{{ auth()->user()->picture }}" alt="User avatar" class="h-full w-full object-cover">
                            @else
                                <span class="text-green-600 font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            @endif
                        </div>
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
                        <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                        <a href="/login" class="text-gray-600 hover:text-gray-900">Sign In</a>
                        <a href="/register" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu (Dashboard only) -->
        @auth
            <div class="md:hidden border-t border-gray-200">
                <div class="py-2 space-y-1">
                    <a href="/meal-suggestions" class="block px-4 py-2 text-gray-600 hover:bg-gray-50">Recipe Suggestions</a>
                    <a href="/daily-meal-1" class="block px-4 py-2 text-gray-600 hover:bg-gray-50">Daily Meal</a>
                    <a href="/personalized-recommendations" class="block px-4 py-2 text-gray-600 hover:bg-gray-50">Recommendations</a>
                    <a href="/health-profile" class="block px-4 py-2 text-gray-600 hover:bg-gray-50">Health Profile</a>
                </div>
            </div>
        @endauth
    </div>
</header>

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
    .header-wrapper + * {
        margin-top: 1rem;
    }

    /* Active navigation link */
    .nav-active {
        color: #047857; /* text-green-600 */
        font-weight: 600;
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
