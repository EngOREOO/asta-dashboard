@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <i class="ti ti-clipboard-text text-white text-xl mr-3"></i>
            {{ $assessment->title }}
            @if($assessment->is_active)
              <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-sm">نشط</span>
            @else
              <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-sm">غير نشط</span>
            @endif
          </h2>
        </div>
        <div class="p-8 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <div class="text-gray-500" style="font-size: 1.2rem;">الدورة</div>
              <div class="font-semibold" style="font-size: 1.4rem;">{{ optional($assessment->course)->title ?? '—' }}</div>
              @if($assessment->course && $assessment->course->instructor)
                <div class="text-gray-500" style="font-size: 1.1rem;">المحاضر: {{ $assessment->course->instructor->name }}</div>
              @endif
            </div>
            <div>
              <div class="text-gray-500" style="font-size: 1.2rem;">النوع</div>
              <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200" style="font-size: 1.1rem;">
                  <i class="ti ti-help-circle mr-2"></i>{{ $assessment->type }}
                </span>
              </div>
            </div>
            @if($assessment->description)
            <div class="md:col-span-2">
              <div class="text-gray-500" style="font-size: 1.2rem;">الوصف</div>
              <p class="text-gray-800" style="font-size: 1.3rem;">{{ $assessment->description }}</p>
            </div>
            @endif
            <div>
              <div class="text-gray-500" style="font-size: 1.2rem;">المدة</div>
              <div style="font-size: 1.3rem;">{{ $assessment->duration_minutes ? $assessment->duration_minutes.' دقيقة' : 'بدون حد زمني' }}</div>
            </div>
            <div>
              <div class="text-gray-500" style="font-size: 1.2rem;">أقصى المحاولات</div>
              <div style="font-size: 1.3rem;">{{ $assessment->max_attempts ? $assessment->max_attempts : 'غير محدود' }}</div>
            </div>
            @if($assessment->passing_score)
            <div>
              <div class="text-gray-500" style="font-size: 1.2rem;">درجة النجاح</div>
              <div style="font-size: 1.3rem;">{{ $assessment->passing_score }}%</div>
            </div>
            @endif
            <div class="md:col-span-2 flex flex-wrap gap-2 items-center">
              @if($assessment->randomize_questions)
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200" style="font-size: 1.1rem;">ترتيب عشوائي للأسئلة</span>
              @endif
              @if($assessment->show_results_immediately)
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200" style="font-size: 1.1rem;">عرض النتائج فوراً</span>
              @endif
            </div>
          </div>

          <!-- Compact students answers block -->
          @if($assessment->attempts->count() > 0)
          <div class="mt-4" x-data="attemptGrader()">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-bold text-gray-800" style="font-size: 1.4rem;">إجابات الطلاب (مختصر)</h3>
              <a href="#full-attempts" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" style="font-size: 1.1rem;">المزيد</a>
            </div>
            <div class="space-y-3">
              @foreach($assessment->attempts->take(5) as $attempt)
              <div class="flex items-center justify-between p-3 rounded-2xl border border-gray-200 bg-white">
                <div>
                  <div class="font-semibold" style="font-size: 1.2rem;">{{ $attempt->user->name }}</div>
                  <div class="text-gray-500" style="font-size: 1.0rem;">الحالة: {{ $attempt->status ?? '—' }} @if($attempt->completed_at) | {{ optional($attempt->completed_at)->format('Y-m-d H:i') }} @endif</div>
                </div>
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200" style="font-size: 0.95rem;">{{ number_format($attempt->score ?? 0, 1) }}%</span>
                  <button type="button" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl" style="font-size: 1.0rem;" @click="open({{ $attempt->id }})">عرض</button>
                </div>
              </div>
              @endforeach
            </div>

            <!-- Inline Modal -->
            <div class="fixed inset-0 bg-black/40 z-50" x-show="openModal" x-transition @click.self="close()">
              <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-gray-200" x-show="openModal" x-transition>
                  <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h4 class="font-bold" style="font-size: 1.4rem;">إجابات الطالب</h4>
                    <button class="text-gray-500 hover:text-gray-800" @click="close()"><i class="ti ti-x"></i></button>
                  </div>
                  <div class="p-6 max-h-[70vh] overflow-auto">
                    <template x-if="attempt">
                      <div>
                        <template x-for="ans in attempt.answers" :key="ans.id">
                          <div class="mb-4 p-4 rounded-2xl border border-gray-200">
                            <div class="mb-1"><span class="text-gray-600">السؤال:</span> <span x-text="findQuestion(ans.question_id).question"></span></div>
                            <div class="mb-1"><span class="text-gray-600">إجابة الطالب:</span> <span x-text="ans.answer || '—'"></span></div>
                            <div class="grid grid-cols-12 items-center gap-3 mt-2">
                              <div class="col-span-2 text-gray-600">الدرجات</div>
                              <div class="col-span-4">
                                <input type="number" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" step="0.5" min="0" :max="findQuestion(ans.question_id).points || 0" x-model="grades[ans.id]">
                              </div>
                              <div class="col-span-6 text-gray-500">/<span x-text="findQuestion(ans.question_id).points || 0"></span></div>
                            </div>
                          </div>
                        </template>
                      </div>
                    </template>
                    <template x-if="!attempt">
                      <div class="text-center text-gray-500">جاري التحميل...</div>
                    </template>
                  </div>
                  <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-2">
                    <button class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" @click="close()">إغلاق</button>
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl" @click="save()">حفظ التقييم</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          <div class="flex flex-wrap gap-3 pt-2">
            <a href="{{ route('assessments.questions', $assessment) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg" style="font-size: 1.2rem;">
              <i class="ti ti-list mr-2"></i> إدارة الأسئلة
            </a>
            <a href="{{ route('assessments.edit', $assessment) }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50" style="font-size: 1.2rem;">
              <i class="ti ti-edit mr-2"></i> تعديل
            </a>
            <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50" style="font-size: 1.2rem;">
              <i class="ti ti-arrow-right mr-2"></i> الرجوع للقائمة
            </a>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="px-8 py-6 border-b border-white/20">
          <h3 class="font-bold text-gray-800" style="font-size: 1.6rem;">إحصائيات سريعة</h3>
        </div>
        <div class="p-8 grid grid-cols-2 gap-6 text-center">
          <div class="rounded-2xl p-6 bg-indigo-50 border border-indigo-100">
            <div class="text-indigo-600 font-bold" style="font-size: 2rem;">{{ $assessment->questions->count() }}</div>
            <div class="text-gray-600" style="font-size: 1.1rem;">الأسئلة</div>
          </div>
          <div class="rounded-2xl p-6 bg-emerald-50 border border-emerald-100">
            <div class="text-emerald-600 font-bold" style="font-size: 2rem;">{{ $assessment->attempts->count() }}</div>
            <div class="text-gray-600" style="font-size: 1.1rem;">المحاولات</div>
          </div>
        </div>
      </div>
    </div>

    @if($assessment->attempts->count() > 0)
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20" x-data="attemptGrader()">
      <div class="px-8 py-6 border-b border-white/20 flex items-center justify-between">
        <h3 class="font-bold text-gray-800" style="font-size: 1.6rem;">إجابات الطلاب</h3>
        <button type="button" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50" style="font-size: 1.1rem;" @click="reload()">تحديث</button>
      </div>
      <div class="p-6 space-y-4">
        @foreach($assessment->attempts as $attempt)
        <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-200">
          <div>
            <div class="font-semibold" style="font-size: 1.3rem;">{{ $attempt->user->name }}</div>
            <div class="text-gray-500" style="font-size: 1.1rem;">الحالة: {{ $attempt->status ?? '—' }} @if($attempt->completed_at) | {{ optional($attempt->completed_at)->format('Y-m-d H:i') }} @endif</div>
          </div>
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 border border-cyan-200" style="font-size: 1.1rem;">{{ number_format($attempt->score ?? 0, 1) }}%</span>
            <button type="button" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl" style="font-size: 1.1rem;" @click="open({{ $attempt->id }})">عرض الإجابات</button>
            <button type="button" class="inline-flex items-center px-3 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" style="font-size: 1.1rem;" @click="regrade({{ $attempt->id }})">إعادة التصحيح</button>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Modal -->
      <div class="fixed inset-0 bg-black/40 z-50" x-show="openModal" x-transition @click.self="close()">
        <div class="absolute inset-0 flex items-center justify-center p-4">
          <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-gray-200" x-show="openModal" x-transition>
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
              <h4 class="font-bold" style="font-size: 1.4rem;">إجابات الطالب</h4>
              <button class="text-gray-500 hover:text-gray-800" @click="close()"><i class="ti ti-x"></i></button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-auto">
              <template x-if="attempt">
                <div>
                  <template x-for="ans in attempt.answers" :key="ans.id">
                    <div class="mb-4 p-4 rounded-2xl border border-gray-200">
                      <div class="mb-1"><span class="text-gray-600">السؤال:</span> <span x-text="findQuestion(ans.question_id).question"></span></div>
                      <div class="mb-1"><span class="text-gray-600">إجابة الطالب:</span> <span x-text="ans.answer || '—'"></span></div>
                      <div class="grid grid-cols-12 items-center gap-3 mt-2">
                        <div class="col-span-2 text-gray-600">الدرجات</div>
                        <div class="col-span-4">
                          <input type="number" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" step="0.5" min="0" :max="findQuestion(ans.question_id).points || 0" x-model="grades[ans.id]">
                        </div>
                        <div class="col-span-6 text-gray-500">/<span x-text="findQuestion(ans.question_id).points || 0"></span></div>
                      </div>
                    </div>
                  </template>
                </div>
              </template>
              <template x-if="!attempt">
                <div class="text-center text-gray-500">جاري التحميل...</div>
              </template>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-2">
              <button class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50" @click="close()">إغلاق</button>
              <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl" @click="save()">حفظ التقييم</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
