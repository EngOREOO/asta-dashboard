<?php
  $title = 'تحليلات الاختبار';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تحليلات الاختبار</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-chart-line mr-2 text-cyan-500"></i>
          إحصائيات مفصلة عن أداء الاختبار
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
        <a href="<?php echo e(route('quizzes.show', $quiz)); ?>" class="group inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" style="font-size: 1.2rem;">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للاختبار
        </a>
      </div>
    </div>

    <!-- Quiz Info Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10 flex items-center justify-between">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-help-circle text-white text-xl"></i>
            </div>
            <div>
              <h2 class="font-bold text-white" style="font-size: 1.9rem;"><?php echo e($quiz->title); ?></h2>
              <p class="text-white/80" style="font-size: 1.2rem;"><?php echo e($quiz->description ?? 'لا يوجد وصف'); ?></p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-3xl font-bold text-white mb-1"><?php echo e($totalAttempts); ?></div>
            <div class="text-white/80" style="font-size: 1.1rem;">إجمالي المحاولات</div>
          </div>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-6">
        <div class="flex flex-wrap gap-4">
          <div class="flex items-center px-4 py-2 bg-blue-50 rounded-xl border border-blue-200">
            <i class="ti ti-clock text-blue-600 mr-2"></i>
            <span class="text-blue-800 font-semibold" style="font-size: 1.2rem;">
              <?php echo e($quiz->time_limit ? $quiz->time_limit . ' دقيقة' : 'بدون حد زمني'); ?>

            </span>
          </div>
          <div class="flex items-center px-4 py-2 bg-yellow-50 rounded-xl border border-yellow-200">
            <i class="ti ti-percentage text-yellow-600 mr-2"></i>
            <span class="text-yellow-800 font-semibold" style="font-size: 1.2rem;">
              درجة النجاح: <?php echo e($quiz->passing_score ?? 0); ?>%
            </span>
          </div>
          <div class="flex items-center px-4 py-2 bg-green-50 rounded-xl border border-green-200">
            <i class="ti ti-list text-green-600 mr-2"></i>
            <span class="text-green-800 font-semibold" style="font-size: 1.2rem;">
              <?php echo e($quiz->questions()->count()); ?> أسئلة
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white/70 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 animate-slide-up">
        <div class="p-6 text-center">
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-users text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e(number_format($totalAttempts)); ?></h3>
          <p class="text-gray-600 font-semibold" style="font-size: 1.2rem;">إجمالي المحاولات</p>
        </div>
      </div>
      
      <div class="bg-white/70 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="p-6 text-center">
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-check-circle text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e(number_format($completedAttempts)); ?></h3>
          <p class="text-gray-600 font-semibold" style="font-size: 1.2rem;">المحاولات المكتملة</p>
        </div>
      </div>
      
      <div class="bg-white/70 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="p-6 text-center">
          <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-percentage text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e(number_format($averageScore, 1)); ?>%</h3>
          <p class="text-gray-600 font-semibold" style="font-size: 1.2rem;">متوسط الدرجات</p>
        </div>
      </div>
      
      <div class="bg-white/70 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="p-6 text-center">
          <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-trophy text-white text-2xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e(number_format($passRate, 1)); ?>%</h3>
          <p class="text-gray-600 font-semibold" style="font-size: 1.2rem;">معدل النجاح</p>
        </div>
      </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Score Distribution Chart -->
      <div class="lg:col-span-2">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-chart-bar text-white"></i>
                </div>
                توزيع الدرجات
              </h3>
            </div>
          </div>
          
          <div class="p-8">
            
            <div class="grid grid-cols-5 gap-4 mb-6">
              <?php $__currentLoopData = $scoreRanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                <div class="text-2xl font-bold text-gray-800 mb-1"><?php echo e($count); ?></div>
                <div class="text-sm text-gray-600 font-semibold"><?php echo e($range); ?>%</div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <?php if($attempts->count() > 0): ?>
            <div class="space-y-2">
              <div class="text-sm font-semibold text-gray-700 mb-2">توزيع النسب</div>
              <div class="flex h-3 bg-gray-200 rounded-full overflow-hidden">
                <?php
                  $total = $attempts->count();
                  $widths = array_map(function($count) use ($total) {
                    return $total > 0 ? ($count / $total) * 100 : 0;
                  }, $scoreRanges);
                ?>
                <div class="bg-gradient-to-r from-red-500 to-red-600" style="width: <?php echo e($widths['0-20']); ?>%"></div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600" style="width: <?php echo e($widths['21-40']); ?>%"></div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600" style="width: <?php echo e($widths['41-60']); ?>%"></div>
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600" style="width: <?php echo e($widths['61-80']); ?>%"></div>
                <div class="bg-gradient-to-r from-green-500 to-green-600" style="width: <?php echo e($widths['81-100']); ?>%"></div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      
      <!-- Recent Attempts -->
      <div class="lg:col-span-1">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.4rem;">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                  <i class="ti ti-activity text-white text-sm"></i>
                </div>
                المحاولات الأخيرة
              </h3>
            </div>
          </div>
          
          <div class="p-6">
            <?php
              $recentAttempts = $quiz->attempts()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
            ?>
            
            <?php if($recentAttempts->count() > 0): ?>
              <div class="space-y-4">
                <?php $__currentLoopData = $recentAttempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
                  <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-white font-bold text-sm">
                      <?php echo e(Str::of($attempt->user->name ?? 'مستخدم')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <div class="font-semibold text-gray-800" style="font-size: 1.1rem;"><?php echo e($attempt->user->name ?? 'مستخدم'); ?></div>
                    <div class="flex items-center gap-2 text-sm">
                      <span class="font-semibold text-gray-600"><?php echo e($attempt->score ?? 0); ?>%</span>
                      <?php if($attempt->is_passed): ?>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-lg text-xs font-semibold">نجح</span>
                      <?php else: ?>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-lg text-xs font-semibold">فشل</span>
                      <?php endif; ?>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                      <?php echo e($attempt->created_at->diffForHumans()); ?>

                    </div>
                  </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            <?php else: ?>
              <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i class="ti ti-activity text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-semibold" style="font-size: 1.2rem;">لا توجد محاولات حديثة</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Question Analytics -->
    <?php if($questionAnalytics->count() > 0): ?>
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-list-check text-white"></i>
            </div>
            تحليل الأسئلة
          </h3>
        </div>
      </div>
      
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="text-right py-4 px-4 font-bold text-gray-800" style="font-size: 1.3rem;">السؤال</th>
                <th class="text-right py-4 px-4 font-bold text-gray-800" style="font-size: 1.3rem;">نوع السؤال</th>
                <th class="text-right py-4 px-4 font-bold text-gray-800" style="font-size: 1.3rem;">الصعوبة</th>
                <th class="text-right py-4 px-4 font-bold text-gray-800" style="font-size: 1.3rem;">معدل الإجابة الصحيحة</th>
                <th class="text-right py-4 px-4 font-bold text-gray-800" style="font-size: 1.3rem;">النقاط</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $questionAnalytics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                <td class="py-4 px-4">
                  <div class="font-semibold text-gray-800" style="font-size: 1.2rem;">
                    <?php echo e(Str::limit($item['question']->question, 50)); ?>

                  </div>
                </td>
                <td class="py-4 px-4">
                  <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-semibold text-sm">
                    <?php switch($item['question']->type):
                      case ('multiple_choice'): ?>
                        اختيارات متعددة
                        <?php break; ?>
                      <?php case ('multiple_choice_two'): ?>
                        اختيارات متعددة (إجابتان)
                        <?php break; ?>
                      <?php case ('true_false'): ?>
                        صح أو خطأ
                        <?php break; ?>
                      <?php case ('text'): ?>
                        نص حر
                        <?php break; ?>
                      <?php default: ?>
                        <?php echo e($item['question']->type); ?>

                    <?php endswitch; ?>
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-lg font-semibold text-sm
                    <?php echo e($item['difficulty'] === 'Easy' ? 'bg-green-100 text-green-800' : 
                       ($item['difficulty'] === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                    <?php echo e($item['difficulty']); ?>

                  </span>
                </td>
                <td class="py-4 px-4">
                  <div class="flex items-center">
                    <div class="w-16 h-2 bg-gray-200 rounded-full mr-3 overflow-hidden">
                      <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full" style="width: <?php echo e($item['correct_rate']); ?>%"></div>
                    </div>
                    <span class="font-semibold text-gray-700" style="font-size: 1.1rem;"><?php echo e(number_format($item['correct_rate'], 1)); ?>%</span>
                  </div>
                </td>
                <td class="py-4 px-4">
                  <span class="font-bold text-gray-800" style="font-size: 1.2rem;"><?php echo e($item['question']->points ?? 1); ?></span>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Performance Trends -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-trending-up text-white"></i>
            </div>
            اتجاهات الأداء
          </h3>
        </div>
      </div>
      
      <div class="p-8">
        <?php
          $weeklyAttempts = $quiz->attempts()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, AVG(score) as avg_score')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        ?>
        
        <?php if($weeklyAttempts->count() > 0): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div>
            <h4 class="font-bold text-gray-800 mb-4 flex items-center" style="font-size: 1.4rem;">
              <i class="ti ti-calendar mr-2 text-cyan-500"></i>
              المحاولات اليومية (آخر 30 يوم)
            </h4>
            <div class="space-y-3">
              <?php $__currentLoopData = $weeklyAttempts->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                <div class="font-semibold text-gray-800" style="font-size: 1.2rem;"><?php echo e(\Carbon\Carbon::parse($day->date)->format('Y-m-d')); ?></div>
                <div class="flex items-center gap-4">
                  <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-semibold text-sm"><?php echo e($day->count); ?></span>
                  <span class="font-semibold text-gray-700" style="font-size: 1.1rem;"><?php echo e(number_format($day->avg_score ?? 0, 1)); ?>%</span>
                </div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
          
          <div>
            <h4 class="font-bold text-gray-800 mb-4 flex items-center" style="font-size: 1.4rem;">
              <i class="ti ti-chart-pie mr-2 text-cyan-500"></i>
              ملخص الأداء
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                <div class="text-2xl font-bold text-blue-800 mb-1"><?php echo e($weeklyAttempts->sum('count')); ?></div>
                <div class="text-blue-600 font-semibold text-sm">إجمالي المحاولات</div>
              </div>
              <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                <div class="text-2xl font-bold text-green-800 mb-1"><?php echo e(number_format($weeklyAttempts->avg('avg_score'), 1)); ?>%</div>
                <div class="text-green-600 font-semibold text-sm">متوسط الدرجات</div>
              </div>
              <div class="text-center p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl border border-cyan-200">
                <div class="text-2xl font-bold text-cyan-800 mb-1"><?php echo e($weeklyAttempts->count()); ?></div>
                <div class="text-cyan-600 font-semibold text-sm">أيام النشاط</div>
              </div>
              <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                <div class="text-2xl font-bold text-yellow-800 mb-1"><?php echo e(number_format($weeklyAttempts->avg('count'), 1)); ?></div>
                <div class="text-yellow-600 font-semibold text-sm">متوسط المحاولات/يوم</div>
              </div>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
          <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ti ti-chart-line text-gray-400 text-3xl"></i>
          </div>
          <p class="text-gray-500 font-semibold" style="font-size: 1.3rem;">لا توجد بيانات كافية لعرض الاتجاهات</p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Individual User Performance -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-users text-white"></i>
            </div>
            أداء المستخدمين
          </h3>
        </div>
      </div>
      
      <div class="p-8">
        <?php
          $userPerformance = $quiz->attempts()
            ->with('user')
            ->whereNotNull('completed_at')
            ->get()
            ->groupBy('user_id')
            ->map(function ($attempts) {
              $user = $attempts->first()->user;
              $totalAttempts = $attempts->count();
              $bestScore = $attempts->max('score');
              $averageScore = $attempts->avg('score');
              $passedAttempts = $attempts->where('is_passed', true)->count();
              $lastAttempt = $attempts->sortByDesc('created_at')->first();
              
              return [
                'user' => $user,
                'totalAttempts' => $totalAttempts,
                'bestScore' => $bestScore,
                'averageScore' => $averageScore,
                'passedAttempts' => $passedAttempts,
                'passRate' => $totalAttempts > 0 ? ($passedAttempts / $totalAttempts) * 100 : 0,
                'lastAttempt' => $lastAttempt,
                'firstAttempt' => $attempts->sortBy('created_at')->first(),
              ];
            })
            ->sortByDesc('averageScore');
        ?>
        
        <?php if($userPerformance->count() > 0): ?>
        <div class="space-y-4">
          <?php $__currentLoopData = $userPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $performance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-lg">
                    <?php echo e(Str::of($performance['user']->name ?? 'مستخدم')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

                  </span>
                </div>
                <div>
                  <h4 class="font-bold text-gray-800" style="font-size: 1.4rem;"><?php echo e($performance['user']->name ?? 'مستخدم'); ?></h4>
                  <p class="text-gray-600" style="font-size: 1.1rem;"><?php echo e($performance['user']->email ?? 'لا يوجد إيميل'); ?></p>
                </div>
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold text-gray-800"><?php echo e(number_format($performance['averageScore'], 1)); ?>%</div>
                <div class="text-gray-600 text-sm">متوسط الدرجات</div>
              </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
              <div class="text-center p-3 bg-white rounded-xl border border-gray-200">
                <div class="text-lg font-bold text-blue-600"><?php echo e($performance['totalAttempts']); ?></div>
                <div class="text-sm text-gray-600">إجمالي المحاولات</div>
              </div>
              <div class="text-center p-3 bg-white rounded-xl border border-gray-200">
                <div class="text-lg font-bold text-green-600"><?php echo e(number_format($performance['bestScore'], 1)); ?>%</div>
                <div class="text-sm text-gray-600">أفضل درجة</div>
              </div>
              <div class="text-center p-3 bg-white rounded-xl border border-gray-200">
                <div class="text-lg font-bold text-yellow-600"><?php echo e($performance['passedAttempts']); ?></div>
                <div class="text-sm text-gray-600">محاولات ناجحة</div>
              </div>
              <div class="text-center p-3 bg-white rounded-xl border border-gray-200">
                <div class="text-lg font-bold <?php echo e($performance['passRate'] >= 70 ? 'text-green-600' : ($performance['passRate'] >= 50 ? 'text-yellow-600' : 'text-red-600')); ?>">
                  <?php echo e(number_format($performance['passRate'], 1)); ?>%
                </div>
                <div class="text-sm text-gray-600">معدل النجاح</div>
              </div>
            </div>
            
            <div class="flex items-center justify-between text-sm text-gray-600">
              <div class="flex items-center">
                <i class="ti ti-calendar mr-1"></i>
                أول محاولة: <?php echo e($performance['firstAttempt']->created_at->format('Y-m-d')); ?>

              </div>
              <div class="flex items-center">
                <i class="ti ti-clock mr-1"></i>
                آخر محاولة: <?php echo e($performance['lastAttempt']->created_at->format('Y-m-d')); ?>

              </div>
            </div>
            
            <!-- Progress Bar for Pass Rate -->
            <div class="mt-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">معدل النجاح</span>
                <span class="text-sm font-semibold text-gray-700"><?php echo e(number_format($performance['passRate'], 1)); ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full <?php echo e($performance['passRate'] >= 70 ? 'bg-gradient-to-r from-green-500 to-green-600' : ($performance['passRate'] >= 50 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-red-500 to-red-600')); ?>" 
                     style="width: <?php echo e($performance['passRate']); ?>%"></div>
              </div>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
          <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ti ti-users text-gray-400 text-3xl"></i>
          </div>
          <p class="text-gray-500 font-semibold" style="font-size: 1.3rem;">لا توجد محاولات مكتملة من المستخدمين</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/quizzes/analytics.blade.php ENDPATH**/ ?>