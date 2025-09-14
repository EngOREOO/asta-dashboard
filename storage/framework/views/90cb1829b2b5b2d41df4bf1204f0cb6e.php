<?php
  $title = 'إدارة أسئلة الاختبار';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إدارة أسئلة الاختبار</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-clipboard-check mr-2 text-cyan-500"></i>
          <?php echo e($quiz->title); ?>

        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
        <button type="button" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
          <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
          إضافة سؤال جديد
        </button>
        <a href="<?php echo e(route('quizzes.show', $quiz)); ?>" class="group inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" style="font-size: 1.2rem;">
          <i class="ti ti-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          عرض الاختبار
        </a>
        <a href="<?php echo e(route('quizzes.index')); ?>" class="group inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" style="font-size: 1.2rem;">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للقائمة
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Questions Content -->
      <div class="lg:col-span-2">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
            <div class="relative z-10">
              <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
                  <i class="ti ti-help-circle text-white text-xl"></i>
                </div>
                أسئلة الاختبار
              </h2>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
          </div>

          <div class="p-8">
            <?php if($quiz->questions->count() > 0): ?>
              <div class="space-y-4">
                <?php $__currentLoopData = $quiz->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                  <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                      <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-4">
                          <span class="text-white font-bold text-sm">Q<?php echo e($question->order); ?></span>
                        </div>
                        <div class="flex-grow">
                          <h3 class="font-semibold text-gray-900 mb-2" style="font-size: 1.3rem;"><?php echo e(Str::limit($question->question, 80)); ?></h3>
                          <div class="flex items-center space-x-3 space-x-reverse">
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-semibold" style="font-size: 1.1rem;">
                              <i class="ti ti-star mr-1"></i>
                              <?php echo e($question->points); ?> نقاط
                            </span>
                            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-lg font-semibold" style="font-size: 1.1rem;">
                              <i class="ti ti-category mr-1"></i>
                              <?php echo e(ucfirst(str_replace('_', ' ', $question->type))); ?>

                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="flex items-center space-x-2 space-x-reverse">
                        <a href="<?php echo e(route('quizzes.questions.edit', [$quiz, $question])); ?>" class="group inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-xl hover:bg-gray-100 hover:scale-110 transition-all duration-200" title="تعديل">
                          <i class="ti ti-edit group-hover:scale-110 transition-transform duration-200"></i>
                        </a>
                        <form action="<?php echo e(route('quizzes.questions.destroy', [$quiz, $question])); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                          <?php echo csrf_field(); ?>
                          <?php echo method_field('DELETE'); ?>
                          <button type="submit" class="group inline-flex items-center p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 hover:scale-110 transition-all duration-200" title="حذف">
                            <i class="ti ti-trash group-hover:scale-110 transition-transform duration-200"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                    
                    <!-- Question Details (Collapsible) -->
                    <div class="border-t border-gray-200 pt-4">
                      <button class="flex items-center justify-between w-full text-right text-gray-600 hover:text-gray-900 transition-colors duration-200" onclick="toggleQuestionDetails(<?php echo e($question->id); ?>)">
                        <span class="font-medium" style="font-size: 1.2rem;">عرض التفاصيل</span>
                        <i class="ti ti-chevron-down transition-transform duration-200" id="chevron-<?php echo e($question->id); ?>"></i>
                      </button>
                      
                      <div id="details-<?php echo e($question->id); ?>" class="mt-4 hidden">
                        <div class="bg-gray-50 p-4 rounded-xl">
                          <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-2 flex items-center" style="font-size: 1.2rem;">
                              <i class="ti ti-file-text mr-2 text-blue-500"></i>
                              نص السؤال
                            </h4>
                            <p class="text-gray-700 leading-relaxed" style="font-size: 1.3rem;"><?php echo e($question->question); ?></p>
                            
                            <?php if($question->image): ?>
                            <div class="mt-3">
                              <img src="<?php echo e(asset('storage/' . $question->image)); ?>" alt="صورة السؤال" class="rounded-xl shadow-sm" style="max-height: 200px;">
                            </div>
                            <?php endif; ?>
                          </div>
                          
                          <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center" style="font-size: 1.2rem;">
                              <i class="ti ti-list mr-2 text-green-500"></i>
                              الإجابات
                            </h4>
                            
                            <?php if($question->type === 'multiple_choice'): ?>
                              <div class="space-y-2">
                                <?php $__currentLoopData = $question->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center p-3 bg-white rounded-xl border <?php echo e($answer->is_correct ? 'border-green-200 bg-green-50' : 'border-gray-200'); ?>">
                                  <div class="flex items-center mr-3">
                                    <?php if($answer->is_correct): ?>
                                      <i class="ti ti-check-circle text-green-500 text-lg"></i>
                                    <?php else: ?>
                                      <i class="ti ti-circle text-gray-400 text-lg"></i>
                                    <?php endif; ?>
                                  </div>
                                  <div class="flex-grow">
                                    <p class="text-gray-700 font-medium" style="font-size: 1.2rem;"><?php echo e($answer->answer_text); ?></p>
                                    <?php if($answer->feedback): ?>
                                      <p class="text-gray-500 text-sm mt-1"><?php echo e($answer->feedback); ?></p>
                                    <?php endif; ?>
                                  </div>
                                  <?php if($answer->is_correct): ?>
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded-lg font-semibold text-sm">
                                      <i class="ti ti-check mr-1"></i>
                                      صحيح
                                    </span>
                                  <?php endif; ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </div>
                            <?php elseif($question->type === 'true_false'): ?>
                              <div class="p-4 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center">
                                  <i class="ti ti-check-circle text-green-500 text-lg mr-3"></i>
                                  <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-lg font-semibold" style="font-size: 1.2rem;">
                                    <?php echo e($question->correctAnswers->first()->answer_text === 'true' ? 'صح' : 'خطأ'); ?>

                                  </span>
                                </div>
                              </div>
                            <?php else: ?>
                              <div class="p-4 bg-white rounded-xl border border-gray-200">
                                <p class="text-gray-700 font-medium" style="font-size: 1.2rem;"><?php echo e($question->correctAnswers->first()->answer_text ?? 'لم يتم توفير إجابة'); ?></p>
                              </div>
                            <?php endif; ?>
                          </div>
                          
                          <?php if($question->explanation): ?>
                          <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-2 flex items-center" style="font-size: 1.2rem;">
                              <i class="ti ti-info-circle mr-2 text-purple-500"></i>
                              الشرح
                            </h4>
                            <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl">
                              <p class="text-gray-700 leading-relaxed" style="font-size: 1.2rem;"><?php echo e($question->explanation); ?></p>
                            </div>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            <?php else: ?>
              <!-- Empty State -->
              <div class="text-center py-16">
                <div class="mb-8">
                  <div class="w-24 h-24 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-help-circle text-4xl text-teal-600"></i>
                  </div>
                  <h3 class="text-2xl font-bold text-gray-900 mb-4">لا توجد أسئلة بعد</h3>
                  <p class="text-gray-600 mb-8 max-w-md mx-auto" style="font-size: 1.3rem;">
                    ابدأ ببناء اختبارك من خلال إضافة الأسئلة المختلفة.
                  </p>
                  <button type="button" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                    <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                    إضافة أول سؤال
                  </button>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Quiz Summary -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-chart-bar text-white text-lg"></i>
                </div>
                ملخص الاختبار
              </h3>
            </div>
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
              <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                  <i class="ti ti-help-circle text-2xl text-blue-600"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1"><?php echo e($quiz->questions->count()); ?></h4>
                <p class="text-gray-600 font-medium" style="font-size: 1.2rem;">إجمالي الأسئلة</p>
              </div>
              <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-100 to-green-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                  <i class="ti ti-star text-2xl text-green-600"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1"><?php echo e($quiz->questions->sum('points')); ?></h4>
                <p class="text-gray-600 font-medium" style="font-size: 1.2rem;">إجمالي النقاط</p>
              </div>
            </div>
            
            <?php if($quiz->questions->count() > 0): ?>
            <div class="bg-gray-50 p-4 rounded-xl">
              <h4 class="text-gray-700 font-semibold mb-3 flex items-center" style="font-size: 1.2rem;">
                <i class="ti ti-category mr-2 text-purple-500"></i>
                أنواع الأسئلة
              </h4>
              <div class="space-y-2">
                <?php
                  $types = $quiz->questions->groupBy('type');
                ?>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $questions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600" style="font-size: 1.1rem;"><?php echo e(ucfirst(str_replace('_', ' ', $type))); ?>:</span>
                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-lg font-semibold" style="font-size: 1.1rem;">
                      <?php echo e($questions->count()); ?>

                    </span>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-teal-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-bolt text-white text-lg"></i>
                </div>
                إجراءات سريعة
              </h3>
            </div>
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
          </div>

          <div class="p-6">
            <div class="space-y-3">
              <button type="button" class="w-full group inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.2rem;" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                إضافة سؤال
              </button>
              
              <?php if($quiz->questions->count() > 0): ?>
              <a href="<?php echo e(route('quizzes.attempts', $quiz)); ?>" class="w-full group inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-4 focus:ring-green-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.2rem;">
                <i class="ti ti-users mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                عرض المحاولات
              </a>
              <a href="<?php echo e(route('quizzes.analytics', $quiz)); ?>" class="w-full group inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.2rem;">
                <i class="ti ti-chart-bar mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                التحليلات
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-white/95 backdrop-blur-xl border-0 shadow-2xl">
      <form action="<?php echo e(route('quizzes.questions.store', $quiz)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-header bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white border-0">
          <h5 class="modal-title font-bold flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-plus text-white text-lg"></i>
            </div>
            إضافة سؤال جديد
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-8" style="font-size: 1.3rem;">
          <div class="space-y-6">
            <!-- Question Text -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200">
              <label for="question" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-help-circle mr-2 text-blue-500"></i>
                نص السؤال *
              </label>
              <textarea class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" id="question" name="question" rows="4" required placeholder="اكتب نص السؤال هنا..."></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Question Type -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
                <label for="type" class="block text-gray-700 mb-3 font-semibold flex items-center">
                  <i class="ti ti-list mr-2 text-blue-500"></i>
                  نوع السؤال *
                </label>
                <select class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" id="type" name="type" required onchange="toggleAnswerOptions()">
                  <option value="">اختر النوع</option>
                  <option value="multiple_choice">اختيارات متعددة</option>
                  <option value="true_false">صح/خطأ</option>
                  <option value="short_answer">إجابة قصيرة</option>
                  <option value="essay">مقال</option>
                </select>
              </div>
              
              <!-- Points -->
              <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
                <label for="points" class="block text-gray-700 mb-3 font-semibold flex items-center">
                  <i class="ti ti-star mr-2 text-green-500"></i>
                  النقاط *
                </label>
                <input type="number" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" id="points" name="points" value="1" min="1" required placeholder="مثال: 5">
              </div>
            </div>
            
            <!-- Question Image -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200">
              <label for="image" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-photo mr-2 text-purple-500"></i>
                صورة السؤال (اختياري)
              </label>
              <input type="file" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" id="image" name="image" accept="image/*">
            </div>
            
            <!-- Explanation -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-6 rounded-2xl border border-orange-200">
              <label for="explanation" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-info-circle mr-2 text-orange-500"></i>
                الشرح (اختياري)
              </label>
              <textarea class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" id="explanation" name="explanation" rows="3" placeholder="اكتب شرح السؤال هنا..."></textarea>
            </div>
            
            <!-- Answers Container -->
            <div id="answersContainer" class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200">
              <label class="block text-gray-700 mb-4 font-semibold flex items-center">
                <i class="ti ti-list mr-2 text-gray-500"></i>
                الإجابات *
              </label>
              
              <div id="multipleChoiceAnswers" style="display: none;">
                <div class="space-y-3">
                  <div class="answer-option bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                      <input class="w-5 h-5 text-blue-600 focus:ring-blue-500" type="checkbox" name="answers[0][is_correct]" value="1">
                      <input type="text" class="flex-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" name="answers[0][text]" placeholder="خيار الإجابة">
                      <input type="text" class="flex-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" name="answers[0][feedback]" placeholder="التعليق (اختياري)">
                    </div>
                  </div>
                  <div class="answer-option bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                      <input class="w-5 h-5 text-blue-600 focus:ring-blue-500" type="checkbox" name="answers[1][is_correct]" value="1">
                      <input type="text" class="flex-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" name="answers[1][text]" placeholder="خيار الإجابة">
                      <input type="text" class="flex-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" name="answers[1][feedback]" placeholder="التعليق (اختياري)">
                    </div>
                  </div>
                </div>
                <button type="button" class="mt-4 group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl hover:from-blue-600 hover:to-cyan-600 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" onclick="addAnswerOption()">
                  <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                  إضافة خيار
                </button>
              </div>
              
              <div id="trueFalseAnswers" style="display: none;">
                <div class="space-y-3">
                  <label class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-all duration-200 cursor-pointer">
                    <input class="w-5 h-5 text-green-600 focus:ring-green-500" type="radio" name="answers[0][text]" value="true" id="trueOption">
                    <span class="text-green-700 font-semibold">صح</span>
                  </label>
                  <label class="flex items-center gap-4 p-4 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-all duration-200 cursor-pointer">
                    <input class="w-5 h-5 text-red-600 focus:ring-red-500" type="radio" name="answers[0][text]" value="false" id="falseOption">
                    <span class="text-red-700 font-semibold">خطأ</span>
                  </label>
                </div>
                <input type="hidden" name="answers[0][is_correct]" value="1">
              </div>
              
              <div id="shortAnswerField" style="display: none;">
                <input type="text" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" name="answers[0][text]" placeholder="الإجابة الصحيحة">
                <input type="hidden" name="answers[0][is_correct]" value="1">
              </div>
              
              <div id="essayField" style="display: none;">
                <textarea class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" name="answers[0][text]" rows="4" placeholder="الإجابة النموذجية أو معايير التقييم"></textarea>
                <input type="hidden" name="answers[0][is_correct]" value="1">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-gray-50 border-0 p-6">
          <div class="flex items-center justify-end space-x-4 space-x-reverse w-full">
            <button type="button" class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" data-bs-dismiss="modal">
              <i class="ti ti-x mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
              إلغاء
            </button>
            <button type="submit" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105">
              <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
              إضافة السؤال
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let answerIndex = 2;

function toggleAnswerOptions() {
  const type = document.getElementById('type').value;
  const containers = ['multipleChoiceAnswers', 'trueFalseAnswers', 'shortAnswerField', 'essayField'];
  
  containers.forEach(id => {
    document.getElementById(id).style.display = 'none';
  });
  
  switch(type) {
    case 'multiple_choice':
      document.getElementById('multipleChoiceAnswers').style.display = 'block';
      break;
    case 'true_false':
      document.getElementById('trueFalseAnswers').style.display = 'block';
      break;
    case 'short_answer':
      document.getElementById('shortAnswerField').style.display = 'block';
      break;
    case 'essay':
      document.getElementById('essayField').style.display = 'block';
      break;
  }
}

function addAnswerOption() {
  const container = document.getElementById('multipleChoiceAnswers');
  const button = container.querySelector('button');
  
  const newOption = document.createElement('div');
  newOption.className = 'answer-option bg-white p-4 rounded-xl border border-gray-200 shadow-sm';
  newOption.innerHTML = `
    <div class="flex items-center gap-3">
      <input class="w-5 h-5 text-blue-600 focus:ring-blue-500" type="checkbox" name="answers[${answerIndex}][is_correct]" value="1">
      <input type="text" class="flex-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" name="answers[${answerIndex}][text]" placeholder="خيار الإجابة">
      <input type="text" class="flex-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" name="answers[${answerIndex}][feedback]" placeholder="التعليق (اختياري)">
      <button type="button" class="group inline-flex items-center p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 hover:scale-105 transition-all duration-200" onclick="removeAnswerOption(this)">
        <i class="ti ti-trash group-hover:scale-110 transition-transform duration-200"></i>
      </button>
    </div>
  `;
  
  container.insertBefore(newOption, button);
  answerIndex++;
}

function removeAnswerOption(button) {
  button.closest('.answer-option').remove();
}

function toggleQuestionDetails(questionId) {
  const details = document.getElementById(`details-${questionId}`);
  const chevron = document.getElementById(`chevron-${questionId}`);
  
  if (details.classList.contains('hidden')) {
    details.classList.remove('hidden');
    chevron.style.transform = 'rotate(180deg)';
  } else {
    details.classList.add('hidden');
    chevron.style.transform = 'rotate(0deg)';
  }
}

// Handle True/False selection
document.addEventListener('change', function(e) {
  if (e.target.name === 'answers[0][text]' && e.target.type === 'radio') {
    // Set the correct answer for true/false
    const hiddenInput = document.querySelector('input[name="answers[0][is_correct]"]');
    if (hiddenInput) {
      hiddenInput.value = '1';
    }
  }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/quizzes/questions.blade.php ENDPATH**/ ?>