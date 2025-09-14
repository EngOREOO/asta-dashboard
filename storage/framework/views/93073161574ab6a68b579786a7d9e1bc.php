<?php
    $title = 'بنك الأسئلة';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">بنك الأسئلة</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">إدارة جميع الأسئلة في النظام</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('question-bank.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-2xl hover:from-teal-600 hover:to-cyan-700 shadow-lg transition-all duration-300" style="font-size: 1.3rem;">
          <i class="ti ti-plus mr-2"></i>
          إضافة سؤال جديد
        </a>
      </div>
    </div>

    <?php if(session('success')): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl" style="font-size: 1.3rem;">
        <?php echo e(session('success')); ?>

      </div>
    <?php endif; ?>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-database text-white text-xl"></i>
            </div>
            قائمة الأسئلة
          </h2>
        </div>
      </div>

      <div class="p-8">
        <?php if($questions->count() > 0): ?>
          <div class="overflow-x-auto">
            <table class="w-full text-right" style="font-size: 1.3rem;">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">#</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">نص السؤال</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">النوع</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الدرجات</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">المستوى</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">التقييم</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">تاريخ الإنشاء</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الإجراءات</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4 text-gray-700"><?php echo e($questions->firstItem() + $index); ?></td>
                  <td class="px-6 py-4">
                    <div class="max-w-xs truncate" title="<?php echo e($question->question); ?>">
                      <?php echo e(Str::limit($question->question, 50)); ?>

                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <?php
                      $typeLabels = [
                        'mcq' => 'اختيارات متعددة',
                        'text' => 'نصي',
                      ];
                      $typeLabel = $typeLabels[$question->type] ?? $question->type;
                    ?>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                      <?php echo e($question->type === 'mcq' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                      <?php echo e($typeLabel); ?>

                    </span>
                  </td>
                  <td class="px-6 py-4 text-gray-700"><?php echo e($question->points ?? '-'); ?></td>
                  <td class="px-6 py-4">
                    <?php if($question->difficulty): ?>
                      <?php
                        $difficultyLabels = [
                          'easy' => 'سهل',
                          'medium' => 'متوسط',
                          'hard' => 'صعب',
                        ];
                        $difficultyLabel = $difficultyLabels[$question->difficulty] ?? $question->difficulty;
                        $difficultyColors = [
                          'easy' => 'bg-green-100 text-green-800',
                          'medium' => 'bg-yellow-100 text-yellow-800',
                          'hard' => 'bg-red-100 text-red-800',
                        ];
                        $difficultyColor = $difficultyColors[$question->difficulty] ?? 'bg-gray-100 text-gray-800';
                      ?>
                      <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($difficultyColor); ?>">
                        <?php echo e($difficultyLabel); ?>

                      </span>
                    <?php else: ?>
                      <span class="text-gray-400">-</span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4">
                    <?php if($question->assessment): ?>
                      <a href="<?php echo e(route('assessments.show', $question->assessment)); ?>" class="text-blue-600 hover:text-blue-800">
                        <?php echo e($question->assessment->title); ?>

                      </a>
                    <?php else: ?>
                      <span class="text-gray-400">بنك الأسئلة</span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 text-gray-500"><?php echo e($question->created_at->format('Y-m-d')); ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-2 space-x-reverse">
                      <a href="<?php echo e(route('question-bank.show', $question)); ?>" class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors duration-200" style="font-size: 1.1rem;">
                        <i class="ti ti-eye mr-1"></i>
                        عرض
                      </a>
                      <a href="<?php echo e(route('question-bank.edit', $question)); ?>" class="inline-flex items-center px-3 py-2 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-xl hover:bg-yellow-100 transition-colors duration-200" style="font-size: 1.1rem;">
                        <i class="ti ti-edit mr-1"></i>
                        تعديل
                      </a>
                      <form method="POST" action="<?php echo e(route('question-bank.destroy', $question)); ?>" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition-colors duration-200" style="font-size: 1.1rem;">
                          <i class="ti ti-trash mr-1"></i>
                          حذف
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>

          <div class="mt-6">
            <?php echo e($questions->links()); ?>

          </div>
        <?php else: ?>
          <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="ti ti-database text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2" style="font-size: 1.5rem;">لا توجد أسئلة بعد</h3>
            <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">ابدأ بإضافة أول سؤال إلى بنك الأسئلة</p>
            <a href="<?php echo e(route('question-bank.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-2xl hover:from-teal-600 hover:to-cyan-700 shadow-lg transition-all duration-300" style="font-size: 1.3rem;">
              <i class="ti ti-plus mr-2"></i>
              إضافة سؤال جديد
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/question-bank/index.blade.php ENDPATH**/ ?>