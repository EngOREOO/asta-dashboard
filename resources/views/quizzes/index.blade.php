@php($title = 'إدارة الاختبارات')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إدارة الاختبارات</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-clipboard-check mr-2 text-cyan-500"></i>
          إدارة جميع الاختبارات في النظام
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('quizzes.create') }}" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;">
          <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
          إنشاء اختبار جديد
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl flex items-center animate-fade-in" style="font-size: 1.3rem;">
        <i class="ti ti-check-circle mr-2"></i>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl flex items-center animate-fade-in" style="font-size: 1.3rem;">
        <i class="ti ti-alert-circle mr-2"></i>
        {{ session('error') }}
      </div>
    @endif
    <!-- Main Content Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-clipboard-check text-white text-xl"></i>
            </div>
            قائمة الاختبارات
          </h2>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        @if($quizzes->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full text-right" style="font-size: 1.3rem;" id="quizzes-table">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">#</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الاختبار</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الدورة</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الأسئلة</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">المحاولات</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">المدة</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الحالة</th>
                  <th class="px-6 py-4 text-right font-semibold text-gray-700">الإجراءات</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                @foreach($quizzes as $quiz)
                  <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $quiz->id }}</td>
                    <td class="px-6 py-4">
                      <div>
                        <div class="font-semibold text-gray-900">{{ $quiz->title }}</div>
                        @if($quiz->description)
                          <div class="text-sm text-gray-500 mt-1">{{ Str::limit($quiz->description, 50) }}</div>
                        @endif
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <div>
                        <div class="font-semibold text-gray-900">{{ $quiz->course->title ?? 'لا توجد دورة' }}</div>
                        @if($quiz->course && $quiz->course->instructor)
                          <div class="text-sm text-gray-500 mt-1">بواسطة {{ $quiz->course->instructor->name }}</div>
                        @endif
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="ti ti-help-circle mr-1"></i>
                        {{ $quiz->questions_count ?? 0 }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="ti ti-users mr-1"></i>
                        {{ $quiz->attempts_count ?? 0 }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                      @if($quiz->duration_minutes)
                        <div class="flex items-center">
                          <i class="ti ti-clock mr-1"></i>
                          {{ $quiz->duration_minutes }} دقيقة
                        </div>
                      @else
                        <div class="flex items-center">
                          <i class="ti ti-infinity mr-1"></i>
                          بدون حد
                        </div>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      @if($quiz->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                          <i class="ti ti-check-circle mr-1"></i>
                          نشط
                        </span>
                      @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                          <i class="ti ti-x-circle mr-1"></i>
                          غير نشط
                        </span>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center space-x-2 space-x-reverse">
                        <a href="{{ route('quizzes.show', $quiz) }}" class="group inline-flex items-center p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 hover:scale-110 transition-all duration-200" title="عرض">
                          <i class="ti ti-eye group-hover:scale-110 transition-transform duration-200"></i>
                        </a>
                        <a href="{{ route('quizzes.questions', $quiz) }}" class="group inline-flex items-center p-2 bg-cyan-50 text-cyan-600 rounded-xl hover:bg-cyan-100 hover:scale-110 transition-all duration-200" title="الأسئلة">
                          <i class="ti ti-list group-hover:scale-110 transition-transform duration-200"></i>
                        </a>
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="group inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-xl hover:bg-gray-100 hover:scale-110 transition-all duration-200" title="تعديل">
                          <i class="ti ti-edit group-hover:scale-110 transition-transform duration-200"></i>
                        </a>
                        <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف جميع الأسئلة والمحاولات.')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="group inline-flex items-center p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 hover:scale-110 transition-all duration-200" title="حذف">
                            <i class="ti ti-trash group-hover:scale-110 transition-transform duration-200"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          @if($quizzes->hasPages())
            <div class="mt-8 flex justify-center">
              <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 shadow-lg">
                {{ $quizzes->links() }}
              </div>
            </div>
          @endif
        @else
          <!-- Empty State -->
          <div class="text-center py-16">
            <div class="mb-8">
              <div class="w-24 h-24 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ti ti-clipboard-check text-4xl text-teal-600"></i>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">لا توجد اختبارات</h3>
              <p class="text-gray-600 mb-8 max-w-md mx-auto" style="font-size: 1.3rem;">
                ابدأ بإنشاء اختبارات لتقييم معرفة طلابك وتتبع تقدمهم في التعلم.
              </p>
              <a href="{{ route('quizzes.create') }}" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;">
                <i class="ti ti-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                إنشاء أول اختبار
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#quizzes-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'desc']],
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
        },
        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6"<"mb-4 sm:mb-0"l><"mb-4 sm:mb-0"f>>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between mt-6"<"mb-4 sm:mb-0"i><"mb-4 sm:mb-0"p>>',
        initComplete: function() {
          // Add custom styling to DataTable elements
          $('.dataTables_length select').addClass('border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500');
          $('.dataTables_filter input').addClass('border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500');
          $('.dataTables_info').addClass('text-gray-600');
          $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 rounded-xl hover:bg-blue-50 transition-colors duration-200');
          $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-600 text-white');
        }
      });
    }
  });
</script>
@endpush
@endsection
