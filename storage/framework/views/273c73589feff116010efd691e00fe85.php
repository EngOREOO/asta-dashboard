<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'subtitle' => '',
    'value' => '',
    'icon' => '',
    'color' => 'blue',
    'href' => null,
    'clickable' => false,
    'class' => ''
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => '',
    'subtitle' => '',
    'value' => '',
    'icon' => '',
    'color' => 'blue',
    'href' => null,
    'clickable' => false,
    'class' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<?php if($href && $clickable): ?>
    <a href="<?php echo e($href); ?>" class="block">
<?php endif; ?>

<div <?php echo e($attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-all duration-200 font-arabic ' . $class])); ?>>
    <?php if($icon): ?>
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 <?php echo e($iconColor); ?> rounded-2xl flex items-center justify-center">
                <i class="<?php echo e($icon); ?> text-xl"></i>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if($title): ?>
        <h3 class="font-semibold text-gray-900 mb-1" style="font-size: 1.3rem;"><?php echo e($title); ?></h3>
    <?php endif; ?>
    
    <?php if($subtitle): ?>
        <p class="text-gray-600 mb-3" style="font-size: 1.3rem;"><?php echo e($subtitle); ?></p>
    <?php endif; ?>
    
    <?php if($value !== ''): ?>
        <div class="text-3xl font-bold text-gray-900"><?php echo e($value); ?></div>
    <?php endif; ?>
    
    <?php echo e($slot); ?>

</div>

<?php if($href && $clickable): ?>
    </a>
<?php endif; ?>
<?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/components/admin/card.blade.php ENDPATH**/ ?>