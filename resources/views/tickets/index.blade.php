<x-app-layout>
    <x-slot name="title">Tickets</x-slot>

    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Tickets</h1>
            @can('create', App\Models\Ticket::class)
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Ticket
            </a>
            @endcan
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('tickets.index') }}" class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search tickets..."
                       class="col-span-2 md:col-span-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="open"        {{ request('status') === 'open'        ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="resolved"    {{ request('status') === 'resolved'    ? 'selected' : '' }}>Resolved</option>
                    <option value="closed"      {{ request('status') === 'closed'      ? 'selected' : '' }}>Closed</option>
                </select>

                <select name="priority" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Priorities</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high"     {{ request('priority') === 'high'     ? 'selected' : '' }}>High</option>
                    <option value="medium"   {{ request('priority') === 'medium'   ? 'selected' : '' }}>Medium</option>
                    <option value="low"      {{ request('priority') === 'low'      ? 'selected' : '' }}>Low</option>
                </select>

                <select name="category_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 mt-3">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search','status','priority','category_id','assigned']))
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Clear</a>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                    <div class="ml-auto flex gap-2">
                        <a href="{{ route('tickets.index', array_merge(request()->query(), ['assigned' => 'me'])) }}"
                           class="px-3 py-2 text-xs {{ request('assigned') === 'me' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-800' }} rounded-lg font-medium transition-colors">
                            My tickets
                        </a>
                        <a href="{{ route('tickets.index', array_merge(request()->query(), ['assigned' => 'unassigned'])) }}"
                           class="px-3 py-2 text-xs {{ request('assigned') === 'unassigned' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-800' }} rounded-lg font-medium transition-colors">
                            Unassigned
                        </a>
                    </div>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if($tickets->isEmpty())
                <p class="text-sm text-gray-400 text-center py-12">No tickets found.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">#</th>
                            <th class="px-5 py-3 text-left font-medium">Title</th>
                            @if(!auth()->user()->isEmployee())
                            <th class="px-5 py-3 text-left font-medium">Submitted by</th>
                            @endif
                            <th class="px-5 py-3 text-left font-medium">Category</th>
                            <th class="px-5 py-3 text-left font-medium">Priority</th>
                            <th class="px-5 py-3 text-left font-medium">Status</th>
                            <th class="px-5 py-3 text-left font-medium">Assigned</th>
                            <th class="px-5 py-3 text-left font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('tickets.show', $ticket) }}" class="font-mono text-indigo-600 hover:underline">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td class="px-5 py-3 text-gray-800">
                                <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-indigo-600 truncate block max-w-xs">{{ $ticket->title }}</a>
                            </td>
                            @if(!auth()->user()->isEmployee())
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->user->name }}</td>
                            @endif
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->category->name }}</td>
                            <td class="px-5 py-3"><x-priority-badge :priority="$ticket->priority"/></td>
                            <td class="px-5 py-3"><x-status-badge :status="$ticket->status"/></td>
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->assignedAgent?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-400 whitespace-nowrap">{{ $ticket->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $tickets->links() }}
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
