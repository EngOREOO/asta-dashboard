<?php ($title = 'التخصصات'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6 relative overflow-hidden text-white">
        <div class="relative z-10 flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold">التخصصات</h1>
            <p class="text-white/80 mt-1">أضف تخصصاً جديداً من خلال الحقل أدناه</p>
          </div>
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white/90 text-sm">
            <?php echo e($specializations->count()); ?> عنصر
          </div>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8 space-y-8">
        <?php if(session('success')): ?>
          <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-800" id="flash-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('specializations.store')); ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
          <?php echo csrf_field(); ?>
          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2">اسم التخصص</label>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div>
            <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl">إضافة</button>
          </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-gray-200">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-right text-gray-700 font-medium">#</th>
                <th class="px-4 py-3 text-right text-gray-700 font-medium">الاسم</th>
                <th class="px-4 py-3 text-right text-gray-700 font-medium">الكود</th>
                <th class="px-4 py-3 text-right text-gray-700 font-medium">الحالة</th>
                <th class="px-4 py-3 text-right text-gray-700 font-medium">الإجراءات</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <?php $__empty_1 = true; $__currentLoopData = $specializations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3"><?php echo e($spec->id); ?></td>
                  <td class="px-4 py-3"><?php echo e($spec->name); ?></td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800 code-badge"><?php echo e($spec->slug); ?></span>
                  </td>
                  <td class="px-4 py-3">
                    <form action="<?php echo e(route('specializations.toggle-active', $spec)); ?>" method="POST">
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm <?php echo e($spec->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700'); ?>">
                        <i class="ti <?php echo e($spec->is_active ? 'ti-toggle-right' : 'ti-toggle-left'); ?> ml-1"></i>
                        <?php echo e($spec->is_active ? 'نشط' : 'غير نشط'); ?>

                      </button>
                    </form>
                  </td>
                  <td class="px-4 py-3">
                    <form action="<?php echo e(route('specializations.destroy', $spec)); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص؟')" class="inline">
                      <?php echo csrf_field(); ?>
                      <?php echo method_field('DELETE'); ?>
                      <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                        <i class="ti ti-trash text-sm"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                      <i class="ti ti-list text-2xl text-gray-400"></i>
                    </div>
                    لا توجد تخصصات بعد
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/specializations/index.blade.php ENDPATH**/ ?>