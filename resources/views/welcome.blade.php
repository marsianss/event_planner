<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="text-center py-16">
                <h1 class="text-5xl font-bold mb-6 dark:text-white">Plan and manage your events with ease</h1>
                <p class="text-xl mb-10 max-w-2xl mx-auto dark:text-gray-300">Discover events, create your own, and connect with attendees - all in one place.</p>

                <div class="flex flex-wrap justify-center gap-4">
                    @guest
                        <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-md transition">
                            Get Started
                        </a>
                        <a href="{{ route('events.search') }}" class="bg-transparent border border-gray-300 dark:border-gray-600 hover:border-gray-500 dark:hover:border-gray-400 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-md transition">
                            Browse Events
                        </a>
                    @else
                        <a href="{{ route('events.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-md transition">
                            Create New Event
                        </a>
                        <a href="{{ route('events.dashboard') }}" class="bg-transparent border border-gray-300 dark:border-gray-600 hover:border-gray-500 dark:hover:border-gray-400 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-md transition">
                            Manage Your Events
                        </a>
                    @endguest
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
