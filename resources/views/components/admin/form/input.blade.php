@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false, 'class' => ''])

<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input type="{{ $type }}" 
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           {{ $required ? 'required' : '' }}
           {{ $attributes->merge(['class' => 'block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 ' . $class]) }}>
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    <!-- Reserve space for error to prevent layout shift -->
    @if(!$errors->has($name))
        <div class="h-5"></div>
    @endif
</div>
