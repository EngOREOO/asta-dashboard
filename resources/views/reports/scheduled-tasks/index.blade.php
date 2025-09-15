@php
    $title = 'المهام المجدولة';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 via-green-600 to-teal-700 bg-clip-text text-transparent">
          المهام المجدولة
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-clock mr-2 text-emerald-500"></i>
          مراقبة وإدارة المهام المجدولة في النظام
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <button onclick="refreshTasks()" 
                class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 transition-all duration-300">
          <i class="ti ti-refresh mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          تحديث المهام
        </button>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي المهام</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalTasks }}</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-list text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">مكتملة</p>
            <p class="text-3xl font-bold text-green-600">{{ $completedTasks }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0 }}%</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-check text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">قيد التشغيل</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $runningTasks }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $totalTasks > 0 ? round(($runningTasks / $totalTasks) * 100, 1) : 0 }}%</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-loader text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">فشلت</p>
            <p class="text-3xl font-bold text-red-600">{{ $failedTasks }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $totalTasks > 0 ? round(($failedTasks / $totalTasks) * 100, 1) : 0 }}%</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-x text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-clock mr-3"></i>
          قائمة المهام المجدولة
        </h3>
      </div>
      
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-900">اسم المهمة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الجدولة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">آخر تشغيل</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">التشغيل التالي</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الحالة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">المدة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">الإجراءات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($scheduledTasks as $task)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 flex items-center justify-center">
                          <i class="ti ti-settings text-white text-sm"></i>
                        </div>
                      </div>
                      <div class="mr-4">
                        <div class="font-medium text-gray-900">{{ $task['name'] }}</div>
                        <div class="text-sm text-gray-500">{{ $task['description'] }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $task['schedule'] }}</code>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <div class="text-sm">{{ $task['last_run']->format('Y-m-d H:i') }}</div>
                    <div class="text-xs text-gray-500">{{ $task['last_run']->diffForHumans() }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <div class="text-sm">{{ $task['next_run']->format('Y-m-d H:i') }}</div>
                    <div class="text-xs text-gray-500">{{ $task['next_run']->diffForHumans() }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    @if($task['status'] === 'completed')
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="ti ti-check mr-1"></i>
                        مكتملة
                      </span>
                    @elseif($task['status'] === 'running')
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="ti ti-loader mr-1"></i>
                        قيد التشغيل
                      </span>
                    @elseif($task['status'] === 'failed')
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="ti ti-x mr-1"></i>
                        فشلت
                      </span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                      {{ $task['duration'] }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                      <button onclick="runTask('{{ $task['name'] }}')" 
                              class="text-emerald-600 hover:text-emerald-900 transition-colors duration-200">
                        <i class="ti ti-play text-lg"></i>
                      </button>
                      <button onclick="viewLogs('{{ $task['name'] }}')" 
                              class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                        <i class="ti ti-file-text text-lg"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Status Overview -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-chart-pie mr-3"></i>
          نظرة عامة على حالة المهام
        </h3>
      </div>
      <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center">
            <div class="text-4xl font-bold text-green-600 mb-2">{{ $completedTasks }}</div>
            <div class="text-gray-600">مهام مكتملة</div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
              <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"></div>
            </div>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-yellow-600 mb-2">{{ $runningTasks }}</div>
            <div class="text-gray-600">مهام قيد التشغيل</div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
              <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalTasks > 0 ? ($runningTasks / $totalTasks) * 100 : 0 }}%"></div>
            </div>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-red-600 mb-2">{{ $failedTasks }}</div>
            <div class="text-gray-600">مهام فشلت</div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
              <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalTasks > 0 ? ($failedTasks / $totalTasks) * 100 : 0 }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function refreshTasks() {
    window.location.reload();
}

function runTask(taskName) {
    if (confirm('هل تريد تشغيل المهمة "' + taskName + '" الآن؟')) {
        // Implementation for running task
        alert('سيتم تشغيل المهمة: ' + taskName);
    }
}

function viewLogs(taskName) {
    // Implementation for viewing task logs
    alert('عرض سجلات المهمة: ' + taskName);
}
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
