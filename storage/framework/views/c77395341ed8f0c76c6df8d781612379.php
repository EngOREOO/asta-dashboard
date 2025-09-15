<?php $__env->startSection('title', $courseMaterial->title); ?>

<?php $__env->startSection('content'); ?>
    <!-- Gradient Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden mb-8">
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
                    <i class="ti ti-file-description"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold"><?php echo e($courseMaterial->title); ?></h1>
                    <?php if($courseMaterial->description): ?>
                        <p class="text-white/80 mt-1 text-sm"><?php echo e($courseMaterial->description); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('course-materials.index')); ?>" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                    <i class="ti ti-arrow-right mr-2"></i>
                    العودة للمواد
                </a>
                <a href="<?php echo e(route('course-materials.edit', $courseMaterial)); ?>" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                    <i class="ti ti-edit mr-2"></i>
                    تعديل المادة
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content - Material Details -->
        <div class="lg:col-span-2">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <div class="flex items-start space-x-4 rtl:space-x-reverse mb-6">
                    <!-- Material Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-blue-600 rounded-2xl flex items-center justify-center">
                            <i class="ti ti-<?php echo e($courseMaterial->type === 'video' ? 'video' : ($courseMaterial->type === 'document' ? 'file-document' : ($courseMaterial->type === 'quiz' ? 'help-circle' : 'clipboard'))); ?> text-white text-2xl"></i>
                        </div>
                    </div>
                    
                    <!-- Material Info -->
                    <div class="flex-1">
                        <h2 class="font-bold text-gray-900 mb-2" style="font-size: 1.3rem;"><?php echo e($courseMaterial->title); ?></h2>
                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium <?php echo e($courseMaterial->type === 'video' ? 'bg-blue-100 text-blue-800' : ($courseMaterial->type === 'document' ? 'bg-purple-100 text-purple-800' : ($courseMaterial->type === 'quiz' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'))); ?>" style="font-size: 1.3rem;">
                                <i class="ti ti-<?php echo e($courseMaterial->type === 'video' ? 'video' : ($courseMaterial->type === 'document' ? 'file-document' : ($courseMaterial->type === 'quiz' ? 'help-circle' : 'clipboard'))); ?> mr-1"></i>
                                <?php echo e($courseMaterial->type === 'video' ? 'فيديو' : ($courseMaterial->type === 'document' ? 'مستند' : ($courseMaterial->type === 'quiz' ? 'اختبار' : 'مهمة'))); ?>

                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium <?php echo e($courseMaterial->is_free ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'); ?>" style="font-size: 1.3rem;">
                                <i class="ti ti-<?php echo e($courseMaterial->is_free ? 'gift' : 'lock'); ?> mr-1"></i>
                                <?php echo e($courseMaterial->is_free ? 'مجاني' : 'مدفوع'); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <?php if($courseMaterial->description): ?>
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3" style="font-size: 1.3rem;">وصف المادة</h3>
                        <p class="text-gray-600 leading-relaxed" style="font-size: 1.3rem;"><?php echo e($courseMaterial->description); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Course Information -->
                <?php if($courseMaterial->course): ?>
                    <div class="border-t pt-6 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">معلومات الدورة</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($courseMaterial->course->title); ?></h4>
                                    <?php if($courseMaterial->course->instructor): ?>
                                        <p class="text-gray-600" style="font-size: 1.3rem;">المحاضر: <?php echo e($courseMaterial->course->instructor->name); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'outline','size' => 'sm','href' => ''.e(route('courses.show', $courseMaterial->course)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'sm','href' => ''.e(route('courses.show', $courseMaterial->course)).'']); ?>
                                    <i class="ti ti-eye mr-1"></i>
                                    عرض الدورة
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- File and Content Access -->
                <?php if($courseMaterial->file_path || $courseMaterial->content_url): ?>
                    <div class="border-t pt-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">الوصول للمحتوى</h3>
                        <div class="space-y-3">
                            <?php if($courseMaterial->type === 'video' && $courseMaterial->file_path): ?>
                                <div class="p-4 border border-gray-200 rounded-2xl bg-black/5">
                                    <video controls class="w-full rounded-xl shadow" style="max-height: 520px;">
                                        <?php ($publicPath = public_path($courseMaterial->file_path)); ?>
                                        <?php ($src = file_exists($publicPath) ? asset($courseMaterial->file_path) : asset('storage/' . $courseMaterial->file_path)); ?>
                                        <source src="<?php echo e($src); ?>" type="video/mp4">
                                        متصفحك لا يدعم تشغيل الفيديو. <a href="<?php echo e($src); ?>" class="text-blue-600 underline">تحميل الملف</a>
                                    </video>
                                </div>
                            <?php endif; ?>
                            <?php if($courseMaterial->file_path): ?>
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-download text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" style="font-size: 1.3rem;">ملف المادة</p>
                                            <?php if($courseMaterial->file_size): ?>
                                                <p class="text-gray-500" style="font-size: 1.3rem;">الحجم: <?php echo e(number_format($courseMaterial->file_size / 1024 / 1024, 2)); ?> ميجا</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php ($docSrc = file_exists(public_path($courseMaterial->file_path)) ? asset($courseMaterial->file_path) : asset('storage/' . $courseMaterial->file_path)); ?>
                                    <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'primary','size' => 'sm','href' => ''.e($docSrc).'','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'sm','href' => ''.e($docSrc).'','target' => '_blank']); ?>
                                        <i class="ti ti-download mr-1"></i>
                                        تحميل الملف
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($courseMaterial->content_url): ?>
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-external-link text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" style="font-size: 1.3rem;">رابط خارجي</p>
                                            <p class="text-gray-500" style="font-size: 1.3rem;"><?php echo e(parse_url($courseMaterial->content_url, PHP_URL_HOST)); ?></p>
                                        </div>
                                    </div>
                                    <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'secondary','size' => 'sm','href' => ''.e($courseMaterial->content_url).'','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','href' => ''.e($courseMaterial->content_url).'','target' => '_blank']); ?>
                                        <i class="ti ti-external-link mr-1"></i>
                                        فتح الرابط
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar - Material Info -->
        <div class="lg:col-span-1">
            <!-- Material Details Card -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">تفاصيل المادة</h3>
                <div class="space-y-4">
                    <?php if($courseMaterial->duration): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600" style="font-size: 1.3rem;">المدة</span>
                            <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e(gmdate('H:i:s', $courseMaterial->duration)); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600" style="font-size: 1.3rem;">الترتيب</span>
                        <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($courseMaterial->order ?? 'غير محدد'); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600" style="font-size: 1.3rem;">تاريخ الإنشاء</span>
                        <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($courseMaterial->created_at->format('Y-n-j')); ?></span>
                    </div>
                    
                    <?php if($courseMaterial->updated_at != $courseMaterial->created_at): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600" style="font-size: 1.3rem;">آخر تحديث</span>
                            <span class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($courseMaterial->updated_at->format('Y-n-j')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Completion Statistics -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">إحصائيات الإكمال</h3>
                
                <?php if($courseMaterial->completions && $courseMaterial->completions->count() > 0): ?>
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-teal-600 mb-2"><?php echo e($courseMaterial->completions->count()); ?></div>
                        <p class="text-gray-600" style="font-size: 1.3rem;">إجمالي المكملين</p>
                    </div>
                    
                    <h4 class="font-semibold text-gray-900 mb-3" style="font-size: 1.3rem;">آخر المكملين</h4>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $courseMaterial->completions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $completion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-white"><?php echo e(substr($completion->user->name, 0, 1)); ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate" style="font-size: 1.3rem;"><?php echo e($completion->user->name); ?></p>
                                    <p class="text-gray-500" style="font-size: 1.3rem;"><?php echo e($completion->completed_at->format('Y-n-j')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-users text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-2" style="font-size: 1.3rem;">لا توجد إكمالات</h4>
                        <p class="text-gray-500" style="font-size: 1.3rem;">لم يكمل أحد هذه المادة بعد.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
                <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'primary','size' => 'sm','href' => ''.e(route('course-materials.edit', $courseMaterial)).'','class' => 'w-full justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'sm','href' => ''.e(route('course-materials.edit', $courseMaterial)).'','class' => 'w-full justify-center']); ?>
                        <i class="ti ti-edit mr-2"></i>
                        تعديل المادة
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                    
                    <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'outline','size' => 'sm','href' => ''.e(route('course-materials.index')).'','class' => 'w-full justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'sm','href' => ''.e(route('course-materials.index')).'','class' => 'w-full justify-center']); ?>
                        <i class="ti ti-list mr-2"></i>
                        عرض جميع المواد
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                    
                    <?php if($courseMaterial->course): ?>
                        <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'secondary','size' => 'sm','href' => ''.e(route('courses.show', $courseMaterial->course)).'','class' => 'w-full justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','href' => ''.e(route('courses.show', $courseMaterial->course)).'','class' => 'w-full justify-center']); ?>
                            <i class="ti ti-book mr-2"></i>
                            عرض الدورة
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                    <?php endif; ?>
                    
                    <form action="<?php echo e(route('course-materials.destroy', $courseMaterial)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <?php if (isset($component)) { $__componentOriginal60a020e5340f3f52bbc4501dc9f93102 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.button','data' => ['variant' => 'danger','size' => 'sm','type' => 'submit','class' => 'w-full justify-center','onclick' => 'return confirm(\'هل أنت متأكد من حذف هذه المادة؟\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger','size' => 'sm','type' => 'submit','class' => 'w-full justify-center','onclick' => 'return confirm(\'هل أنت متأكد من حذف هذه المادة؟\')']); ?>
                            <i class="ti ti-trash mr-2"></i>
                            حذف المادة
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $attributes = $__attributesOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__attributesOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102)): ?>
<?php $component = $__componentOriginal60a020e5340f3f52bbc4501dc9f93102; ?>
<?php unset($__componentOriginal60a020e5340f3f52bbc4501dc9f93102); ?>
<?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/course-materials/show.blade.php ENDPATH**/ ?>