function attemptGrader(){
  return {
    openModal: false,
    attempt: null,
    grades: {},
    async open(id){
      this.openModal = true;
      this.attempt = null;
      this.grades = {};
      const res = await fetch(`{{ route('assessment-attempts.show', ['attempt' => 'ATID']) }}`.replace('ATID', id));
      if(res.ok){
        const data = await res.json();
        this.attempt = data.attempt;
        (this.attempt.answers || []).forEach(a => { this.grades[a.id] = a.points_earned ?? 0; });
      }
    },
    close(){ this.openModal = false; },
    findQuestion(qid){
      if(!this.attempt) return {points:0, question:''};
      return (this.attempt.assessment.questions || []).find(q => q.id === qid) || {points:0, question:''};
    },
    async save(){
      if(!this.attempt) return;
      const answers = Object.keys(this.grades).map(id => ({id, points_earned: parseFloat(this.grades[id] || 0)}));
      await fetch(`{{ route('assessment-attempts.grade', ['attempt' => 'ATID']) }}`.replace('ATID', this.attempt.id), {
        method: 'POST', headers: {'X-CSRF-TOKEN': `{{ csrf_token() }}`, 'Content-Type':'application/json'}, body: JSON.stringify({answers})
      });
      this.close();
      location.reload();
    },
    async regrade(id){
      await fetch(`{{ route('assessment-attempts.regrade', ['attempt' => 'ATID']) }}`.replace('ATID', id), {method:'POST', headers:{'X-CSRF-TOKEN': `{{ csrf_token() }}`}});
      location.reload();
    },
    reload(){ location.reload(); }
  }
}
</script>
@endpush
@endsection
