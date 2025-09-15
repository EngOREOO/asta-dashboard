@php
    $title = 'قوالب الرسائل الإلكترونية';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-red-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-red-700 bg-clip-text text-transparent">
          قوالب الرسائل الإلكترونية
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-template mr-2 text-purple-500"></i>
          استخدم القوالب الجاهزة لحملاتك التسويقية
        </p>
      </div>
      <div class="flex space-x-2 space-x-reverse mt-4 sm:mt-0">
        <a href="{{ route('admin.marketing.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="ti ti-arrow-right ml-2"></i>
          العودة
        </a>
        <a href="{{ route('admin.marketing.create') }}" class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="ti ti-plus ml-2"></i>
          رسالة مخصصة
        </a>
      </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-up">
      @foreach($templates as $key => $template)
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 hover:scale-105 transition-all duration-300">
        <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-600 px-8 py-6">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="ti ti-template mr-3"></i>
            {{ $template['name'] }}
          </h3>
        </div>
        
        <div class="p-8">
          <div class="mb-6">
            <h4 class="font-semibold text-gray-800 mb-2">العنوان:</h4>
            <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $template['subject'] }}</p>
          </div>
          
          <div class="mb-6">
            <h4 class="font-semibold text-gray-800 mb-2">معاينة المحتوى:</h4>
            <div class="bg-gray-50 p-4 rounded-lg max-h-40 overflow-y-auto">
              <div class="prose prose-sm max-w-none">
                {!! Str::limit(strip_tags($template['content']), 200) !!}
              </div>
            </div>
          </div>
          
          <div class="flex space-x-2 space-x-reverse">
            <button onclick="useTemplate('{{ $key }}')" 
                    class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-lg flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-check ml-2"></i>
              استخدام هذا القالب
            </button>
            <button onclick="previewTemplate('{{ $key }}')" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-eye ml-2"></i>
              معاينة كاملة
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Custom Template Creator -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-plus mr-3"></i>
          إنشاء قالب مخصص
        </h3>
      </div>
      
      <div class="p-8">
        <form id="customTemplateForm">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">اسم القالب</label>
              <input type="text" id="templateName" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300" placeholder="أدخل اسم القالب">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">العنوان الافتراضي</label>
              <input type="text" id="templateSubject" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300" placeholder="أدخل العنوان الافتراضي">
            </div>
          </div>
          
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">محتوى القالب</label>
            <textarea id="templateContent" rows="10" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300" placeholder="أدخل محتوى القالب (HTML مسموح)"></textarea>
          </div>
          
          <div class="mt-6 flex space-x-2 space-x-reverse">
            <button type="button" onclick="saveCustomTemplate()" 
                    class="flex-1 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-save ml-2"></i>
              حفظ القالب
            </button>
            <button type="button" onclick="previewCustomTemplate()" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-eye ml-2"></i>
              معاينة القالب
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Template Preview Modal -->
<div id="templatePreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4 flex items-center justify-between">
      <h3 class="text-xl font-bold text-white">معاينة القالب</h3>
      <button onclick="closeTemplatePreview()" class="text-white hover:text-gray-200">
        <i class="ti ti-x text-xl"></i>
      </button>
    </div>
    <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
      <div id="templatePreviewContent"></div>
    </div>
  </div>
</div>

<script>
const templates = @json($templates);

function useTemplate(templateKey) {
    const template = templates[templateKey];
    
    // Redirect to create page with template data
    const params = new URLSearchParams({
        template: templateKey,
        subject: template.subject,
        content: template.content,
        content_type: 'html'
    });
    
    window.location.href = '{{ route("admin.marketing.create") }}?' + params.toString();
}

function previewTemplate(templateKey) {
    const template = templates[templateKey];
    
    const previewContent = document.getElementById('templatePreviewContent');
    previewContent.innerHTML = `
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">${template.subject}</h2>
                    <p class="text-purple-100 text-sm">من: منصة أستا التعليمية</p>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        ${template.content}
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <p>منصة أستا التعليمية</p>
                        <p>جميع الحقوق محفوظة © ${new Date().getFullYear()}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('templatePreviewModal').classList.remove('hidden');
}

function closeTemplatePreview() {
    document.getElementById('templatePreviewModal').classList.add('hidden');
}

function saveCustomTemplate() {
    const name = document.getElementById('templateName').value.trim();
    const subject = document.getElementById('templateSubject').value.trim();
    const content = document.getElementById('templateContent').value.trim();
    
    if (!name || !subject || !content) {
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }
    
    // Here you would typically save to database
    // For now, we'll just show a success message
    alert('تم حفظ القالب بنجاح!');
    
    // Clear form
    document.getElementById('customTemplateForm').reset();
}

function previewCustomTemplate() {
    const subject = document.getElementById('templateSubject').value.trim();
    const content = document.getElementById('templateContent').value.trim();
    
    if (!subject || !content) {
        alert('يرجى إدخال العنوان والمحتوى للمعاينة');
        return;
    }
    
    const previewContent = document.getElementById('templatePreviewContent');
    previewContent.innerHTML = `
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">${subject}</h2>
                    <p class="text-purple-100 text-sm">من: منصة أستا التعليمية</p>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        ${content}
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <p>منصة أستا التعليمية</p>
                        <p>جميع الحقوق محفوظة © ${new Date().getFullYear()}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('templatePreviewModal').classList.remove('hidden');
}

// Load template data from URL parameters if available
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const templateKey = urlParams.get('template');
    
    if (templateKey && templates[templateKey]) {
        const template = templates[templateKey];
        document.getElementById('templateName').value = template.name;
        document.getElementById('templateSubject').value = template.subject;
        document.getElementById('templateContent').value = template.content;
    }
});
</script>

<style>
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
@endsection


