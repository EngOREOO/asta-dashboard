<?php ($title = 'تفاصيل الدورة'); ?>


<?php $__env->startSection('content'); ?>
<div class="space-y-6">
  <!-- Header Section with Course Thumbnail -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="relative h-48 bg-gradient-to-r from-blue-600 to-indigo-700">
      <?php if($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)): ?>
        <img src="<?php echo e(Storage::disk('public')->url($course->thumbnail)); ?>" 
             alt="<?php echo e($course->title); ?>"
             class="w-full h-full object-cover opacity-20">
      <?php endif; ?>
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-indigo-700/90"></div>
      
      <!-- Header Content -->
      <div class="absolute inset-0 flex flex-col justify-end p-6 text-white">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between">
          <div class="mb-4 sm:mb-0">
            <h1 class="font-bold mb-2 text-white" style="font-size: 1.9rem;"><?php echo e($course->title); ?></h1>
            <p class="text-blue-100" style="font-size: 1.3rem;">
              <?php if($course->instructor): ?>
                <i class="ti ti-user mr-2"></i><?php echo e($course->instructor->name); ?>

              <?php else: ?>
                <i class="ti ti-user mr-2"></i>بدون مدرس
              <?php endif; ?>
            </p>
          </div>
          <div class="flex items-center gap-3">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $course)): ?>
              <a href="<?php echo e(route('courses.edit', $course)); ?>" 
                 class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200">
                <i class="ti ti-edit mr-2"></i>
                <span style="font-size: 1.3rem;">تعديل الدورة</span>
              </a>
            <?php endif; ?>
            <a href="<?php echo e(route('courses.index')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200">
              <i class="ti ti-arrow-left mr-2"></i>
              <span style="font-size: 1.3rem;">العودة</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Course Overview Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الحالة','subtitle' => 'حالة الدورة','icon' => 'ti ti-check','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الحالة','subtitle' => 'حالة الدورة','icon' => 'ti ti-check','color' => 'green']); ?>
      <?php ($statusAr = ['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'][$course->status] ?? $course->status); ?>
      <div class="text-center">
        <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium" style="font-size: 1.3rem;
          <?php echo e($course->status === 'approved' ? 'bg-green-100 text-green-800' : 
             ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
             ($course->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))); ?>">
          <i class="ti ti-<?php echo e($course->status === 'approved' ? 'check' : ($course->status === 'pending' ? 'clock' : ($course->status === 'rejected' ? 'x' : 'file'))); ?> mr-1"></i>
          <?php echo e($statusAr); ?>

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
    
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'السعر','subtitle' => 'تكلفة الدورة','icon' => 'ti ti-currency-dollar','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'السعر','subtitle' => 'تكلفة الدورة','icon' => 'ti ti-currency-dollar','color' => 'blue']); ?>
      <div class="text-center space-y-2">
        <?php ($original = (float)($course->price ?? 0)); ?>
        <?php ($discounted = (float)$course->discounted_price); ?>
        <?php if($original > 0): ?>
          <?php if($discounted < $original): ?>
            <div class="flex items-center justify-center gap-2">
              <span class="line-through text-gray-400" style="font-size: 1.3rem;"><?php echo e(number_format($original, 2)); ?></span>
              <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-emerald-100 text-emerald-800" style="font-size: 1.3rem;">
                <?php echo e(number_format($discounted, 2)); ?>

                <img src="<?php echo e(asset('riyal.svg')); ?>" alt="ريال" class="w-5 h-5 mr-1">
              </span>
            </div>
            <div>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-pink-100 text-pink-800" style="font-size: 1.1rem;">خصم <?php echo e(round((1-($discounted/$original))*100)); ?>%</span>
            </div>
          <?php else: ?>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-blue-100 text-blue-800" style="font-size: 1.3rem;">
              <?php echo e(number_format($original, 2)); ?>

              <img src="<?php echo e(asset('riyal.svg')); ?>" alt="ريال" class="w-5 h-5 mr-1">
            </span>
          <?php endif; ?>
        <?php else: ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.3rem;">
            <i class="ti ti-gift mr-1"></i>
            مجاني
          </span>
        <?php endif; ?>
        <?php ($activeCoupons = \App\Models\Coupon::active()->where(function($q) use($course){ $q->where('applies_to','all')->orWhereHas('courses', fn($c)=>$c->where('courses.id',$course->id)); })->get()); ?>
        <?php if($activeCoupons->count()): ?>
          <div class="flex flex-wrap gap-2 justify-center">
            <?php $__currentLoopData = $activeCoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-teal-100 text-teal-800" style="font-size: 1.1rem;">كوبون <?php echo e($c->code); ?> — <?php echo e($c->percentage); ?>%</span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
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
    
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'المستوى','subtitle' => 'مستوى الصعوبة','icon' => 'ti ti-stairs','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المستوى','subtitle' => 'مستوى الصعوبة','icon' => 'ti ti-stairs','color' => 'purple']); ?>
      <div class="text-center">
        <?php if($course->difficulty_level): ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-purple-100 text-purple-800" style="font-size: 1.3rem;">
            <i class="ti ti-<?php echo e($course->difficulty_level === 'beginner' ? 'baby' : ($course->difficulty_level === 'intermediate' ? 'user' : 'crown')); ?> mr-1"></i>
            <?php echo e(['beginner'=>'مبتدئ','intermediate'=>'متوسط','advanced'=>'متقدم'][$course->difficulty_level] ?? $course->difficulty_level); ?>

          </span>
        <?php else: ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">
            <i class="ti ti-stairs mr-1"></i>
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
    
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'المدة','subtitle' => 'وقت الدورة','icon' => 'ti ti-clock','color' => 'yellow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المدة','subtitle' => 'وقت الدورة','icon' => 'ti ti-clock','color' => 'yellow']); ?>
      <div class="text-center">
        <?php if($course->estimated_duration): ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-yellow-100 text-yellow-800" style="font-size: 1.3rem;">
            <i class="ti ti-clock mr-1"></i>
            <?php echo e($course->estimated_duration); ?> ساعة
          </span>
        <?php else: ?>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">
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
  </div>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Course Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Course Description -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'وصف الدورة','icon' => 'ti ti-file-text','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'وصف الدورة','icon' => 'ti ti-file-text','color' => 'blue']); ?>
        <div class="prose prose-gray max-w-none">
          <p class="text-gray-700 leading-relaxed" style="font-size: 1.3rem;">
            <?php echo e($course->description ?? 'لا يوجد وصف متاح لهذه الدورة.'); ?>

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

      <!-- Course Materials / Lessons -->
      <?php if($course->materials && $course->materials->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الدروس والمواد','subtitle' => ''.e($course->materials->count()).' عنصر','icon' => 'ti ti-files','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الدروس والمواد','subtitle' => ''.e($course->materials->count()).' عنصر','icon' => 'ti ti-files','color' => 'green']); ?>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">#</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">العنوان</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">النوع</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">المدة</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">الترتيب</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = $course->materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2"><?php echo e($i+1); ?></td>
                  <td class="px-4 py-2"><?php echo e($material->title); ?></td>
                  <td class="px-4 py-2"><?php echo e(['video'=>'فيديو','document'=>'مستند','link'=>'رابط','quiz'=>'اختبار'][$material->type] ?? $material->type); ?></td>
                  <td class="px-4 py-2"><?php echo e($material->duration ? $material->duration.' دقيقة' : '—'); ?></td>
                  <td class="px-4 py-2"><?php echo e($material->order ?? 0); ?></td>
                  <td class="px-4 py-2">
                    <div class="inline-flex items-center gap-2">
                      <a href="<?php echo e(route('course-materials.show', $material)); ?>" class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" title="عرض"><i class="ti ti-eye text-sm"></i></a>
                      <a href="<?php echo e(route('course-materials.edit', $material)); ?>" class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" title="تعديل"><i class="ti ti-edit text-sm"></i></a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
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
      <?php endif; ?>

      <!-- Learning Paths -->
      <?php if($course->learningPaths && $course->learningPaths->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'المسارات التعليمية','subtitle' => ''.e($course->learningPaths->count()).' مسار','icon' => 'ti ti-route','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المسارات التعليمية','subtitle' => ''.e($course->learningPaths->count()).' مسار','icon' => 'ti ti-route','color' => 'green']); ?>
          <div class="flex flex-wrap gap-2">
            <?php $__currentLoopData = $course->learningPaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.1rem;"><?php echo e($lp->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
      <?php endif; ?>

      <!-- Topics -->
      <?php if($course->topics && $course->topics->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'محاور الدورة','subtitle' => ''.e($course->topics->count()).' محور','icon' => 'ti ti-list-details','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'محاور الدورة','subtitle' => ''.e($course->topics->count()).' محور','icon' => 'ti ti-list-details','color' => 'blue']); ?>
          <ul class="list-disc pr-6 space-y-1 text-gray-700" style="font-size: 1.2rem;">
            <?php $__currentLoopData = $course->topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($topic->title ?? $topic->name ?? 'موضوع'); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
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

      <!-- Course Quizzes -->
      <?php if($course->quizzes && $course->quizzes->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'الاختبارات','subtitle' => ''.e($course->quizzes->count()).' اختبار','icon' => 'ti ti-help-circle','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الاختبارات','subtitle' => ''.e($course->quizzes->count()).' اختبار','icon' => 'ti ti-help-circle','color' => 'purple']); ?>
          <div class="space-y-3">
            <?php $__currentLoopData = $course->quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="group flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200 cursor-pointer border border-transparent hover:border-gray-200"
                   onclick="window.location.href='<?php echo e(route('quizzes.show', $quiz)); ?>'">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <i class="ti ti-help-circle text-purple-600 text-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 group-hover:text-purple-700 transition-colors duration-200" style="font-size: 1.3rem;"><?php echo e($quiz->title); ?></h4>
                    <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
                      <i class="ti ti-list-check mr-1"></i>
                      <?php echo e($quiz->questions_count ?? 0); ?> سؤال
                    </p>
                  </div>
                </div>
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    <?php echo e($quiz->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                    <i class="ti ti-<?php echo e($quiz->is_active ? 'check' : 'x'); ?> mr-1"></i>
                    <?php echo e($quiz->is_active ? 'نشط' : 'غير نشط'); ?>

                  </span>
                  <i class="ti ti-chevron-right text-gray-400 group-hover:text-purple-500 transition-colors duration-200"></i>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
      <?php endif; ?>

      <!-- Rejection Reason -->
      <?php if($course->rejection_reason): ?>
        <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'سبب الرفض','icon' => 'ti ti-alert-circle','color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'سبب الرفض','icon' => 'ti ti-alert-circle','color' => 'red']); ?>
          <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start">
              <i class="ti ti-alert-circle text-red-500 mt-0.5 mr-2"></i>
              <p class="text-red-800" style="font-size: 1.3rem;"><?php echo e($course->rejection_reason); ?></p>
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
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Course Thumbnail -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'صورة الدورة','icon' => 'ti ti-image','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'صورة الدورة','icon' => 'ti ti-image','color' => 'blue']); ?>
        <?php if($course->thumbnail && file_exists(public_path($course->thumbnail))): ?>
          <img src="<?php echo e(asset($course->thumbnail)); ?>" 
               alt="<?php echo e($course->title); ?>"
               class="w-full h-48 object-cover rounded-xl">
        <?php else: ?>
          <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center">
            <img src="<?php echo e(asset('images/asta-logo.png')); ?>" 
                 alt="ASTA Logo" 
                 class="w-20 h-20 object-contain opacity-60">
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

      <!-- Course Info -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'معلومات الدورة','icon' => 'ti ti-info-circle','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'معلومات الدورة','icon' => 'ti ti-info-circle','color' => 'gray']); ?>
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-user mr-2 text-gray-400"></i>المدرّس:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              <?php if($course->instructor): ?>
                <a href="<?php echo e(route('instructors.show', $course->instructor)); ?>" 
                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                  <?php echo e($course->instructor->name); ?>

                  <i class="ti ti-external-link ml-1 text-xs"></i>
                </a>
              <?php else: ?>
                <span class="text-gray-500">—</span>
              <?php endif; ?>
            </span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-category mr-2 text-gray-400"></i>القسم:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              <?php if($course->category): ?>
                <a href="<?php echo e(route('categories.show', $course->category)); ?>" 
                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                  <?php echo e($course->category->name); ?>

                  <i class="ti ti-external-link ml-1 text-xs"></i>
                </a>
              <?php else: ?>
                <span class="text-gray-500">—</span>
              <?php endif; ?>
            </span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-language mr-2 text-gray-400"></i>اللغة:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($course->language ?? 'غير محدد'); ?></span>
          </div>
          
          <?php if($course->duration_days): ?>
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-calendar-event mr-2 text-gray-400"></i>مدة الدوره بالأيام:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($course->duration_days); ?> يوم</span>
          </div>
          <?php endif; ?>
          
          <?php if($course->awarding_institution): ?>
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-building mr-2 text-gray-400"></i>الجهه المانحه:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($course->awarding_institution); ?></span>
          </div>
          <?php endif; ?>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-calendar mr-2 text-gray-400"></i>تاريخ الإنشاء:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              <?php echo e($course->created_at ? $course->created_at->format('Y-m-d') : '—'); ?>

            </span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-refresh mr-2 text-gray-400"></i>آخر تحديث:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              <?php echo e($course->updated_at ? $course->updated_at->format('Y-m-d') : '—'); ?>

            </span>
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

      <!-- Course Stats -->
      <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'إحصائيات الدورة','icon' => 'ti ti-chart-bar','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إحصائيات الدورة','icon' => 'ti ti-chart-bar','color' => 'green']); ?>
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-users mr-2 text-green-500"></i>عدد الطلاب:
            </span>
            <span class="font-bold text-green-700" style="font-size: 1.3rem;"><?php echo e($course->students_count ?? 0); ?></span>
          </div>
          
          <?php if($course->average_rating): ?>
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
              <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
                <i class="ti ti-star mr-2 text-yellow-500"></i>التقييم:
              </span>
              <div class="flex items-center">
                <div class="flex items-center text-yellow-400 mr-2">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="ti ti-star <?php echo e($i <= $course->average_rating ? 'fill-current' : ''); ?>"></i>
                  <?php endfor; ?>
                </div>
                <span class="font-medium text-yellow-700" style="font-size: 1.3rem;">(<?php echo e($course->total_ratings ?? 0); ?>)</span>
              </div>
            </div>
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
  </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
.prose {
  max-width: none;
}

.prose p {
  margin: 0;
  line-height: 1.7;
}

/* Smooth hover transitions */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Enhanced card shadows */
.card-hover {
  transition: all 0.3s ease;
}

.card-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>

<!-- Alpine.js for Interactive Features -->
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('courseShow', () => ({
    init() {
      // Add smooth scroll behavior
      this.$nextTick(() => {
        const elements = document.querySelectorAll('[data-smooth-scroll]');
        elements.forEach(el => {
          el.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(el.getAttribute('href'));
            if (target) {
              target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          });
        });
      });
    }
  }));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/courses/show.blade.php ENDPATH**/ ?>