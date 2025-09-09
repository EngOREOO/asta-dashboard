@php($title = 'تفاصيل المستخدم')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
      <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
            {{ Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
          </div>
          <div class="min-w-0">
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
            <p class="text-white/80 text-sm text-ltr">{{ $user->email }}</p>
          </div>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">عودة إلى المستخدمين</a>
      </div>
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
      <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- Profile card -->
      <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <div class="space-y-3">
          <div class="text-gray-700">الحالة: <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800">نشط</span></div>
          <div class="text-gray-700">الأدوار:</div>
          <div class="flex flex-wrap gap-2">
            @foreach($user->roles as $role)
              @php($roleAr = ['admin'=>'مدير','instructor'=>'محاضر','student'=>'طالب','user'=>'مستخدم'][$role->name] ?? $role->name)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">{{ $roleAr }}</span>
            @endforeach
          </div>
          <div class="text-gray-700">عدد الدورات: {{ method_exists($user,'instructorCourses') ? $user->instructorCourses->count() : ($user->courses->count() ?? 0) }}</div>
        </div>
      </div>

      <!-- Courses table -->
      <div class="xl:col-span-2 bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <h3 class="text-gray-700 font-semibold mb-4">الدورات</h3>
        <div class="admin-table-container">
          <table class="admin-table" id="user-courses-table">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-right">#</th>
                <th class="text-right">العنوان</th>
                <th class="text-right">الدور</th>
                <th class="text-right">الحالة</th>
                <th class="text-right">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              @php($courses = method_exists($user,'instructorCourses') ? $user->instructorCourses : $user->courses)
              @foreach($courses as $course)
              @php($statusAr = ['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'][$course->status] ?? ($course->status ?? 'مسودة'))
              <tr>
                <td class="text-center">{{ $course->id }}</td>
                <td class="font-medium">{{ $course->title }}</td>
                <td class="text-rtl">{{ method_exists($user,'instructorCourses') ? 'محاضر' : 'طالب' }}</td>
                <td>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium {{ $course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700') }}" style="font-size: 1.2rem;">{{ $statusAr }}</span>
                </td>
                <td class="text-center">
                  <div class="inline-flex items-center gap-2">
                    <a class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" href="{{ route('courses.show', $course) }}" title="عرض"><i class="ti ti-eye text-base"></i></a>
                    @can('update', $course)
                    <a class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" href="{{ route('courses.edit', $course) }}" title="تعديل"><i class="ti ti-edit text-base"></i></a>
                    @endcan
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#user-courses-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        language: {
          sProcessing: 'جاري التحميل...',
          sLengthMenu: 'أظهر _MENU_ سجل',
          sZeroRecords: 'لا توجد نتائج',
          sInfo: 'إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل',
          sInfoEmpty: 'يعرض 0 إلى 0 من أصل 0 سجل',
          sInfoFiltered: '(منتقاة من مجموع _MAX_ سجل)',
          sSearch: 'ابحث:',
          oPaginate: { sFirst: 'الأول', sPrevious: 'السابق', sNext: 'التالي', sLast: 'الأخير' }
        }
      });
    }
  });
</script>
@endsection
