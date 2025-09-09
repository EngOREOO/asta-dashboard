@php
    $title = 'تعديل المستخدم';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100">
  <div class="space-y-8 p-6">
    
    <!-- Success/Error Notifications -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-check-circle text-green-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-alert-circle text-red-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent">
          تعديل المستخدم
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-user mr-2 text-cyan-500"></i>
          تحديث معلومات المستخدم
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <a href="{{ route('users.show', $user) }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          عرض
        </a>
        <a href="{{ route('users.index') }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للمستخدمين
        </a>
      </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="text-2xl font-bold text-white flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-user text-white text-xl"></i>
            </div>
            معلومات المستخدم
          </h2>
          <p class="text-blue-100 mt-2">تحديث بيانات المستخدم</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-8">
          @csrf
          @method('PUT')
          
          <!-- Validation Errors -->
          @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 animate-shake">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-alert-circle text-red-400 text-xl"></i>
                </div>
                <div class="mr-3">
                  <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                  <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          @endif

          <!-- Basic Information -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-user mr-2 text-cyan-500"></i>
              المعلومات الأساسية
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('name') border-red-300 @enderror"
                       required>
                @error('name')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 email-label-arabic">البريد الإلكتروني</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('email') border-red-300 @enderror"
                       required>
                @error('email')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Password Section -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-lock mr-2 text-purple-500"></i>
              كلمة المرور
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('password') border-red-300 @enderror"
                       placeholder="اتركها فارغة للاحتفاظ بكلمة المرور الحالية">
                @error('password')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">اتركها فارغة للاحتفاظ بكلمة المرور الحالية</p>
              </div>
              
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                       placeholder="أعد إدخال كلمة المرور">
              </div>
            </div>
          </div>

          <!-- Role Section -->
          @if(!empty($roles))
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-shield mr-2 text-green-500"></i>
              الصلاحيات
            </h3>
            
            <div>
              <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">الدور</label>
              @php $currentRoleId = optional($user->roles->first())->id; @endphp
              <select id="role_id" 
                      name="role_id" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm">
                <option value="">بدون دور</option>
                @php($roleMap = ['admin' => 'مدير', 'instructor' => 'محاضر', 'student' => 'طالب', 'user' => 'مستخدم'])
                @foreach($roles as $id => $name)
                  <option value="{{ $id }}" @selected(old('role_id', $currentRoleId) == $id)>{{ $roleMap[$name] ?? $name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          @endif

          <!-- Action Buttons -->
          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-8 border-t border-gray-200">
            <a href="{{ route('users.show', $user) }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="ti ti-x mr-2"></i>
              إلغاء
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
              <i class="ti ti-check mr-2"></i>
              حفظ التغييرات
            </button>
          </div>
        </form>

        <!-- Danger Zone -->
        <div class="mt-8 pt-8 border-t border-red-200">
          <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-alert-triangle text-red-400 text-xl"></i>
              </div>
              <div class="mr-4">
                <h3 class="text-lg font-medium text-red-800">منطقة الخطر</h3>
                <p class="text-sm text-red-600 mt-1">حذف هذا المستخدم نهائياً. لا يمكن التراجع عن هذا الإجراء.</p>
              </div>
            </div>
            <div class="mt-4">
              <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                  <i class="ti ti-trash mr-2"></i>
                  حذف المستخدم
                </button>
              </form>
            </div>
          </div>
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

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
  20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-slide-down {
  animation: slideDown 0.4s ease-out;
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to right, #2563eb, #7c3aed);
}
</style>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    // Auto-hide notifications after 5 seconds
    setTimeout(function() {
      const notifications = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
      notifications.forEach(notification => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
      });
    }, 5000);

    // Form validation
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
      submitButton.disabled = true;
      submitButton.innerHTML = '<i class="ti ti-loader-2 mr-2 animate-spin"></i>جاري الحفظ...';
    });
  });
</script>
@endsection