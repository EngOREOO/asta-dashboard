@props(['href', 'class' => ''])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'block group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 hover:shadow-md hover:ring-blue-300 transition-all duration-200 ' . $class]) }}>
    {{ $slot }}
</a>
