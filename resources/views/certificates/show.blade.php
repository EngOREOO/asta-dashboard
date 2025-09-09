@php($title = 'تفاصيل الشهادة')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تفاصيل الشهادة</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;"><i class="ti ti-certificate mr-2 text-cyan-500"></i>عرض الشهادة ومعلوماتها</p>
      </div>
      <div class="flex gap-3">
        @if($certificate->certificate_url)
        <a href="{{ $certificate->certificate_url }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-2xl shadow hover:bg-indigo-700" style="font-size: 1.3rem;">
          <i class="ti ti-download mr-2"></i>تنزيل
        </a>
        @endif
        <a href="{{ route('certificates.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl text-gray-700 shadow hover:shadow-lg" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>رجوع
        </a>
      </div>
    </div>

    <!-- Certificate Preview + Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Preview -->
      <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <!-- Toggle designs -->
        <div class="flex items-center justify-end gap-2 mb-4">
          <button type="button" class="px-4 py-2 rounded-xl border text-gray-700 bg-white hover:bg-gray-50" onclick="toggleDesign(1)">تصميم حديث</button>
          <button type="button" class="px-4 py-2 rounded-xl border text-gray-700 bg-white hover:bg-gray-50" onclick="toggleDesign(2)">تصميم رسمي</button>
        </div>

        <!-- Variant 1: Modern gradient (current) -->
        <div id="cert-variant-1" class="relative rounded-[28px] overflow-hidden shadow-xl bg-gradient-to-br from-white to-blue-50">
          <!-- Decorative corners -->
          <div class="absolute -top-20 -right-20 w-64 h-64 bg-cyan-200/30 rounded-full blur-3xl"></div>
          <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-indigo-200/30 rounded-full blur-3xl"></div>

          <div id="certificate-canvas" class="relative p-10 border-4 rounded-[24px]" style="border-image: linear-gradient(45deg, #06b6d4, #3b82f6, #8b5cf6) 1;">
            <div class="flex items-center justify-between mb-8">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center text-white">
                  <i class="ti ti-certificate"></i>
                </div>
                <div class="text-gray-500" style="font-size: 1.1rem;">رقم الشهادة: #{{ $certificate->id }}</div>
              </div>
              <div class="text-gray-400" style="font-size: 0.95rem;">{{ now()->format('Y-n-j') }}</div>
            </div>

            <div class="text-center">
              <h2 class="font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-700" style="font-size: 2.2rem;">شهادة إتمام</h2>
              <p class="text-gray-500 mt-1" style="font-size: 1.1rem;">This certificate is proudly presented to</p>

              <div class="mt-6">
                <div class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-100">
                  <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white flex items-center justify-center text-lg">
                    {{ Str::of(optional($certificate->user)->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                  </div>
                  <div class="text-gray-900 font-bold" style="font-size: 1.6rem;">{{ optional($certificate->user)->name ?? '—' }}</div>
                </div>
                <div class="email-content text-ltr mt-1 text-gray-500">{{ optional($certificate->user)->email ?? '' }}</div>
              </div>

              <p class="mt-6 text-gray-700" style="font-size: 1.25rem;">لاستكماله/ا بنجاح متطلبات دورة</p>
              <div class="font-semibold text-gray-900" style="font-size: 1.5rem;">{{ optional($certificate->course)->title ?? '—' }}</div>

              <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-right">
                <div>
                  <div class="text-gray-500 mb-1">المحاضر</div>
                  <div class="font-semibold">{{ optional($certificate->course->instructor)->name ?? '—' }}</div>
                </div>
                <div>
                  <div class="text-gray-500 mb-1">القسم</div>
                  <div class="font-semibold">{{ optional($certificate->course->category)->name ?? '—' }}</div>
                </div>
                <div>
                  <div class="text-gray-500 mb-1">تاريخ الإصدار</div>
                  <div class="font-semibold">{{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y-n-j H:i') : '—' }}</div>
                </div>
              </div>

              <!-- Signature Row -->
              <div class="mt-10 flex items-center justify-between">
                <div class="text-left">
                  <div class="w-40 h-10 bg-gradient-to-r from-gray-200 to-gray-100 rounded-full mb-2"></div>
                  <div class="text-gray-500">توقيع المدير</div>
                </div>
                <div class="text-right">
                  <div class="w-40 h-10 bg-gradient-to-r from-gray-200 to-gray-100 rounded-full mb-2"></div>
                  <div class="text-gray-500">ختم المؤسسة</div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-3">
            @if($certificate->certificate_url)
            <a href="{{ $certificate->certificate_url }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700"><i class="ti ti-download mr-2"></i>تحميل الملف</a>
            @endif
            <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl shadow hover:bg-gray-50"><i class="ti ti-printer mr-2"></i>طباعة</button>
          </div>
        </div>

        <!-- Variant 2: Formal with brand frame -->
        <div id="cert-variant-2" class="hidden relative mt-6 rounded-[28px] overflow-hidden shadow-xl bg-white">
          <!-- Outer frame using brand gradient -->
          <div class="relative p-10 rounded-[24px]" style="background:#fff; border:4px solid transparent; border-image: linear-gradient(45deg, #06b6d4, #3b82f6, #1e40af) 1; box-shadow: inset 0 0 0 10px rgba(59,130,246,0.08);">
            <!-- Inner subtle frame -->
            <div class="absolute inset-4 rounded-[18px] pointer-events-none" style="border:2px solid rgba(14,165,233,0.25);"></div>
            <!-- Decorative brand corners -->
            <div class="pointer-events-none">
              <div class="absolute -top-10 -right-10 w-40 h-40 bg-cyan-200/20 rounded-full blur-2xl"></div>
              <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-blue-200/20 rounded-full blur-2xl"></div>
            </div>
            <!-- Top Bar with Logo -->
            <div class="flex items-center justify-between mb-8">
              <div class="flex items-center gap-4">
                <img src="{{ asset('images/asta-logo.png') }}" alt="ASTA" class="h-10 w-auto" />
                <div>
                  <div class="text-gray-900 font-extrabold tracking-widest" style="font-size: 1.3rem;">ASTA Academy</div>
                  <div class="text-gray-500" style="font-size: .95rem;">Certificate ID: #{{ $certificate->id }}</div>
                </div>
              </div>
              <div class="text-gray-600" style="font-size: .95rem;">{{ now()->format('Y-n-j') }}</div>
            </div>

            <div class="text-center">
              <h2 class="font-serif font-bold text-gray-900" style="font-size: 2.4rem; letter-spacing:.06em;">CERTIFICATE OF COMPLETION</h2>
              <p class="text-gray-700 mt-2 font-serif" style="font-size: 1.1rem;">This certifies that</p>

              <div class="mt-6">
                <div class="inline-block px-10 py-3 rounded-2xl" style="border:2px solid transparent; border-image: linear-gradient(90deg, #06b6d4, #3b82f6) 1;">
                  <div class="font-serif text-gray-900" style="font-size: 1.9rem;">{{ optional($certificate->user)->name ?? '—' }}</div>
                </div>
                <div class="email-content text-ltr mt-1 text-gray-500">{{ optional($certificate->user)->email ?? '' }}</div>
              </div>

              <p class="mt-6 text-gray-700 font-serif" style="font-size: 1.15rem;">has successfully completed the course</p>
              <div class="font-semibold text-gray-900" style="font-size: 1.5rem;">{{ optional($certificate->course)->title ?? '—' }}</div>

              <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                  <div class="text-gray-600 mb-1">Instructor</div>
                  <div class="font-medium">{{ optional($certificate->course->instructor)->name ?? '—' }}</div>
                </div>
                <div>
                  <div class="text-gray-600 mb-1">Category</div>
                  <div class="font-medium">{{ optional($certificate->course->category)->name ?? '—' }}</div>
                </div>
                <div>
                  <div class="text-gray-600 mb-1">Issued on</div>
                  <div class="font-medium">{{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y-n-j') : '—' }}</div>
                </div>
              </div>

              <!-- Signature Row -->
              <div class="mt-12 grid grid-cols-2 gap-8 items-end">
                <div class="text-left">
                  <div class="mb-2" style="height:2px; width: 12rem; background: linear-gradient(90deg, #06b6d4, #3b82f6);"></div>
                  <div class="text-gray-700">Director Signature</div>
                </div>
                <div class="text-right">
                  <div class="mb-2 ml-auto" style="height:2px; width: 12rem; background: linear-gradient(90deg, #3b82f6, #1e40af);"></div>
                  <div class="text-gray-700">Official Seal</div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-3">
            @if($certificate->certificate_url)
            <a href="{{ $certificate->certificate_url }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700"><i class="ti ti-download mr-2"></i>تحميل الملف</a>
            @endif
            <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl shadow hover:bg-gray-50"><i class="ti ti-printer mr-2"></i>طباعة</button>
          </div>
        </div>
      </div>

      <!-- Details / Actions -->
      <div class="lg:col-span-1 space-y-6">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الشهادة</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
              <span class="text-gray-600">رقم الشهادة</span>
              <span class="font-medium">{{ $certificate->id }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
              <span class="text-gray-600">الحالة</span>
              <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.2rem;">صادرة</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
              <span class="text-gray-600">أُصدرت</span>
              <span class="font-medium">{{ $certificate->created_at ? $certificate->created_at->format('Y-n-j') : '—' }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات</h3>
          <div class="space-y-3">
            @if($certificate->certificate_url)
            <a href="{{ $certificate->certificate_url }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700"><i class="ti ti-download mr-2"></i>تنزيل الشهادة</a>
            @endif
            <form action="{{ route('certificates.destroy', $certificate) }}" method="POST" onsubmit="return confirm('سحب الشهادة؟')">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-xl shadow hover:bg-red-700"><i class="ti ti-trash mr-2"></i>سحب الشهادة</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleDesign(n) {
    const v1 = document.getElementById('cert-variant-1');
    const v2 = document.getElementById('cert-variant-2');
    if (n === 1) { v1.classList.remove('hidden'); v2.classList.add('hidden'); }
    else { v2.classList.remove('hidden'); v1.classList.add('hidden'); }
  }
</script>
@endsection
