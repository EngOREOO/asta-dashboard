@php($title = 'إضافة سؤال جديد')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إضافة سؤال جديد</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-help-circle mr-2 text-cyan-500"></i>
          أضف سؤال جديد إلى بنك الأسئلة
        </p>
      </div>
      <a href="{{ route('question-bank.index') }}" class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300" style="font-size: 1.3rem;">
        <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
        العودة لبنك الأسئلة
      </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-help-circle text-white text-xl"></i>
            </div>
            نموذج إضافة سؤال
          </h2>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        <form action="{{ route('question-bank.store') }}" method="POST" class="space-y-8" style="font-size: 1.3rem;">
          @csrf

          <!-- Question Text -->
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200">
            <label for="question" class="block text-gray-700 mb-3 font-semibold flex items-center">
              <i class="ti ti-help-circle mr-2 text-cyan-500"></i>
              نص السؤال *
            </label>
            <textarea id="question" name="question" rows="4" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" required placeholder="اكتب نص السؤال هنا...">{{ old('question') }}</textarea>
            @error('question')<p class="text-red-600 mt-2 flex items-center" style="font-size: 1.2rem;"><i class="ti ti-alert-circle mr-1"></i>{{ $message }}</p>@enderror
          </div>

          <!-- Form Fields Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Question Type -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
              <label for="type" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-list mr-2 text-blue-500"></i>
                نوع السؤال *
              </label>
              <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" required>
                <option value="">اختر النوع</option>
                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>اختيارات متعددة</option>
                <option value="multiple_choice_two" {{ old('type') == 'multiple_choice_two' ? 'selected' : '' }}>اختيارات متعددة إجابتين</option>
                <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>صح/خطأ</option>
                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>نصي</option>
              </select>
              @error('type')<p class="text-red-600 mt-2 flex items-center" style="font-size: 1.2rem;"><i class="ti ti-alert-circle mr-1"></i>{{ $message }}</p>@enderror
            </div>

            <!-- Points -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
              <label for="points" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-star mr-2 text-green-500"></i>
                النقاط *
              </label>
              <input type="number" id="points" name="points" min="1" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" value="{{ old('points') }}" required placeholder="مثال: 5">
              @error('points')<p class="text-red-600 mt-2 flex items-center" style="font-size: 1.2rem;"><i class="ti ti-alert-circle mr-1"></i>{{ $message }}</p>@enderror
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Difficulty -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-6 rounded-2xl border border-orange-200">
              <label for="difficulty" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-gauge mr-2 text-orange-500"></i>
                مستوى الصعوبة
              </label>
              <select id="difficulty" name="difficulty" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;">
                <option value="">اختر المستوى</option>
                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
              </select>
              @error('difficulty')<p class="text-red-600 mt-2 flex items-center" style="font-size: 1.2rem;"><i class="ti ti-alert-circle mr-1"></i>{{ $message }}</p>@enderror
            </div>

            <!-- Category -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200">
              <label for="category" class="block text-gray-700 mb-3 font-semibold flex items-center">
                <i class="ti ti-category mr-2 text-purple-500"></i>
                التصنيف
              </label>
              <select id="category" name="category" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;">
                <option value="">اختر التصنيف</option>
                @foreach($categories as $category)
                  <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category')<p class="text-red-600 mt-2 flex items-center" style="font-size: 1.2rem;"><i class="ti ti-alert-circle mr-1"></i>{{ $message }}</p>@enderror
            </div>
          </div>

          <!-- Dynamic fields based on question type -->
          <div id="type-dependent-fields" class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200">
            <h3 class="text-gray-700 mb-4 font-semibold flex items-center" style="font-size: 1.4rem;">
              <i class="ti ti-settings mr-2 text-gray-500"></i>
              إعدادات السؤال
            </h3>
            <div class="space-y-4">
              <!-- This will be populated by JavaScript based on question type -->
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-8">
            <a href="{{ route('question-bank.index') }}" class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-2xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" style="font-size: 1.3rem;">
              <i class="ti ti-x mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
              إلغاء
            </a>
            <button type="submit" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;">
              <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
              إضافة السؤال
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const typeSelect = document.getElementById('type');
  const typeDependentFields = document.getElementById('type-dependent-fields');

  function renderFieldsByType(type) {
    typeDependentFields.innerHTML = '';
    
    if (type === 'multiple_choice' || type === 'multiple_choice_two') {
      const inputType = type === 'multiple_choice_two' ? 'checkbox' : 'radio';
      const instructionText = type === 'multiple_choice_two' ? 'حدد إجابتين صحيحتين' : 'حدد إجابة واحدة صحيحة';
      
      const optionsContainer = document.createElement('div');
      optionsContainer.innerHTML = `
        <div class="space-y-6">
          <div class="flex items-center justify-between">
            <label class="block text-gray-700 font-semibold flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-list mr-2 text-blue-500"></i>
              الخيارات
            </label>
            <button type="button" class="add-option group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl hover:from-blue-600 hover:to-cyan-600 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" style="font-size: 1.1rem;">
              <i class="ti ti-plus mr-1 group-hover:rotate-90 transition-transform duration-300"></i>
              إضافة خيار
            </button>
          </div>
          <div class="options-container space-y-3">
            <div class="option-row bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
              <div class="flex items-center gap-3">
                <input type="${inputType}" name="correct_answer[]" value="0" class="correct-answer w-5 h-5 text-blue-600 focus:ring-blue-500" />
                <input type="text" name="options[]" placeholder="الخيار الأول" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" required />
                <button type="button" class="remove-option group inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 hover:scale-105 transition-all duration-200" style="font-size: 1.1rem;">
                  <i class="ti ti-trash group-hover:scale-110 transition-transform duration-200"></i>
                </button>
              </div>
            </div>
            <div class="option-row bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
              <div class="flex items-center gap-3">
                <input type="${inputType}" name="correct_answer[]" value="1" class="correct-answer w-5 h-5 text-blue-600 focus:ring-blue-500" />
                <input type="text" name="options[]" placeholder="الخيار الثاني" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" required />
                <button type="button" class="remove-option group inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 hover:scale-105 transition-all duration-200" style="font-size: 1.1rem;">
                  <i class="ti ti-trash group-hover:scale-110 transition-transform duration-200"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-blue-700 flex items-center" style="font-size: 1.2rem;">
              <i class="ti ti-info-circle mr-2"></i>
              ${instructionText}
            </p>
          </div>
        </div>
      `;
      typeDependentFields.appendChild(optionsContainer);

      // Add event listeners
      const addOptionBtn = optionsContainer.querySelector('.add-option');
      const optionsContainerEl = optionsContainer.querySelector('.options-container');

      addOptionBtn.addEventListener('click', function() {
        const optionIndex = optionsContainerEl.querySelectorAll('.option-row').length;
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200';
        optionRow.innerHTML = `
          <div class="flex items-center gap-3">
            <input type="${inputType}" name="correct_answer[]" value="${optionIndex}" class="correct-answer w-5 h-5 text-blue-600 focus:ring-blue-500" />
            <input type="text" name="options[]" placeholder="الخيار ${optionIndex + 1}" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" required />
            <button type="button" class="remove-option group inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 hover:scale-105 transition-all duration-200" style="font-size: 1.1rem;">
              <i class="ti ti-trash group-hover:scale-110 transition-transform duration-200"></i>
            </button>
          </div>
        `;
        optionsContainerEl.appendChild(optionRow);
        
        console.log('Added option row with index:', optionIndex);
        
        // Add remove functionality
        optionRow.querySelector('.remove-option').addEventListener('click', function() {
          optionRow.remove();
        });
      });

      // Add remove functionality to existing options
      optionsContainerEl.querySelectorAll('.remove-option').forEach(btn => {
        btn.addEventListener('click', function() {
          btn.closest('.option-row').remove();
        });
      });

      // For multiple_choice_two, limit to 2 selections
      if (type === 'multiple_choice_two') {
        optionsContainerEl.addEventListener('change', function(e) {
          if (e.target.classList.contains('correct-answer')) {
            const checkedBoxes = optionsContainerEl.querySelectorAll('.correct-answer:checked');
            if (checkedBoxes.length > 2) {
              e.target.checked = false;
              alert('يمكن اختيار إجابتين فقط');
            }
          }
        });
      }

    } else if (type === 'true_false') {
      const trueFalseContainer = document.createElement('div');
      trueFalseContainer.innerHTML = `
        <div class="space-y-6">
          <label class="block text-gray-700 font-semibold flex items-center" style="font-size: 1.3rem;">
            <i class="ti ti-checkbox mr-2 text-green-500"></i>
            الإجابة الصحيحة
          </label>
          <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="space-y-4">
              <label class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-all duration-200 cursor-pointer">
                <input type="radio" name="correct_answer[]" value="0" class="w-5 h-5 text-green-600 focus:ring-green-500" />
                <span class="text-green-700 font-semibold" style="font-size: 1.3rem;">صح</span>
              </label>
              <label class="flex items-center gap-4 p-4 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-all duration-200 cursor-pointer">
                <input type="radio" name="correct_answer[]" value="1" class="w-5 h-5 text-red-600 focus:ring-red-500" />
                <span class="text-red-700 font-semibold" style="font-size: 1.3rem;">خطأ</span>
              </label>
            </div>
          </div>
        </div>
      `;
      typeDependentFields.appendChild(trueFalseContainer);

    } else if (type === 'text') {
      const textContainer = document.createElement('div');
      textContainer.innerHTML = `
        <div class="space-y-6">
          <label for="correct_answer_text" class="block text-gray-700 font-semibold flex items-center" style="font-size: 1.3rem;">
            <i class="ti ti-edit mr-2 text-purple-500"></i>
            الإجابة النموذجية (اختياري)
          </label>
          <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <textarea id="correct_answer_text" name="correct_answer" rows="4" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all duration-200 hover:shadow-md focus:shadow-lg" style="font-size: 1.3rem;" placeholder="اكتب الإجابة النموذجية هنا..."></textarea>
          </div>
          <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
            <p class="text-purple-700 flex items-center" style="font-size: 1.2rem;">
              <i class="ti ti-info-circle mr-2"></i>
              يمكن ترك هذا الحقل فارغاً إذا لم تكن هناك إجابة نموذجية محددة
            </p>
          </div>
        </div>
      `;
      typeDependentFields.appendChild(textContainer);
    }
  }

  // Initial render if type is already selected
  if (typeSelect.value) {
    renderFieldsByType(typeSelect.value);
  }

  // Render fields when type changes
  typeSelect.addEventListener('change', function() {
    renderFieldsByType(this.value);
  });

  // Add form validation before submission
  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
    console.log('Form submission started...');
    
    const questionText = document.getElementById('question').value.trim();
    const questionType = document.getElementById('type').value;
    
    console.log('Question text:', questionText);
    console.log('Question type:', questionType);
    
    if (!questionText) {
      e.preventDefault();
      alert('يرجى إدخال نص السؤال');
      return false;
    }
    
    if (!questionType) {
      e.preventDefault();
      alert('يرجى اختيار نوع السؤال');
      return false;
    }
    
    // Check if dynamic fields are required and populated
    if (questionType === 'multiple_choice' || questionType === 'multiple_choice_two') {
      const options = document.querySelectorAll('input[name="options[]"]');
      const correctAnswers = document.querySelectorAll('input[name="correct_answer[]"]:checked');
      
      console.log('Options found:', options.length);
      console.log('Correct answers found:', correctAnswers.length);
      
      if (options.length === 0) {
        e.preventDefault();
        alert('يرجى إضافة خيارات للسؤال');
        return false;
      }
      
      // Check if at least one option has text
      let hasOptionText = false;
      let optionTexts = [];
      options.forEach(option => {
        const text = option.value.trim();
        optionTexts.push(text);
        if (text) {
          hasOptionText = true;
        }
      });
      
      console.log('Option texts:', optionTexts);
      
      if (!hasOptionText) {
        e.preventDefault();
        alert('يرجى إدخال نص للخيارات');
        return false;
      }
      
      // Check correct answers
      if (questionType === 'multiple_choice' && correctAnswers.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار إجابة صحيحة واحدة');
        return false;
      }
      
      if (questionType === 'multiple_choice_two' && correctAnswers.length !== 2) {
        e.preventDefault();
        alert('يرجى اختيار إجابتين صحيحتين');
        return false;
      }
    }
    
    if (questionType === 'true_false') {
      const correctAnswers = document.querySelectorAll('input[name="correct_answer[]"]:checked');
      if (correctAnswers.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار إجابة صحيحة (صح أو خطأ)');
        return false;
      }
    }
    
    console.log('Form validation passed, submitting...');
    
    // Debug: Log all form data before submission
    const formData = new FormData(form);
    console.log('Form data being submitted:');
    for (let [key, value] of formData.entries()) {
      console.log(key + ': ' + value);
    }
    
    // Don't prevent default - let the form submit normally
    return true;
  });
});
</script>
@endpush
@endsection
