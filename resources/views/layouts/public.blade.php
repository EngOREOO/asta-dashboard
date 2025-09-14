<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons-sprite.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="ti ti-school text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">{{ config('app.name', 'ASTA') }}</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <a href="{{ route('testimonials.public') }}" 
                       class="text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium">
                        الشهادات
                    </a>
                    <a href="{{ route('testimonial.form') }}" 
                       class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                        شارك رأيك
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="ti ti-school text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold">{{ config('app.name', 'ASTA') }}</span>
                    </div>
                    <p class="text-gray-300 leading-relaxed">
                        منصة تعليمية متطورة تهدف إلى تقديم أفضل تجربة تعليمية للطلاب والمتعلمين.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">روابط سريعة</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('testimonials.public') }}" class="text-gray-300 hover:text-white transition-colors">الشهادات</a></li>
                        <li><a href="{{ route('testimonial.form') }}" class="text-gray-300 hover:text-white transition-colors">شارك رأيك</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">تواصل معنا</h3>
                    <div class="space-y-2">
                        <p class="text-gray-300">البريد الإلكتروني: info@asta.edu.sa</p>
                        <p class="text-gray-300">الهاتف: +966 XX XXX XXXX</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">
                    © {{ date('Y') }} {{ config('app.name', 'ASTA') }}. جميع الحقوق محفوظة.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

