@php($title = 'الأدوار والصلاحيات')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
      <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
            <i class="ti ti-lock text-2xl"></i>
          </div>
          <div class="min-w-0">
            <h1 class="text-2xl font-bold">الأدوار والصلاحيات</h1>
            <p class="text-white/80 text-sm">إدارة أدوار المستخدمين وصلاحياتهم</p>
          </div>
        </div>
        <a href="{{ route('roles.create') }}" class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur-sm transition-all duration-300">
          <i class="ti ti-plus text-lg ml-2"></i>
          إنشاء دور جديد
        </a>
      </div>
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
      <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <!-- Roles Grid -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
      <div class="px-8 py-6 border-b border-white/20 bg-gradient-to-r from-gray-50 to-gray-100">
        <h2 class="text-xl font-bold text-gray-800">جميع الأدوار</h2>
      </div>
      <div class="p-8">
        @if($roles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($roles as $role)
          <div class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                    {{ Str::substr($role->name, 0, 1) }}
                  </div>
                  <div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ $role->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $role->permissions_count }} صلاحية</p>
                  </div>
                </div>
                @if($role->name === 'super-admin')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  سوبر أدمن
                </span>
                @endif
              </div>
              
              <div class="flex items-center gap-2">
                <a href="{{ route('roles.edit', $role) }}" 
                   class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 text-sm font-medium">
                  <i class="ti ti-edit text-sm ml-1"></i>
                  تعديل
                </a>
                @if($role->name !== 'super-admin')
                <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟');" class="flex-1">
                  @csrf @method('DELETE')
                  <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 transition-all duration-300 text-sm font-medium">
                    <i class="ti ti-trash text-sm ml-1"></i>
                    حذف
                  </button>
                </form>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div class="text-center py-12">
          <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
            <i class="ti ti-lock text-3xl text-gray-400"></i>
          </div>
          <h3 class="text-lg font-semibold text-gray-600 mb-2">لا توجد أدوار</h3>
          <p class="text-gray-500 mb-6">ابدأ بإنشاء دور جديد لإدارة الصلاحيات</p>
          <a href="{{ route('roles.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">
            <i class="ti ti-plus text-lg ml-2"></i>
            إنشاء دور جديد
          </a>
        </div>
        @endif
        
        @if($roles->hasPages())
        <div class="mt-8 flex justify-center">
          {{ $roles->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection


