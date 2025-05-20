{{-- filepath: c:\Users\denni\Herd\event_planner\resources\views\dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-2">{{ __("Total Events") }}</h3>
                        <p class="text-3xl font-bold">{{ \App\Models\Event::count() }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-2">{{ __("Total Registrations") }}</h3>
                        <p class="text-3xl font-bold">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __("Welcome back!") }}</h3>
                    <p class="mb-6">{{ __("You're logged in!") }}</p>
                    <div class="flex gap-4">
                        <a href="{{ route('events.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                            {{ __('View Events') }}
                        </a>
                        <a href="" class="inline-block px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                            {{ __('View Registrations') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>