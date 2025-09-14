<?php
    $title = 'تفاصيل المسار';
?>



<?php $__env->startSection('content'); ?>
<div class="space-y-6">
  <!-- Header Section -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900"><?php echo e($degree->name); ?></h1>
      <p class="mt-2 text-sm text-gray-600">تفاصيل المسار المهنية والدورات المرتبطة بها</p>
    </div>
    <div class="mt-4 sm:mt-0">
      <a href="<?php echo e(route('degrees.edit', $degree)); ?>" 
         class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
        <i class="ti ti-edit mr-2"></i>تعديل المسار
      </a>
    </div>
  </div>

  <!-- Degree Overview Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Level Card -->
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'المستوى','subtitle' => 'مستوى المسار','icon' => 'ti ti-school','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المستوى','subtitle' => 'مستوى المسار','icon' => 'ti ti-school','color' => 'blue']); ?>
      <div class="text-center">
        <?php
          $levelMapping = [
            1 => ['bg-blue-100 text-blue-800', 'ti ti-certificate', 'شهادة', 'certificate'],
            2 => ['bg-gray-100 text-gray-800', 'ti ti-school', 'متوسط', 'intermediate'],
            3 => ['bg-gray-100 text-gray-800', 'ti ti-school', 'ثانوي', 'secondary'],
            4 => ['bg-purple-100 text-purple-800', 'ti ti-graduation-cap', 'دبلوم', 'diploma'],
            5 => ['bg-green-100 text-green-800', 'ti ti-award', 'بكالوريوس', 'bachelor'],
            6 => ['bg-yellow-100 text-yellow-800', 'ti ti-trophy', 'ماجستير', 'master'],
            7 => ['bg-red-100 text-red-800', 'ti ti-crown', 'دكتوراه', 'doctorate']
          ];
          $levelInfo = $levelMapping[$degree->level] ?? ['bg-gray-100 text-gray-800', 'ti ti-school', 'غير محدد', 'unknown'];
        ?>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium <?php echo e($levelInfo[0]); ?>">
          <i class="<?php echo e($levelInfo[1]); ?> mr-1"></i>
          <?php echo e($levelInfo[2]); ?>

        </span>
      </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
    
    <!-- Duration Card -->
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'المدة','subtitle' => 'مدة الدراسة','icon' => 'ti ti-clock','color' => 'yellow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المدة','subtitle' => 'مدة الدراسة','icon' => 'ti ti-clock','color' => 'yellow']); ?>
      <div class="text-center">
        <?php if($degree->duration_months): ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
            <i class="ti ti-clock mr-1"></i>
            <?php echo e($degree->duration_months); ?> شهر
          </span>
        <?php else: ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
            <i class="ti ti-clock mr-1"></i>
            غير محدد
          </span>
        <?php endif; ?>
      </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
    
    <!-- Courses Card -->
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الدورات','subtitle' => 'عدد الدورات','icon' => 'ti ti-book','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الدورات','subtitle' => 'عدد الدورات','icon' => 'ti ti-book','color' => 'green']); ?>
      <div class="text-center">
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
          <i class="ti ti-book mr-1"></i>
          <?php echo e($degree->courses->count()); ?> دورة
        </span>
      </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
    
    <!-- Status Card -->
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الحالة','subtitle' => 'حالة المسار','icon' => 'ti ti-check','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الحالة','subtitle' => 'حالة المسار','icon' => 'ti ti-check','color' => 'purple']); ?>
      <div class="text-center">
        <?php if($degree->is_active): ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
            <i class="ti ti-check mr-1"></i>
            نشطة
          </span>
        <?php else: ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
            <i class="ti ti-x mr-1"></i>
            غير نشطة
          </span>
        <?php endif; ?>
      </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
  </div>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Degree Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Description -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'وصف المسار','icon' => 'ti ti-file-text','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'وصف المسار','icon' => 'ti ti-file-text','color' => 'blue']); ?>
        <div class="prose prose-gray max-w-none">
          <p class="text-gray-700 leading-relaxed text-base">
            <?php echo e($degree->description ?? 'لا يوجد وصف متاح لهذه المسار.'); ?>

          </p>
        </div>
       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>

      <!-- Associated Courses -->
      <?php if($degree->courses->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الدورات المرتبطة','subtitle' => ''.e($degree->courses->count()).' دورة','icon' => 'ti ti-book-2','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الدورات المرتبطة','subtitle' => ''.e($degree->courses->count()).' دورة','icon' => 'ti ti-book-2','color' => 'green']); ?>
          <div class="space-y-3">
            <?php $__currentLoopData = $degree->courses->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="group flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200 cursor-pointer border border-transparent hover:border-gray-200"
                   onclick="window.location.href='<?php echo e(route('courses.show', $course)); ?>'">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <i class="ti ti-book text-green-600 text-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors duration-200"><?php echo e($course->title); ?></h4>
                    <p class="text-sm text-gray-600 flex items-center">
                      <?php if($course->instructor): ?>
                        <i class="ti ti-user mr-1"></i>
                        <?php echo e($course->instructor->name); ?>

                      <?php endif; ?>
                      <?php if($course->price > 0): ?>
                        <span class="ml-2 text-blue-600 font-medium">
                          <img src="<?php echo e(asset('riyal.svg')); ?>" alt="ريال" class="w-4 h-4 inline ml-1">
                          <?php echo e(number_format($course->price, 2)); ?>

                        </span>
                      <?php else: ?>
                        <span class="ml-2 text-green-600 font-medium">مجاني</span>
                      <?php endif; ?>
                    </p>
                  </div>
                </div>
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                  <?php
                    $statusClasses = [
                      'approved' => 'bg-green-100 text-green-800',
                      'pending' => 'bg-yellow-100 text-yellow-800',
                      'rejected' => 'bg-red-100 text-red-800'
                    ];
                    $statusTexts = [
                      'approved' => 'معتمدة',
                      'pending' => 'قيد المراجعة',
                      'rejected' => 'مرفوضة'
                    ];
                    $statusClass = $statusClasses[$course->status] ?? 'bg-gray-100 text-gray-800';
                    $statusText = $statusTexts[$course->status] ?? 'مسودة';
                  ?>
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                    <?php echo e($statusText); ?>

                  </span>
                  <i class="ti ti-chevron-right text-gray-400 group-hover:text-green-500 transition-colors duration-200"></i>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          
          <?php if($degree->courses->count() > 10): ?>
            <div class="text-center mt-4">
              <span class="text-sm text-gray-500">و <?php echo e($degree->courses->count() - 10); ?> دورات أخرى...</span>
            </div>
          <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Degree Info -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'معلومات المسار','icon' => 'ti ti-info-circle','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'معلومات المسار','icon' => 'ti ti-info-circle','color' => 'blue']); ?>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">تاريخ الإنشاء:</span>
            <span class="text-sm font-medium text-gray-900"><?php echo e($degree->created_at->format('M j, Y')); ?></span>
          </div>
          
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">آخر تحديث:</span>
            <span class="text-sm font-medium text-gray-900"><?php echo e($degree->updated_at->format('M j, Y')); ?></span>
          </div>
          
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">المعرف:</span>
            <span class="text-sm font-medium text-gray-900">#<?php echo e($degree->id); ?></span>
          </div>
        </div>
       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>

      <!-- Actions -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الإجراءات','icon' => 'ti ti-settings','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الإجراءات','icon' => 'ti ti-settings','color' => 'gray']); ?>
        <div class="space-y-3">
          <a href="<?php echo e(route('degrees.edit', $degree)); ?>" 
             class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
            <i class="ti ti-edit mr-2"></i>تعديل المسار
          </a>
          
          <a href="<?php echo e(route('degrees.index')); ?>" 
             class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 transition ease-in-out duration-150">
            <i class="ti ti-arrow-left mr-2"></i>العودة للقائمة
          </a>
        </div>
       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/degrees/show.blade.php ENDPATH**/ ?>