<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Events') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('events.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('My Events Dashboard') }}
                </a>
                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Create New Event') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and filter form -->
            <div class="mb-8 bg-gray-800 p-6 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <form action="{{ route('events.search') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="search" :value="__('Search')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Search for events..." />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-700 bg-gray-900 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-1">
                                <x-input-label for="start_date" :value="__('From Date')" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="request('start_date')" />
                            </div>
                            <div class="flex-1">
                                <x-input-label for="end_date" :value="__('To Date')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="request('end_date')" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                            {{ __('Search Events') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Events list -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                @if($events->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-gray-400 text-lg">{{ __('No events found matching your criteria.') }}</p>
                        <p class="mt-4">
                            <a href="{{ route('events.index') }}" class="text-blue-400 hover:underline">{{ __('View all events') }}</a>
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($events as $event)
                            <div class="bg-gray-900 rounded-lg border border-gray-700 shadow-md overflow-hidden flex flex-col">
                                <div class="h-48 overflow-hidden relative">
                                    @if($event->image_path)
                                        <img src="{{ asset('storage/'.$event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    @if($event->is_featured)
                                        <div class="absolute top-2 right-2 bg-yellow-600 text-white text-xs font-bold px-2 py-1 rounded">
                                            {{ __('Featured') }}
                                        </div>
                                    @endif

                                    @if($event->status === 'draft')
                                        <div class="absolute top-2 left-2 bg-gray-600 text-white text-xs font-bold px-2 py-1 rounded">
                                            {{ __('Draft') }}
                                        </div>
                                    @elseif($event->status === 'cancelled')
                                        <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                            {{ __('Cancelled') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4 flex-1 flex flex-col">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xl font-bold text-gray-100 mb-2">
                                                {{ $event->title }}
                                            </h3>
                                            @if($event->price > 0)
                                                <div class="text-green-400 font-bold">
                                                    {{ $event->currency }} {{ number_format($event->price, 2) }}
                                                </div>
                                            @else
                                                <div class="text-green-800 font-medium text-sm px-2 py-1 bg-green-200 rounded">
                                                    {{ __('Free') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-sm text-gray-400 mb-2">
                                            <div class="flex items-center mb-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $event->start_date->format('M d, Y - H:i') }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $event->location }}
                                            </div>
                                        </div>

                                        @if($event->category)
                                            <div class="mb-2">
                                                <span class="inline-block bg-blue-900 text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                                    {{ $event->category->name }}
                                                </span>
                                            </div>
                                        @endif

                                        <p class="text-gray-300 text-sm line-clamp-2 mb-4">
                                            {{ $event->short_description ?? Str::limit($event->description, 100) }}
                                        </p>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('events.show', $event) }}" class="inline-block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-3">
                        {{ $events->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
