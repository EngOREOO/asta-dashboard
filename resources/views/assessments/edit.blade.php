@php($title = 'تعديل الاختبار')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تعديل الاختبار</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">قم بتحديث إعدادات وبيانات الاختبار</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('assessments.show', $assessment) }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للتقييم
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
            نموذج تعديل الاختبار
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="{{ route('assessments.update', $assessment) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf
          @method('PUT')

          <div>
            <label for="course_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدورة *</label>
            <select id="course_id" name="course_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر الدورة</option>
              @foreach($courses as $course)
              <option value="{{ $course->id }}" {{ old('course_id', $assessment->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }} @if($course->instructor) - {{ $course->instructor->name }} @endif</option>
              @endforeach
            </select>
            @error('course_id')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="type" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نوع الاختبار *</label>
            <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر النوع</option>
              <option value="quiz" {{ old('type', $assessment->type) == 'quiz' ? 'selected' : '' }}>اختبار</option>
              <option value="exam" {{ old('type', $assessment->type) == 'exam' ? 'selected' : '' }}>امتحان</option>
              <option value="assignment" {{ old('type', $assessment->type) == 'assignment' ? 'selected' : '' }}>واجب</option>
              <option value="survey" {{ old('type', $assessment->type) == 'survey' ? 'selected' : '' }}>استبيان</option>
            </select>
            @error('type')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="title" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">عنوان الاختبار *</label>
            <input id="title" name="title" type="text" value="{{ old('title', $assessment->title) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('title')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">{{ old('description', $assessment->description) }}</textarea>
            @error('description')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="duration_minutes" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">المدة (دقائق)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" value="{{ old('duration_minutes', $assessment->duration_minutes) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لبدون حد زمني</small>
            @error('duration_minutes')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="max_attempts" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">أقصى عدد محاولات</label>
            <input id="max_attempts" name="max_attempts" type="number" value="{{ old('max_attempts', $assessment->max_attempts) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لعدد غير محدود</small>
            @error('max_attempts')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="passing_score" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نسبة النجاح (%)</label>
            <input id="passing_score" name="passing_score" type="number" value="{{ old('passing_score', $assessment->passing_score) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0" max="100">
            @error('passing_score')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-4 items-center">
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $assessment->is_active) ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">نشط</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="randomize_questions" name="randomize_questions" value="1" {{ old('randomize_questions', $assessment->randomize_questions) ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">ترتيب عشوائي للأسئلة</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" {{ old('show_results_immediately', $assessment->show_results_immediately) ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">عرض النتائج فوراً</span>
            </label>
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">تعيين للطلاب</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <div>
                @php($assignAll = old('assign_all'))
                <select name="assign_all" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                  <option value="0" {{ $assignAll == '0' ? 'selected' : '' }}>تحديد طلاب</option>
                  <option value="1" {{ $assignAll == '1' ? 'selected' : '' }}>كل الطلاب</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <select id="user_ids" name="user_ids[]" multiple class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem; min-height: 48px;">
                  @php($selectedUsers = old('user_ids', $assessment->assignments->pluck('user_id')->all()))
                  @foreach($students as $student)
                  <option value="{{ $student->id }}" {{ in_array($student->id, $selectedUsers) ? 'selected' : '' }}>
                    {{ $student->name }} - {{ $student->email }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
            <small class="text-gray-500">اختر "كل الطلاب" أو حدد طلاباً بعينهم.</small>
            @error('user_ids')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">إضافة أسئلة جديدة</label>
            <div id="questions-wrapper" class="space-y-4">
              <!-- Append-only. Existing questions تظهر في صفحة الأسئلة -->
            </div>
            <button type="button" id="add-question" class="mt-2 inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" style="font-size: 1.3rem;">
              + إضافة سؤال
            </button>
            <small class="block text-gray-500 mt-1">اختر نوع السؤال، ثم أضف الخيارات أو الإجابة.</small>
            @error('questions')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">تحديث الاختبار</button>
            <a href="{{ route('assessments.show', $assessment) }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const wrapper = document.getElementById('questions-wrapper');
  const addBtn = document.getElementById('add-question');
  const assignAll = document.querySelector('select[name="assign_all"]');
  const usersSelect = document.getElementById('user_ids');

  function addOptionRow(qIndex, optionsBox, value = '', isChecked = false) {
    const optIndex = optionsBox.querySelectorAll('.option-row').length;
    const row = document.createElement('div');
    row.className = 'option-row flex items-center gap-3';
    row.innerHTML = `
      <input type=\"radio\" name=\"questions[${qIndex}][correct_answer]\" value=\"${optIndex}\" ${isChecked ? 'checked' : ''} />
      <input type=\"text\" name=\"questions[${qIndex}][options][${optIndex}]\" value=\"${value || ''}\" placeholder=\"الخيار\" class=\"flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500\" style=\"font-size: 1.3rem;\" />
      <button type=\"button\" class=\"remove-option inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100\" style=\"font-size: 1.1rem;\">حذف</button>
    `;
    optionsBox.appendChild(row);
    row.querySelector('.remove-option').addEventListener('click', () => row.remove());
  }

  function renderFieldsByType(container, index, type, prefill = {}) {
    const holder = container.querySelector('.type-dependent');
    holder.innerHTML = '';
    if (type === 'multiple_choice') {
      const box = document.createElement('div');
      box.innerHTML = `
        <div class=\"space-y-2\">
          <div class=\"flex items-center justify-between\">
            <span class=\"text-gray-700\" style=\"font-size: 1.2rem;\">الخيارات (حدد الصحيح)</span>
            <button type=\"button\" class=\"add-option inline-flex items-center px-3 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50\" style=\"font-size: 1.1rem;\">+ خيار</button>
          </div>
          <div class=\"options-box space-y-2\"></div>
        </div>
      `;
      holder.appendChild(box);
      const optionsBox = box.querySelector('.options-box');
      const addBtn = box.querySelector('.add-option');
      addBtn.addEventListener('click', () => addOptionRow(index, optionsBox));
      const options = Array.isArray(prefill.options) ? prefill.options : [];
      if (options.length) {
        options.forEach((opt, i) => addOptionRow(index, optionsBox, opt, String(prefill.correct_answer) === String(i)));
      } else {
        addOptionRow(index, optionsBox);
        addOptionRow(index, optionsBox);
      }
    } else if (type === 'true_false') {
      const tf = document.createElement('div');
      tf.innerHTML = `
        <label class=\"block text-gray-700 mb-1\" style=\"font-size: 1.2rem;\">الإجابة الصحيحة</label>
        <select name=\"questions[${index}][correct_answer]\" class=\"w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500\" style=\"font-size: 1.3rem;\">
          <option value=\"true\" ${prefill.correct_answer === true || prefill.correct_answer === 'true' ? 'selected' : ''}>صح</option>
          <option value=\"false\" ${prefill.correct_answer === false || prefill.correct_answer === 'false' ? 'selected' : ''}>خطأ</option>
        </select>
      `;
      holder.appendChild(tf);
    } else if (type === 'text') {
      const txt = document.createElement('div');
      txt.innerHTML = `
        <label class=\"block text-gray-700 mb-1\" style=\"font-size: 1.2rem;\">إجابة نموذجية (اختياري)</label>
        <textarea name=\"questions[${index}][correct_answer]\" rows=\"2\" class=\"w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500\" style=\"font-size: 1.3rem;\">${prefill.correct_answer || ''}</textarea>
      `;
      holder.appendChild(txt);
    }
  }

  function addQuestion(prefill = {}) {
    const index = wrapper.children.length;
    const container = document.createElement('div');
    container.className = 'p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3';
    const type = prefill.type || 'multiple_choice';
    container.innerHTML = `
      <div class=\"grid grid-cols-1 md:grid-cols-4 gap-3\">
        <input type=\"text\" name=\"questions[${index}][question]\" placeholder=\"نص السؤال\" value=\"${prefill.question || ''}\" class=\"md:col-span-2 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500\" style=\"font-size: 1.3rem;\" />
        <select name=\"questions[${index}][type]\" class=\"w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 question-type\" style=\"font-size: 1.3rem;\">\n+          <option value=\"multiple_choice\" ${type === 'multiple_choice' ? 'selected' : ''}>اختيارات متعددة</option>
          <option value=\"true_false\" ${type === 'true_false' ? 'selected' : ''}>صح/خطأ</option>
          <option value=\"text\" ${type === 'text' ? 'selected' : ''}>نصي</option>
        </select>
        <input type=\"number\" name=\"questions[${index}][points]\" placeholder=\"الدرجات\" value=\"${prefill.points || ''}\" class=\"w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500\" style=\"font-size: 1.3rem;\" />
      </div>
      <div class=\"type-dependent\"></div>
      <div>
        <button type=\"button\" class=\"remove-question inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100\" style=\"font-size: 1.1rem;\">حذف السؤال</button>
      </div>
    `;
    wrapper.appendChild(container);
    container.querySelector('.remove-question').addEventListener('click', () => container.remove());
    const typeSelect = container.querySelector('.question-type');
    renderFieldsByType(container, index, type, prefill);
    typeSelect.addEventListener('change', (e) => renderFieldsByType(container, index, e.target.value));
  }

  addBtn.addEventListener('click', () => addQuestion());

  try {
    const oldQuestions = @json(old('questions', []));
    if (Array.isArray(oldQuestions)) {
      oldQuestions.forEach(q => addQuestion(q));
    }
  } catch (e) {}

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
@endpush
