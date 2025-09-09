@extends('layouts.dash')

@section('title', 'تحليلات المواد التعليمية')

@section('content')
<div class="space-y-6">
  <!-- Header Section -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="relative h-32 bg-gradient-to-r from-blue-600 to-indigo-700">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-indigo-700/90"></div>
      
      <!-- Header Content -->
      <div class="absolute inset-0 flex flex-col justify-center p-6 text-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold mb-2">تحليلات المواد التعليمية</h1>
            <p class="text-blue-100 text-lg">نظرة شاملة على أداء المواد التعليمية في النظام</p>
          </div>
          <div class="mt-4 sm:mt-0 flex items-center gap-3">
            <a href="{{ route('course-materials.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200">
              <i class="ti ti-plus mr-2"></i>
              إضافة مادة جديدة
            </a>
            <a href="{{ route('course-materials.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200">
              <i class="ti ti-list mr-2"></i>
              عرض جميع المواد
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-admin.card 
      title="إجمالي المواد" 
      subtitle="جميع المواد التعليمية"
      icon="ti ti-files"
      color="blue"
    >
      <div class="text-2xl font-bold text-blue-600">{{ $totalMaterials }}</div>
    </x-admin.card>
    
    <x-admin.card 
      title="المواد المرئية" 
      subtitle="فيديوهات تعليمية"
      icon="ti ti-video"
      color="green"
    >
      <div class="text-2xl font-bold text-green-600">{{ $materialsByType->where('type', 'video')->first()?->count ?? 0 }}</div>
    </x-admin.card>
    
    <x-admin.card 
      title="المواد المكتوبة" 
      subtitle="مستندات وملفات"
      icon="ti ti-file-document"
      color="purple"
    >
      <div class="text-2xl font-bold text-purple-600">{{ $materialsByType->where('type', 'document')->first()?->count ?? 0 }}</div>
    </x-admin.card>
    
    <x-admin.card 
      title="معدل الإكمال" 
      subtitle="نسبة إكمال المواد"
      icon="ti ti-check-circle"
      color="teal"
    >
      <div class="text-2xl font-bold text-teal-600">
        {{ number_format($totalMaterials > 0 ? ($popularMaterials->sum('completion_count') / $totalMaterials * 100) : 0, 1) }}%
      </div>
    </x-admin.card>
  </div>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Popular Materials Section -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-100">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-trending-up text-blue-500 mr-2"></i>
              المواد الأكثر شعبية
            </h2>
            <span class="text-sm text-gray-500">{{ $popularMaterials->total() }} مادة</span>
          </div>
        </div>
        
        <div class="p-4">
          @if($popularMaterials->count() > 0)
            <div class="space-y-4">
              @foreach($popularMaterials as $material)
                <div class="group border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer"
                     onclick="window.location.href='{{ route('course-materials.show', $material) }}'">
                  <div class="flex items-start space-x-4 rtl:space-x-reverse">
                    <!-- Material Icon -->
                    <div class="flex-shrink-0">
                      <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="ti ti-{{ $material->type === 'video' ? 'video' : ($material->type === 'document' ? 'file-document' : 'check') }} text-white text-lg"></i>
                      </div>
                    </div>
                    
                    <!-- Material Details -->
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                          {{ $material->title }}
                        </h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                          {{ $material->type === 'video' ? 'bg-blue-100 text-blue-800' : 
                             ($material->type === 'document' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }}">
                          <i class="ti ti-{{ $material->type === 'video' ? 'video' : ($material->type === 'document' ? 'file-document' : 'check') }} mr-1"></i>
                          {{ $material->type === 'video' ? 'فيديو' : ($material->type === 'document' ? 'مستند' : 'اختبار') }}
                        </span>
                      </div>
                      
                      @if($material->description)
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                          {{ Str::limit($material->description, 120) }}
                        </p>
                      @endif
                      
                      <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 rtl:space-x-reverse text-sm text-gray-500">
                          <div class="flex items-center space-x-1 rtl:space-x-reverse">
                            <i class="ti ti-book text-blue-500"></i>
                            <span class="font-medium">{{ $material->course_title ?? 'دورة غير محددة' }}</span>
                          </div>
                          <div class="flex items-center space-x-1 rtl:space-x-reverse">
                            <i class="ti ti-check text-green-500"></i>
                            <span class="font-medium">{{ $material->completion_count ?? 0 }} إكمال</span>
                          </div>
                          @if($material->duration)
                            <div class="flex items-center space-x-1 rtl:space-x-reverse">
                              <i class="ti ti-clock text-yellow-500"></i>
                              <span class="font-medium">{{ $material->duration }} دقيقة</span>
                            </div>
                          @endif
                        </div>
                        
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                          <a href="{{ route('course-materials.show', $material) }}" 
                             class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                            <i class="ti ti-eye mr-1"></i>
                            عرض
                          </a>
                          <a href="{{ route('course-materials.edit', $material) }}" 
                             class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="ti ti-edit mr-1"></i>
                            تعديل
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Pagination -->
            @if($popularMaterials->hasPages())
              <div class="mt-6 flex justify-center">
                {{ $popularMaterials->links() }}
              </div>
            @endif
          @else
            <div class="text-center py-12">
              <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-file-text text-2xl text-gray-400"></i>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مواد</h3>
              <p class="text-gray-600 mb-6">لم يتم إنشاء أي مواد تعليمية بعد.</p>
              <a href="{{ route('course-materials.create') }}" 
                 class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="ti ti-plus mr-2"></i>
                إضافة مادة جديدة
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Materials by Type -->
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="ti ti-chart-pie text-purple-500 mr-2"></i>
            المواد حسب النوع
          </h3>
        </div>
        
        <div class="p-4">
          @if($materialsByType->count() > 0)
            <div class="space-y-3">
              @foreach($materialsByType as $type)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                  <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-3 h-3 rounded-full 
                      {{ $type->type === 'video' ? 'bg-blue-500' : 
                         ($type->type === 'document' ? 'bg-purple-500' : 'bg-green-500') }}"></div>
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                      <i class="ti ti-{{ $type->type === 'video' ? 'video' : ($type->type === 'document' ? 'file-document' : 'check') }} mr-2 text-gray-400"></i>
                      {{ $type->type === 'video' ? 'فيديو' : ($type->type === 'document' ? 'مستند' : 'اختبار') }}
                    </span>
                  </div>
                  <span class="text-lg font-bold text-gray-900">{{ $type->count }}</span>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-8">
              <i class="ti ti-chart-pie text-2xl text-gray-300 mb-2"></i>
              <p class="text-gray-500">لا توجد بيانات متاحة</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="ti ti-bolt text-yellow-500 mr-2"></i>
            إجراءات سريعة
          </h3>
        </div>
        
        <div class="p-4 space-y-3">
          <a href="{{ route('course-materials.create') }}" 
             class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="ti ti-plus mr-2"></i>
            إضافة مادة جديدة
          </a>
          
          <a href="{{ route('course-materials.index') }}" 
             class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="ti ti-list mr-2"></i>
            عرض جميع المواد
          </a>
        </div>
      </div>

      <!-- Performance Insights -->
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="ti ti-target text-red-500 mr-2"></i>
            رؤى الأداء
          </h3>
        </div>
        
        <div class="p-4 space-y-4">
          <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
            <div class="text-2xl font-bold text-blue-600 mb-1">
              {{ $popularMaterials->count() > 0 ? $popularMaterials->first()->completion_count : 0 }}
            </div>
            <div class="text-sm text-blue-700 font-medium">أعلى معدل إكمال</div>
          </div>
          
          <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-100">
            <div class="text-2xl font-bold text-green-600 mb-1">
              {{ $materialsByType->sum('count') }}
            </div>
            <div class="text-sm text-green-700 font-medium">إجمالي المواد</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth hover transitions */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Enhanced card shadows */
.card-hover {
  transition: all 0.3s ease;
}

.card-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endsection
