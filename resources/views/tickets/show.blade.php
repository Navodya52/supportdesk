<x-app-layout>
    <x-slot name="title">{{ $ticket->ticket_number }}</x-slot>

    <div class="max-w-4xl space-y-5">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('tickets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Tickets</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-sm font-mono text-gray-700">{{ $ticket->ticket_number }}</span>
                </div>
                <h1 class="text-xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    <x-status-badge :status="$ticket->status"/>
                    <x-priority-badge :priority="$ticket->priority"/>
                    <span class="text-xs text-gray-400">{{ $ticket->category->name }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @can('update', $ticket)
                <a href="{{ route('tickets.edit', $ticket) }}"
                   class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Edit
                </a>
                @endcan
                @can('delete', $ticket)
                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
                      onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-2 text-sm bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                        Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- Main content --}}
            <div class="md:col-span-2 space-y-5">

                {{-- Description --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Description</h2>
                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->description }}</div>
                </div>

                {{-- Resolution (if present) --}}
                @if($ticket->resolution)
                <div class="bg-green-50 rounded-xl border border-green-200 p-5">
                    <h2 class="text-sm font-semibold text-green-800 mb-3">Resolution</h2>
                    <div class="text-sm text-green-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->resolution }}</div>
                </div>
                @endif

                {{-- Comments --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-700">Comments ({{ $ticket->comments->count() }})</h2>
                    </div>

                    @if($ticket->comments->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($ticket->comments as $comment)
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $comment->user->name }}</span>
                                <x-role-badge :role="$comment->user->role"/>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap pl-9">{{ $comment->comment }}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Add comment form --}}
                    @can('comment', $ticket)
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
                        <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}" class="space-y-3">
                            @csrf
                            <textarea name="comment" rows="3"
                                      class="w-full px-3 py-2 border {{ $errors->has('comment') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                                      placeholder="Add a comment...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                Post Comment
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>

            </div>

            {{-- Sidebar details --}}
            <div class="space-y-4">

                {{-- Ticket details --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-700">Details</h2>

                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Status</dt>
                            <dd class="mt-0.5"><x-status-badge :status="$ticket->status"/></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Priority</dt>
                            <dd class="mt-0.5"><x-priority-badge :priority="$ticket->priority"/></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Category</dt>
                            <dd class="mt-0.5 text-gray-700">{{ $ticket->category->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Submitted by</dt>
                            <dd class="mt-0.5 text-gray-700">{{ $ticket->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Assigned to</dt>
                            <dd class="mt-0.5 text-gray-700">{{ $ticket->assignedAgent?->name ?? 'Unassigned' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Created</dt>
                            <dd class="mt-0.5 text-gray-700">{{ $ticket->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        @if($ticket->resolved_at)
                        <div>
                            <dt class="text-xs font-medium text-gray-500">Resolved at</dt>
                            <dd class="mt-0.5 text-gray-700">{{ $ticket->resolved_at->format('M d, Y H:i') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Assign ticket (admin only) --}}
                @can('assign', $ticket)
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Assign Ticket</h2>
                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="space-y-3">
                        @csrf
                        <select name="assigned_to"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Unassigned</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                                class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Update Assignment
                        </button>
                    </form>
                </div>
                @endcan

                {{-- Quick status update for employee --}}
                @if(auth()->user()->isEmployee() && in_array($ticket->status, ['open', 'in_progress', 'pending', 'resolved']))
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Close Ticket</h2>
                    <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="closed">
                        <button type="submit"
                                onclick="return confirm('Mark this ticket as closed?')"
                                class="w-full px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                            Mark as Closed
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
