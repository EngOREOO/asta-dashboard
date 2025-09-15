<?php
    $title = 'إنشاء حملة تسويقية';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-red-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-red-700 bg-clip-text text-transparent">
          إنشاء حملة تسويقية
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-mail mr-2 text-purple-500"></i>
          أرسل رسائل مخصصة لمستخدميك
        </p>
      </div>
      <div class="flex space-x-2 space-x-reverse mt-4 sm:mt-0">
        <a href="<?php echo e(route('admin.marketing.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="ti ti-arrow-right ml-2"></i>
          العودة
        </a>
      </div>
    </div>

    <!-- Email Composer Form -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-edit mr-3"></i>
          محرر الرسائل الإلكترونية
        </h3>
      </div>
      
      <div class="p-8">
        <form method="POST" action="<?php echo e(route('admin.marketing.send')); ?>" enctype="multipart/form-data" id="emailForm">
          <?php echo csrf_field(); ?>
          
          <!-- Recipients Selection -->
          <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">اختيار المستلمين</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <label class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                <input type="checkbox" name="recipients[]" value="students" class="ml-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center ml-3">
                    <i class="ti ti-users text-white"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">الطلاب</p>
                    <p class="text-sm text-gray-600"><?php echo e(number_format($students->count())); ?> طالب</p>
                  </div>
                </div>
              </label>

              <label class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                <input type="checkbox" name="recipients[]" value="instructors" class="ml-3 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center ml-3">
                    <i class="ti ti-user-star text-white"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">المدربين</p>
                    <p class="text-sm text-gray-600"><?php echo e(number_format($instructors->count())); ?> مدرب</p>
                  </div>
                </div>
              </label>

              <label class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                <input type="checkbox" name="recipients[]" value="all" class="ml-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center ml-3">
                    <i class="ti ti-mail text-white"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">جميع المستخدمين</p>
                    <p class="text-sm text-gray-600"><?php echo e(number_format($students->count() + $instructors->count())); ?> مستخدم</p>
                  </div>
                </div>
              </label>
            </div>
          </div>

          <!-- Email Subject -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان الرسالة</label>
            <input type="text" name="subject" required 
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                   placeholder="أدخل عنوان الرسالة">
          </div>

          <!-- Content Type -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">نوع المحتوى</label>
            <div class="flex space-x-4 space-x-reverse">
              <label class="flex items-center">
                <input type="radio" name="content_type" value="text" checked class="ml-2 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                <span class="text-gray-700">نص عادي</span>
              </label>
              <label class="flex items-center">
                <input type="radio" name="content_type" value="html" class="ml-2 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                <span class="text-gray-700">HTML</span>
              </label>
            </div>
          </div>

          <!-- Email Content -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">محتوى الرسالة</label>
            <div class="border border-gray-300 rounded-xl overflow-hidden">
              <div class="bg-gray-50 px-4 py-2 border-b border-gray-300">
                <div class="flex items-center space-x-2 space-x-reverse">
                  <button type="button" onclick="toggleEditor()" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="ti ti-code"></i> محرر HTML
                  </button>
                  <button type="button" onclick="previewEmail()" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="ti ti-eye"></i> معاينة
                  </button>
                </div>
              </div>
              <textarea name="content" id="emailContent" required rows="15" 
                        class="w-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-300"
                        placeholder="أدخل محتوى الرسالة هنا..."></textarea>
            </div>
          </div>

          <!-- Attachments -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">المرفقات (اختياري)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-purple-400 transition-colors duration-300">
              <input type="file" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx,.txt" 
                     class="hidden" id="fileInput" onchange="handleFiles(this)">
              <label for="fileInput" class="cursor-pointer">
                <i class="ti ti-cloud-upload text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">اسحب الملفات هنا أو انقر للاختيار</p>
                <p class="text-sm text-gray-400 mt-1">يمكن رفع عدة ملفات (حد أقصى 10MB لكل ملف)</p>
              </label>
            </div>
            <div id="fileList" class="mt-4 space-y-2"></div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4">
            <button type="button" onclick="previewEmail()" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-eye ml-2"></i>
              معاينة الرسالة
            </button>
            
            <button type="submit" 
                    class="flex-1 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
              <i class="ti ti-send ml-2"></i>
              إرسال الرسالة
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4 flex items-center justify-between">
      <h3 class="text-xl font-bold text-white">معاينة الرسالة</h3>
      <button onclick="closePreview()" class="text-white hover:text-gray-200">
        <i class="ti ti-x text-xl"></i>
      </button>
    </div>
    <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
      <div id="previewContent"></div>
    </div>
  </div>
</div>

<script>
function toggleEditor() {
    const contentType = document.querySelector('input[name="content_type"]:checked').value;
    const textarea = document.getElementById('emailContent');
    
    if (contentType === 'html') {
        // Switch to HTML mode
        textarea.style.fontFamily = 'monospace';
        textarea.placeholder = 'أدخل محتوى HTML هنا...';
    } else {
        // Switch to text mode
        textarea.style.fontFamily = 'inherit';
        textarea.placeholder = 'أدخل محتوى الرسالة هنا...';
    }
}

function handleFiles(input) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        fileItem.innerHTML = `
            <div class="flex items-center">
                <i class="ti ti-file text-gray-400 ml-2"></i>
                <span class="text-sm text-gray-700">${file.name}</span>
                <span class="text-xs text-gray-500 mr-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
            </div>
            <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                <i class="ti ti-x"></i>
            </button>
        `;
        fileList.appendChild(fileItem);
    });
}

function removeFile(index) {
    const input = document.getElementById('fileInput');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    handleFiles(input);
}

function previewEmail() {
    const form = document.getElementById('emailForm');
    const formData = new FormData(form);
    
    // Show loading
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = '<div class="text-center py-8"><i class="ti ti-loader animate-spin text-2xl text-gray-400"></i><p class="text-gray-600 mt-2">جاري تحميل المعاينة...</p></div>';
    
    document.getElementById('previewModal').classList.remove('hidden');
    
    // Send preview request
    fetch('<?php echo e(route("admin.marketing.preview")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(html => {
        previewContent.innerHTML = html;
    })
    .catch(error => {
        previewContent.innerHTML = '<div class="text-center py-8 text-red-600"><i class="ti ti-alert-circle text-2xl"></i><p class="mt-2">حدث خطأ في تحميل المعاينة</p></div>';
    });
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Form validation
document.getElementById('emailForm').addEventListener('submit', function(e) {
    const recipients = document.querySelectorAll('input[name="recipients[]"]:checked');
    if (recipients.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار مستلم واحد على الأقل');
        return;
    }
    
    const subject = document.querySelector('input[name="subject"]').value.trim();
    if (!subject) {
        e.preventDefault();
        alert('يرجى إدخال عنوان الرسالة');
        return;
    }
    
    const content = document.querySelector('textarea[name="content"]').value.trim();
    if (!content) {
        e.preventDefault();
        alert('يرجى إدخال محتوى الرسالة');
        return;
    }
    
    // Show confirmation
    const recipientCount = recipients.length;
    const recipientText = recipientCount === 1 ? recipients[0].value : 'المستلمين المحددين';
    
    if (!confirm(`هل أنت متأكد من إرسال الرسالة إلى ${recipientText}؟`)) {
        e.preventDefault();
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/marketing/create.blade.php ENDPATH**/ ?>