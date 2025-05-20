<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('My Events Dashboard') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150">
                    {{ __('Create New Event') }}
                </a>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150">
                    {{ __('View All Events') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-900/50 border-l-4 border-green-500 text-green-200 p-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-200 p-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="mb-10">
                <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-200 mb-4">{{ __('Event Statistics') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-900/50 p-6 rounded-lg shadow-sm border border-blue-800">
                                <div class="text-blue-300 text-xl font-bold">{{ $events->count() }}</div>
                                <div class="text-gray-300">{{ __('Total Events') }}</div>
                            </div>

                            <div class="bg-green-900/50 p-6 rounded-lg shadow-sm border border-green-800">
                                <div class="text-green-300 text-xl font-bold">{{ $upcomingEvents->count() }}</div>
                                <div class="text-gray-300">{{ __('Upcoming Events') }}</div>
                            </div>

                            <div class="bg-yellow-900/50 p-6 rounded-lg shadow-sm border border-yellow-800">
                                <div class="text-yellow-300 text-xl font-bold">{{ $events->where('status', 'draft')->count() }}</div>
                                <div class="text-gray-300">{{ __('Draft Events') }}</div>
                            </div>

                            <div class="bg-purple-900/50 p-6 rounded-lg shadow-sm border border-purple-800">
                                <div class="text-purple-300 text-xl font-bold">{{ $events->sum('registrations_count') }}</div>
                                <div class="text-gray-300">{{ __('Total Registrations') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Table -->
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-200 mb-4">{{ __('Manage Your Events') }}</h3>

                    @if($events->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-200">{{ __('No events found') }}</h3>
                            <p class="mt-1 text-sm text-gray-400">{{ __('Get started by creating a new event.') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('Create Event') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            {{ __('Event') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            {{ __('Registrations') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-900 divide-y divide-gray-700">
                                    @foreach($events as $event)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if($event->image_path)
                                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center">
                                                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-200">
                                                            {{ $event->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-400">
                                                            {{ $event->location }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-200">{{ $event->start_date->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-400">{{ $event->start_date->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($event->status === 'published')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-200">
                                                        {{ __('Published') }}
                                                    </span>
                                                @elseif($event->status === 'draft')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-gray-200">
                                                        {{ __('Draft') }}
                                                    </span>
                                                @elseif($event->status === 'cancelled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-200">
                                                        {{ __('Cancelled') }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/50 text-blue-200">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                @endif

                                                @if($event->is_featured)
                                                    <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900/50 text-yellow-200">
                                                        {{ __('Featured') }}
                                                    </span>
                                                @endif

                                                @if($event->is_private)
                                                    <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-900/50 text-purple-200">
                                                        {{ __('Private') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                                <div class="flex items-center">
                                                    <span class="mr-2">{{ $event->registrations_count }}</span>
                                                    @if($event->max_participants)
                                                        <span>/ {{ $event->max_participants }}</span>
                                                    @endif
                                                </div>

                                                @if($event->max_participants)
                                                    <div class="w-full bg-gray-700 rounded-full h-1.5 mt-1">
                                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(($event->registrations_count / $event->max_participants) * 100, 100) }}%"></div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-3">
                                                    <a href="{{ route('events.show', $event) }}" class="text-blue-400 hover:text-blue-300" title="{{ __('View') }}">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>

                                                    <a href="{{ route('events.edit', $event) }}" class="text-indigo-400 hover:text-indigo-300" title="{{ __('Edit') }}">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this event?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-400 hover:text-red-300" title="{{ __('Delete') }}">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
