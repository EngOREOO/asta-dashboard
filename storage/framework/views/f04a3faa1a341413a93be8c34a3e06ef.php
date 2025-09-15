<?php
    $title = 'تقرير التسجيلات';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-700 bg-clip-text text-transparent">
          تقرير التسجيلات
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-users mr-2 text-blue-500"></i>
          تحليل شامل لتسجيلات الطلاب في الدورات
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <button onclick="exportReport()" 
                class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-file-export mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          تصدير Excel
        </button>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="text-2xl font-bold text-white flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-filter text-white text-xl"></i>
            </div>
            فلترة التقرير
          </h2>
        </div>
      </div>
      
      <div class="p-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
            <input type="date" 
                   id="start_date" 
                   name="start_date" 
                   value="<?php echo e($startDate->format('Y-m-d')); ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm">
          </div>
          
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
            <input type="date" 
                   id="end_date" 
                   name="end_date" 
                   value="<?php echo e($endDate->format('Y-m-d')); ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm">
          </div>
          
          <div class="flex items-end">
            <button type="submit" 
                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl hover:from-blue-600 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="ti ti-search mr-2"></i>
              تطبيق المرشح
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي التسجيلات</p>
            <p class="text-3xl font-bold text-blue-600"><?php echo e(number_format($totalEnrollments)); ?></p>
            <?php if($growthRate > 0): ?>
              <p class="text-sm text-green-600 flex items-center mt-1">
                <i class="ti ti-trending-up mr-1"></i>
                +<?php echo e(number_format($growthRate, 1)); ?>%
              </p>
            <?php elseif($growthRate < 0): ?>
              <p class="text-sm text-red-600 flex items-center mt-1">
                <i class="ti ti-trending-down mr-1"></i>
                <?php echo e(number_format($growthRate, 1)); ?>%
              </p>
            <?php endif; ?>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-users text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">أعلى دورة تسجيلاً</p>
            <p class="text-lg font-bold text-purple-600"><?php echo e($topCourses->first()->title ?? 'غير متاح'); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($topCourses->first()->students_count ?? 0); ?> طالب</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-trophy text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">متوسط التسجيلات/دورة</p>
            <p class="text-3xl font-bold text-green-600"><?php echo e($totalEnrollments > 0 ? round($totalEnrollments / max($topCourses->count(), 1), 1) : 0); ?></p>
            <p class="text-sm text-gray-500 mt-1">طالب لكل دورة</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-chart-bar text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-slide-up">
      <!-- Enrollments Chart -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-chart-line mr-3"></i>
            التسجيلات الشهرية
          </h3>
        </div>
        <div class="p-8">
          <canvas id="enrollmentsChart" width="400" height="200"></canvas>
        </div>
      </div>

      <!-- Enrollments by Category -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-chart-pie mr-3"></i>
            التسجيلات حسب الفئة
          </h3>
        </div>
        <div class="p-8">
          <canvas id="categoryChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Top Courses Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-trophy mr-3"></i>
          أكثر الدورات تسجيلاً
        </h3>
      </div>
      
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-900">#</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">اسم الدورة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الفئة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">المدرّس</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">عدد التسجيلات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php $__currentLoopData = $topCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo e($index + 1); ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center">
                          <i class="ti ti-book text-white text-sm"></i>
                        </div>
                      </div>
                      <div class="mr-4">
                        <div class="font-medium text-gray-900"><?php echo e($course->title); ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <?php echo e($course->category->name ?? 'غير محدد'); ?>

                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <?php echo e($course->instructor->name ?? 'غير محدد'); ?>

                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                      <?php echo e($course->students_count); ?> طالب
                    </span>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Enrollments Chart
const enrollmentsCtx = document.getElementById('enrollmentsChart').getContext('2d');
new Chart(enrollmentsCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($chartData['labels'], 15, 512) ?>,
        datasets: [{
            label: 'التسجيلات',
            data: <?php echo json_encode($chartData['enrollments'], 15, 512) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($enrollmentsByCategory->pluck('category_name'), 15, 512) ?>,
        datasets: [{
            data: <?php echo json_encode($enrollmentsByCategory->pluck('enrollment_count'), 15, 512) ?>,
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(14, 165, 233, 0.8)',
                'rgba(34, 211, 238, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

function exportReport() {
    window.location.href = '<?php echo e(route("admin.reports.enrollments")); ?>?export=excel&start_date=<?php echo e($startDate->format("Y-m-d")); ?>&end_date=<?php echo e($endDate->format("Y-m-d")); ?>';
}
</script>

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

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/reports/enrollments/index.blade.php ENDPATH**/ ?>