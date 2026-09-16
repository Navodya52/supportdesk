@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-sm text-sm transition-colors disabled:bg-gray-50 disabled:text-gray-500']) }}>

