<x-app-layout>
    <x-slot name="title">Edit {{ $category->name }}</x-slot>

    <div class="max-w-lg space-y-5">

        <div>
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Categories</a>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Edit Category</h1>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                           class="w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           required>
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-gray-400 text-xs">(optional)</span></label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="px-5 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
