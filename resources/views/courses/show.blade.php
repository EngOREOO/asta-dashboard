@php($title = 'تفاصيل الدورة')
@extends('layouts.dash')

@section('content')
<div class="space-y-6">
  <!-- Header Section with Course Thumbnail -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="relative h-48 bg-gradient-to-r from-blue-600 to-indigo-700">
      @if($course->thumbnail && Storage::disk('public')->exists($course->thumbnail))
        <img src="{{ Storage::disk('public')->url($course->thumbnail) }}" 
             alt="{{ $course->title }}"
             class="w-full h-full object-cover opacity-20">
      @endif
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-indigo-700/90"></div>
      
      <!-- Header Content -->
      <div class="absolute inset-0 flex flex-col justify-end p-6 text-white">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between">
          <div class="mb-4 sm:mb-0">
            <h1 class="font-bold mb-2 text-white" style="font-size: 1.9rem;">{{ $course->title }}</h1>
            <p class="text-blue-100" style="font-size: 1.3rem;">
              @if($course->instructor)
                <i class="ti ti-user mr-2"></i>{{ $course->instructor->name }}
              @else
                <i class="ti ti-user mr-2"></i>بدون مدرس
              @endif
            </p>
          </div>
          <div class="flex items-center gap-3">
            @can('update', $course)
              <a href="{{ route('courses.edit', $course) }}" 
                 class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200">
                <i class="ti ti-edit mr-2"></i>
                <span style="font-size: 1.3rem;">تعديل الدورة</span>
              </a>
            @endcan
            <a href="{{ route('courses.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white hover:bg-white/30 transition-all duration-200">
              <i class="ti ti-arrow-left mr-2"></i>
              <span style="font-size: 1.3rem;">العودة</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Course Overview Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-admin.card title="الحالة" subtitle="حالة الدورة" icon="ti ti-check" color="green">
      @php($statusAr = ['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'][$course->status] ?? $course->status)
      <div class="text-center">
        <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium" style="font-size: 1.3rem;
          {{ $course->status === 'approved' ? 'bg-green-100 text-green-800' : 
             ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
             ($course->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
          <i class="ti ti-{{ $course->status === 'approved' ? 'check' : ($course->status === 'pending' ? 'clock' : ($course->status === 'rejected' ? 'x' : 'file')) }} mr-1"></i>
          {{ $statusAr }}
        </span>
      </div>
    </x-admin.card>
    
    <x-admin.card title="السعر" subtitle="تكلفة الدورة" icon="ti ti-currency-dollar" color="blue">
      <div class="text-center space-y-2">
        @php($original = (float)($course->price ?? 0))
        @php($discounted = (float)$course->discounted_price)
        @if($original > 0)
          @if($discounted < $original)
            <div class="flex items-center justify-center gap-2">
              <span class="line-through text-gray-400" style="font-size: 1.3rem;">{{ number_format($original, 2) }}</span>
              <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-emerald-100 text-emerald-800" style="font-size: 1.3rem;">
                {{ number_format($discounted, 2) }}
                <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-5 h-5 mr-1">
              </span>
            </div>
            <div>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-pink-100 text-pink-800" style="font-size: 1.1rem;">خصم {{ round((1-($discounted/$original))*100) }}%</span>
            </div>
          @else
            <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-blue-100 text-blue-800" style="font-size: 1.3rem;">
              {{ number_format($original, 2) }}
              <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-5 h-5 mr-1">
            </span>
          @endif
        @else
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.3rem;">
            <i class="ti ti-gift mr-1"></i>
            مجاني
          </span>
        @endif
        @php($activeCoupons = \App\Models\Coupon::active()->where(function($q) use($course){ $q->where('applies_to','all')->orWhereHas('courses', fn($c)=>$c->where('courses.id',$course->id)); })->get())
        @if($activeCoupons->count())
          <div class="flex flex-wrap gap-2 justify-center">
            @foreach($activeCoupons as $c)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-teal-100 text-teal-800" style="font-size: 1.1rem;">كوبون {{ $c->code }} — {{ $c->percentage }}%</span>
            @endforeach
          </div>
        @endif
      </div>
    </x-admin.card>
    
    <x-admin.card title="المستوى" subtitle="مستوى الصعوبة" icon="ti ti-stairs" color="purple">
      <div class="text-center">
        @if($course->difficulty_level)
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-purple-100 text-purple-800" style="font-size: 1.3rem;">
            <i class="ti ti-{{ $course->difficulty_level === 'beginner' ? 'baby' : ($course->difficulty_level === 'intermediate' ? 'user' : 'crown') }} mr-1"></i>
            {{ ['beginner'=>'مبتدئ','intermediate'=>'متوسط','advanced'=>'متقدم'][$course->difficulty_level] ?? $course->difficulty_level }}
          </span>
        @else
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">
            <i class="ti ti-stairs mr-1"></i>
            غير محدد
          </span>
        @endif
      </div>
    </x-admin.card>
    
    <x-admin.card title="المدة" subtitle="وقت الدورة" icon="ti ti-clock" color="yellow">
      <div class="text-center">
        @if($course->estimated_duration)
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-yellow-100 text-yellow-800" style="font-size: 1.3rem;">
            <i class="ti ti-clock mr-1"></i>
            {{ $course->estimated_duration }} ساعة
          </span>
        @else
          <span class="inline-flex items-center px-3 py-1.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">
            <i class="ti ti-clock mr-1"></i>
            غير محدد
          </span>
        @endif
      </div>
    </x-admin.card>
  </div>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Course Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Course Description -->
      <x-admin.card title="وصف الدورة" icon="ti ti-file-text" color="blue">
        <div class="prose prose-gray max-w-none">
          <p class="text-gray-700 leading-relaxed" style="font-size: 1.3rem;">
            {{ $course->description ?? 'لا يوجد وصف متاح لهذه الدورة.' }}
          </p>
        </div>
      </x-admin.card>

      <!-- Course Materials / Lessons -->
      @if($course->materials && $course->materials->count() > 0)
        <x-admin.card title="الدروس والمواد" subtitle="{{ $course->materials->count() }} عنصر" icon="ti ti-files" color="green">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">#</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">العنوان</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">النوع</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">المدة</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">الترتيب</th>
                  <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">الإجراءات</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($course->materials as $i => $material)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2">{{ $i+1 }}</td>
                  <td class="px-4 py-2">{{ $material->title }}</td>
                  <td class="px-4 py-2">{{ ['video'=>'فيديو','document'=>'مستند','link'=>'رابط','quiz'=>'اختبار'][$material->type] ?? $material->type }}</td>
                  <td class="px-4 py-2">{{ $material->duration ? $material->duration.' دقيقة' : '—' }}</td>
                  <td class="px-4 py-2">{{ $material->order ?? 0 }}</td>
                  <td class="px-4 py-2">
                    <div class="inline-flex items-center gap-2">
                      <a href="{{ route('course-materials.show', $material) }}" class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" title="عرض"><i class="ti ti-eye text-sm"></i></a>
                      <a href="{{ route('course-materials.edit', $material) }}" class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" title="تعديل"><i class="ti ti-edit text-sm"></i></a>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </x-admin.card>
      @endif

      <!-- Learning Paths -->
      @if($course->learningPaths && $course->learningPaths->count() > 0)
        <x-admin.card title="المسارات التعليمية" subtitle="{{ $course->learningPaths->count() }} مسار" icon="ti ti-route" color="green">
          <div class="flex flex-wrap gap-2">
            @foreach($course->learningPaths as $lp)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.1rem;">{{ $lp->name }}</span>
            @endforeach
          </div>
        </x-admin.card>
      @endif

      <!-- Topics -->
      @if($course->topics && $course->topics->count() > 0)
        <x-admin.card title="محاور الدورة" subtitle="{{ $course->topics->count() }} محور" icon="ti ti-list-details" color="blue">
          <ul class="list-disc pr-6 space-y-1 text-gray-700" style="font-size: 1.2rem;">
            @foreach($course->topics as $topic)
              <li>{{ $topic->title ?? $topic->name ?? 'موضوع' }}</li>
            @endforeach
          </ul>
        </x-admin.card>
      @endif

      <!-- Course Quizzes -->
      @if($course->quizzes && $course->quizzes->count() > 0)
        <x-admin.card title="الاختبارات" subtitle="{{ $course->quizzes->count() }} اختبار" icon="ti ti-help-circle" color="purple">
          <div class="space-y-3">
            @foreach($course->quizzes as $quiz)
              <div class="group flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200 cursor-pointer border border-transparent hover:border-gray-200"
                   onclick="window.location.href='{{ route('quizzes.show', $quiz) }}'">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <i class="ti ti-help-circle text-purple-600 text-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 group-hover:text-purple-700 transition-colors duration-200" style="font-size: 1.3rem;">{{ $quiz->title }}</h4>
                    <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
                      <i class="ti ti-list-check mr-1"></i>
                      {{ $quiz->questions_count ?? 0 }} سؤال
                    </p>
                  </div>
                </div>
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    {{ $quiz->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <i class="ti ti-{{ $quiz->is_active ? 'check' : 'x' }} mr-1"></i>
                    {{ $quiz->is_active ? 'نشط' : 'غير نشط' }}
                  </span>
                  <i class="ti ti-chevron-right text-gray-400 group-hover:text-purple-500 transition-colors duration-200"></i>
                </div>
              </div>
            @endforeach
          </div>
        </x-admin.card>
      @endif

      <!-- Rejection Reason -->
      @if($course->rejection_reason)
        <x-admin.card title="سبب الرفض" icon="ti ti-alert-circle" color="red">
          <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start">
              <i class="ti ti-alert-circle text-red-500 mt-0.5 mr-2"></i>
              <p class="text-red-800" style="font-size: 1.3rem;">{{ $course->rejection_reason }}</p>
            </div>
          </div>
        </x-admin.card>
      @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Course Thumbnail -->
      <x-admin.card title="صورة الدورة" icon="ti ti-image" color="blue">
        @if($course->thumbnail && file_exists(public_path($course->thumbnail)))
          <img src="{{ asset($course->thumbnail) }}" 
               alt="{{ $course->title }}"
               class="w-full h-48 object-cover rounded-xl">
        @else
          <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center">
            <img src="{{ asset('images/asta-logo.png') }}" 
                 alt="ASTA Logo" 
                 class="w-20 h-20 object-contain opacity-60">
          </div>
        @endif
      </x-admin.card>

      <!-- Course Info -->
      <x-admin.card title="معلومات الدورة" icon="ti ti-info-circle" color="gray">
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-user mr-2 text-gray-400"></i>المدرّس:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              @if($course->instructor)
                <a href="{{ route('instructors.show', $course->instructor) }}" 
                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                  {{ $course->instructor->name }}
                  <i class="ti ti-external-link ml-1 text-xs"></i>
                </a>
              @else
                <span class="text-gray-500">—</span>
              @endif
            </span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-category mr-2 text-gray-400"></i>القسم:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              @if($course->category)
                <a href="{{ route('categories.show', $course->category) }}" 
                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                  {{ $course->category->name }}
                  <i class="ti ti-external-link ml-1 text-xs"></i>
                </a>
              @else
                <span class="text-gray-500">—</span>
              @endif
            </span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-language mr-2 text-gray-400"></i>اللغة:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $course->language ?? 'غير محدد' }}</span>
          </div>
          
          @if($course->duration_days)
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-calendar-event mr-2 text-gray-400"></i>مدة الدوره بالأيام:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $course->duration_days }} يوم</span>
          </div>
          @endif
          
          @if($course->awarding_institution)
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-building mr-2 text-gray-400"></i>الجهه المانحه:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $course->awarding_institution }}</span>
          </div>
          @endif
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-calendar mr-2 text-gray-400"></i>تاريخ الإنشاء:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              {{ $course->created_at ? $course->created_at->format('Y-m-d') : '—' }}
            </span>
          </div>
          
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-refresh mr-2 text-gray-400"></i>آخر تحديث:
            </span>
            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">
              {{ $course->updated_at ? $course->updated_at->format('Y-m-d') : '—' }}
            </span>
          </div>
        </div>
      </x-admin.card>

      <!-- Course Stats -->
      <x-admin.card title="إحصائيات الدورة" icon="ti ti-chart-bar" color="green">
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
            <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
              <i class="ti ti-users mr-2 text-green-500"></i>عدد الطلاب:
            </span>
            <span class="font-bold text-green-700" style="font-size: 1.3rem;">{{ $course->students_count ?? 0 }}</span>
          </div>
          
          @if($course->average_rating)
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
              <span class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
                <i class="ti ti-star mr-2 text-yellow-500"></i>التقييم:
              </span>
              <div class="flex items-center">
                <div class="flex items-center text-yellow-400 mr-2">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="ti ti-star {{ $i <= $course->average_rating ? 'fill-current' : '' }}"></i>
                  @endfor
                </div>
                <span class="font-medium text-yellow-700" style="font-size: 1.3rem;">({{ $course->total_ratings ?? 0 }})</span>
              </div>
            </div>
          @endif
        </div>
      </x-admin.card>
    </div>
  </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
.prose {
  max-width: none;
}

.prose p {
  margin: 0;
  line-height: 1.7;
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

<!-- Alpine.js for Interactive Features -->
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('courseShow', () => ({
    init() {
      // Add smooth scroll behavior
      this.$nextTick(() => {
        const elements = document.querySelectorAll('[data-smooth-scroll]');
        elements.forEach(el => {
          el.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(el.getAttribute('href'));
            if (target) {
              target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          });
        });
      });
    }
  }));
});
</script>
@endsection
