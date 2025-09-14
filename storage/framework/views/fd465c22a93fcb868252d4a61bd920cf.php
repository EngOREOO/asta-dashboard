<?php ($title = 'إنشاء تقييم'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إنشاء تقييم جديد</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">اربط التقييم بدورة وحدد الإعدادات</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('assessments.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للتقييمات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-clipboard-text text-white text-xl"></i>
            </div>
            نموذج إنشاء تقييم
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="<?php echo e(route('assessments.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php echo csrf_field(); ?>

          <div>
            <label for="course_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدورة *</label>
            <select id="course_id" name="course_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر الدورة</option>
              <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>><?php echo e($course->title); ?> <?php if($course->instructor): ?> - <?php echo e($course->instructor->name); ?> <?php endif; ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="type" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نوع التقييم *</label>
            <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر النوع</option>
              <option value="quiz" <?php echo e(old('type') == 'quiz' ? 'selected' : ''); ?>>اختبار</option>
              <option value="exam" <?php echo e(old('type') == 'exam' ? 'selected' : ''); ?>>امتحان</option>
              <option value="assignment" <?php echo e(old('type') == 'assignment' ? 'selected' : ''); ?>>واجب</option>
              <option value="survey" <?php echo e(old('type') == 'survey' ? 'selected' : ''); ?>>استبيان</option>
            </select>
            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2">
            <label for="title" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">عنوان التقييم *</label>
            <input id="title" name="title" type="text" value="<?php echo e(old('title')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;"><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="duration_minutes" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">المدة (دقائق)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" value="<?php echo e(old('duration_minutes')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لبدون حد زمني</small>
            <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="max_attempts" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">أقصى عدد محاولات</label>
            <input id="max_attempts" name="max_attempts" type="number" value="<?php echo e(old('max_attempts')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لعدد غير محدود</small>
            <?php $__errorArgs = ['max_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="passing_score" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نسبة النجاح (%)</label>
            <input id="passing_score" name="passing_score" type="number" value="<?php echo e(old('passing_score')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0" max="100">
            <?php $__errorArgs = ['passing_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="total_questions" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">إجمالي الأسئلة</label>
            <input id="total_questions" name="total_questions" type="number" value="<?php echo e(old('total_questions')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">عدد الأسئلة المتوقع في التقييم</small>
            <?php $__errorArgs = ['total_questions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-4 items-center">
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">نشط</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="randomize_questions" name="randomize_questions" value="1" <?php echo e(old('randomize_questions') ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">ترتيب عشوائي للأسئلة</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" <?php echo e(old('show_results_immediately') ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">عرض النتائج فوراً</span>
            </label>
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">تعيين للطلاب</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <div>
                <select name="assign_all" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                  <option value="0">تحديد طلاب</option>
                  <option value="1">كل الطلاب</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <select id="user_ids" name="user_ids[]" multiple class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem; min-height: 48px;">
                  <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($student->id); ?>" <?php echo e(collect(old('user_ids', []))->contains($student->id) ? 'selected' : ''); ?>>
                    <?php echo e($student->name); ?> - <?php echo e($student->email); ?>

                  </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <small class="text-gray-500">اختر "كل الطلاب" أو حدد طلاباً بعينهم.</small>
            <?php $__errorArgs = ['user_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">إضافة أسئلة</label>
            <div class="flex items-center gap-3 mb-2">
              <label class="inline-flex items-center gap-2">
                <input type="checkbox" id="toggle_saved" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span style="font-size: 1.1rem;">إظهار الأسئلة المحفوظة</span>
              </label>
            </div>
            <div id="saved-list" class="hidden mb-3 p-3 bg-white border border-gray-200 rounded-xl"></div>
            <div id="questions-wrapper" class="space-y-4">
              <!-- Question template will be cloned by JS -->
            </div>
            <button type="button" id="add-question" class="mt-2 inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" style="font-size: 1.3rem;">
              + إضافة سؤال
            </button>
            <small class="block text-gray-500 mt-1">اختر نوع السؤال، ثم أضف الخيارات أو الإجابة.</small>
            <?php $__errorArgs = ['questions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">إنشاء التقييم</button>
            <a href="<?php echo e(route('assessments.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const wrapper = document.getElementById('questions-wrapper');
  const addBtn = document.getElementById('add-question');
  const assignAll = document.querySelector('select[name="assign_all"]');
  const usersSelect = document.getElementById('user_ids');

  function addOptionRow(qIndex, optionsBox, value = '', isChecked = false, selectMode = 'radio') {
    const optIndex = optionsBox.querySelectorAll('.option-row').length;
    const row = document.createElement('div');
    row.className = 'option-row flex items-center gap-3';
    const selectorName = selectMode === 'checkbox' ? `questions[${qIndex}][correct_answer][]` : `questions[${qIndex}][correct_answer]`;
    const selectorType = selectMode === 'checkbox' ? 'checkbox' : 'radio';
    row.innerHTML = `
      <input type="${selectorType}" name="${selectorName}" value="${optIndex}" ${isChecked ? 'checked' : ''} />
      <input type="text" name="questions[${qIndex}][options][${optIndex}]" value="${value || ''}" placeholder="الخيار" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" />
      <button type="button" class="remove-option inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100" style="font-size: 1.1rem;">حذف</button>
    `;
    optionsBox.appendChild(row);
    row.querySelector('.remove-option').addEventListener('click', () => row.remove());
  }

  function renderFieldsByType(container, index, type, prefill = {}) {
    const holder = container.querySelector('.type-dependent');
    holder.innerHTML = '';
    if (type === 'multiple_choice' || type === 'multiple_choice_two') {
      const box = document.createElement('div');
      box.innerHTML = `
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-gray-700" style="font-size: 1.2rem;">${type === 'multiple_choice_two' ? 'الخيارات (حدد إجابتين صحيحتين)' : 'الخيارات (حدد الإجابة الصحيحة)'} </span>
            <button type="button" class="add-option inline-flex items-center px-3 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" style="font-size: 1.1rem;">+ خيار</button>
          </div>
          <div class="options-box space-y-2"></div>
        </div>
      `;
      holder.appendChild(box);
      const optionsBox = box.querySelector('.options-box');
      const addBtn = box.querySelector('.add-option');
      const selectMode = type === 'multiple_choice_two' ? 'checkbox' : 'radio';
      addBtn.addEventListener('click', () => addOptionRow(index, optionsBox, '', false, selectMode));
      const options = Array.isArray(prefill.options) ? prefill.options : [];
      if (options.length) {
        if (type === 'multiple_choice_two') {
          const selected = Array.isArray(prefill.correct_answer) ? prefill.correct_answer.map(String) : String(prefill.correct_answer || '').split(',');
          options.forEach((opt, i) => addOptionRow(index, optionsBox, opt, selected.includes(String(i)), selectMode));
        } else {
          options.forEach((opt, i) => addOptionRow(index, optionsBox, opt, String(prefill.correct_answer) === String(i), selectMode));
        }
      } else {
        addOptionRow(index, optionsBox, '', false, selectMode);
        addOptionRow(index, optionsBox, '', false, selectMode);
      }
      // Enforce max 2 selections for the two-answer type
      if (type === 'multiple_choice_two') {
        const enforceMaxTwo = () => {
          const checks = Array.from(optionsBox.querySelectorAll('input[type="checkbox"]'));
          const selected = checks.filter(c => c.checked);
          if (selected.length > 2) {
            // Uncheck the one that was just checked
            const last = selected[selected.length - 1];
            last.checked = false;
          }
        };
        optionsBox.addEventListener('change', (e) => {
          if (e.target && e.target.matches('input[type="checkbox"]')) {
            enforceMaxTwo();
          }
        });
        // Run once in case of prefill
        enforceMaxTwo();
      }
    } else if (type === 'true_false') {
      const tf = document.createElement('div');
      tf.innerHTML = `
        <label class="block text-gray-700 mb-1" style="font-size: 1.2rem;">الإجابة الصحيحة</label>
        <select name="questions[${index}][correct_answer]" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
          <option value="true" ${prefill.correct_answer === true || prefill.correct_answer === 'true' ? 'selected' : ''}>صح</option>
          <option value="false" ${prefill.correct_answer === false || prefill.correct_answer === 'false' ? 'selected' : ''}>خطأ</option>
        </select>
      `;
      holder.appendChild(tf);
    } else if (type === 'text') {
      const txt = document.createElement('div');
      txt.innerHTML = `
        <label class="block text-gray-700 mb-1" style="font-size: 1.2rem;">إجابة نموذجية (اختياري)</label>
        <textarea name="questions[${index}][correct_answer]" rows="2" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">${prefill.correct_answer || ''}</textarea>
      `;
      holder.appendChild(txt);
    }
  }

  function getSavedCountSync() {
    const savedList = document.getElementById('saved-list');
    if (savedList && !savedList.classList.contains('hidden')) {
      return savedList.querySelectorAll('[data-saved]').length;
    }
    const attr = savedList?.getAttribute('data-count');
    return attr ? parseInt(attr) || 0 : 0;
  }

  function computeQuestionNumber(localIndexZeroBased) {
    const savedCount = getSavedCountSync();
    return savedCount + localIndexZeroBased + 1;
  }

  function addQuestion(prefill = {}) {
    const index = wrapper.children.length;
    const container = document.createElement('div');
    container.className = 'p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3';
    const type = prefill.type || 'multiple_choice';
    
    // Get current question number and total
    const currentQuestion = computeQuestionNumber(index);
    const totalQuestions = document.getElementById('total_questions')?.value || '';
    const progressText = totalQuestions ? `${currentQuestion} / ${totalQuestions}` : `${currentQuestion}`;
    
    container.innerHTML = `
      <div class="flex justify-between items-center mb-2">
        <h3 class="font-semibold text-gray-700" style="font-size: 1.2rem;">السؤال ${currentQuestion}</h3>
        <span class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full" style="font-size: 1.1rem;">${progressText}</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text" name="questions[${index}][question]" placeholder="نص السؤال" value="${prefill.question || ''}" class="md:col-span-2 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" />
        <select name="questions[${index}][type]" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 question-type" style="font-size: 1.3rem;">
          <option value="multiple_choice" ${type === 'multiple_choice' ? 'selected' : ''}>اختيارات متعددة</option>
          <option value="multiple_choice_two" ${type === 'multiple_choice_two' ? 'selected' : ''}>اختيارات متعددة إجابتين</option>
          <option value="true_false" ${type === 'true_false' ? 'selected' : ''}>صح/خطأ</option>
          <option value="text" ${type === 'text' ? 'selected' : ''}>نصي</option>
        </select>
        <input type="number" name="questions[${index}][points]" placeholder="الدرجات" value="${prefill.points || ''}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" />
      </div>
      <div class="type-dependent"></div>
      <div>
        <button type="button" class="remove-question inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100" style="font-size: 1.1rem;">حذف السؤال</button>
      </div>
    `;
    wrapper.appendChild(container);
    container.querySelector('.remove-question').addEventListener('click', () => {
      container.remove();
      updateAllProgressIndicators();
    });
    const typeSelect = container.querySelector('.question-type');
    renderFieldsByType(container, index, type, prefill);
    typeSelect.addEventListener('change', (e) => renderFieldsByType(container, index, e.target.value));
  }

  // Function to update all progress indicators
  function updateAllProgressIndicators() {
    const totalQuestions = document.getElementById('total_questions')?.value || '';
    const questionContainers = wrapper.querySelectorAll('.p-4.bg-gray-50');
    
    questionContainers.forEach((container, index) => {
      const currentQuestion = computeQuestionNumber(index);
      const progressText = totalQuestions ? `${currentQuestion} / ${totalQuestions}` : `${currentQuestion}`;
      const progressSpan = container.querySelector('.text-sm.text-gray-500');
      const questionTitle = container.querySelector('h3');
      
      if (progressSpan) {
        progressSpan.textContent = progressText;
      }
      if (questionTitle) {
        questionTitle.textContent = `السؤال ${currentQuestion}`;
      }
    });
  }

  async function validateCurrentAndAddNew() {
    const last = wrapper.lastElementChild;
    if (last) {
      const i = Array.from(wrapper.children).indexOf(last);
      const q = {};
      q.question = (last.querySelector(`input[name="questions[${i}][question]"]`)?.value || '').trim();
      q.type = last.querySelector(`select[name="questions[${i}][type]"]`)?.value || 'multiple_choice';
      q.points = last.querySelector(`input[name="questions[${i}][points]"]`)?.value || '';
      const opts = [];
      last.querySelectorAll('.options-box input[type="text"]').forEach(inp => { const v = (inp.value || '').trim(); if (v !== '') opts.push(v); });
      if (opts.length) q.options = opts;

      // Client validations
      if (!q.question) {
        alert('حقل نص السؤال مطلوب.');
        last.querySelector(`input[name="questions[${i}][question]"]`)?.focus();
        return;
      }
      if (q.type === 'multiple_choice_two') {
        const indices = [];
        last.querySelectorAll('.options-box input[type="checkbox"]').forEach((cb, idx) => { if (cb.checked) indices.push(idx); });
        q.correct_answer = indices;
        if (opts.length < 2) { alert('أضف على الأقل خيارين.'); return; }
        if (indices.length !== 2) { alert('يجب تحديد إجابتين صحيحتين.'); return; }
      } else if (q.type === 'multiple_choice') {
        const r = last.querySelector('.options-box input[type="radio"]:checked');
        if (r) q.correct_answer = r.value;
        if (opts.length < 2) { alert('أضف على الأقل خيارين.'); return; }
        if (!r) { alert('يرجى تحديد الإجابة الصحيحة.'); return; }
      } else if (q.type === 'true_false') {
        q.correct_answer = last.querySelector(`select[name="questions[${i}][correct_answer]"]`)?.value || '';
        if (!q.correct_answer) { alert('يرجى اختيار الإجابة الصحيحة (صح/خطأ).'); return; }
      } else {
        q.correct_answer = (last.querySelector('.type-dependent textarea')?.value || '').trim();
      }

      try {
        const res = await fetch("<?php echo e(route('assessments.validate-question')); ?>", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
            'Accept': 'application/json'
          },
          credentials: 'same-origin',
          body: JSON.stringify(q)
        });
        if (!res.ok) {
          const body = await res.json().catch(()=>({}));
          alert(body.message || 'تحقق من السؤال غير ناجح.');
          return;
        }
        // Remove the previous question block after successful save
        last.remove();
        // If saved list is visible, refresh it
        if (document.getElementById('toggle_saved')?.checked) {
          await refreshSavedList();
        }
      } catch (e) {
        alert('تعذر الاتصال بالخادم للتحقق من السؤال.');
        return;
      }
    }

    addQuestion();
    // Update progress indicators after adding new question
    updateAllProgressIndicators();
  }

  addBtn.addEventListener('click', validateCurrentAndAddNew);

  // Add event listener for total questions field to update progress indicators
  const totalQuestionsField = document.getElementById('total_questions');
  if (totalQuestionsField) {
    totalQuestionsField.addEventListener('input', updateAllProgressIndicators);
  }

  // Saved questions toggle and fetch
  const toggleSaved = document.getElementById('toggle_saved');
  const savedList = document.getElementById('saved-list');

  async function refreshSavedList() {
    try {
      const res = await fetch("<?php echo e(route('assessments.temp-questions')); ?>", { credentials: 'same-origin' });
      const data = await res.json();
      savedList.setAttribute('data-count', String((data.questions || []).length));
      const items = (data.questions || []).map((q, idx) => {
        // Render an editable block identical to the creation form so it can be modified and re-saved
        const totalQ = document.getElementById('total_questions')?.value || '';
        const currentNumber = idx + 1;
        const headerBar = `
          <div class="flex justify-between items-center mb-2">
            <h3 class="font-semibold text-gray-700" style="font-size: 1.2rem;">السؤال ${currentNumber}</h3>
            <span class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full" style="font-size: 1.1rem;">${totalQ ? `${currentNumber} / ${totalQ}` : `${currentNumber}`}</span>
          </div>`;
        const typeSelect = `
          <select data-idx="${idx}" class="saved-type w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size:1.1rem;">
            <option value="multiple_choice" ${q.type==='mcq' && (!q.correct_answer || (q.correct_answer||'').split(',').length===1) ? 'selected' : ''}>اختيارات متعددة</option>
            <option value="multiple_choice_two" ${q.type==='mcq' && (q.correct_answer||'').split(',').filter(Boolean).length===2 ? 'selected' : ''}>اختيارات متعددة إجابتين</option>
            <option value="true_false" ${q.type==='mcq' && !Array.isArray(q.options) ? 'selected' : ''}>صح/خطأ</option>
            <option value="text" ${q.type==='text' ? 'selected' : ''}>نصي</option>
          </select>`;

        const header = `${headerBar}<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-2">
            <input type="text" class="saved-question md:col-span-2 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" data-idx="${idx}" value="${q.question?.replace(/\"/g,'&quot;') || ''}" />
            ${typeSelect}
            <input type="number" class="saved-points w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" data-idx="${idx}" value="${q.points || ''}" placeholder="الدرجات" />
          </div>`;

        // Build options UI
        let body = '';
        if (q.type === 'text') {
          body = `<textarea class="saved-answer w-full border-gray-300 rounded-xl" rows="2" data-idx="${idx}">${q.correct_answer || ''}</textarea>`;
        } else {
          const options = Array.isArray(q.options) ? q.options : ['صح','خطأ'];
          const selected = (q.correct_answer || '').split(',').filter(Boolean);
          const isTwo = selected.length === 2;
          const selectorType = isTwo ? 'checkbox' : 'radio';
          body = `<div class="saved-options space-y-2" data-idx="${idx}">` + options.map((opt, i)=>{
            const checked = selected.includes(String(i)) ? 'checked' : '';
            const name = selectorType==='radio' ? `name="saved_${idx}_correct"` : '';
            return `<label class="flex items-center gap-2"><input type="${selectorType}" ${name} value="${i}" ${checked}> <input type="text" class="opt w-full border-gray-300 rounded-xl" value="${opt.replace(/\"/g,'&quot;')}" /></label>`;
          }).join('') + `</div>`;
        }

        return `<div class="p-3 mb-3 bg-gray-50 border border-gray-200 rounded-xl" data-saved="${idx}">${header}${body}
          <div class="mt-2 text-left"><button type="button" class="save-saved inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg" data-idx="${idx}">حفظ التعديل</button></div>
        </div>`;
      }).join('');
      savedList.innerHTML = items || '<div class="text-gray-500">لا توجد أسئلة محفوظة بعد.</div>';

      // Attach handlers to save edited items
      savedList.querySelectorAll('.save-saved').forEach(btn => {
        btn.addEventListener('click', async () => {
          const i = parseInt(btn.getAttribute('data-idx'));
          const host = savedList.querySelector(`[data-saved="${i}"]`);
          const payload = {};
          payload.question = host.querySelector('.saved-question').value.trim();
          payload.type = host.querySelector('.saved-type').value;
          payload.points = host.querySelector('.saved-points').value;

          if (payload.type === 'text') {
            payload.correct_answer = host.querySelector('.saved-answer').value.trim();
          } else {
            const options = Array.from(host.querySelectorAll('.saved-options .opt')).map(el => el.value.trim()).filter(v=>v!=='');
            payload.options = options;
            const checks = Array.from(host.querySelectorAll('.saved-options input[type="checkbox"]:checked, .saved-options input[type="radio"]:checked'));
            payload.correct_answer = checks.map(c => c.value);
          }

          // client validation similar to main form
          if (!payload.question) { alert('حقل نص السؤال مطلوب.'); return; }
          if (payload.type === 'multiple_choice') {
            if (!payload.options || payload.options.length < 2) { alert('أضف على الأقل خيارين.'); return; }
            if (!Array.isArray(payload.correct_answer) || payload.correct_answer.length !== 1) { alert('يرجى تحديد إجابة واحدة صحيحة.'); return; }
          }
          if (payload.type === 'multiple_choice_two') {
            if (!payload.options || payload.options.length < 2) { alert('أضف على الأقل خيارين.'); return; }
            if (!Array.isArray(payload.correct_answer) || payload.correct_answer.length !== 2) { alert('يجب تحديد إجابتين صحيحتين.'); return; }
          }
          if (payload.type === 'true_false') {
            if (!Array.isArray(payload.correct_answer) || payload.correct_answer.length !== 1) { alert('يرجى اختيار صح أو خطأ.'); return; }
          }

          try {
            const res = await fetch(`<?php echo e(url('/assessments/temp-questions')); ?>/${i}`, {
              method: 'PUT',
              headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value, 'Accept': 'application/json' },
              credentials: 'same-origin',
              body: JSON.stringify(payload),
            });
            if (!res.ok) {
              const body = await res.json().catch(()=>({}));
              alert(body.message || 'فشل حفظ التعديل');
              return;
            }
            alert('تم حفظ السؤال بنجاح');
            await refreshSavedList();
            updateAllProgressIndicators();
          } catch (e) {
            alert('تعذر حفظ التعديل');
          }
        });
      });
    } catch (e) {
      savedList.innerHTML = '<div class="text-red-600">تعذر تحميل الأسئلة المحفوظة.</div>';
    }
  }

  if (toggleSaved) {
    toggleSaved.addEventListener('change', async () => {
      if (toggleSaved.checked) {
        savedList.classList.remove('hidden');
        await refreshSavedList();
      } else {
        savedList.classList.add('hidden');
      }
    });
  }

  // Autosave/restore form state to survive refresh
  const FORM_KEY = 'assessments_create_autosave_v1';
  const formEl = document.querySelector('form[action$="assessments"]');

  function collectFormState() {
    const formData = new FormData(formEl);
    const entries = {};
    formData.forEach((v, k) => {
      if (k.endsWith('[]')) {
        const key = k.slice(0, -2);
        entries[key] = entries[key] || [];
        entries[key].push(v);
      } else if (entries[k] !== undefined) {
        if (!Array.isArray(entries[k])) entries[k] = [entries[k]];
        entries[k].push(v);
      } else {
        entries[k] = v;
      }
    });
    return entries;
  }

  function restoreFormState(state) {
    if (!state) return;
    // Restore simple fields
    ['course_id','type','title','description','duration_minutes','max_attempts','passing_score'].forEach(id => {
      if (state[id] !== undefined) {
        const el = document.getElementById(id);
        if (el) el.value = state[id];
      }
    });
    ['is_active','randomize_questions','show_results_immediately'].forEach(id => {
      if (state[id] !== undefined) {
        const el = document.getElementById(id);
        if (el) el.checked = !!state[id];
      }
    });
    // Restore questions if present
    if (Array.isArray(state.__questions)) {
      state.__questions.forEach(q => addQuestion(q));
    } else {
      // Fallback to old() data
      try {
        const oldQuestions = <?php echo json_encode(old('questions', []), 512) ?>;
        if (Array.isArray(oldQuestions)) {
          oldQuestions.forEach(q => addQuestion(q));
        }
      } catch (e) {}
    }
  }

  function collectQuestions() {
    const qs = [];
    wrapper.querySelectorAll('.p-4.bg-gray-50.rounded-2xl').forEach((container, i) => {
      const q = {};
      q.question = container.querySelector(`input[name="questions[${i}][question]"]`)?.value || '';
      q.type = container.querySelector(`select[name="questions[${i}][type]"]`)?.value || 'multiple_choice';
      q.points = container.querySelector(`input[name="questions[${i}][points]"]`)?.value || '';
      const options = [];
      container.querySelectorAll('.options-box input[type="text"]').forEach(inp => options.push(inp.value));
      if (options.length) q.options = options;
      if (q.type === 'multiple_choice_two') {
        const indices = [];
        container.querySelectorAll('.options-box input[type="checkbox"]').forEach((cb, idx) => { if (cb.checked) indices.push(idx); });
        q.correct_answer = indices.join(',');
      } else if (q.type === 'multiple_choice') {
        const r = container.querySelector('.options-box input[type="radio"]:checked');
        if (r) q.correct_answer = String(r.value);
      } else if (q.type === 'true_false') {
        q.correct_answer = container.querySelector(`select[name="questions[${i}][correct_answer]"]`)?.value || '';
      } else {
        q.correct_answer = container.querySelector('.type-dependent textarea')?.value || '';
      }
      qs.push(q);
    });
    return qs;
  }

  // Initial restore
  try {
    const saved = localStorage.getItem(FORM_KEY);
    const state = saved ? JSON.parse(saved) : null;
    restoreFormState(state);
  } catch (e) {
    // Fallback to old() handled inside restore
    restoreFormState(null);
  }

  // Autosave on input changes
  const debouncedSave = (() => {
    let t; return () => { clearTimeout(t); t = setTimeout(() => {
      const state = collectFormState();
      state.__questions = collectQuestions();
      localStorage.setItem(FORM_KEY, JSON.stringify(state));
    }, 300); };
  })();
  document.addEventListener('input', debouncedSave, true);
  document.addEventListener('change', debouncedSave, true);

  // On submit, keep a copy; optionally clear after success on index page
  formEl.addEventListener('submit', () => {
    const state = collectFormState();
    state.__questions = collectQuestions();
    localStorage.setItem(FORM_KEY, JSON.stringify(state));
  });

  function toggleUsersSelect() {
    const isAll = assignAll && assignAll.value === '1';
    if (usersSelect) {
      usersSelect.disabled = isAll;
      usersSelect.parentElement.classList.toggle('opacity-50', isAll);
    }
  }
  if (assignAll) {
    assignAll.addEventListener('change', toggleUsersSelect);
    toggleUsersSelect();
  }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/assessments/create.blade.php ENDPATH**/ ?>