<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'ASTA') }}</title>
  
  <!-- Vite Assets -->
  @vite(['resources/css/dash.css', 'resources/js/dash/assets.js'])
  
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">
  
  <!-- Custom Styles -->
  <style>
    :root {
      --asta-primary: #0ea5e9;
      --asta-secondary: #0891b2;
      --asta-accent: #06b6d4;
      --asta-teal: #14b8a6;
      --asta-blue: #3b82f6;
      --asta-dark: #1e40af;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
      animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
      animation: slideUp 0.8s ease-out;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic min-h-screen">
  <div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      @yield('content')
    </div>
  </div>
</body>
</html>
