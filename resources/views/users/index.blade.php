@php
    $title = 'المستخدمون';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">
          إدارة المستخدمين
        </h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-users mr-2 text-cyan-500"></i>
          عرض وإدارة جميع المستخدمين في النظام
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('users.create') }}" 
           class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 border border-transparent rounded-2xl font-semibold text-white uppercase tracking-widest hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 disabled:opacity-25 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105" style="font-size: 1.3rem;">
          <i class="ti ti-square-plus mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          إضافة مستخدم جديد
        </a>
      </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center header-icon-container backdrop-blur-sm">
              <i class="ti ti-users text-white text-xl"></i>
            </div>
            قائمة المستخدمين
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">عرض وإدارة جميع المستخدمين</p>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        <!-- Filters -->
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">بحث</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" placeholder="ابحث بالاسم أو البريد">
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">جميع الأدوار</label>
            <select name="role" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
              <option value="">الكل</option>
              @foreach(Spatie\Permission\Models\Role::query()->pluck('name') as $role)
                @php
                  $arabicRole = match($role) {
                    'admin' => 'مدير',
                    'instructor' => 'محاضر',
                    'student' => 'طالب',
                    'user' => 'مستخدم',
                    default => $role
                  };
                @endphp
                <option value="{{ $role }}" @selected(($filters['role'] ?? '') === $role)>{{ $arabicRole }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl py-3" style="font-size: 1.3rem;">بحث</button>
          </div>
        </form>

        @if($users->count() > 0)
        <div class="admin-table-container">
          <table class="admin-table" id="users-table">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">#</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الصورة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الاسم</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider email-label-arabic" style="font-size: 1.3rem;">البريد الإلكتروني</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الدور</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">تاريخ الانضمام</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الحالة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الإجراءات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($users as $user)
              <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 text-center" style="font-size: 1.3rem;">{{ $user->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @if($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path))
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-lg shadow-sm object-cover border-2 border-gray-200">
                  @else
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center shadow-sm">
                      <img src="{{ asset('images/asta-logo.png') }}" alt="ASTA Logo" class="w-6 h-6 object-contain opacity-80">
                    </div>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-rtl">
                  <div class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $user->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-ltr">
                  <div class="text-gray-900 email-content" style="font-size: 1.3rem;">{{ $user->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-rtl" style="font-size: 1.3rem;">
                  @php
                    $roleNames = $user->roles->pluck('name');
                    $arabicRoles = [];
                    foreach($roleNames as $role) {
                      switch($role) {
                        case 'admin':
                          $arabicRoles[] = 'مدير';
                          break;
                        case 'instructor':
                          $arabicRoles[] = 'محاضر';
                          break;
                        case 'student':
                          $arabicRoles[] = 'طالب';
                          break;
                        case 'user':
                          $arabicRoles[] = 'مستخدم';
                          break;
                        default:
                          $arabicRoles[] = $role;
                      }
                    }
                  @endphp
                  {{ implode(', ', $arabicRoles) ?: '—' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-rtl text-gray-900" style="font-size: 1.3rem;">{{ optional($user->created_at)->format('Y-n-j') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <form action="{{ route('users.toggle-active', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                      <i class="ti {{ $user->is_active ? 'ti-toggle-right' : 'ti-toggle-left' }} ml-1"></i>
                      {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                    </button>
                  </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium text-center" style="font-size: 1.3rem;">
                  <div class="flex items-center space-x-2 space-x-reverse">
                    <a href="{{ route('users.show', $user) }}" class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" style="font-size: 1.3rem;" title="عرض">
                      <i class="ti ti-eye text-sm"></i>
                    </a>
                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200" style="font-size: 1.3rem;" title="تعديل">
                      <i class="ti ti-edit text-sm"></i>
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" style="font-size: 1.3rem;" title="حذف">
                        <i class="ti ti-trash text-sm"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
          <div class="mt-6 flex justify-center">
            {{ $users->withQueryString()->links() }}
          </div>
        @endif
        @else
        <div class="text-center py-12">
          <div class="mb-6">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto">
              <i class="ti ti-users text-4xl text-blue-500"></i>
            </div>
          </div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا يوجد مستخدمون</h3>
          <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">ابدأ بإضافة مستخدم جديد للنظام.</p>
          <a href="{{ route('users.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl" style="font-size: 1.3rem;">
            <i class="ti ti-square-plus mr-2"></i>إضافة مستخدم جديد
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);} }
@keyframes slideUp { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: translateY(0);} }
.animate-fade-in { animation: fadeIn 0.6s ease-out; }
.animate-slide-up { animation: slideUp 0.8s ease-out; }
.table-responsive::-webkit-scrollbar { height: 8px; }
.table-responsive::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
.table-responsive::-webkit-scrollbar-thumb { background: linear-gradient(to right, #3b82f6, #8b5cf6); border-radius: 4px; }
.table-responsive::-webkit-scrollbar-thumb:hover { background: linear-gradient(to right, #2563eb, #7c3aed); }
</style>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#users-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'desc']],
        language: {
          sProcessing: 'جاري التحميل...',
          sLengthMenu: 'أظهر _MENU_ سجل',
          sZeroRecords: 'لم يعثر على أية سجلات',
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


