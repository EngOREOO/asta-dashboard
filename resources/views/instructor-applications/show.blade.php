@php($title = 'تفاصيل طلب المحاضر')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic" x-data="{
  showReject:false,
  showApprove:false,
  notify:false,
  message:'',
  openReject(){ this.showReject=true; }, closeReject(){ this.showReject=false; },
  openApprove(){ this.showApprove=true; }, closeApprove(){ this.showApprove=false; }
}">
  <div class="space-y-8 p-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
      <div class="relative z-10 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">تفاصيل طلب المحاضر</h1>
          <p class="text-white/80 mt-1">{{ optional($instructorApplication->user)->name ?? '—' }} • {{ optional($instructorApplication->user)->email ?? '—' }}</p>
        </div>
        <a href="{{ route('instructor-applications.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">عودة إلى الطلبات</a>
      </div>
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
      <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- Main content -->
      <div class="xl:col-span-2 bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <!-- Applicant Profile Header -->
        <div class="flex items-center gap-4 mb-6">
          <div class="h-14 w-14 rounded-2xl bg-gradient-to-r from-blue-500 to-purple-500 text-white flex items-center justify-center text-xl font-bold">
            {{ Str::of(optional($instructorApplication->user)->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
          </div>
          <div class="min-w-0">
            <div class="text-gray-900 font-semibold" style="font-size: 1.3rem;">{{ optional($instructorApplication->user)->name ?? '—' }}</div>
            <div class="text-gray-500 text-sm text-ltr">{{ optional($instructorApplication->user)->email ?? '—' }}</div>
          </div>
        </div>

        <!-- Details Grid (always rendered, with fallbacks) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-gray-700 font-semibold mb-2">مجال التدريس</h4>
            <p class="text-gray-600">{{ $instructorApplication->teaching_field ?: '—' }}</p>
          </div>
          <div>
            <h4 class="text-gray-700 font-semibold mb-2">الخبرة</h4>
            <p class="text-gray-600">{{ $instructorApplication->experience ?: '—' }}</p>
          </div>
          <div>
            <h4 class="text-gray-700 font-semibold mb-2">المؤهلات</h4>
            <p class="text-gray-600">{{ $instructorApplication->qualifications ?: '—' }}</p>
          </div>
          <div>
            <h4 class="text-gray-700 font-semibold mb-2">الدافع</h4>
            <p class="text-gray-600">{{ $instructorApplication->motivation ?: '—' }}</p>
          </div>
          <div class="md:col-span-2">
            <h4 class="text-gray-700 font-semibold mb-2">ملاحظات المراجعة</h4>
            <div class="rounded-2xl border border-blue-100 bg-blue-50 text-blue-800 p-4">{{ $instructorApplication->notes ?: '—' }}</div>
          </div>
        </div>
      </div>

      <!-- Side info & actions -->
      <div class="space-y-6">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
          <h4 class="text-gray-700 font-semibold mb-4">معلومات الطلب</h4>
          <div class="space-y-3 text-gray-700">
            <div>الحالة: <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium {{ ($instructorApplication->status ?? 'pending') === 'approved' ? 'bg-green-100 text-green-800' : (($instructorApplication->status ?? 'pending') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ['approved'=>'مقبول','rejected'=>'مرفوض','pending'=>'قيد المراجعة'][$instructorApplication->status ?? 'pending'] }}</span></div>
            <div>تاريخ التقديم: {{ $instructorApplication->created_at ? $instructorApplication->created_at->format('Y-n-j') : '—' }}</div>
            @if($instructorApplication->reviewed_at)
            <div>تاريخ المراجعة: {{ $instructorApplication->reviewed_at->format('Y-n-j') }}</div>
            @endif
            @if($instructorApplication->reviewer)
            <div>المراجع: {{ $instructorApplication->reviewer->name }}</div>
            @endif
          </div>
        </div>

        @if($instructorApplication->status === 'pending')
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
          <h4 class="text-gray-700 font-semibold mb-4">إجراءات المسؤول</h4>
          <div class="flex items-center gap-3">
            <button type="button" @click="openApprove()" class="inline-flex items-center px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700">
              <i class="ti ti-check mr-2"></i> قبول الطلب
            </button>
            <button type="button" @click="openReject()" class="inline-flex items-center px-4 py-2 rounded-xl text-white bg-red-600 hover:bg-red-700">
              <i class="ti ti-x mr-2"></i> رفض الطلب
            </button>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Approve Modal -->
  @if($instructorApplication->status === 'pending')
  <div x-cloak x-show="showApprove" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
    <div class="fixed inset-0 bg-black/40" @click="closeApprove()"></div>
    <div class="relative w-full sm:max-w-md bg-white rounded-2xl shadow-2xl m-0 sm:m-6 overflow-hidden">
      <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold">تأكيد قبول الطلب</h3></div>
      <form method="POST" action="{{ route('instructor-applications.approve', $instructorApplication) }}" class="p-6 space-y-4">
        @csrf
        <p class="text-gray-700">هل تريد قبول طلب "{{ optional($instructorApplication->user)->name ?? '—' }}"؟</p>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" @click="closeApprove()" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">إلغاء</button>
          <button type="submit" class="px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700">تأكيد القبول</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Reject Modal -->
  <div x-cloak x-show="showReject" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
    <div class="fixed inset-0 bg-black/40" @click="closeReject()"></div>
    <div class="relative w-full sm:max-w-lg bg-white rounded-2xl shadow-2xl m-0 sm:m-6 overflow-hidden">
      <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold">رفض الطلب</h3></div>
      <form method="POST" action="{{ route('instructor-applications.reject', $instructorApplication) }}" class="p-6 space-y-4">
        @csrf
        <p class="text-gray-700">هل أنت متأكد من رفض طلب "{{ optional($instructorApplication->user)->name ?? '—' }}"؟</p>
        <div>
          <label for="rejection_reason" class="block mb-2">سبب الرفض <span class="text-red-600">*</span></label>
          <textarea id="rejection_reason" name="rejection_reason" required rows="3" class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-red-500" placeholder="يرجى توضيح سبب رفض هذا الطلب..."></textarea>
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" @click="closeReject()" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">إلغاء</button>
          <button type="submit" class="px-4 py-2 rounded-xl text-white bg-red-600 hover:bg-red-700">رفض الطلب</button>
        </div>
      </form>
    </div>
  </div>
  @endif
</div>
@endsection
