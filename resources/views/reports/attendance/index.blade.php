@php
    $title = 'تقرير الحضور والتفاعل';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Error Display -->
    @if(isset($error))
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6 animate-fade-in">
      <div class="flex items-center">
        <i class="ti ti-alert-circle text-red-500 text-2xl ml-3"></i>
        <div>
          <h3 class="text-lg font-semibold text-red-800">خطأ في تحميل البيانات</h3>
          <p class="text-red-600 mt-1">{{ $error }}</p>
        </div>
      </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 bg-clip-text text-transparent">
          تقرير الحضور والتفاعل
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-calendar-check mr-2 text-purple-500"></i>
          تحليل شامل لحضور الطلاب ونشاطهم في الدورات
        </p>
      </div>
      <div class="flex space-x-2 space-x-reverse mt-4 sm:mt-0">
        <button class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="ti ti-file-export ml-2"></i>
          تصدير Excel
        </button>
        <button class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="ti ti-file-download ml-2"></i>
          تصدير PDF
        </button>
      </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-filter mr-3"></i>
          فلاتر البحث المتقدمة
        </h3>
      </div>
      <div class="p-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" 
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" 
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">الدورة</label>
            <select name="course_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
              <option value="">جميع الدورات</option>
              @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                  {{ $course->title }}
                </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">المدرب</label>
            <select name="instructor_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
              <option value="">جميع المدربين</option>
              @foreach($instructors as $instructor)
                <option value="{{ $instructor->id }}" {{ $instructorId == $instructor->id ? 'selected' : '' }}>
                  {{ $instructor->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
            <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
              <option value="all" {{ $status == 'all' ? 'selected' : '' }}>جميع الحالات</option>
              <option value="enrolled" {{ $status == 'enrolled' ? 'selected' : '' }}>مسجل</option>
              <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>قيد التقدم</option>
              <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>مكتمل</option>
            </select>
          </div>
          <div class="flex items-end">
            <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-search ml-2"></i>
              تطبيق الفلاتر
            </button>
          </div>
          <div class="flex items-end">
            <a href="{{ route('admin.reports.attendance') }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl text-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-refresh ml-2"></i>
              إعادة تعيين
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي الطلاب</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalStudents) }}</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-users text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">طلاب نشطون</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($activeStudents) }}</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-user-check text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">معدل الحضور</p>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($attendanceRate, 1) }}%</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-percentage text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">معدل الإكمال</p>
            <p class="text-3xl font-bold text-orange-600">{{ number_format($completionRate, 1) }}%</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-trophy text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-slide-up">
      <!-- Monthly Attendance Chart -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-chart-line mr-3"></i>
            التسجيلات الشهرية
          </h3>
        </div>
        <div class="p-8">
          <canvas id="monthlyAttendanceChart" height="300"></canvas>
        </div>
      </div>

      <!-- Attendance by Course Chart -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-chart-pie mr-3"></i>
            التسجيلات حسب الدورة
          </h3>
        </div>
        <div class="p-8">
          <canvas id="courseAttendanceChart" height="300"></canvas>
        </div>
      </div>
    </div>

    <!-- Top Courses and Instructors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-slide-up">
      <!-- Top Courses -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-trophy mr-3"></i>
            أفضل الدورات
          </h3>
        </div>
        <div class="p-8">
          <div class="space-y-4">
            @foreach($attendanceByCourse->take(5) as $course)
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl hover:shadow-lg transition-all duration-300">
              <div class="flex-1">
                <h4 class="font-semibold text-gray-900 text-lg">{{ $course->title }}</h4>
                <p class="text-sm text-gray-600">{{ $course->total_enrollments }} تسجيل</p>
              </div>
              <div class="text-left">
                <p class="text-sm font-medium text-green-600">{{ $course->completed_students }} مكتمل</p>
                <p class="text-xs text-gray-500">{{ number_format($course->avg_progress, 1) }}% متوسط التقدم</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Top Instructors -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-user-star mr-3"></i>
            أفضل المدربين
          </h3>
        </div>
        <div class="p-8">
          <div class="space-y-4">
            @foreach($attendanceByInstructor->take(5) as $instructor)
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl hover:shadow-lg transition-all duration-300">
              <div class="flex-1">
                <h4 class="font-semibold text-gray-900 text-lg">{{ $instructor->instructor_name }}</h4>
                <p class="text-sm text-gray-600">{{ $instructor->total_enrollments }} تسجيل</p>
              </div>
              <div class="text-left">
                <p class="text-sm font-medium text-green-600">{{ $instructor->completed_students }} مكتمل</p>
                <p class="text-xs text-gray-500">{{ number_format($instructor->avg_progress, 1) }}% متوسط التقدم</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-clock mr-3"></i>
          النشاط الأخير
        </h3>
      </div>
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطالب</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدورة</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التقدم</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آخر نشاط</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($recentActivity as $activity)
              <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $activity->student_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $activity->course_title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                      <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: {{ $activity->progress ?? 0 }}%"></div>
                    </div>
                    <span class="text-sm font-medium">{{ $activity->progress ?? 0 }}%</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if($activity->status == 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      <i class="ti ti-check ml-1"></i>
                      مكتمل
                    </span>
                  @elseif($activity->status == 'in_progress')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      <i class="ti ti-clock ml-1"></i>
                      قيد التقدم
                    </span>
                  @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                      <i class="ti ti-user-plus ml-1"></i>
                      مسجل
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $activity->updated_at ? \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() : 'غير محدد' }}
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                  <i class="ti ti-inbox text-4xl mb-2 block"></i>
                  لا توجد بيانات متاحة
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Detailed Enrollments Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-list-details mr-3"></i>
          تفاصيل التسجيلات
        </h3>
      </div>
      <div class="p-8">
        <div class="overflow-x-auto rounded-xl border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">الطالب</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">البريد الإلكتروني</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">الدورة</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">المدرب</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">التقدم</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">تاريخ التسجيل</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">تاريخ الإكمال</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">الحالة</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              @forelse($enrollments as $enrollment)
              <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300 border-b border-gray-100">
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $enrollment->student_name ?? 'غير محدد' }}</td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">{{ $enrollment->student_email ?? 'غير محدد' }}</td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $enrollment->course_title ?? 'غير محدد' }}</td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">{{ $enrollment->instructor_name ?? 'غير محدد' }}</td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-900">
                  <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-3 mr-3 shadow-inner">
                      <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full shadow-sm transition-all duration-500" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                    </div>
                    <span class="text-sm font-bold text-gray-800 min-w-[3rem]">{{ $enrollment->progress ?? 0 }}%</span>
                  </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">{{ $enrollment->created_at ? \Carbon\Carbon::parse($enrollment->created_at)->format('Y-m-d') : 'غير محدد' }}</td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">{{ $enrollment->completed_at ? \Carbon\Carbon::parse($enrollment->completed_at)->format('Y-m-d') : '-' }}</td>
                <td class="px-6 py-5 whitespace-nowrap">
                  @if($enrollment->completed_at)
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200 shadow-sm">
                      <i class="ti ti-check ml-1 text-green-600"></i>
                      مكتمل
                    </span>
                  @elseif($enrollment->progress > 0)
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200 shadow-sm">
                      <i class="ti ti-clock ml-1 text-blue-600"></i>
                      قيد التقدم
                    </span>
                  @else
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200 shadow-sm">
                      <i class="ti ti-user-plus ml-1 text-gray-600"></i>
                      مسجل
                    </span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                      <i class="ti ti-inbox text-2xl text-gray-400"></i>
                    </div>
                    <div class="text-gray-500 text-lg font-medium">لا توجد بيانات متاحة</div>
                    <div class="text-gray-400 text-sm">لم يتم العثور على أي تسجيلات في الفترة المحددة</div>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if($enrollments->hasPages())
          <div class="mt-8">
            {{ $enrollments->appends(request()->query())->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Attendance Chart
    const monthlyCtx = document.getElementById('monthlyAttendanceChart').getContext('2d');
    const monthlyData = @json($attendanceByMonth);
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'التسجيلات',
                data: monthlyData.map(item => item.count),
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(147, 51, 234)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Course Attendance Chart
    const courseCtx = document.getElementById('courseAttendanceChart').getContext('2d');
    const courseData = @json($attendanceByCourse->take(5));
    
    new Chart(courseCtx, {
        type: 'doughnut',
        data: {
            labels: courseData.map(item => item.title),
            datasets: [{
                data: courseData.map(item => item.total_enrollments),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
</script>

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