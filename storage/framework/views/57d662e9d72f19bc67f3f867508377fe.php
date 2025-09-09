<?php ($title = 'تعديل مستوى مهني'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex items-center justify-between">
      <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size:1.9rem;">تعديل مستوى مهني</h1>
      <a href="<?php echo e(route('career-levels.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size:1.3rem;">عودة</a>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="p-8">
        <form method="POST" action="<?php echo e(route('career-levels.update', $level)); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size:1.3rem;">ادخل اسم المستوي</label>
            <input type="text" name="name" value="<?php echo e(old('name', $level->name)); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size:1.3rem;" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size:1.2rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size:1.3rem;">الحالة</label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $level->is_active) ? 'checked' : ''); ?>>
              <span style="font-size:1.2rem;">نشط</span>
            </label>
          </div>
          <div class="md:col-span-2 flex items-center gap-3">
            <button class="px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow hover:shadow-lg" style="font-size:1.3rem;">حفظ</button>
            <a href="<?php echo e(route('career-levels.index')); ?>" class="px-6 py-3 rounded-2xl bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 shadow-sm" style="font-size:1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/career-levels/edit.blade.php ENDPATH**/ ?>