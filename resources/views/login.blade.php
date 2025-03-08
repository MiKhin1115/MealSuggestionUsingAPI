<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Meal Suggestion</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   
    </head>
    <body class="antialiased">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-bold text-green-600">Meal_Suggestion</a>
                    </div>
                    <div class="flex items-center space-x-4">
                    <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                    <a href="/register" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Sign Up</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/dashboard/daily_meal.jpeg') }}');">
            <div class="bg-white p-8 rounded-lg shadow-xl w-96 space-y-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Welcome Back</h2>
                
                <form class="space-y-4" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
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
                    

                    <button type="submit" 
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Sign In
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        Don't have an account? 
                        <a href="/register" class="font-medium text-green-600 hover:text-green-500">Sign up</a>
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