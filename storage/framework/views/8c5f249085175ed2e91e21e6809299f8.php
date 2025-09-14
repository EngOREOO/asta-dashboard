<?php ($title = 'التعليقات'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">التعليقات والتقييمات</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">إدارة تعليقات الطلاب والموافقة عليها</p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-2">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800" style="font-size: 1.2rem;">قيد المراجعة: <?php echo e($reviews->where('is_approved', null)->count()); ?></span>
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800" style="font-size: 1.2rem;">المعتمدة: <?php echo e($reviews->where('is_approved', true)->count()); ?></span>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white" style="font-size: 1.3rem;">قائمة التعليقات</h2>
        </div>
      </div>
      <div class="p-8">
        <?php if($reviews->count() > 0): ?>
        <div class="admin-table-container">
          <table class="admin-table" id="reviews-table" style="font-size: 1.2rem; text-align: center;">
            <thead class="bg-gray-50">
              <tr>
                <th>#</th>
                <th>الطالب</th>
                <th>الدورة</th>
                <th>التقييم</th>
                <th>التعليق</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50">
                  <td class="text-center"><?php echo e($review->id); ?></td>
                  <td>
                    <div class="grid [grid-template-columns:48px_1fr] items-center gap-3 justify-items-end mx-auto">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white flex items-center justify-center flex-shrink-0">
                        <?php echo e(Str::of($review->user->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

                      </div>
                      <div class="text-right">
                        <div class="font-medium"><?php echo e(optional($review->user)->name ?? '—'); ?></div>
                        <!-- <div class="email-content text-ltr"><?php echo e(optional($review->user)->email ?? '—'); ?></div> -->
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="inline-block text-center">
                      <div class="font-medium"><?php echo e(\Illuminate\Support\Str::words(optional($review->course)->title ?? '—', 2, ' …')); ?></div>
                      <div class="text-gray-500"><?php echo e(optional($review->course->instructor)->name ?? '—'); ?></div>
                    </div>
                  </td>
                  <td>
                    <div class="inline-flex items-center justify-center gap-1">
                      <i class="ti ti-star-filled text-amber-500"></i>
                      <span class="font-medium"><?php echo e($review->rating); ?>/5</span>
                    </div>
                  </td>
                  <td>
                    <div class="max-w-xs text-gray-700"><?php echo e(\Illuminate\Support\Str::words($review->message ?? 'بدون تعليق', 2, ' …')); ?></div>
                  </td>
                  <td>
                    <?php if($review->is_approved === null): ?>
                      <span class="inline-flex px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800" style="font-size: 1.1rem;">قيد المراجعة</span>
                    <?php elseif($review->is_approved): ?>
                      <span class="inline-flex px-2.5 py-0.5 rounded-full bg-green-100 text-green-800" style="font-size: 1.1rem;">معتمدة</span>
                    <?php else: ?>
                      <span class="inline-flex px-2.5 py-0.5 rounded-full bg-red-100 text-red-800" style="font-size: 1.1rem;">مرفوضة</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo e($review->created_at ? $review->created_at->format('Y-n-j') : '—'); ?></td>
                  <td class="text-center">
                    <div class="inline-flex items-center gap-2">
                      <a class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" href="<?php echo e(route('reviews.show', $review)); ?>" title="عرض"><i class="ti ti-eye text-base"></i></a>
                      <?php if($review->is_approved !== true): ?>
                      <form action="<?php echo e(route('reviews.approve', $review)); ?>" method="POST" class="inline" onsubmit="return confirm('اعتماد هذه المراجعة؟')">
                        <?php echo csrf_field(); ?>
                        <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-green-700 bg-green-100 hover:bg-green-200" type="submit" title="اعتماد"><i class="ti ti-check text-base"></i></button>
                      </form>
                      <?php endif; ?>
                      <?php if($review->is_approved !== false): ?>
                      <form action="<?php echo e(route('reviews.reject', $review)); ?>" method="POST" class="inline" onsubmit="return confirm('رفض هذه المراجعة؟')">
                        <?php echo csrf_field(); ?>
                        <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-yellow-700 bg-yellow-100 hover:bg-yellow-200" type="submit" title="رفض"><i class="ti ti-x text-base"></i></button>
                      </form>
                      <?php endif; ?>
                      <form action="<?php echo e(route('reviews.destroy', $review)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف هذه المراجعة نهائياً؟')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-red-700 bg-red-100 hover:bg-red-200" type="submit" title="حذف"><i class="ti ti-trash text-base"></i></button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>

        <?php if($reviews->hasPages()): ?>
          <div class="mt-6 flex justify-center"><?php echo e($reviews->links()); ?></div>
        <?php endif; ?>
        <?php else: ?>
          <div class="text-center py-12">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ti ti-message-2 text-4xl text-blue-500"></i></div>
            <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا توجد تعليقات</h3>
            <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">لا توجد تعليقات حالياً للمراجعة.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#reviews-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[6, 'desc']],
        language: { sSearch: 'ابحث:' }
      });
    }
  });
</script>
<style>
  #reviews-table td, #reviews-table th { vertical-align: middle; text-align: center; }
  #reviews-table td .text-left { text-align: left; }
  #reviews-table td .text-right { text-align: right; }
  #reviews-table td > div { margin-left: auto; margin-right: auto; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/reviews/index.blade.php ENDPATH**/ ?>