@extends('layouts.dash')

@section('title', 'أسئلة التقييم')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6">
        <h2 class="font-bold text-white flex items-center" style="font-size: 1.8rem;">
          <i class="ti ti-list mr-3"></i>
          أسئلة: {{ $assessment->title }}
        </h2>
      </div>

      <div class="p-6 flex flex-wrap gap-3">
        <a href="{{ route('assessments.show', $assessment) }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50" style="font-size: 1.2rem;">
          <i class="ti ti-arrow-right mr-2"></i> الرجوع للتقييم
        </a>
        <a href="{{ route('assessments.edit', $assessment) }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50" style="font-size: 1.2rem;">
          <i class="ti ti-edit mr-2"></i> إضافة/تعديل أسئلة
        </a>
      </div>

      <div class="p-8">
        @if($questions->count() > 0)
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($questions as $question)
              <div class="p-5 rounded-2xl border border-gray-200 bg-white">
                <div class="flex items-start justify-between gap-3">
                  <div class="font-semibold" style="font-size: 1.3rem;">{{ $question->question ?? $question->question_text }}</div>
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200" style="font-size: 1.0rem;">
                    {{ ($question->type ?? null) === 'mcq' ? 'اختيارات' : 'نصي' }}
                  </span>
                </div>

                @php($opts = is_string($question->options) ? json_decode($question->options, true) : $question->options)
                @if(is_array($opts) && count($opts))
                  <div class="mt-3 space-y-1">
                    @foreach($opts as $idx => $opt)
                      <div class="flex items-center gap-2 text-gray-700">
                        <span class="text-gray-400">{{ is_numeric($idx) ? $idx+1 : $idx }}.</span>
                        <span>{{ $opt }}</span>
                        @if((string)$question->correct_answer === (string)$idx)
                          <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200" style="font-size: 0.9rem;">صحيح</span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                @endif

                <div class="mt-3 flex items-center justify-between">
                  <div class="text-gray-600" style="font-size: 1.0rem;">
                    الدرجة: <span class="font-semibold">{{ $question->points ?? 1 }}</span>
                  </div>
                  @if(($question->type ?? null) !== 'mcq' && $question->correct_answer)
                    <div class="text-gray-500" style="font-size: 0.95rem;">نموذج إجابة: {{ Str::limit($question->correct_answer, 40) }}</div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          @if($questions->hasPages())
            <div class="mt-6">
              {{ $questions->links() }}
            </div>
          @endif
        @else
          <div class="text-center py-12">
            <div class="mb-3">
              <i class="ti ti-help-circle text-3xl text-gray-400"></i>
            </div>
            <div class="text-gray-700" style="font-size: 1.3rem;">لا توجد أسئلة بعد</div>
            <p class="text-gray-500" style="font-size: 1.1rem;">يمكنك إضافة أسئلة من صفحة تعديل التقييم.</p>
            <a href="{{ route('assessments.edit', $assessment) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg" style="font-size: 1.2rem;">
              <i class="ti ti-plus mr-2"></i> إضافة أول سؤال
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
