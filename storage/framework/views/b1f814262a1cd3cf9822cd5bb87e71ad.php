<?php
    $title = 'إحصائيات الكوبونات';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-red-600 via-pink-600 to-purple-700 bg-clip-text text-transparent">
          إحصائيات الكوبونات
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-discount mr-2 text-red-500"></i>
          تحليل استخدام أكواد الخصم والعروض
        </p>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي الكوبونات</p>
            <p class="text-3xl font-bold text-red-600"><?php echo e($totalCoupons); ?></p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-discount text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">الكوبونات النشطة</p>
            <p class="text-3xl font-bold text-green-600"><?php echo e($activeCoupons); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($totalCoupons > 0 ? round(($activeCoupons / $totalCoupons) * 100, 1) : 0); ?>% من الإجمالي</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-check text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Coupon Usage Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-red-500 via-pink-500 to-purple-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-list mr-3"></i>
          استخدام الكوبونات
        </h3>
      </div>
      
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-900">كود الكوبون</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">نسبة الخصم</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الحالة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">عدد الاستخدامات</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">إجمالي الخصم</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php $__empty_1 = true; $__currentLoopData = $couponUsage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="font-medium text-gray-900"><?php echo e($coupon->code); ?></div>
                    <div class="text-sm text-gray-500"><?php echo e($coupon->description); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-red-100 text-red-800">
                      <?php echo e($coupon->discount_percentage); ?>%
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php if($coupon->is_active): ?>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800">
                        نشط
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800">
                        غير نشط
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">
                      <?php echo e($coupon->usage_count); ?>

                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                    <?php echo e(number_format($coupon->total_discount, 2)); ?> ريال
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    لا توجد بيانات متاحة
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

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/reports/promo-code-statistics/index.blade.php ENDPATH**/ ?>