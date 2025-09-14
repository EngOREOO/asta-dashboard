@php
    $title = 'عرض السؤال';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">عرض السؤال</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">تفاصيل السؤال في بنك الأسئلة</p>
      </div>
      <div class="mt-4 sm:mt-0 flex space-x-4 space-x-reverse">
        <a href="{{ route('question-bank.edit', $questionBank) }}" class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-2xl hover:bg-yellow-600 shadow-lg transition-all duration-300" style="font-size: 1.3rem;">
          <i class="ti ti-edit mr-2"></i>
          تعديل
        </a>
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
              <i class="ti ti-eye text-white text-xl"></i>
            </div>
            تفاصيل السؤال
          </h2>
        </div>
      </div>

      <div class="p-8">
        <div class="space-y-6">
          <!-- Question Text -->
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-semibold text-gray-700 mb-3" style="font-size: 1.4rem;">نص السؤال</h3>
            <p class="text-gray-800 leading-relaxed" style="font-size: 1.3rem;">{{ $questionBank->question }}</p>
          </div>

          <!-- Question Details -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-xl p-4">
              <h4 class="font-semibold text-blue-700 mb-2" style="font-size: 1.2rem;">النوع</h4>
              <p class="text-blue-800" style="font-size: 1.3rem;">{{ ['mcq' => 'اختيارات متعددة', 'text' => 'نصي'][$questionBank->type] ?? $questionBank->type }}</p>
            </div>

            <div class="bg-green-50 rounded-xl p-4">
              <h4 class="font-semibold text-green-700 mb-2" style="font-size: 1.2rem;">الدرجات</h4>
              <p class="text-green-800" style="font-size: 1.3rem;">{{ $questionBank->points ?? '-' }}</p>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4">
              <h4 class="font-semibold text-yellow-700 mb-2" style="font-size: 1.2rem;">المستوى</h4>
              @if($questionBank->difficulty)
                @php
                  $difficultyLabels = [
                    'easy' => 'سهل',
                    'medium' => 'متوسط',
                    'hard' => 'صعب',
                  ];
                  $difficultyLabel = $difficultyLabels[$questionBank->difficulty] ?? $questionBank->difficulty;
                @endphp
                <p class="text-yellow-800" style="font-size: 1.3rem;">{{ $difficultyLabel }}</p>
              @else
                <p class="text-yellow-800" style="font-size: 1.3rem;">غير محدد</p>
              @endif
            </div>

            <div class="bg-purple-50 rounded-xl p-4">
              <h4 class="font-semibold text-purple-700 mb-2" style="font-size: 1.2rem;">التصنيف</h4>
              <p class="text-purple-800" style="font-size: 1.3rem;">{{ $questionBank->category ?? '-' }}</p>
            </div>
          </div>

          <!-- Options and Correct Answer -->
          @if($questionBank->type === 'mcq')
            <div class="bg-gray-50 rounded-xl p-6">
              <h3 class="font-semibold text-gray-700 mb-4" style="font-size: 1.4rem;">الخيارات والإجابة الصحيحة</h3>
              
              @if($questionBank->options && count($questionBank->options) > 0)
                <div class="space-y-3">
                  @foreach($questionBank->options as $index => $option)
                    @php
                      $isCorrect = false;
                      if ($questionBank->correct_answer) {
                        $correctAnswers = explode(',', $questionBank->correct_answer);
                        $isCorrect = in_array((string)$index, $correctAnswers);
                      }
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-lg {{ $isCorrect ? 'bg-green-100 border border-green-300' : 'bg-white border border-gray-200' }}">
                      <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isCorrect ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}" style="font-size: 1.2rem;">
                        {{ $index + 1 }}
                      </div>
                      <span class="flex-1 {{ $isCorrect ? 'text-green-800 font-semibold' : 'text-gray-700' }}" style="font-size: 1.3rem;">{{ $option }}</span>
                      @if($isCorrect)
                        <i class="ti ti-check text-green-600" style="font-size: 1.5rem;"></i>
                      @endif
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-gray-500" style="font-size: 1.3rem;">لا توجد خيارات محددة</p>
              @endif
            </div>
          @elseif($questionBank->type === 'text')
            <div class="bg-gray-50 rounded-xl p-6">
              <h3 class="font-semibold text-gray-700 mb-4" style="font-size: 1.4rem;">الإجابة النموذجية</h3>
              @if($questionBank->correct_answer)
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                  <p class="text-gray-800 leading-relaxed" style="font-size: 1.3rem;">{{ $questionBank->correct_answer }}</p>
                </div>
              @else
                <p class="text-gray-500" style="font-size: 1.3rem;">لا توجد إجابة نموذجية محددة</p>
              @endif
            </div>
          @endif

          <!-- Assessment Info -->
          @if($questionBank->assessment)
            <div class="bg-gray-50 rounded-xl p-6">
              <h3 class="font-semibold text-gray-700 mb-4" style="font-size: 1.4rem;">التقييم المرتبط</h3>
              <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-gray-800" style="font-size: 1.3rem;">
                  <a href="{{ route('assessments.show', $questionBank->assessment) }}" class="text-blue-600 hover:text-blue-800">
                    {{ $questionBank->assessment->title }}
                  </a>
                </p>
                @if($questionBank->assessment->course)
                  <p class="text-gray-600 mt-2" style="font-size: 1.2rem;">
                    الدورة: {{ $questionBank->assessment->course->title }}
                  </p>
                @endif
              </div>
            </div>
          @else
            <div class="bg-gray-50 rounded-xl p-6">
              <h3 class="font-semibold text-gray-700 mb-4" style="font-size: 1.4rem;">التقييم المرتبط</h3>
              <p class="text-gray-500" style="font-size: 1.3rem;">هذا السؤال جزء من بنك الأسئلة العام</p>
            </div>
          @endif

          <!-- Metadata -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-xl p-4">
              <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 1.2rem;">تاريخ الإنشاء</h4>
              <p class="text-gray-800" style="font-size: 1.3rem;">{{ $questionBank->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
              <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 1.2rem;">آخر تحديث</h4>
              <p class="text-gray-800" style="font-size: 1.3rem;">{{ $questionBank->updated_at->format('Y-m-d H:i') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
