<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Page header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Overview of all support activity</p>
            </div>
            <a href="{{ route('tickets.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                View All Tickets
            </a>
        </div>

        {{-- Ticket status stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach([
                ['Total',       $totalTickets,      'bg-white',         'text-gray-900'],
                ['Open',        $openTickets,       'bg-blue-50',       'text-blue-700'],
                ['In Progress', $inProgressTickets, 'bg-yellow-50',     'text-yellow-700'],
                ['Pending',     $pendingTickets,    'bg-orange-50',     'text-orange-700'],
                ['Resolved',    $resolvedTickets,   'bg-green-50',      'text-green-700'],
            ] as [$label, $count, $bg, $color])
            <div class="rounded-xl border border-gray-200 {{ $bg }} p-4">
                <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
                <p class="text-2xl font-bold {{ $color }} mt-1">{{ $count }}</p>
            </div>
            @endforeach
        </div>

        {{-- Secondary stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500">Total Employees</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalEmployees }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500">Support Agents</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAgents }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-medium text-gray-500">Categories</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCategories }}</p>
            </div>
        </div>

        {{-- Priority breakdown --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Priority Breakdown</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach([
                    ['Critical', $priorityBreakdown['critical'], 'text-red-700',    'bg-red-100'],
                    ['High',     $priorityBreakdown['high'],     'text-orange-700', 'bg-orange-100'],
                    ['Medium',   $priorityBreakdown['medium'],   'text-yellow-700', 'bg-yellow-100'],
                    ['Low',      $priorityBreakdown['low'],      'text-green-700',  'bg-green-100'],
                ] as [$label, $count, $color, $bg])
                <div class="rounded-lg {{ $bg }} px-4 py-3 text-center">
                    <p class="text-xl font-bold {{ $color }}">{{ $count }}</p>
                    <p class="text-xs font-medium {{ $color }} mt-0.5">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent tickets --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Recent Tickets</h2>
                <a href="{{ route('tickets.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View all →</a>
            </div>
            @if($recentTickets->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">No tickets yet.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">#</th>
                            <th class="px-5 py-3 text-left font-medium">Title</th>
                            <th class="px-5 py-3 text-left font-medium">Submitted by</th>
                            <th class="px-5 py-3 text-left font-medium">Category</th>
                            <th class="px-5 py-3 text-left font-medium">Priority</th>
                            <th class="px-5 py-3 text-left font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentTickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('tickets.show', $ticket) }}" class="font-mono text-indigo-600 hover:underline">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td class="px-5 py-3 text-gray-800 max-w-xs truncate">{{ $ticket->title }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->user->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $ticket->category->name }}</td>
                            <td class="px-5 py-3"><x-priority-badge :priority="$ticket->priority"/></td>
                            <td class="px-5 py-3"><x-status-badge :status="$ticket->status"/></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
