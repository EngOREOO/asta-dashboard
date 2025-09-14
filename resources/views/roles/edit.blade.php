@php($title = 'تعديل دور')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic" x-data="permMatrix()">
  <div class="space-y-8 p-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
      <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
            <i class="ti ti-edit text-2xl"></i>
          </div>
          <div class="min-w-0">
            <h1 class="text-2xl font-bold">تعديل الدور: {{ $role->name }}</h1>
            <p class="text-white/80 text-sm">تعديل معلومات الدور وصلاحياته</p>
          </div>
        </div>
        <a href="{{ route('roles.index') }}" class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur-sm transition-all duration-300">
          <i class="ti ti-arrow-right text-lg ml-2"></i>
          العودة للأدوار
        </a>
      </div>
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
      <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-6">
      @csrf @method('PUT')
      
      <!-- Role Basic Info -->
      <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-white/20 bg-gradient-to-r from-gray-50 to-gray-100">
          <h2 class="text-xl font-bold text-gray-800">معلومات الدور الأساسية</h2>
        </div>
        <div class="p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-gray-700 mb-3 font-semibold">اسم الدور</label>
              <input name="name" 
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300" 
                     placeholder="أدخل اسم الدور"
                     value="{{ $role->name }}"
                     required>
              @error('name')
                <p class="text-red-600 mt-2 text-sm">{{ $message }}</p>
              @enderror
            </div>
            <div class="flex items-center justify-center">
              <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border border-red-200">
                <input type="checkbox" 
                       id="is_super" 
                       name="is_super" 
                       value="1" 
                       class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500"
                       {{ $role->name === 'super-admin' || $role->permissions->count() === \Spatie\Permission\Models\Permission::count() ? 'checked' : '' }}>
                <label for="is_super" class="text-gray-700 font-semibold">
                  <i class="ti ti-crown text-red-500 ml-1"></i>
                  سوبر أدمن (كل الصلاحيات)
                </label>
              </div>
            </div>
          </div>
          
          <!-- Debug Info -->
          <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
            <h4 class="font-semibold text-blue-800 mb-2">معلومات التصحيح:</h4>
            <p class="text-blue-700 text-sm">عدد الصلاحيات الحالية: {{ $role->permissions->count() }}</p>
            <p class="text-blue-700 text-sm">الصلاحيات: {{ $role->permissions->pluck('name')->implode(', ') }}</p>
          </div>
        </div>
      </div>

      <!-- Permissions Matrix -->
      <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-white/20 bg-gradient-to-r from-gray-50 to-gray-100 flex items-center justify-between">
          <h3 class="text-xl font-bold text-gray-800">مصفوفة الصلاحيات</h3>
          <button type="button" 
                  class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300"
                  @click="toggleAll()">
            <i class="ti ti-check text-lg ml-2"></i>
            تحديد الكل
          </button>
        </div>
        <div class="p-8 space-y-6">
          @php(
            $groups = [
              'إدارة المستخدمين' => explode(',', 'users,instructors,instructor-applications,roles-permissions'),
              'إدارة الدورات' => explode(',', 'courses,categories,course-levels,topics'),
              'الاختبارات والتقييم' => explode(',', 'assessments,quizzes'),
              'إدارة الطلاب' => explode(',', 'enrollments,student-progress'),
              'الكوبونات والخصومات' => explode(',', 'coupons'),
              'المسارات المهنية' => explode(',', 'degrees,learning-paths'),
              'الشهادات' => explode(',', 'certificates'),
              'التعليقات والآراء' => explode(',', 'reviews,testimonials,comments'),
              'الملفات' => explode(',', 'files'),
              'التحليلات' => explode(',', 'analytics'),
              'إعدادات النظام' => explode(',', 'system-settings'),
            ]
          )
          @php($rolePerms = $role->permissions->pluck('name')->toArray())
          @foreach($groups as $label => $modules)
          <div class="rounded-2xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                  <i class="ti ti-folder text-white text-sm"></i>
                </div>
                <h4 class="font-bold text-gray-800">{{ $label }}</h4>
              </div>
              <button type="button" 
                      class="inline-flex items-center px-3 py-1.5 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-300"
                      @click="toggleSection('{{ implode('|',$modules) }}')">
                <i class="ti ti-check text-sm ml-1"></i>
                تحديد القسم
              </button>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($modules as $module)
                <div class="bg-white/50 rounded-xl p-4 border border-gray-100">
                  <div class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="ti ti-dots text-gray-400"></i>
                    {{ $module }}
                  </div>
                  <div class="space-y-2">
                    @foreach(['read','create','edit','delete'] as $action)
                    @php($perm = $module.'.'.$action)
                    @php($actionAr = ['read'=>'قراءة','create'=>'إنشاء','edit'=>'تعديل','delete'=>'حذف'][$action])
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-all duration-300 cursor-pointer">
                      <input type="checkbox" 
                             name="permissions[]" 
                             value="{{ $perm }}" 
                             class="perm-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                             data-module="{{ $module }}"
                             {{ in_array($perm,$rolePerms) ? 'checked' : '' }}
                             title="{{ $perm }} - {{ in_array($perm,$rolePerms) ? 'محدد' : 'غير محدد' }}">
                      <span class="text-gray-700 text-sm font-medium">{{ $actionAr }}</span>
                      @if(in_array($perm,$rolePerms))
                        <span class="text-green-600 text-xs">✓</span>
                      @endif
                    </label>
                    @endforeach
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-4 justify-end">
        <a href="{{ route('roles.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-300">
          <i class="ti ti-x text-lg ml-2"></i>
          إلغاء
        </a>
        <button class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg">
          <i class="ti ti-check text-lg ml-2"></i>
          حفظ التغييرات
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function permMatrix(){
  return {
    toggleAll(){
      const boxes = document.querySelectorAll('.perm-checkbox');
      const allChecked = Array.from(boxes).every(b=>b.checked);
      boxes.forEach(b=> b.checked = !allChecked);
    },
    toggleSection(mods){
      const modules = mods.split('|');
      modules.forEach(m=>{
        const boxes = document.querySelectorAll(`.perm-checkbox[data-module="${m}"]`);
        const allChecked = Array.from(boxes).every(b=>b.checked);
        boxes.forEach(b=> b.checked = !allChecked);
      });
    }
  }
}
</script>
@endpush
@endsection


