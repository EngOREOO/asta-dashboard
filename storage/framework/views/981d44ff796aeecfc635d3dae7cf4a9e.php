<?php
    $title = 'تقرير المبيعات';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 bg-clip-text text-transparent">
          تقرير المبيعات
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-chart-line mr-2 text-green-500"></i>
          تحليل شامل لأداء المبيعات والإيرادات
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <button onclick="exportReport()" 
                class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-500/20 transition-all duration-300">
          <i class="ti ti-file-export mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          تصدير Excel
        </button>
        <button onclick="exportPDF()" 
                class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-file-download mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          تصدير PDF
        </button>
      </div>
    </div>

    <!-- Error Display -->
    <?php if(isset($error)): ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6 animate-fade-in">
      <div class="flex items-center">
        <i class="ti ti-alert-circle text-red-500 text-2xl ml-3"></i>
        <div>
          <h3 class="text-lg font-semibold text-red-800">خطأ في تحميل البيانات</h3>
          <p class="text-red-600 mt-1"><?php echo e($error); ?></p>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Advanced Filters -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-filter mr-3"></i>
          فلاتر البحث المتقدمة
        </h3>
      </div>
      
      <div class="p-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
            <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>" 
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
            <input type="date" name="date_to" value="<?php echo e($dateTo); ?>" 
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
            <select name="category_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
              <option value="">جميع الفئات</option>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php echo e($categoryId == $category->id ? 'selected' : ''); ?>>
                  <?php echo e($category->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">المدرب</label>
            <select name="instructor_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
              <option value="">جميع المدربين</option>
              <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($instructor->id); ?>" <?php echo e($instructorId == $instructor->id ? 'selected' : ''); ?>>
                  <?php echo e($instructor->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">نوع السعر</label>
            <select name="price_range" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
              <option value="all" <?php echo e($priceRange == 'all' ? 'selected' : ''); ?>>جميع الدورات</option>
              <option value="paid" <?php echo e($priceRange == 'paid' ? 'selected' : ''); ?>>مدفوعة فقط</option>
              <option value="free" <?php echo e($priceRange == 'free' ? 'selected' : ''); ?>>مجانية فقط</option>
            </select>
          </div>
          
          <div class="flex items-end">
            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-search ml-2"></i>
              تطبيق الفلاتر
            </button>
          </div>
          
          <div class="flex items-end">
            <a href="<?php echo e(route('admin.reports.sales')); ?>" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl text-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-refresh ml-2"></i>
              إعادة تعيين
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي المبيعات</p>
            <p class="text-3xl font-bold text-green-600"><?php echo e(number_format($totalSales, 2)); ?> ريال</p>
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
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-currency-dollar text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي الدورات</p>
            <p class="text-3xl font-bold text-blue-600"><?php echo e($totalCourses); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($paidCourses); ?> مدفوعة</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-book text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">الدورات المدفوعة</p>
            <p class="text-3xl font-bold text-purple-600"><?php echo e($paidCourses); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($totalCourses > 0 ? round(($paidCourses / $totalCourses) * 100, 1) : 0); ?>% من الإجمالي</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-credit-card text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">الدورات المجانية</p>
            <p class="text-3xl font-bold text-orange-600"><?php echo e($freeCourses); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($totalCourses > 0 ? round(($freeCourses / $totalCourses) * 100, 1) : 0); ?>% من الإجمالي</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-gift text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-slide-up">
      <!-- Sales Chart -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-chart-line mr-3"></i>
            المبيعات الشهرية
          </h3>
        </div>
        <div class="p-8">
          <div class="h-80 relative">
            <canvas id="salesChart"></canvas>
            <?php if(empty($chartData['labels']) || empty($chartData['sales'])): ?>
            <div class="absolute inset-0 flex items-center justify-center bg-gray-50 rounded-lg">
              <div class="text-center">
                <i class="ti ti-chart-line text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500 text-lg">لا توجد بيانات مبيعات متاحة</p>
                <p class="text-gray-400 text-sm">تأكد من وجود دورات مدفوعة في الفترة المحددة</p>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Sales by Category -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-chart-pie mr-3"></i>
            المبيعات حسب الفئة
          </h3>
        </div>
        <div class="p-8">
          <div class="h-80">
            <canvas id="categoryChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Courses Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-trophy mr-3"></i>
          أفضل الدورات مبيعاً
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
                <th class="px-6 py-4 text-right font-medium text-gray-900">السعر</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">عدد الطلاب</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الإيرادات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php $__currentLoopData = $topCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo e($index + 1); ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-green-500 to-blue-500 flex items-center justify-center">
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
                    <?php if($course->price > 0): ?>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800">
                        <?php echo e(number_format($course->price, 2)); ?> ريال
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800">
                        مجاني
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">
                      <?php echo e($course->students_count); ?>

                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                    <?php echo e(number_format($course->price * $course->students_count, 2)); ?> ريال
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
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        // Destroy existing chart if it exists
        if (window.salesChartInstance) {
            window.salesChartInstance.destroy();
        }

        const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
        
        // Ensure we have valid data
        if (!chartData.labels || !chartData.sales || chartData.labels.length === 0) {
            console.log('No sales data available for chart');
            return;
        }
        
        window.salesChartInstance = new Chart(salesCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    label: 'المبيعات (ريال)',
                    data: chartData.sales || [],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    pointHoverBackgroundColor: 'rgb(34, 197, 94)',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3,
                    borderWidth: 3,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 2,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'الشهر: ' + context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('ar-SA') + ' ريال';
                            },
                            afterLabel: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed.y / total) * 100).toFixed(1);
                                return 'النسبة: ' + percentage + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'الشهر',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#374151'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'المبيعات (ريال)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#374151'
                        },
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return value.toLocaleString('ar-SA') + ' ريال';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverBackgroundColor: 'rgb(34, 197, 94)',
                        hoverBorderColor: '#fff',
                        hoverBorderWidth: 3
                    }
                }
            }
        });
    }

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        // Destroy existing chart if it exists
        if (window.categoryChartInstance) {
            window.categoryChartInstance.destroy();
        }

        const categoryData = <?php echo json_encode($salesByCategory, 15, 512) ?>;
        
        // Ensure we have valid data
        if (!categoryData || categoryData.length === 0) {
            console.log('No category data available for chart');
            return;
        }
        
        window.categoryChartInstance = new Chart(categoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($salesByCategory->pluck('category_name'), 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($salesByCategory->pluck('total_sales'), 15, 512) ?>,
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(14, 165, 233, 0.8)',
                        'rgba(34, 211, 238, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed.toLocaleString() + ' ريال (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});

// Export functions
function exportReport() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '<?php echo e(route("admin.reports.sales")); ?>?' + params.toString();
}

function exportPDF() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.location.href = '<?php echo e(route("admin.reports.sales")); ?>?' + params.toString();
}

// Chart refresh function for filter changes
function refreshCharts() {
    if (window.salesChartInstance) {
        window.salesChartInstance.destroy();
        window.salesChartInstance = null;
    }
    if (window.categoryChartInstance) {
        window.categoryChartInstance.destroy();
        window.categoryChartInstance = null;
    }
    // Charts will be recreated when page reloads with new filters
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

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/reports/sales/index.blade.php ENDPATH**/ ?>