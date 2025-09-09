@props(['title', 'subtitle' => null])

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $title }}</h2>
            @if($subtitle)
                <p class="mt-1 text-sm text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
        
        @if(isset($actions))
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
