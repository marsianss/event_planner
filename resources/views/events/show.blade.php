<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Event Details') }}
            </h2>
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Back to Events') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-800 border-l-4 border-green-400 text-green-100 p-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-800 border-l-4 border-red-400 text-red-100 p-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Event Image and Main Info -->
                        <div class="lg:col-span-2">
                            <div class="mb-6 rounded-lg overflow-hidden h-80 relative">
                                @if($event->image_path)
                                    <img src="{{ asset('storage/'.$event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                                        <svg class="w-24 h-24 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                @if($event->is_featured)
                                    <div class="absolute top-4 right-4 bg-yellow-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                                        {{ __('Featured Event') }}
                                    </div>
                                @endif

                                @if($event->status === 'draft')
                                    <div class="absolute top-4 left-4 bg-gray-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                                        {{ __('Draft') }}
                                    </div>
                                @elseif($event->status === 'cancelled')
                                    <div class="absolute top-4 left-4 bg-red-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                                        {{ __('Cancelled') }}
                                    </div>
                                @endif
                            </div>

                            <h1 class="text-3xl font-bold text-gray-100 mb-4">{{ $event->title }}</h1>

                            <div class="flex flex-wrap gap-4 items-center mb-6">
                                <div class="flex items-center text-gray-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $event->start_date->format('F d, Y - H:i') }}</span>
                                </div>

                                @if($event->end_date)
                                    <div class="flex items-center text-gray-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ __('Until') }} {{ $event->end_date->format('F d, Y - H:i') }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center text-gray-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event->location }}</span>
                                </div>

                                @if($event->category)
                                    <div class="flex items-center">
                                        <span class="inline-block bg-blue-900 text-blue-200 text-sm font-medium px-3 py-1 rounded-full">
                                            {{ $event->category->name }}
                                        </span>
                                    </div>
                                @endif

                                @if($event->price > 0)
                                    <div class="flex items-center">
                                        <span class="inline-block bg-green-900 text-green-200 text-sm font-medium px-3 py-1 rounded-full">
                                            {{ $event->currency }} {{ number_format($event->price, 2) }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <span class="inline-block bg-green-200 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                            {{ __('Free') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Event Description -->
                            <div class="prose prose-invert max-w-none text-gray-300">
                                {!! nl2br(e($event->description)) !!}
                            </div>

                            <!-- Tags -->
                            @if($event->tags->isNotEmpty())
                                <div class="mt-6">
                                    <h3 class="text-lg font-medium text-gray-200 mb-2">{{ __('Tags') }}</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($event->tags as $tag)
                                            <span class="inline-block bg-gray-700 text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Event Organizer -->
                            <div class="mt-8 border-t border-gray-700 pt-6">
                                <h3 class="text-lg font-medium text-gray-200 mb-4">{{ __('Event Organizer') }}</h3>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center">
                                            @if($event->user->profile_image)
                                                <img src="{{ asset('storage/'.$event->user->profile_image) }}" alt="{{ $event->user->name }}" class="h-10 w-10 rounded-full">
                                            @else
                                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-200">
                                            {{ $event->user->name }}
                                            @if($event->user->is_verified_organizer)
                                                <span class="inline-block ml-1 text-blue-400" title="{{ __('Verified Organizer') }}">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </p>
                                        @if($event->user->username)
                                            <p class="text-sm text-gray-400">
                                                {{ '@' . $event->user->username }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-900 p-6 rounded-lg shadow-sm border border-gray-700">
                                <!-- Registration Status -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-200 mb-2">{{ __('Event Status') }}</h3>

                                    @if($event->max_participants)
                                        <div class="text-sm text-gray-400 mb-2">
                                            {{ $event->registrations->count() }} / {{ $event->max_participants }} {{ __('participants') }}
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2.5 mb-4">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($event->registrations->count() / $event->max_participants) * 100, 100) }}%"></div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400 mb-4">
                                            {{ $event->registrations->count() }} {{ __('participants registered') }}
                                        </div>
                                    @endif

                                    @if($isFull)
                                        <div class="bg-yellow-900 text-yellow-200 text-sm font-medium px-3 py-2 rounded-lg mb-4">
                                            {{ __('This event is full') }}
                                        </div>
                                    @endif

                                    @if($event->status === 'cancelled')
                                        <div class="bg-red-900 text-red-200 text-sm font-medium px-3 py-2 rounded-lg mb-4">
                                            {{ __('This event has been cancelled') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Registration / Edit / Delete Buttons -->
                                <div class="space-y-3">
                                    <!-- For testing purposes, always show organizer actions -->
                                    <a href="{{ route('events.edit', $event) }}" class="inline-block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                        {{ __('Edit Event') }}
                                    </a>

                                    @if($event->status === 'draft')
                                        <form action="{{ route('events.status.update', $event) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="published">
                                            <button type="submit" class="inline-block w-full text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                                {{ __('Publish Event') }}
                                            </button>
                                        </form>
                                    @elseif($event->status === 'published')
                                        <form action="{{ route('events.status.update', $event) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="inline-block w-full text-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                                {{ __('Cancel Event') }}
                                            </button>
                                        </form>
                                    @elseif($event->status === 'cancelled')
                                        <form action="{{ route('events.status.update', $event) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="published">
                                            <button type="submit" class="inline-block w-full text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                                {{ __('Reactivate Event') }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this event? This action cannot be undone.') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block w-full text-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                            {{ __('Delete Event') }}
                                        </button>
                                    </form>
                                </div>

                                <!-- Event Details -->
                                <div class="mt-8 border-t border-gray-700 pt-6">
                                    <h3 class="text-lg font-medium text-gray-200 mb-4">{{ __('Event Details') }}</h3>

                                    <div class="space-y-4">
                                        @if($event->address)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-300">{{ __('Address') }}</h4>
                                                <p class="text-sm text-gray-400">{{ $event->address }}</p>
                                            </div>
                                        @endif

                                        @if($event->max_participants)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-300">{{ __('Capacity') }}</h4>
                                                <p class="text-sm text-gray-400">{{ $event->max_participants }} {{ __('participants') }}</p>
                                            </div>
                                        @endif

                                        @if($event->is_private)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-300">{{ __('Event Type') }}</h4>
                                                <p class="text-sm text-gray-400">{{ __('Private Event') }}</p>
                                            </div>
                                        @endif

                                        @if($event->custom_fields)
                                            @foreach(json_decode($event->custom_fields) as $field => $value)
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-300">{{ __(ucfirst(str_replace('_', ' ', $field))) }}</h4>
                                                    <p class="text-sm text-gray-400">{{ $value }}</p>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
