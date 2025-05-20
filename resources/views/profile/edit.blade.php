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
                <div class="flex flex-col items-center space-y-4">
                    <!-- Profile Picture -->
                    <div class="relative">
                        <img 
                            id="profile-picture-preview"
                            src="{{ auth()->user()->profile_picture ?? 'https://via.placeholder.com/150' }}" 
                            alt="Profile Picture" 
                            class="w-24 h-24 rounded-full border-2 border-gray-300 dark:border-gray-600 shadow-md"
                        >
                        <button type="button" id="change-picture-btn" class="mt-2 bg-indigo-500 text-white text-xs px-4 py-2 rounded-full shadow hover:bg-indigo-600">
                            Change Picture
                        </button>
                    </div>

                    <!-- User Name & Social Links -->
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->email }}
                        </p>
                        <!-- Social Links -->
                        <div class="flex justify-center space-x-4 mt-2">
                            @if(auth()->user()->x)
                                <a href="{{ auth()->user()->x }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="X">
                                    <!-- X icon SVG -->
                                    <svg class="w-6 h-6 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M17.53 6.47a.75.75 0 0 0-1.06 0L12 10.94 7.53 6.47a.75.75 0 1 0-1.06 1.06L10.94 12l-4.47 4.47a.75.75 0 1 0 1.06 1.06L12 13.06l4.47 4.47a.75.75 0 0 0 1.06-1.06L13.06 12l4.47-4.47a.75.75 0 0 0 0-1.06z"/></svg>
                                </a>
                            @endif
                            @if(auth()->user()->instagram)
                                <a href="{{ auth()->user()->instagram }}" target="_blank" class="text-pink-500 hover:text-pink-700" title="Instagram">
                                    <!-- Instagram icon SVG -->
                                    <svg class="w-6 h-6 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M7.75 2A5.75 5.75 0 0 0 2 7.75v8.5A5.75 5.75 0 0 0 7.75 22h8.5A5.75 5.75 0 0 0 22 16.25v-8.5A5.75 5.75 0 0 0 16.25 2h-8.5zm0 1.5h8.5A4.25 4.25 0 0 1 20.5 7.75v8.5A4.25 4.25 0 0 1 16.25 20.5h-8.5A4.25 4.25 0 0 1 3.5 16.25v-8.5A4.25 4.25 0 0 1 7.75 3.5zm4.25 2.75A5.75 5.75 0 1 0 17.75 12 5.75 5.75 0 0 0 12 6.25zm0 1.5A4.25 4.25 0 1 1 7.75 12 4.25 4.25 0 0 1 12 7.75zm5.25-.75a1 1 0 1 0 1 1 1 1 0 0 0-1-1z"/></svg>
                                </a>
                            @endif
                            @if(auth()->user()->linkedin)
                                <a href="{{ auth()->user()->linkedin }}" target="_blank" class="text-blue-700 hover:text-blue-900" title="LinkedIn">
                                    <!-- LinkedIn icon SVG -->
                                    <svg class="w-6 h-6 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5v-14c0-2.76-2.24-5-5-5zm-11 19h-3v-9h3v9zm-1.5-10.28c-.97 0-1.75-.79-1.75-1.75s.78-1.75 1.75-1.75 1.75.79 1.75 1.75-.78 1.75-1.75 1.75zm13.5 10.28h-3v-4.5c0-1.08-.02-2.47-1.5-2.47-1.5 0-1.73 1.17-1.73 2.39v4.58h-3v-9h2.88v1.23h.04c.4-.76 1.38-1.56 2.84-1.56 3.04 0 3.6 2 3.6 4.59v5.74z"/></svg>
                                </a>
                            @endif
                        </div>
                        <!-- Button to open account settings modal -->
                        <button type="button" id="open-settings-btn" class="mt-4 bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-900">
                            Change Account Settings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cropper Modal -->
            <div id="cropper-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Crop Your Profile Picture</h3>
                    <div class="w-72 h-72 flex items-center justify-center">
                        <img id="cropper-image" src="" alt="Cropper Image" class="max-w-full max-h-full">
                    </div>
                    <div class="mt-4 flex justify-end space-x-4">
                        <button type="button" id="cancel-crop-btn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="button" id="save-crop-btn" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">
                            Save
                        </button>
                    </div>
                </div>
            </div>

            <!-- Account Settings Modal -->
            <div id="settings-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50">
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-2xl w-full max-w-3xl border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Account Settings</h3>
                        <button type="button" id="close-settings-btn" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-3xl leading-none">&times;</button>
                    </div>
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left: Profile Info & Password -->
                        <div class="flex-1 space-y-8">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Profile Information</h4>
                                @include('profile.partials.update-profile-information-form')
                            </div>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Update Password</h4>
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                        <!-- Right: Social Links -->
                        <div class="flex-1 space-y-8">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Social Links</h4>
                                @include('profile.partials.socials')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper.js CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="{{ asset('js/profile-picture-cropper.js') }}"></script>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            profileUpdateRoute: '{{ route('user-profile.update') }}'
        };

        // Modal logic for account settings
        document.addEventListener('DOMContentLoaded', function () {
            const openBtn = document.getElementById('open-settings-btn');
            const closeBtn = document.getElementById('close-settings-btn');
            const modal = document.getElementById('settings-modal');
            if(openBtn && closeBtn && modal) {
                openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
                closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            }
        });
    </script>
</x-app-layout>
