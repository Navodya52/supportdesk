<x-app-layout>
    <x-slot name="title">Edit {{ $ticket->ticket_number }}</x-slot>

    <div class="max-w-2xl space-y-5">

        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('tickets.show', $ticket) }}" class="text-sm text-gray-500 hover:text-gray-700">← {{ $ticket->ticket_number }}</a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Ticket</h1>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-5">
                @csrf @method('PATCH')

                {{-- Employee: only status change --}}
                @if(auth()->user()->isEmployee())

                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                        <p class="font-medium text-gray-800 mb-1">{{ $ticket->title }}</p>
                        <p class="text-gray-500">You can update the status of your ticket.</p>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="open"   {{ $ticket->status === 'open'   ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                @else
                {{-- Admin / Agent: full edit --}}

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $ticket->title) }}"
                               class="w-full px-3 py-2 border {{ $errors->has('title') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="category_id" name="category_id"
                                    class="w-full px-3 py-2 border {{ $errors->has('category_id') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select id="priority" name="priority"
                                    class="w-full px-3 py-2 border {{ $errors->has('priority') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="low"      {{ old('priority', $ticket->priority) === 'low'      ? 'selected' : '' }}>Low</option>
                                <option value="medium"   {{ old('priority', $ticket->priority) === 'medium'   ? 'selected' : '' }}>Medium</option>
                                <option value="high"     {{ old('priority', $ticket->priority) === 'high'     ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('priority', $ticket->priority) === 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('priority') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border {{ $errors->has('status') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="open"        {{ old('status', $ticket->status) === 'open'        ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ old('status', $ticket->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="pending"     {{ old('status', $ticket->status) === 'pending'     ? 'selected' : '' }}>Pending</option>
                            <option value="resolved"    {{ old('status', $ticket->status) === 'resolved'    ? 'selected' : '' }}>Resolved</option>
                            <option value="closed"      {{ old('status', $ticket->status) === 'closed'      ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="5"
                                  class="w-full px-3 py-2 border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $ticket->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="resolution" class="block text-sm font-medium text-gray-700 mb-1">Resolution <span class="text-gray-400 text-xs">(optional)</span></label>
                        <textarea id="resolution" name="resolution" rows="4"
                                  class="w-full px-3 py-2 border {{ $errors->has('resolution') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Describe the resolution steps taken...">{{ old('resolution', $ticket->resolution) }}</textarea>
                        @error('resolution') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                @endif

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('tickets.show', $ticket) }}"
                       class="px-5 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
