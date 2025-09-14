@php
    $title = 'تعديل السؤال';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تعديل السؤال</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">تعديل سؤال في بنك الأسئلة</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('question-bank.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة لبنك الأسئلة
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-edit text-white text-xl"></i>
            </div>
            نموذج تعديل السؤال
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="{{ route('question-bank.update', $questionBank) }}" method="POST" class="space-y-6">
          @csrf
          @method('PUT')

          <div>
            <label for="question" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نص السؤال *</label>
            <textarea id="question" name="question" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>{{ old('question', $questionBank->question) }}</textarea>
            @error('question')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="type" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نوع السؤال *</label>
              <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
                <option value="">اختر النوع</option>
                @php
                  $currentType = old('type', $questionBank->type === 'mcq' ? 'multiple_choice' : 'text');
                  // Determine if it's multiple_choice_two based on correct_answer
                  if ($questionBank->type === 'mcq' && $questionBank->correct_answer && strpos($questionBank->correct_answer, ',') !== false) {
                    $currentType = 'multiple_choice_two';
                  } elseif ($questionBank->type === 'mcq' && !$questionBank->options) {
                    $currentType = 'true_false';
                  }
                @endphp
                <option value="multiple_choice" {{ $currentType == 'multiple_choice' ? 'selected' : '' }}>اختيارات متعددة</option>
                <option value="multiple_choice_two" {{ $currentType == 'multiple_choice_two' ? 'selected' : '' }}>اختيارات متعددة إجابتين</option>
                <option value="true_false" {{ $currentType == 'true_false' ? 'selected' : '' }}>صح/خطأ</option>
                <option value="text" {{ $currentType == 'text' ? 'selected' : '' }}>نصي</option>
              </select>
              @error('type')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
            </div>

            <div>
              <label for="points" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدرجات</label>
              <input id="points" name="points" type="number" value="{{ old('points', $questionBank->points) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
              @error('points')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="difficulty" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">مستوى الصعوبة</label>
              <select id="difficulty" name="difficulty" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                <option value="">اختر المستوى</option>
                <option value="easy" {{ old('difficulty', $questionBank->difficulty) == 'easy' ? 'selected' : '' }}>سهل</option>
                <option value="medium" {{ old('difficulty', $questionBank->difficulty) == 'medium' ? 'selected' : '' }}>متوسط</option>
                <option value="hard" {{ old('difficulty', $questionBank->difficulty) == 'hard' ? 'selected' : '' }}>صعب</option>
              </select>
              @error('difficulty')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
            </div>

            <div>
              <label for="category" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">التصنيف</label>
              <select id="category" name="category" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                <option value="">اختر التصنيف</option>
                @foreach($categories as $category)
                  <option value="{{ $category->name }}" {{ old('category', $questionBank->category) == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
            </div>
          </div>

          <!-- Dynamic fields based on question type -->
          <div id="type-dependent-fields" class="space-y-4">
            <!-- This will be populated by JavaScript based on question type -->
          </div>

          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-6">
            <a href="{{ route('question-bank.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors duration-200" style="font-size: 1.3rem;">
              إلغاء
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:from-teal-600 hover:to-cyan-700 shadow-lg transition-all duration-300" style="font-size: 1.3rem;">
              <i class="ti ti-check mr-2"></i>
              حفظ التعديلات
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
  
  // Question data from server
  const questionData = {
    type: @json($questionBank->type),
    options: @json($questionBank->options),
    correct_answer: @json($questionBank->correct_answer),
  };

  function renderFieldsByType(type) {
    typeDependentFields.innerHTML = '';
    
    if (type === 'multiple_choice' || type === 'multiple_choice_two') {
      const optionsContainer = document.createElement('div');
      optionsContainer.innerHTML = `
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <label class="block text-gray-700 font-semibold" style="font-size: 1.3rem;">الخيارات</label>
            <button type="button" class="add-option inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" style="font-size: 1.1rem;">
              <i class="ti ti-plus mr-1"></i>
              إضافة خيار
            </button>
          </div>
          <div class="options-container space-y-2">
          </div>
          <div class="text-sm text-gray-600">
            ${type === 'multiple_choice_two' ? 'حدد إجابتين صحيحتين' : 'حدد إجابة واحدة صحيحة'}
          </div>
        </div>
      `;
      typeDependentFields.appendChild(optionsContainer);

      // Add existing options
      const optionsContainerEl = optionsContainer.querySelector('.options-container');
      const options = questionData.options || ['صح', 'خطأ'];
      const correctAnswers = questionData.correct_answer ? questionData.correct_answer.split(',') : [];
      
      options.forEach((option, index) => {
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row flex items-center gap-3';
        optionRow.innerHTML = `
          <input type="${type === 'multiple_choice_two' ? 'checkbox' : 'radio'}" name="correct_answer[]" value="${index}" class="correct-answer" ${correctAnswers.includes(String(index)) ? 'checked' : ''} />
          <input type="text" name="options[]" value="${option}" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required />
          <button type="button" class="remove-option inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100" style="font-size: 1.1rem;">حذف</button>
        `;
        optionsContainerEl.appendChild(optionRow);
      });

      // Add event listeners
      const addOptionBtn = optionsContainer.querySelector('.add-option');

      addOptionBtn.addEventListener('click', function() {
        const optionIndex = optionsContainerEl.querySelectorAll('.option-row').length;
        const optionRow = document.createElement('div');
        optionRow.className = 'option-row flex items-center gap-3';
        optionRow.innerHTML = `
          <input type="${type === 'multiple_choice_two' ? 'checkbox' : 'radio'}" name="correct_answer[]" value="${optionIndex}" class="correct-answer" />
          <input type="text" name="options[]" placeholder="الخيار ${optionIndex + 1}" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required />
          <button type="button" class="remove-option inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100" style="font-size: 1.1rem;">حذف</button>
        `;
        optionsContainerEl.appendChild(optionRow);
        
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
      const correctAnswer = questionData.correct_answer;
      trueFalseContainer.innerHTML = `
        <div class="space-y-4">
          <label class="block text-gray-700 font-semibold" style="font-size: 1.3rem;">الإجابة الصحيحة</label>
          <div class="space-y-2">
            <label class="flex items-center gap-3">
              <input type="radio" name="correct_answer[]" value="0" class="text-blue-600" ${correctAnswer === 'true' ? 'checked' : ''} />
              <span style="font-size: 1.3rem;">صح</span>
            </label>
            <label class="flex items-center gap-3">
              <input type="radio" name="correct_answer[]" value="1" class="text-blue-600" ${correctAnswer === 'false' ? 'checked' : ''} />
              <span style="font-size: 1.3rem;">خطأ</span>
            </label>
          </div>
        </div>
      `;
      typeDependentFields.appendChild(trueFalseContainer);

    } else if (type === 'text') {
      const textContainer = document.createElement('div');
      textContainer.innerHTML = `
        <div class="space-y-4">
          <label for="correct_answer_text" class="block text-gray-700 font-semibold" style="font-size: 1.3rem;">الإجابة النموذجية (اختياري)</label>
          <textarea id="correct_answer_text" name="correct_answer" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" placeholder="اكتب الإجابة النموذجية هنا...">${questionData.correct_answer || ''}</textarea>
        </div>
      `;
      typeDependentFields.appendChild(textContainer);
    }
  }

  // Initial render
  renderFieldsByType(typeSelect.value);

  // Render fields when type changes
  typeSelect.addEventListener('change', function() {
    renderFieldsByType(this.value);
  });
});
</script>
@endpush
@endsection
