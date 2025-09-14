<?php ($title = 'تسجيل طالب'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تسجيل طالب في دورة</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">اختر الطالب والدورة وحدد الإعدادات الأولية</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('enrollments.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للتسجيلات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-user-plus text-white text-xl"></i>
            </div>
            نموذج التسجيل
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="<?php echo e(route('enrollments.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php echo csrf_field(); ?>

          <div>
            <label for="user_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الطالب *</label>
            <select id="user_id" name="user_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر الطالب</option>
              <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($student->id); ?>" <?php echo e(old('user_id') == $student->id ? 'selected' : ''); ?>><?php echo e($student->name); ?> - <?php echo e($student->email); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="course_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدورة *</label>
            <select id="course_id" name="course_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر الدورة</option>
              <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>><?php echo e($course->title); ?> <?php if($course->instructor): ?> - <?php echo e($course->instructor->name); ?> <?php endif; ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="progress" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نسبة التقدم المبدئية (%)</label>
            <input id="progress" name="progress" type="number" value="<?php echo e(old('progress', 0)); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0" max="100">
            <?php $__errorArgs = ['progress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="grade" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">التقدير</label>
            <input id="grade" name="grade" type="text" value="<?php echo e(old('grade')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" placeholder="A, B, C..">
            <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2">
            <label for="notes" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">ملاحظات</label>
            <textarea id="notes" name="notes" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;"><?php echo e(old('notes')); ?></textarea>
            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">تسجيل</button>
            <a href="<?php echo e(route('enrollments.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/enrollments/create.blade.php ENDPATH**/ ?>