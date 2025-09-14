<?php ($title = 'إدارة الشهادات'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 2.0rem;">سجل الشهادات</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.4rem;"><i class="ti ti-certificate mr-2 text-cyan-500"></i>إدارة وإصدار الشهادات</p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <a href="<?php echo e(route('certificates.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg transition" style="font-size: 1.4rem;"><i class="ti ti-square-plus mr-2"></i>إصدار شهادة</a>
        <form action="<?php echo e(route('certificates.bulk-issue')); ?>" method="POST" class="inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-2xl shadow hover:bg-green-700 transition" style="font-size: 1.4rem;"><i class="ti ti-certificate mr-2"></i>إصدار جماعي</button>
        </form>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.3rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm"><i class="ti ti-certificate text-white text-xl"></i></div>
            قائمة الشهادات
          </h2>
        </div>
      </div>
      <div class="p-8">
        <?php if($certificates->count() > 0): ?>
        <div class="admin-table-container">
          <table class="admin-table" id="certificates-table" style="font-size: 1.3rem;">
            <thead class="bg-gray-50">
              <tr>
                <th style="font-size: 1.3rem;">#</th>
                <th style="font-size: 1.3rem;">الطالب</th>
                <th style="font-size: 1.3rem;">الدورة</th>
                <th style="font-size: 1.3rem;">رمز الشهادة</th>
                <th style="font-size: 1.3rem;">المحاضر</th>
                <th style="font-size: 1.3rem;">تاريخ الإصدار</th>
                <th style="font-size: 1.3rem;">الحالة</th>
                <th style="font-size: 1.3rem;">الإجراءات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
          <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr class="hover:bg-gray-50">
            <td class="text-center"><?php echo e($certificate->id); ?></td>
            <td>
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white flex items-center justify-center">
                  <?php echo e(Str::of($certificate->user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

                </div>
                <div>
                  <div class="font-medium"><?php echo e($certificate->user->name); ?></div>
                  <div class="email-content text-ltr"><?php echo e($certificate->user->email); ?></div>
                </div>
              </div>
            </td>
            <td>
              <div class="font-medium"><?php echo e(optional($certificate->course)->title ?? '—'); ?></div>
              <div class="text-gray-500">
                <?php if($certificate->course && $certificate->course->category): ?>
                  <?php echo e($certificate->course->category->name); ?>

                <?php elseif($certificate->course && $certificate->course->difficulty_level): ?>
                  <?php echo e(['beginner'=>'مبتدئ','intermediate'=>'متوسط','advanced'=>'متقدم'][$certificate->course->difficulty_level] ?? $certificate->course->difficulty_level); ?>

                <?php else: ?>
                  —
                <?php endif; ?>
              </div>
            </td>
            <td class="text-ltr">
              <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-800" style="font-family: Arial, sans-serif;">
                <?php echo e($certificate->code ?? '—'); ?>

              </span>
            </td>
            <td><?php echo e(optional($certificate->course->instructor)->name ?? '—'); ?></td>
            <td><?php echo e($certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y-n-j') : '—'); ?></td>
            <td><span class="inline-flex px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.2rem;">صادرة</span></td>
            <td class="text-center">
              <a href="<?php echo e(route('certificates.show', $certificate)); ?>" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" title="عرض">
                <i class="ti ti-eye text-base"></i>
              </a>
              <form action="<?php echo e(route('certificates.destroy', $certificate)); ?>" method="POST" class="inline ml-1" onsubmit="return confirm('هل أنت متأكد؟')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-red-700 bg-red-100 hover:bg-red-200" title="حذف"><i class="ti ti-trash text-base"></i></button>
              </form>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
              </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
          <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ti ti-certificate text-4xl text-blue-500"></i></div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا توجد شهادات</h3>
          <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">ابدأ بإصدار الشهادات للطلاب الذين أتموا الدورات.</p>
          <a href="<?php echo e(route('certificates.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow hover:shadow-lg transition"><i class="ti ti-square-plus mr-2"></i>إصدار أول شهادة</a>
        </div>
        <?php endif; ?>
        <?php if($certificates->hasPages()): ?>
          <div class="mt-6 flex justify-center"><?php echo e($certificates->links()); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#certificates-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'desc']],
        language: { sSearch: 'ابحث:' }
      });
    }
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/certificates/index.blade.php ENDPATH**/ ?>