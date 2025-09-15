<?php $__env->startSection('title', 'تفاصيل المحاضر'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Gradient Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden mb-8">
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
                    <?php echo e(Str::of($instructor->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold">تفاصيل المحاضر</h1>
                    <p class="text-white/80 mt-1 text-sm text-ltr"><?php echo e($instructor->email); ?></p>
                </div>
            </div>
            <a href="<?php echo e(route('users.edit', $instructor)); ?>" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                <i class="ti ti-edit mr-2"></i>
                تعديل الملف الشخصي
            </a>
        </div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-teal-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl font-bold text-white"><?php echo e($instructor->initials); ?></span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900"><?php echo e($instructor->name); ?></h2>
                    <p class="text-gray-600 email-content text-ltr"><?php echo e($instructor->email); ?></p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">الدور</span>
                        <?php ($roleAr = ['admin'=>'مدير','instructor'=>'محاضر','student'=>'طالب','user'=>'مستخدم'][$instructor->roles->pluck('name')->first()] ?? ($instructor->roles->pluck('name')->first() ?: 'محاضر')); ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800"><?php echo e($roleAr); ?></span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">تاريخ الانضمام</span>
                        <span class="text-sm font-medium text-gray-900"><?php echo e(optional($instructor->created_at)->format('Y-m-d')); ?></span>
                    </div>

                    <?php if($instructor->teaching_field): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-600">مجال التدريس</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($instructor->teaching_field); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($instructor->job_title): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-600">المسمى الوظيفي</span>
                            <span class="text-sm font-medium text-gray-900"><?php echo e($instructor->job_title); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="lg:col-span-2">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">الدورات التدريبية</h3>
                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-teal-600"><?php echo e($instructor->instructor_courses_count ?? 0); ?></div>
                            <div class="text-sm text-gray-500">إجمالي الدورات</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($instructor->total_students); ?></div>
                            <div class="text-sm text-gray-500">إجمالي الطلاب</div>
                        </div>
                    </div>
                </div>

                <?php if(($instructor->instructorCourses ?? collect())->count() > 0): ?>
                    <div class="admin-table-container">
                        <table class="admin-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th>#</th>
                                    <th>عنوان الدورة</th>
                                    <th>الطلاب</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $instructor->instructorCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="text-center"><?php echo e($course->id); ?></td>
                                        <td class="font-medium">
                                            <?php echo e($course->title); ?>

                                            <?php if($course->materials_count): ?>
                                                <div class="text-xs text-gray-500"><?php echo e($course->materials_count); ?> مادة تعليمية</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo e($course->students_count ?? 0); ?></td>
                                        <td>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium <?php echo e($course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')); ?>" style="font-size: 1.2rem;">
                                                <?php echo e($course->status === 'approved' ? 'معتمدة' : ($course->status === 'pending' ? 'قيد المراجعة' : 'مسودة')); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="<?php echo e(route('courses.show', $course)); ?>" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" title="عرض"><i class="ti ti-eye text-base"></i></a>
                                                <a href="<?php echo e(route('courses.edit', $course)); ?>" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" title="تعديل"><i class="ti ti-edit text-base"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-book text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد دورات</h3>
                        <p class="text-gray-500">لم يقم هذا المحاضر بإنشاء أي دورات بعد.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/instructors/show.blade.php ENDPATH**/ ?>