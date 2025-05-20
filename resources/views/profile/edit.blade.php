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
        </div>
    </div>

    <!-- Cropper.js CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <!-- Your JS file -->
    <script src="{{ asset('js/profile-picture-cropper.js') }}"></script>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            profileUpdateRoute: '{{ route('user-profile.update') }}'
        };
    </script>
</x-app-layout>
