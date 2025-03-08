@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
        <p class="mt-1 text-sm text-gray-600">Update your profile information and account settings.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            <!-- Profile Image Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="relative">
                    <div class="h-32 w-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center border-4 border-white shadow">
                        @if(auth()->user()->picture)
                            <img src="{{ asset(auth()->user()->picture) }}" alt="Profile picture" class="h-full w-full object-cover" id="profile-preview">
                        @else
                            <span class="text-4xl text-green-600 font-semibold" id="profile-initial">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            <img src="" alt="Profile preview" class="h-full w-full object-cover hidden" id="profile-preview">
                        @endif
                    </div>
                    <label for="picture" class="absolute bottom-0 right-0 bg-green-600 text-white p-2 rounded-full shadow cursor-pointer hover:bg-green-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                    <input type="file" id="picture" name="picture" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900">Profile Picture</h3>
                    <p class="text-sm text-gray-600 mt-1">Upload a new profile picture (JPG, PNG or GIF, max 1MB)</p>
                    @error('picture')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field (Read-only) -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" value="{{ auth()->user()->email }}" disabled
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                    <p class="text-sm text-gray-500 mt-1">Your email address cannot be changed</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                <p class="text-sm text-gray-600 mb-4">Leave these fields empty if you don't want to change your password</p>

                <!-- Current Password Field -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password Field -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors duration-200">
                    Go Back
                </a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('profile-preview');
                const initial = document.getElementById('profile-initial');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if (initial) {
                    initial.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection 