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
          <button type="button" class="px-4 py-2 rounded-xl border text-gray-700 bg-white hover:bg-gray-50" onclick="toggleDesign(1)">تصميم SVG</button>
          <button type="button" class="px-4 py-2 rounded-xl border text-gray-700 bg-white hover:bg-gray-50" onclick="toggleDesign(2)">تصميم رسمي</button>
        </div>

        <!-- Variant 1: SVG Template -->
        <div id="cert-variant-1" class="relative rounded-[28px] overflow-hidden shadow-xl bg-white">
          <div class="certificate-svg-container">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="100%" height="auto" viewBox="0 0 1684 1191" class="w-full h-auto">
              <!-- Background -->
              <path d="M0 0 C555.72 0 1111.44 0 1684 0 C1684 393.03 1684 786.06 1684 1191 C1128.28 1191 572.56 1191 0 1191 C0 797.97 0 404.94 0 0 Z " fill="#FEFEFE" transform="translate(0,0)"/>
              
              <!-- Border -->
              <path d="M0 0 C529.65 0 1059.3 0 1605 0 C1605 90.75 1605 181.5 1605 275 C1578.93 275 1552.86 275 1526 275 C1505.40521079 239.69464706 1505.40521079 239.69464706 1510.22265625 215.56835938 C1513.01615025 202.281983 1513.01615025 202.281983 1510.25 189.4375 C1506.34693446 185.20917899 1502.83317088 183.40811211 1497.375 181.5625 C1492.33566426 179.82192282 1487.37568207 177.99068867 1482.4453125 175.96484375 C1478.85838938 174.54955619 1475.20335392 173.3361171 1471.54296875 172.125 C1465.9318099 169.64264401 1463.13351944 166.05557022 1460.22265625 160.765625 C1459.88882431 160.16876892 1459.55499237 159.57191284 1459.21104431 158.95697021 C1458.15152814 157.05999892 1457.10604659 155.155796 1456.0625 153.25 C1449.28160695 139.38313851 1449.28160695 139.38313851 1438.5625 129 C1428.35081341 126.13356166 1419.09918624 130.42663035 1409.58203125 134.046875 C1408.62176025 134.40845703 1407.66148926 134.77003906 1406.67211914 135.14257812 C1404.76099139 135.86685546 1402.85490964 136.604627" fill="#30ADAD" transform="translate(0,0)"/>
              
              <!-- Decorative Elements -->
              <path d="M0 0 C160.38 0 320.76 0 486 0 C486 4.95 486 9.9 486 15 C339.81 15.33 193.62 15.66 43 16 C44.32 17.32 45.64 18.64 47 20 C48.43304064 22.86608129 48.30644155 24.81300783 48 28 C46.72245446 30.0360882 45.39300182 32.04109119 44 34 C43.71023811 37.16770724 43.71023811 37.16770724 46 39.8125 C48.24992294 43.39831468 48.43073227 44.8649702 48 49 C47.78601563 49.87293701 47.57203125 50.74587402 47.3515625 51.64526367 C46.89040046 56.04583838 47.86409851 59.45942839 49.0625 63.67578125 C49.28893188 64.50924576 49.51536377 65.34271027 49.74865723 66.20143127 C50.48726935 68.90853007 51.24320906 71.61043237 52 74.3125 C52.51722838 76.19065596 53.03349926 78.06907587 53.54882812 79.94775391 C54.56600327 83.64795114 55.59022636 87.34607501 56.62109375 91.04248047 C57.73411149 95.03529013 58.82693067 99.03308374 59.90625 103.03515625 C70.46216796 141.93255654 83.30112675 180.56683829 97.88720703 218.1328125 C99.08952908 221.23067783 100.27673123 224.33415928 101.46484375 227.4375" fill="#D9B763" transform="translate(39,39)"/>
              
              <!-- Certificate Title -->
              <text x="842" y="200" text-anchor="middle" font-family="Arial, sans-serif" font-size="48" font-weight="bold" fill="#2C3E50">شهادة إتمام</text>
              <text x="842" y="240" text-anchor="middle" font-family="Arial, sans-serif" font-size="24" fill="#7F8C8D">CERTIFICATE OF COMPLETION</text>
              
              <!-- Student Name -->
              <text x="842" y="350" text-anchor="middle" font-family="Arial, sans-serif" font-size="36" font-weight="bold" fill="#2C3E50">{{ optional($certificate->user)->name ?? '—' }}</text>
              <text x="842" y="380" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" fill="#7F8C8D">{{ optional($certificate->user)->email ?? '' }}</text>
              
              <!-- Course Information -->
              <text x="842" y="450" text-anchor="middle" font-family="Arial, sans-serif" font-size="24" fill="#34495E">لإتمامه بنجاح دورة</text>
              <text x="842" y="490" text-anchor="middle" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="#2C3E50">{{ optional($certificate->course)->title ?? '—' }}</text>
              
              <!-- Course Details -->
              <text x="842" y="550" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="#7F8C8D">المحاضر: {{ optional($certificate->course)->instructor?->name ?? '—' }}</text>
              <text x="842" y="580" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="#7F8C8D">القسم: {{ optional($certificate->course)->category?->name ?? '—' }}</text>
              
              <!-- Issue Date -->
              <text x="842" y="650" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" fill="#95A5A6">تاريخ الإصدار: {{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y-m-d') : '—' }}</text>
              
              <!-- Certificate ID -->
              <text x="842" y="680" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" fill="#BDC3C7">رقم الشهادة: #{{ $certificate->id }}</text>
              
              <!-- Signatures -->
              <text x="300" y="800" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" fill="#7F8C8D">توقيع المدير</text>
              <line x1="200" y1="820" x2="400" y2="820" stroke="#34495E" stroke-width="2"/>
              
              <text x="1384" y="800" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" fill="#7F8C8D">ختم المؤسسة</text>
              <circle cx="1384" cy="820" r="30" fill="none" stroke="#34495E" stroke-width="2"/>
              
              <!-- ASTA Logo Area -->
              <text x="100" y="100" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#30ADAD">ASTA</text>
              <text x="100" y="125" font-family="Arial, sans-serif" font-size="16" fill="#7F8C8D">Academy</text>
            </svg>
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
                  <div class="font-medium">{{ optional($certificate->course)->instructor?->name ?? '—' }}</div>
                </div>
                <div>
                  <div class="text-gray-600 mb-1">Category</div>
                  <div class="font-medium">{{ optional($certificate->course)->category?->name ?? '—' }}</div>
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

<style>
  .certificate-svg-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  
  .certificate-svg-container svg {
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  
  @media print {
    .certificate-svg-container {
      background: white;
      box-shadow: none;
      padding: 0;
    }
    
    .certificate-svg-container svg {
      box-shadow: none;
    }
  }
</style>

<script>
  function toggleDesign(n) {
    const v1 = document.getElementById('cert-variant-1');
    const v2 = document.getElementById('cert-variant-2');
    if (n === 1) { v1.classList.remove('hidden'); v2.classList.add('hidden'); }
    else { v2.classList.remove('hidden'); v1.classList.add('hidden'); }
  }
</script>
@endsection
