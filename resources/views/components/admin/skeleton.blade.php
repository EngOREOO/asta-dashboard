@props([
    'type' => 'line',
    'class' => '',
    'lines' => 1,
    'height' => 'h-4'
])

@if($type === 'line')
    <div class="animate-pulse {{ $class }}">
        @for($i = 0; $i < $lines; $i++)
            <div class="{{ $height }} bg-gray-200 rounded {{ $i < $lines - 1 ? 'mb-2' : '' }}"></div>
        @endfor
    </div>
@elseif($type === 'card')
    <div class="animate-pulse {{ $class }}">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center space-x-3 rtl:space-x-reverse mb-4">
                <div class="w-10 h-10 bg-gray-200 rounded-xl"></div>
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2 mt-2"></div>
                </div>
            </div>
            <div class="space-y-2">
                <div class="h-3 bg-gray-200 rounded"></div>
                <div class="h-3 bg-gray-200 rounded w-5/6"></div>
            </div>
        </div>
    </div>
@elseif($type === 'table')
    <div class="animate-pulse {{ $class }}">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="h-6 bg-gray-200 rounded w-1/4"></div>
                    <div class="h-10 bg-gray-200 rounded w-32"></div>
                </div>
                <div class="space-y-3">
                    @for($i = 0; $i < 5; $i++)
                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                            <div class="h-4 bg-gray-200 rounded w-16"></div>
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                            <div class="h-4 bg-gray-200 rounded w-20"></div>
                            <div class="h-4 bg-gray-200 rounded w-16"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@elseif($type === 'avatar')
    <div class="animate-pulse {{ $class }}">
        <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
    </div>
@elseif($type === 'image')
    <div class="animate-pulse {{ $class }}">
        <div class="w-16 h-16 bg-gray-200 rounded-lg"></div>
    </div>
@endif
