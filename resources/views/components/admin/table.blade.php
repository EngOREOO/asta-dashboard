@props(['class' => ''])

<div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden font-arabic {{ $class }}">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                {{ $header }}
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $body }}
            </tbody>
        </table>
    </div>
</div>
