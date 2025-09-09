@props([
    'title' => '',
    'subtitle' => '',
    'value' => '',
    'icon' => '',
    'color' => 'blue',
    'href' => null,
    'clickable' => false,
    'class' => ''
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'red' => 'bg-red-50 text-red-600',
        'yellow' => 'bg-yellow-50 text-yellow-600',
        'teal' => 'bg-teal-50 text-teal-600',
        'indigo' => 'bg-indigo-50 text-indigo-600'
    ];
    
    $iconColor = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

@if($href && $clickable)
    <a href="{{ $href }}" class="block">
@endif

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-all duration-200 font-arabic ' . $class]) }}>
    @if($icon)
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 {{ $iconColor }} rounded-2xl flex items-center justify-center">
                <i class="{{ $icon }} text-xl"></i>
            </div>
        </div>
    @endif
    
    @if($title)
        <h3 class="font-semibold text-gray-900 mb-1" style="font-size: 1.3rem;">{{ $title }}</h3>
    @endif
    
    @if($subtitle)
        <p class="text-gray-600 mb-3" style="font-size: 1.3rem;">{{ $subtitle }}</p>
    @endif
    
    @if($value !== '')
        <div class="text-3xl font-bold text-gray-900">{{ $value }}</div>
    @endif
    
    {{ $slot }}
</div>

@if($href && $clickable)
    </a>
@endif
