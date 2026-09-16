@props(['role'])

@php
    $classes = match($role) {
        'admin'    => 'bg-purple-100 text-purple-800',
        'agent'    => 'bg-indigo-100 text-indigo-800',
        'employee' => 'bg-sky-100 text-sky-800',
        default      => 'bg-gray-100 text-gray-600',
    };
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $classes }}">{{ ucfirst($role) }}</span>
