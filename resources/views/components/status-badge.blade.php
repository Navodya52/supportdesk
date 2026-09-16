@props(['status'])

@php
    $classes = match($status) {
        'open'        => 'bg-blue-100 text-blue-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'pending'     => 'bg-orange-100 text-orange-800',
        'resolved'    => 'bg-green-100 text-green-800',
        'closed'      => 'bg-gray-100 text-gray-700',
        default         => 'bg-gray-100 text-gray-600',
    };
    $label = match($status) {
        'open'        => 'Open',
        'in_progress' => 'In Progress',
        'pending'     => 'Pending',
        'resolved'    => 'Resolved',
        'closed'      => 'Closed',
        default         => ucfirst($status),
    };
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $classes }}">{{ $label }}</span>
