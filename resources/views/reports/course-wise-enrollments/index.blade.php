@php
    $title = 'تسجيلات الدورات';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-600 via-blue-600 to-cyan-700 bg-clip-text text-transparent">
          تسجيلات الدورات
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-book mr-2 text-teal-500"></i>
          تحليل التسجيلات لكل دورة على حدة
        </p>
      </div>
    </div>

    <!-- Course Enrollments Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-blue-500 to-cyan-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-list mr-3"></i>
          تسجيلات الدورات
        </h3>
      </div>
      
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-900">اسم الدورة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الفئة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">السعر</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">التسجيلات</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الإيرادات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($courseEnrollments as $course)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $course->title }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    {{ $course->category_name ?? 'غير محدد' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    @if($course->price > 0)
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800">
                        {{ number_format($course->price, 2) }} ريال
                      </span>
                    @else
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800">
                        مجاني
                      </span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">
                      {{ $course->enrollment_count }} طالب
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                    {{ number_format($course->total_revenue, 2) }} ريال
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    لا توجد بيانات متاحة
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if($courseEnrollments->hasPages())
          <div class="mt-6">
            {{ $courseEnrollments->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}
</style>
@endsection
