<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Event') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('View Event') }}
                </a>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to Events') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="title" :value="__('Event Title')" />
                                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $event->title)" required autofocus />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="short_description" :value="__('Short Description')" />
                                    <x-text-input id="short_description" name="short_description" type="text" class="mt-1 block w-full" :value="old('short_description', $event->short_description)" />
                                    <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('A brief summary shown in event listings (max 500 characters)') }}
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Full Description')" />
                                    <textarea id="description" name="description" rows="6" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $event->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="category_id" :value="__('Category')" />
                                    <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('Select a category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="tags" :value="__('Tags')" />
                                    <select id="tags" name="tags[]" multiple class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $selectedTags)) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Hold Ctrl (or Cmd) to select multiple tags') }}
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="event_image" :value="__('Event Image')" />

                                    @if($event->image_path)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="h-32 w-auto object-cover rounded-md">
                                            <p class="mt-1 text-sm text-gray-500">{{ __('Current image') }}</p>
                                        </div>
                                    @endif

                                    <input type="file" id="event_image" name="event_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*" />
                                    <x-input-error :messages="$errors->get('event_image')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Leave empty to keep the current image. Maximum file size: 2MB.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Date, Location & Pricing -->
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="start_date" :value="__('Start Date & Time')" />
                                        <x-text-input id="start_date" name="start_date" type="datetime-local" class="mt-1 block w-full" :value="old('start_date', $event->start_date->format('Y-m-d\TH:i'))" required />
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="end_date" :value="__('End Date & Time (Optional)')" />
                                        <x-text-input id="end_date" name="end_date" type="datetime-local" class="mt-1 block w-full" :value="old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '')" />
                                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="location" :value="__('Location')" />
                                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $event->location)" required />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('City, venue name, or online platform') }}
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Full Address (Optional)')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $event->address)" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="latitude" :value="__('Latitude (Optional)')" />
                                        <x-text-input id="latitude" name="latitude" type="text" class="mt-1 block w-full" :value="old('latitude', $event->latitude)" placeholder="e.g. 51.5074" />
                                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="longitude" :value="__('Longitude (Optional)')" />
                                        <x-text-input id="longitude" name="longitude" type="text" class="mt-1 block w-full" :value="old('longitude', $event->longitude)" placeholder="e.g. -0.1278" />
                                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label for="price" :value="__('Price')" />
                                        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $event->price)" />
                                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ __('Set to 0 for free events') }}
                                        </p>
                                    </div>

                                    <div>
                                        <x-input-label for="currency" :value="__('Currency')" />
                                        <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="USD" {{ old('currency', $event->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency', $event->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="GBP" {{ old('currency', $event->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="CAD" {{ old('currency', $event->currency) == 'CAD' ? 'selected' : '' }}>CAD</option>
                                            <option value="AUD" {{ old('currency', $event->currency) == 'AUD' ? 'selected' : '' }}>AUD</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="max_participants" :value="__('Max Participants')" />
                                        <x-text-input id="max_participants" name="max_participants" type="number" min="1" class="mt-1 block w-full" :value="old('max_participants', $event->max_participants)" />
                                        <x-input-error :messages="$errors->get('max_participants')" class="mt-2" />
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ __('Leave blank for unlimited') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex space-x-4 mt-4">
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_private" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_private', $event->is_private) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ __('Private Event') }}</span>
                                        </label>
                                        <x-input-error :messages="$errors->get('is_private')" class="mt-2" />
                                    </div>

                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ __('Featured Event') }}</span>
                                        </label>
                                        <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Access Code for Private Events -->
                        <div id="private-event-fields" class="border-t pt-6 mt-6 {{ old('is_private', $event->is_private) ? '' : 'hidden' }}">
                            <div class="max-w-md">
                                <x-input-label for="access_code" :value="__('Access Code for Private Event')" />
                                <x-text-input id="access_code" name="access_code" type="text" class="mt-1 block w-full" :value="old('access_code', $event->access_code)" />
                                <x-input-error :messages="$errors->get('access_code')" class="mt-2" />
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('Required for private events. Share this code with invited participants.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Custom Fields Section -->
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Additional Information (Optional)') }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="custom_fields" :value="__('Custom Fields (JSON format)')" />
                                    <textarea id="custom_fields" name="custom_fields" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('custom_fields', $event->custom_fields) }}</textarea>
                                    <x-input-error :messages="$errors->get('custom_fields')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Add custom information in JSON format, e.g. {"dress_code":"formal","refreshments":"provided"}') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between gap-4 border-t pt-6">
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold">{{ __('Current Status') }}:</span>
                                <span class="capitalize ml-1">{{ $event->status }}</span>
                            </div>

                            <div class="flex items-center space-x-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Update Event') }}
                                </button>

                                @if($event->status === 'draft')
                                    <button type="submit" name="publish" value="1" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Update & Publish') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isPrivateCheckbox = document.querySelector('input[name="is_private"]');
            const privateEventFields = document.getElementById('private-event-fields');

            isPrivateCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    privateEventFields.classList.remove('hidden');
                } else {
                    privateEventFields.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
