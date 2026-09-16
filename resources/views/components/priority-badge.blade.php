@props(['priority'])

@php
    $classes = match($priority) {
        'critical' => 'bg-red-100 text-red-800',
        'high'     => 'bg-orange-100 text-orange-800',
        'medium'   => 'bg-yellow-100 text-yellow-800',
        'low'      => 'bg-green-100 text-green-800',
        default      => 'bg-gray-100 text-gray-600',
    };
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $classes }}">{{ ucfirst($priority) }}</span>
