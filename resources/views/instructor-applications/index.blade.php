@php
    $title = 'طلبات المحاضرين';
@endphp
@extends('layouts.dash')
@section('content')
<style>
  [x-cloak] { display: none !important; }
</style>
<div id="ia-root" class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic" x-data="{
  // Reject modal state
  showReject:false, rejectId:null, rejectName:'',
  openReject(id,name){ this.rejectId=id; this.rejectName=name||'—'; this.showReject=true; },
  closeReject(){ this.showReject=false; this.rejectId=null; this.rejectName=''; },
  // Approve modal state
  showApprove:false, approveId:null, approveName:'',
  openApprove(id,name){ this.approveId=id; this.approveName=name||'—'; this.showApprove=true; },
  closeApprove(){ this.showApprove=false; this.approveId=null; this.approveName=''; },
  // Toast
  toastShow:false, toastType:'success', toastMessage:'',
  notify(msg,type='success'){ this.toastMessage=msg; this.toastType=type; this.toastShow=true; setTimeout(()=>this.toastShow=false, 3000); }
}"
x-on:ia:open-approve.window="openApprove($event.detail.id, $event.detail.name)"
x-on:ia:open-reject.window="openReject($event.detail.id, $event.detail.name)"
x-init="$nextTick(()=>{ 
  @if (session('success'))
    toastMessage='{{ addslashes(session('success')) }}'; toastType='success'; toastShow=true; setTimeout(()=>toastShow=false,3000);
  @endif
  @if (session('error'))
    toastMessage='{{ addslashes(session('error')) }}'; toastType='error'; toastShow=true; setTimeout(()=>toastShow=false,3000);
  @endif
})">
  <div class="space-y-8 p-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">
          طلبات المحاضرين
        </h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-id-badge mr-2 text-cyan-500"></i>
          عرض وإدارة جميع طلبات انضمام المحاضرين
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <span class="inline-flex items-center px-4 py-2 rounded-xl bg-amber-100 text-amber-800 border border-amber-200" style="font-size: 1.1rem;">
          {{ $applications->where('status', 'pending')->count() }} طلب قيد المراجعة
        </span>
      </div>
    </div>

    <!-- Applications Table Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center header-icon-container backdrop-blur-sm">
              <i class="ti ti-id-badge text-white text-xl"></i>
            </div>
            قائمة طلبات المحاضرين
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">عرض الحالة والإجراءات لكل طلب</p>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        @if($applications->count() > 0)
        <div class="admin-table-container">
          <table class="admin-table" id="applications-table">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">#</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">المتقدم</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider email-label-arabic" style="font-size: 1.3rem;">البريد الإلكتروني</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الحالة</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">تاريخ التقديم</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">مراجع الطلب</th>
                <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الإجراءات</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($applications as $application)
              <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 text-center" style="font-size: 1.3rem;">{{ $application->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-rtl">
                  <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                      {{ Str::of($application->user->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                    </div>
                    <div>
                      <div class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ optional($application->user)->name ?? '—' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-ltr">
                  <div class="text-gray-900 email-content" style="font-size: 1.3rem;">{{ optional($application->user)->email ?? '—' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap align-middle">
                  @php
                    $status = $application->status ?? 'pending';
                    $badge = $status === 'approved' ? 'bg-green-100 text-green-800' : ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                    $statusAr = match($status) {
                      'approved' => 'مقبول',
                      'rejected' => 'مرفوض',
                      default => 'قيد المراجعة'
                    };
                  @endphp
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium {{ $badge }}" style="font-size: 1.2rem;">{{ $statusAr }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-rtl text-gray-900" style="font-size: 1.3rem;">{{ $application->created_at ? $application->created_at->format('Y-n-j') : '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-rtl">
                  @if($application->reviewer)
                    <div class="text-gray-900" style="font-size: 1.3rem;">{{ $application->reviewer->name }}</div>
                    <div class="text-gray-500" style="font-size: 1.1rem;">{{ $application->reviewed_at ? $application->reviewed_at->format('Y-n-j') : '' }}</div>
                  @else
                    <div class="text-gray-400" style="font-size: 1.3rem;">—</div>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium text-center" style="font-size: 1.3rem;">
                  <div class="inline-flex items-center gap-2">
                    <a href="{{ route('instructor-applications.show', $application) }}" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="عرض">
                      <i class="ti ti-eye text-base"></i>
                    </a>
                    @if($application->status === 'pending')
                      <!-- <button type="button" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="window.openApprove({{ $application->id }}, '{{ optional($application->user)->name ?? '—' }}')" title="قبول">
                        <i class="ti ti-check text-base"></i>
                      </button> -->
                      <!-- <button type="button" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="window.openReject({{ $application->id }}, '{{ optional($application->user)->name ?? '—' }}')" title="رفض">
                        <i class="ti ti-x text-base"></i>
                      </button> -->
                    @else
                      <span class="h-9 inline-flex items-center px-3 rounded-lg bg-gray-100 text-gray-500" title="لا توجد إجراءات">—</span>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($applications->hasPages())
        <div class="mt-6 flex justify-center">
          {{ $applications->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
          <div class="mb-6">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto">
              <i class="ti ti-id-badge text-4xl text-blue-500"></i>
            </div>
          </div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا توجد طلبات</h3>
          <p class="text-gray-500 mb-2" style="font-size: 1.3rem;">لا توجد طلبات محاضرين للمراجعة حاليًا.</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Reject Modal (Tailwind/Alpine) -->
<div x-cloak x-show="showReject" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
  <div class="fixed inset-0 bg-black/40" @click="closeReject()"></div>
  <div class="relative w-full sm:max-w-lg bg-white rounded-2xl shadow-2xl m-0 sm:m-6 overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="text-lg font-semibold">رفض الطلب</h3>
    </div>
    <form :action="`/instructor-applications/${rejectId}/reject`" method="POST" class="p-6 space-y-4">
      @csrf
      <p class="text-gray-700">هل أنت متأكد من رفض طلب "<span class="font-medium" x-text="rejectName"></span>"؟</p>
      <div>
        <label for="rejection_reason" class="block mb-2">سبب الرفض <span class="text-red-600">*</span></label>
        <textarea id="rejection_reason" name="rejection_reason" required rows="3" class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-red-500"></textarea>
      </div>
      <div class="flex items-center justify-end gap-2 pt-2">
        <button type="button" @click="closeReject()" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">إلغاء</button>
        <button type="submit" class="px-4 py-2 rounded-xl text-white bg-red-600 hover:bg-red-700">رفض الطلب</button>
      </div>
    </form>
  </div>
  </div>

<!-- Approve Modal (Tailwind/Alpine) -->
<div x-cloak x-show="showApprove" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
  <div class="fixed inset-0 bg-black/40" @click="closeApprove()"></div>
  <div class="relative w-full sm:max-w-md bg-white rounded-2xl shadow-2xl m-0 sm:m-6 overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="text-lg font-semibold">تأكيد قبول الطلب</h3>
    </div>
    <form :action="`/instructor-applications/${approveId}/approve`" method="POST" class="p-6 space-y-4">
      @csrf
      <p class="text-gray-700">هل تريد قبول طلب "<span class="font-medium" x-text="approveName"></span>"؟</p>
      <div class="flex items-center justify-end gap-2 pt-2">
        <button type="button" @click="closeApprove()" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">إلغاء</button>
        <button type="submit" class="px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700">تأكيد القبول</button>
      </div>
    </form>
  </div>
  </div>

<!-- Toast Notification -->
<div x-cloak x-show="toastShow" :class="{'bg-emerald-600': toastType==='success', 'bg-red-600': toastType==='error'}" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 text-white px-4 py-2 rounded-xl shadow-lg">
  <span x-text="toastMessage"></span>
  <button class="ml-3 text-white/80 hover:text-white" @click="toastShow=false">×</button>
  </div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#applications-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[4, 'desc']],
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

  // Optionally, show success toast after returning with ?approved=1 or ?rejected=1
  window.openApprove = function(id, name){
    window.dispatchEvent(new CustomEvent('ia:open-approve', { detail: { id: id, name: name }}));
  };
  window.openReject = function(id, name){
    window.dispatchEvent(new CustomEvent('ia:open-reject', { detail: { id: id, name: name }}));
  };
</script>
@endsection
