<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Picture and Name Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex items-center space-x-6">
                    <!-- Profile Picture -->
                    <div class="relative">
                        <img 
                            src="{{ auth()->user()->profile_picture ?? 'https://via.placeholder.com/150' }}" 
                            alt="Profile Picture" 
                            class="w-24 h-24 rounded-full border-2 border-gray-300 dark:border-gray-600 shadow-md"
                        >
                        <form method="POST" action="{{ route('user-profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label for="profile_picture" class="cursor-pointer bg-indigo-500 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-indigo-600">
                                Change
                            </label>
                            <input 
                                type="file" 
                                name="profile_picture" 
                                id="profile_picture" 
                                class="hidden" 
                                accept="image/*" 
                                onchange="this.form.submit()"
                            >
                            <button type="submit" class="hidden">Submit</button> <!-- Add this for testing -->
                        </form>
                    </div>

                    <!-- User Name -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Links Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.socials')
                </div>
            </div>

            <!-- Update Profile Information Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
