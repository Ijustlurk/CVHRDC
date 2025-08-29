@props(['active'])

@php
$classes = ($active ?? false)
? 'block w-full pl-3 pr-4 py-2 border-l-4 border-red-600 text-left text-base font-extrabold text-red-600 bg-red-50
focus:outline-none transition duration-150 ease-in-out'
: 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600
hover:text-blue-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-blue-800 focus:bg-gray-50
focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
