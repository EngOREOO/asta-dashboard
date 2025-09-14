@php($title = 'تفاصيل التسجيل')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6">
        <h2 class="font-bold text-white flex items-center" style="font-size: 1.8rem;">
          <i class="ti ti-user-check mr-3"></i>
          تسجيل: {{ $enrollment->student_name }} في {{ $enrollment->course_title }}
        </h2>
      </div>
      <div class="p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <div class="text-gray-500" style="font-size: 1.1rem;">الطالب</div>
            <div class="font-semibold" style="font-size: 1.3rem;">{{ $enrollment->student_name }}</div>
            <div class="text-gray-500" style="font-size: 1.0rem;">{{ $enrollment->student_email }}</div>
          </div>
          <div>
            <div class="text-gray-500" style="font-size: 1.1rem;">الدورة</div>
            <div class="font-semibold" style="font-size: 1.3rem;">{{ $enrollment->course_title }}</div>
            <div class="text-gray-500" style="font-size: 1.0rem;">المدرس: {{ $enrollment->instructor_name ?? '—' }}</div>
          </div>
          <div>
            <div class="text-gray-500" style="font-size: 1.1rem;">التقدم</div>
            <div class="flex items-center gap-3">
              <div class="w-40 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
              </div>
              <span class="text-gray-700" style="font-size: 1.2rem;">{{ $enrollment->progress ?? 0 }}%</span>
            </div>
          </div>
        </div>

        @if(! empty($enrollment->course_description))
        <div>
          <div class="text-gray-500" style="font-size: 1.1rem;">وصف الدورة</div>
          <p class="text-gray-800" style="font-size: 1.2rem;">{{ $enrollment->course_description }}</p>
        </div>
        @endif

        <div class="flex flex-wrap gap-3">
          <a href="{{ route('enrollments.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50" style="font-size: 1.2rem;">
            <i class="ti ti-arrow-right mr-2"></i> الرجوع للقائمة
          </a>
        </div>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="px-8 py-6 border-b border-white/20">
        <h3 class="font-bold text-gray-800" style="font-size: 1.6rem;">مواد الدورة وتقدم الطالب</h3>
      </div>
      <div class="p-8">
        @if(($materialsProgress ?? collect())->count() > 0)
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($materialsProgress as $mat)
              <div class="p-5 rounded-2xl border border-gray-200 bg-white">
                <div class="font-semibold mb-1" style="font-size: 1.2rem;">{{ $mat->title ?? ('مادة #' . $mat->id) }}</div>
                <div class="text-gray-500 mb-2" style="font-size: 1.0rem;">النوع: {{ $mat->type ?? '—' }}</div>
                <div class="flex items-center justify-between">
                  <div class="text-gray-600" style="font-size: 1.0rem;">الترتيب: {{ $mat->order ?? '—' }}</div>
                  @if($mat->completed_at)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200" style="font-size: 0.95rem;">مكتمل</span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-50 text-gray-700 border border-gray-200" style="font-size: 0.95rem;">غير مكتمل</span>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center text-gray-500" style="font-size: 1.2rem;">لا توجد مواد لهذا المساق أو لم يتم العثور على تقدم.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection


