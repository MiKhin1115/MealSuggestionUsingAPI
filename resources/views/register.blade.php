<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Register - Food App</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body class="font-sans antialiased">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-green-900">Meal_Suggestion</a>
                    </div>
                    <div class="flex items-center space-x-4">
                    <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                    <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign In</a>
                        
                    </div>
                </div>
            </div>
        </nav>
        <div class="min-h-screen flex items-center justify-center relative">
            <!-- Background Image -->
            <img src="{{ asset('images/dashboard/recipe_suggestion.jpeg') }}"
                 class="absolute inset-0 w-full h-full object-cover"/>
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50"></div>

            <!-- Registration Form -->
            <div class="relative w-full max-w-md p-8 bg-white rounded-lg shadow-xl mx-4">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Create Account</h2>

                <form method="POST" action="{{ route('register-questions') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-gray-500"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1  rounded-md shadow-sm relative">
                            <input type="password" id="password" name="password" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-600"/>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye-slash toggle-password text-gray-400 hover:text-gray-600 cursor-pointer" data-target="password"></i>
                                
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye-slash toggle-password text-gray-400 hover:text-gray-600 cursor-pointer" data-target="password_confirmation"></i>
                            </div>
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Sign up
                    </button>

                    <p class="mt-6 text-center text-sm text-gray-600">
                        Already have an account? 
                        <a href="/login" class="font-medium text-green-600 hover:text-green-500">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
        <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });
        </script>
    </body>
</html>
