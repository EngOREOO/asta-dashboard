@extends('layouts.dash')

@section('title', 'المستخدمون النشطون')

@section('content')
<div class="space-y-8 font-arabic">
    <!-- Welcome Header -->
    <div class="relative bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 rounded-3xl p-10 text-white overflow-hidden shadow-2xl">
        <!-- Background decorative elements -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-20 translate-x-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-16 -translate-x-16"></div>
        <div class="absolute top-1/2 left-1/2 w-24 h-24 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-4">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ti ti-users text-3xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-white mb-2" style="font-size: 2.5rem;">المستخدمون النشطون</h1>
                        <p class="text-green-100" style="font-size: 1.3rem;">عرض وإدارة المستخدمين النشطين حالياً</p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                    <div class="text-4xl font-bold text-white">{{ $activeUsers->count() }}</div>
                    <div class="text-green-100 text-sm font-medium">مستخدم نشط</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users List -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden hover:shadow-2xl transition-all duration-300">
        <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 p-6 border-b border-white/20">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ti ti-list text-xl text-white"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900" style="font-size: 1.4rem;">قائمة المستخدمين النشطين</h2>
                    <p class="text-gray-600" style="font-size: 1.1rem;">المستخدمون الذين لديهم جلسات نشطة حالياً</p>
                </div>
            </div>
        </div>
        
        @if($activeUsers->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50">
                    <tr>
                        <th class="px-8 py-6 text-right text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-green-200">
                            <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                <i class="ti ti-user text-green-600"></i>
                                <span>المستخدم</span>
                            </div>
                        </th>
                        <th class="px-8 py-6 text-right text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-green-200">
                            <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                <i class="ti ti-mail text-green-600"></i>
                                <span>البريد الإلكتروني</span>
                            </div>
                        </th>
                        <th class="px-8 py-6 text-right text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-green-200">
                            <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                <i class="ti ti-clock text-green-600"></i>
                                <span>آخر نشاط</span>
                            </div>
                        </th>
                        <th class="px-8 py-6 text-right text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-green-200">
                            <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                <i class="ti ti-world text-green-600"></i>
                                <span>عنوان IP</span>
                            </div>
                        </th>
                        <th class="px-8 py-6 text-right text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-green-200">
                            <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                <i class="ti ti-browser text-green-600"></i>
                                <span>المتصفح</span>
                            </div>
                        </th>
                        <th class="px-8 py-6 text-right text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-green-200">
                            <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                <i class="ti ti-settings text-green-600"></i>
                                <span>الإجراءات</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($activeUsers as $activeUser)
                    <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-300 group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    {{ substr($activeUser['user']->name, 0, 1) }}
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-bold text-gray-900" style="font-size: 1.2rem;">{{ $activeUser['user']->name }}</div>
                                    <div class="text-sm text-gray-500 mt-2">
                                        @if($activeUser['user']->hasRole('super-admin'))
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300 shadow-sm">
                                                <i class="ti ti-crown mr-2"></i>
                                                مدير عام
                                            </span>
                                        @elseif($activeUser['user']->hasRole('admin'))
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300 shadow-sm">
                                                <i class="ti ti-shield mr-2"></i>
                                                مدير
                                            </span>
                                        @elseif($activeUser['user']->hasRole('instructor'))
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300 shadow-sm">
                                                <i class="ti ti-user-check mr-2"></i>
                                                مدرب
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300 shadow-sm">
                                                <i class="ti ti-user mr-2"></i>
                                                طالب
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-200">
                                <span class="text-sm font-medium text-gray-700">{{ $activeUser['user']->email }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="relative mr-4">
                                    <div class="w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full shadow-sm animate-pulse"></div>
                                    <div class="absolute inset-0 w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full animate-ping opacity-75"></div>
                                </div>
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-2 rounded-xl border border-green-200">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <i class="ti ti-clock text-green-600 text-sm"></i>
                                        <span class="text-sm font-bold text-green-800">{{ $activeUser['last_seen'] }}</span>
                                    </div>
                                    <div class="text-xs text-green-600 mt-1">نشط الآن</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="bg-gray-100 px-4 py-2 rounded-xl border border-gray-200">
                                <span class="text-sm font-mono font-semibold text-gray-700">{{ $activeUser['ip_address'] ?? 'غير محدد' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="max-w-xs">
                                <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-200" title="{{ $activeUser['user_agent'] ?? 'غير محدد' }}">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <i class="ti ti-browser text-gray-500 text-sm"></i>
                                        <span class="text-sm font-medium text-gray-600 truncate">
                                            {{ $activeUser['user_agent'] ? substr($activeUser['user_agent'], 0, 40) . '...' : 'غير محدد' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <a href="{{ route('users.show', $activeUser['user']->id) }}" 
                                   class="w-10 h-10 bg-gradient-to-r from-blue-100 to-blue-200 hover:from-blue-200 hover:to-blue-300 text-blue-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-sm border border-blue-300">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                @if($activeUser['user']->id !== auth()->id())
                                <button onclick="forceLogout({{ $activeUser['user']->id }}, '{{ $activeUser['user']->name }}')" 
                                        class="w-10 h-10 bg-gradient-to-r from-red-100 to-red-200 hover:from-red-200 hover:to-red-300 text-red-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-sm border border-red-300">
                                    <i class="ti ti-logout text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-16 text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="ti ti-users text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">لا يوجد مستخدمون نشطون</h3>
            <p class="text-gray-500 text-lg">لا يوجد مستخدمون لديهم جلسات نشطة حالياً</p>
            <div class="mt-6">
                <div class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-xl text-gray-600">
                    <i class="ti ti-clock mr-2"></i>
                    جلسات المستخدمين تنتهي بعد 30 دقيقة من عدم النشاط
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Force Logout Confirmation Modal -->
<div id="forceLogoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden backdrop-blur-sm">
    <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 max-w-md mx-4 shadow-2xl border border-white/20">
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                <i class="ti ti-logout text-3xl text-red-600"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">تأكيد تسجيل الخروج</h3>
                <p class="text-gray-600 mt-1">سيتم تسجيل خروج المستخدم فوراً</p>
            </div>
        </div>
        <p class="text-gray-700 mb-6 text-lg">
            هل أنت متأكد من أنك تريد تسجيل خروج المستخدم <span id="userName" class="font-bold text-gray-900"></span>؟
            سيتم قطع جميع جلساته النشطة.
        </p>
        <div class="flex justify-end space-x-4 rtl:space-x-reverse">
            <button onclick="closeForceLogoutModal()" 
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                إلغاء
            </button>
            <button id="confirmForceLogout" 
                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl font-semibold transition-all duration-300 hover:scale-105 shadow-lg">
                تسجيل الخروج
            </button>
        </div>
    </div>
</div>

<script>
let currentUserId = null;

function forceLogout(userId, userName) {
    currentUserId = userId;
    document.getElementById('userName').textContent = userName;
    document.getElementById('forceLogoutModal').classList.remove('hidden');
}

function closeForceLogoutModal() {
    document.getElementById('forceLogoutModal').classList.add('hidden');
    currentUserId = null;
}

document.getElementById('confirmForceLogout').addEventListener('click', function() {
    if (currentUserId) {
        // Show loading state
        this.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>جاري تسجيل الخروج...';
        this.disabled = true;
        
        fetch(`/active-users/${currentUserId}/force-logout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('تم تسجيل خروج المستخدم بنجاح', 'success');
                // Reload the page to update the list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'حدث خطأ أثناء تسجيل خروج المستخدم', 'error');
            }
        })
        .catch(error => {
            showNotification('حدث خطأ أثناء تسجيل خروج المستخدم', 'error');
        })
        .finally(() => {
            // Reset button state
            this.innerHTML = 'تسجيل الخروج';
            this.disabled = false;
            closeForceLogoutModal();
        });
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-6 right-6 z-50 p-4 rounded-2xl shadow-2xl backdrop-blur-xl border border-white/20 ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                <i class="ti ${type === 'success' ? 'ti-check-circle' : 'ti-x-circle'} text-lg"></i>
            </div>
            <span class="font-semibold">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Add animation
    notification.style.transform = 'translateX(100%)';
    notification.style.transition = 'transform 0.3s ease-out';
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection
