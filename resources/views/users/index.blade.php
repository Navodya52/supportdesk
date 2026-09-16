<x-app-layout>
    <x-slot name="title">Users</x-slot>

    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
        </div>

        {{-- Search + filter --}}
        <form method="GET" action="{{ route('users.index') }}" class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <select name="role" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Roles</option>
                    <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    <option value="agent"    {{ request('role') === 'agent'    ? 'selected' : '' }}>Agent</option>
                    <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Search
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Clear</a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if($users->isEmpty())
                <p class="text-sm text-gray-400 text-center py-12">No users found.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium">Name</th>
                            <th class="px-5 py-3 text-left font-medium">Email</th>
                            <th class="px-5 py-3 text-left font-medium">Role</th>
                            <th class="px-5 py-3 text-left font-medium">Status</th>
                            <th class="px-5 py-3 text-left font-medium">Joined</th>
                            <th class="px-5 py-3 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 {{ !$user->is_active ? 'opacity-60' : '' }}">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-3"><x-role-badge :role="$user->role"/></td>
                            <td class="px-5 py-3">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-400 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @can('update', $user)
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Edit</a>
                                    @endcan
                                    @can('delete', $user)
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          onsubmit="return confirm('Delete user {{ $user->name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
