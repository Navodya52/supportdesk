<x-app-layout>
    <x-slot name="title">My Dashboard</x-slot>

    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Track your submitted support requests</p>
            </div>
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Ticket
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500">Total Submitted</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $myTotalTickets }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl border border-blue-100 p-4">
                <p class="text-xs font-medium text-blue-600">Open</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $myOpenTickets }}</p>
            </div>
            <div class="bg-yellow-50 rounded-xl border border-yellow-100 p-4">
                <p class="text-xs font-medium text-yellow-600">In Progress</p>
                <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $myInProgressTickets }}</p>
            </div>
            <div class="bg-orange-50 rounded-xl border border-orange-100 p-4">
                <p class="text-xs font-medium text-orange-600">Pending</p>
                <p class="text-2xl font-bold text-orange-700 mt-1">{{ $myPendingTickets }}</p>
            </div>
            <div class="bg-green-50 rounded-xl border border-green-100 p-4">
                <p class="text-xs font-medium text-green-600">Resolved</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ $myResolvedTickets }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500">Closed</p>
                <p class="text-2xl font-bold text-gray-700 mt-1">{{ $myClosedTickets }}</p>
            </div>
        </div>

        {{-- Recent tickets --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">My Recent Tickets</h2>
                <a href="{{ route('tickets.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View all →</a>
            </div>
            @if($myRecentTickets->isEmpty())
                <div class="text-center py-12">
                    <p class="text-sm text-gray-400 mb-3">You haven't submitted any tickets yet.</p>
                    <a href="{{ route('tickets.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Submit your first ticket
                    </a>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">#</th>
                            <th class="px-5 py-3 text-left font-medium">Title</th>
                            <th class="px-5 py-3 text-left font-medium">Category</th>
                            <th class="px-5 py-3 text-left font-medium">Priority</th>
                            <th class="px-5 py-3 text-left font-medium">Status</th>
                            <th class="px-5 py-3 text-left font-medium">Assigned To</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($myRecentTickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('tickets.show', $ticket) }}" class="font-mono text-indigo-600 hover:underline">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td class="px-5 py-3 text-gray-800 max-w-xs truncate">{{ $ticket->title }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->category->name }}</td>
                            <td class="px-5 py-3"><x-priority-badge :priority="$ticket->priority"/></td>
                            <td class="px-5 py-3"><x-status-badge :status="$ticket->status"/></td>
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->assignedAgent?->name ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
