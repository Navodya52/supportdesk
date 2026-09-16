<x-app-layout>
    <x-slot name="title">Categories</x-slot>

    <div class="max-w-3xl space-y-5">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Category
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if($categories->isEmpty())
                <p class="text-sm text-gray-400 text-center py-12">No categories yet. Create one to get started.</p>
            @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Name</th>
                        <th class="px-5 py-3 text-left font-medium">Description</th>
                        <th class="px-5 py-3 text-left font-medium">Tickets</th>
                        <th class="px-5 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                        <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $category->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $category->tickets_count }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Edit</a>
                                @if($category->tickets_count === 0)
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                </form>
                                @else
                                    <span class="text-xs text-gray-300" title="Cannot delete: has tickets">Delete</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
</x-app-layout>
