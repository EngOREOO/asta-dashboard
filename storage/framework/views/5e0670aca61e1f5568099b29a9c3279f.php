<?php ($title = 'إنشاء اختبار'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إنشاء اختبار جديد</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">اربط الاختبار بدورة وحدد الإعدادات</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('quizzes.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للاختبارات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-clipboard-list text-white text-xl"></i>
            </div>
            نموذج إنشاء اختبار
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="<?php echo e(route('quizzes.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php echo csrf_field(); ?>

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
            <label for="title" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">عنوان الاختبار *</label>
            <input id="title" name="title" type="text" value="<?php echo e(old('title')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;"><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="duration_minutes" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">المدة (دقائق)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" value="<?php echo e(old('duration_minutes')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لبدون حد زمني</small>
            <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="max_attempts" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">أقصى عدد محاولات</label>
            <input id="max_attempts" name="max_attempts" type="number" value="<?php echo e(old('max_attempts')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لعدد غير محدود</small>
            <?php $__errorArgs = ['max_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="passing_score" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نسبة النجاح (%)</label>
            <input id="passing_score" name="passing_score" type="number" value="<?php echo e(old('passing_score')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0" max="100" step="0.01">
            <?php $__errorArgs = ['passing_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="available_from" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">متاح من</label>
            <input id="available_from" name="available_from" type="datetime-local" value="<?php echo e(old('available_from')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
            <?php $__errorArgs = ['available_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="available_until" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">متاح حتى</label>
            <input id="available_until" name="available_until" type="datetime-local" value="<?php echo e(old('available_until')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
            <?php $__errorArgs = ['available_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 items-center">
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">نشط</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="randomize_questions" name="randomize_questions" value="1" <?php echo e(old('randomize_questions') ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">ترتيب عشوائي للأسئلة</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" <?php echo e(old('show_results_immediately', true) ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">عرض النتائج فوراً</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="allow_review" name="allow_review" value="1" <?php echo e(old('allow_review', true) ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">السماح بالمراجعة</span>
            </label>
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">إنشاء الاختبار</button>
            <a href="<?php echo e(route('quizzes.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/quizzes/create.blade.php ENDPATH**/ ?>