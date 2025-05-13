<div class="space-y-6">
    <div>
        <label for="x" class="block text-sm font-medium text-gray-700 dark:text-gray-300">X</label>
        <input type="url" name="x" id="x" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
               value="{{ old('x', auth()->user()->x) }}" 
               placeholder="https://x.com/yourprofile">
    </div>

    <div>
        <label for="instagram" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</label>
        <input type="url" name="instagram" id="instagram" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
               value="{{ old('instagram', auth()->user()->instagram) }}" 
               placeholder="https://instagram.com/yourprofile">
    </div>

    <div>
        <label for="linkedin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">LinkedIn</label>
        <input type="url" name="linkedin" id="linkedin" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
               value="{{ old('linkedin', auth()->user()->linkedin) }}" 
               placeholder="https://linkedin.com/in/yourprofile">
    </div>
</div>

