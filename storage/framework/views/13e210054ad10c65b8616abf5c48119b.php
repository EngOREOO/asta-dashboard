<?php
    $title = 'تقدم الطلاب';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Success/Error Notifications -->
    <?php if(session('success')): ?>
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-check-circle text-green-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="font-medium text-green-800" style="font-size: 1.3rem;"><?php echo e(session('success')); ?></p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-alert-circle text-red-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="font-medium text-red-800" style="font-size: 1.3rem;"><?php echo e(session('error')); ?></p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">
          تتبع تقدم الطلاب
        </h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-progress mr-2 text-cyan-500"></i>
          مراقبة ومتابعة تقدم الطلاب في الدورات التعليمية
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('student-progress.analytics')); ?>" 
           class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 border border-transparent rounded-2xl font-semibold text-white uppercase tracking-widest hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 disabled:opacity-25 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105" style="font-size: 1.3rem;">
          <i class="ti ti-chart-bar mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          التحليلات التفصيلية
        </a>
      </div>
    </div>

    <!-- Student Progress Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-progress text-white text-xl"></i>
            </div>
            قائمة تقدم الطلاب
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">عرض ومتابعة تقدم جميع الطلاب في الدورات</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <?php if($students->count() > 0): ?>
        <div class="table-responsive">
          <table class="table table-hover" id="progress-table">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">#</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الطالب</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الدورات المسجلة</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الدورات المكتملة</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">قيد التقدم</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">التقدم الإجمالي</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">المواد المكتملة</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="hover:bg-gray-50 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($student->id); ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center shadow-sm">
                      <span class="text-white font-medium text-sm">
                        <?php echo e(Str::of($student->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

                      </span>
                    </div>
                  </div>
                  <div class="mr-4">
                    <div class="font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($student->name); ?></div>
                    <div class="text-gray-500 email-content" style="font-size: 1.3rem;"><?php echo e($student->email); ?></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800" style="font-size: 1.3rem;">
                  <?php echo e($student->enrolled_courses_count); ?> دورة
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.3rem;">
                  <?php echo e($student->completed_courses); ?> مكتملة
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-yellow-100 text-yellow-800" style="font-size: 1.3rem;">
                  <?php echo e($student->in_progress_courses); ?> قيد التقدم
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                    <div class="bg-gradient-to-r from-teal-500 to-blue-500 h-2 rounded-full transition-all duration-300" style="width: <?php echo e($student->total_progress); ?>%"></div>
                  </div>
                  <span class="text-sm font-medium text-gray-900" style="font-size: 1.3rem;"><?php echo e($student->total_progress); ?>%</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-purple-100 text-purple-800" style="font-size: 1.3rem;">
                  <?php echo e($student->material_completions_count); ?> مادة
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap font-medium" style="font-size: 1.3rem;">
                <div class="flex items-center space-x-2 space-x-reverse">
                  <a href="<?php echo e(route('student-progress.show', $student)); ?>" 
                     class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" style="font-size: 1.3rem;" title="عرض التفاصيل">
                    <i class="ti ti-eye text-sm"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <?php if($students->hasPages()): ?>
          <div class="mt-6 flex justify-center">
            <?php echo e($students->links()); ?>

          </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="text-center py-12">
          <div class="mb-6">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto">
              <i class="ti ti-progress text-4xl text-blue-500"></i>
            </div>
          </div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا توجد بيانات تقدم للطلاب</h3>
          <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">يحتاج الطلاب إلى التسجيل في الدورات لتتبع التقدم.</p>
          <a href="<?php echo e(route('enrollments.index')); ?>" 
             class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl" style="font-size: 1.3rem;">
            <i class="ti ti-school mr-2"></i>إدارة التسجيلات
          </a>
        </div>
        <?php endif; ?>
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

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-slide-down {
  animation: slideDown 0.4s ease-out;
}

/* Custom scrollbar */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to right, #2563eb, #7c3aed);
}
</style>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#progress-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[5, 'desc']], // Sort by progress column
        language: {
          "sProcessing": "جاري التحميل...",
          "sLengthMenu": "أظهر _MENU_ سجل",
          "sZeroRecords": "لم يعثر على أية سجلات",
          "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل",
          "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
          "sInfoFiltered": "(منتقاة من مجموع _MAX_ سجل)",
          "sInfoPostFix": "",
          "sSearch": "ابحث:",
          "sUrl": "",
          "oPaginate": {
            "sFirst": "الأول",
            "sPrevious": "السابق",
            "sNext": "التالي",
            "sLast": "الأخير"
          }
        }
      });
    }

    // Auto-hide notifications after 5 seconds
    setTimeout(function() {
      const notifications = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
      notifications.forEach(notification => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
      });
    }, 5000);
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/student-progress/index.blade.php ENDPATH**/ ?>