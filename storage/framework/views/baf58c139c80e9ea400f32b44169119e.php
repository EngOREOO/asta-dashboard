<?php ($title = 'المستويات المهنية'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">المستويات المهنية</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-stairs mr-2 text-cyan-500"></i>
          إدارة المستويات المهنية للمسارات
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('career-levels.create')); ?>" 
           class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 border border-transparent rounded-2xl font-semibold text-sm text-white uppercase tracking-widest hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 disabled:opacity-25 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
          <i class="ti ti-plus mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          إضافة مستوى جديد
        </a>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.3rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-stairs text-white text-xl"></i>
            </div>
            قائمة المستويات المهنية
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">عرض وإدارة جميع المستويات المهنية</p>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-6">
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
          <div class="md:col-span-3">
            <label class="block text-sm text-gray-600 mb-1">بحث</label>
            <input type="text" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder="ابحث عن مستوى..." style="font-size: 1.3rem;">
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">الحالة</label>
            <select name="status" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.3rem;">
              <option value="">الكل</option>
              <option value="active" <?php if(($filters['status'] ?? '')==='active'): echo 'selected'; endif; ?>>نشط</option>
              <option value="inactive" <?php if(($filters['status'] ?? '')==='inactive'): echo 'selected'; endif; ?>>غير نشط</option>
            </select>
          </div>
          <div class="flex items-end gap-3">
            <button class="career-action-btn px-5 py-2.5 rounded-xl bg-cyan-600 text-white hover:bg-cyan-700">تصفية</button>
            <a href="<?php echo e(route('career-levels.index')); ?>" class="career-action-btn career-action-btn--secondary px-5 py-2.5 rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200">مسح</a>
          </div>
        </form>

        <div class="admin-table-container">
          <table class="admin-table career-table-lg">
            <thead>
              <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td class="text-center"><?php echo e(($levels->currentPage()-1)*$levels->perPage() + $loop->iteration); ?></td>
                <td><?php echo e($level->name); ?></td>
                <td class="text-center">
                  <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium <?php echo e($level->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>"><?php echo e($level->is_active ? 'نشط' : 'غير نشط'); ?></span>
                </td>
                <td class="text-center">
                  <div class="inline-flex items-center gap-2">
                    <a href="<?php echo e(route('career-levels.edit', $level)); ?>" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" title="تعديل"><i class="ti ti-edit text-base"></i></a>
                    <form action="<?php echo e(route('career-levels.destroy', $level)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف هذا المستوى؟')">
                      <?php echo csrf_field(); ?>
                      <?php echo method_field('DELETE'); ?>
                      <button type="submit" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-red-700 bg-red-100 hover:bg-red-200" title="حذف"><i class="ti ti-trash text-base"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="4" class="text-center py-8 text-gray-500">لا توجد مستويات مهنية</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if($levels->hasPages()): ?>
          <div class="mt-6 flex justify-center"><?php echo e($levels->links()); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<style>
.career-table-lg, .career-table-lg th, .career-table-lg td { font-size: 1.3rem !important; }
.career-action-btn { font-size: 1.3rem; transition: transform .15s ease, box-shadow .2s ease, background-color .2s ease; }
.career-action-btn:hover { transform: translateY(-1px) scale(1.02); box-shadow: 0 10px 18px rgba(59,130,246,0.18); }
.career-action-btn--secondary:hover { box-shadow: 0 10px 18px rgba(0,0,0,0.06); }
</style>



<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/career-levels/index.blade.php ENDPATH**/ ?>