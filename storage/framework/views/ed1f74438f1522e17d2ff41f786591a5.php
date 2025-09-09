<?php ($title = 'تفاصيل المستخدم'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
      <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
            <?php echo e(Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

          </div>
          <div class="min-w-0">
            <h1 class="text-2xl font-bold"><?php echo e($user->name); ?></h1>
            <p class="text-white/80 text-sm text-ltr"><?php echo e($user->email); ?></p>
          </div>
        </div>
        <a href="<?php echo e(route('users.index')); ?>" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">عودة إلى المستخدمين</a>
      </div>
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
      <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- Profile card -->
      <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <div class="space-y-3">
          <div class="text-gray-700">الحالة: <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800">نشط</span></div>
          <div class="text-gray-700">الأدوار:</div>
          <div class="flex flex-wrap gap-2">
            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php ($roleAr = ['admin'=>'مدير','instructor'=>'محاضر','student'=>'طالب','user'=>'مستخدم'][$role->name] ?? $role->name); ?>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800"><?php echo e($roleAr); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <div class="text-gray-700">عدد الدورات: <?php echo e(method_exists($user,'instructorCourses') ? $user->instructorCourses->count() : ($user->courses->count() ?? 0)); ?></div>
        </div>
      </div>

      <!-- Courses table -->
      <div class="xl:col-span-2 bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <h3 class="text-gray-700 font-semibold mb-4">الدورات</h3>
        <div class="admin-table-container">
          <table class="admin-table" id="user-courses-table">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-right">#</th>
                <th class="text-right">العنوان</th>
                <th class="text-right">الدور</th>
                <th class="text-right">الحالة</th>
                <th class="text-right">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php ($courses = method_exists($user,'instructorCourses') ? $user->instructorCourses : $user->courses); ?>
              <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php ($statusAr = ['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'][$course->status] ?? ($course->status ?? 'مسودة')); ?>
              <tr>
                <td class="text-center"><?php echo e($course->id); ?></td>
                <td class="font-medium"><?php echo e($course->title); ?></td>
                <td class="text-rtl"><?php echo e(method_exists($user,'instructorCourses') ? 'محاضر' : 'طالب'); ?></td>
                <td>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium <?php echo e($course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700')); ?>" style="font-size: 1.2rem;"><?php echo e($statusAr); ?></span>
                </td>
                <td class="text-center">
                  <div class="inline-flex items-center gap-2">
                    <a class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" href="<?php echo e(route('courses.show', $course)); ?>" title="عرض"><i class="ti ti-eye text-base"></i></a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $course)): ?>
                    <a class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" href="<?php echo e(route('courses.edit', $course)); ?>" title="تعديل"><i class="ti ti-edit text-base"></i></a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#user-courses-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        language: {
          sProcessing: 'جاري التحميل...',
          sLengthMenu: 'أظهر _MENU_ سجل',
          sZeroRecords: 'لا توجد نتائج',
          sInfo: 'إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل',
          sInfoEmpty: 'يعرض 0 إلى 0 من أصل 0 سجل',
          sInfoFiltered: '(منتقاة من مجموع _MAX_ سجل)',
          sSearch: 'ابحث:',
          oPaginate: { sFirst: 'الأول', sPrevious: 'السابق', sNext: 'التالي', sLast: 'الأخير' }
        }
      });
    }
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/users/show.blade.php ENDPATH**/ ?>