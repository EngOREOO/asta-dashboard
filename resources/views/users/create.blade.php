@php
    $title = 'إضافة مستخدم';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teال-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إضافة مستخدم جديد</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-user-plus mr-2 text-cyan-500"></i>
          إنشاء حساب مستخدم جديد للنظام
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('users.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة لقائمة المستخدمين
        </a>
      </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-user-plus text-white text-xl"></i>
            </div>
            نموذج إنشاء مستخدم
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">أدخل بيانات المستخدم ثم احفظ</p>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf
          <div>
            <label for="name" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الاسم</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('name')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="email" class="block text-gray-700 mb-2 email-label-arabic" style="font-size: 1.3rem;">البريد الإلكتروني</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('email')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="password" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">كلمة المرور</label>
            <input id="password" name="password" type="password" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('password')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="password_confirmation" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">تأكيد كلمة المرور</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
          </div>

          @if(!empty($roles))
          <div>
            <label for="role_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدور</label>
            <select id="role_id" name="role_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
              <option value="">اختيار الدور (اختياري)</option>
              @php($roleMap = ['admin' => 'مدير', 'instructor' => 'محاضر', 'student' => 'طالب', 'user' => 'مستخدم'])
              @foreach($roles as $id => $name)
                <option value="{{ $id }}" @selected(old('role_id') == $id)>{{ $roleMap[$name] ?? $name }}</option>
              @endforeach
            </select>
          </div>
          @endif

          <div>
            <label for="department" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">القسم</label>
            <select id="department" name="department" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
              <option value="">اختر القسم</option>
              @isset($categories)
                @foreach($categories as $cat)
                  <option value="{{ $cat->name }}" @selected(old('department')===$cat->name)>{{ $cat->name }}</option>
                @endforeach
              @endisset
            </select>
            @error('department')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="specialization" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">التخصص</label>
            <select id="specialization" name="specialization" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
              <option value="">اختر التخصص</option>
              @isset($specializations)
                @foreach($specializations as $spec)
                  <option value="{{ $spec->name }}" @selected(old('specialization')===$spec->name)>{{ $spec->name }}</option>
                @endforeach
              @endisset
            </select>
            @error('specialization')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">أرقام الهاتف</label>
            <div id="phones-wrapper" class="space-y-2">
              @php($oldPhones = old('phones', ['']))
              @foreach($oldPhones as $idx => $phone)
                <div class="flex gap-2">
                  <input name="phones[{{ $idx }}]" type="text" value="{{ $phone }}" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                  <button type="button" class="px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-xl" onclick="this.parentElement.remove()">حذف</button>
                </div>
              @endforeach
            </div>
            <button type="button" id="add-phone" class="mt-2 px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl">إضافة رقم</button>
            @error('phones')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">روابط السوشيال</label>
            <div id="socials-wrapper" class="space-y-2">
              @php($oldSocials = old('social_links', [['platform'=>'','url'=>'']]))
              @foreach($oldSocials as $idx => $social)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <input name="social_links[{{ $idx }}][platform]" type="text" value="{{ $social['platform'] ?? '' }}" placeholder="المنصة (مثال: تويتر، لينكدإن)" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                  <div class="flex gap-2">
                    <input name="social_links[{{ $idx }}][url]" type="url" value="{{ $social['url'] ?? '' }}" placeholder="الرابط" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                    <button type="button" class="px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-xl" onclick="this.parentElement.parentElement.remove()">حذف</button>
                  </div>
                </div>
              @endforeach
            </div>
            <button type="button" id="add-social" class="mt-2 px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl">إضافة رابط</button>
            @error('social_links')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">
              <i class="ti ti-device-floppy mr-2"></i>
              حفظ المستخدم
            </button>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);} }
@keyframes slideUp { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: translateY(0);} }
.animate-fade-in { animation: fadeIn 0.6s ease-out; }
.animate-slide-up { animation: slideUp 0.8s ease-out; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const phonesWrapper = document.getElementById('phones-wrapper');
  const addPhoneBtn = document.getElementById('add-phone');
  const socialsWrapper = document.getElementById('socials-wrapper');
  const addSocialBtn = document.getElementById('add-social');

  addPhoneBtn?.addEventListener('click', function() {
    const idx = phonesWrapper.children.length;
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `<input name="phones[${idx}]" type="text" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                     <button type="button" class="px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-xl" onclick="this.parentElement.remove()">حذف</button>`;
    phonesWrapper.appendChild(div);
  });

  addSocialBtn?.addEventListener('click', function() {
    const idx = socialsWrapper.children.length;
    const container = document.createElement('div');
    container.className = 'grid grid-cols-1 md:grid-cols-2 gap-2';
    container.innerHTML = `<input name="social_links[${idx}][platform]" type="text" placeholder="المنصة (مثال: تويتر، لينكدإن)" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                           <div class="flex gap-2">
                             <input name="social_links[${idx}][url]" type="url" placeholder="الرابط" class="flex-1 w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
                             <button type="button" class="px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-xl" onclick="this.parentElement.parentElement.remove()">حذف</button>
                           </div>`;
    socialsWrapper.appendChild(container);
  });
});
</script>
@endsection
