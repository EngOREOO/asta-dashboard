@php
    $title = 'استهلاك التخزين';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-600 via-slate-600 to-blue-700 bg-clip-text text-transparent">
          استهلاك التخزين
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-server mr-2 text-gray-500"></i>
          مراقبة استهلاك مساحة التخزين
        </p>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي التخزين</p>
            <p class="text-3xl font-bold text-gray-600">{{ number_format($totalStorage, 2) }} MB</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-slate-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-server text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">التخزين العام</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($publicStorage, 2) }} MB</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-folder text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">تخزين التطبيق</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($storageApp, 2) }} MB</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-database text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Storage by User -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-gray-500 via-slate-500 to-blue-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-users mr-3"></i>
          استهلاك التخزين حسب المستخدم
        </h3>
      </div>
      
      <div class="p-8">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-900">اسم المستخدم</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">البريد الإلكتروني</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">استهلاك التخزين</th>
                <th class="px-6 py-4 text-right font-medium text-gray-900">النسبة المئوية</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($storageByUser as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    {{ $user->email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">
                      {{ number_format($user->storage_used, 2) }} MB
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                    <div class="flex items-center">
                      <div class="w-full bg-gray-200 rounded-full h-2 mr-3">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: {{ $totalStorage > 0 ? ($user->storage_used / $totalStorage) * 100 : 0 }}%"></div>
                      </div>
                      <span class="text-sm text-gray-600">{{ $totalStorage > 0 ? round(($user->storage_used / $totalStorage) * 100, 1) : 0 }}%</span>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    لا توجد بيانات متاحة
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
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
