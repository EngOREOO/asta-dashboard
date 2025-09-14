<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
  <div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4">
            <i class="ti ti-edit text-white text-xl"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800" style="font-size: 2rem;">تعديل الشهادة</h1>
            <p class="text-gray-600" style="font-size: 1.1rem;">تعديل شهادة <?php echo e($testimonial->user_name); ?></p>
          </div>
        </div>
        <a href="<?php echo e(route('testimonials.index')); ?>" 
           class="bg-white/70 backdrop-blur-xl shadow-lg rounded-2xl px-6 py-3 text-gray-700 font-semibold hover:shadow-xl transition-all duration-300 border border-white/20">
          <i class="ti ti-arrow-right mr-2"></i>العودة للقائمة
        </a>
      </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="bg-gradient-to-r from-orange-500 via-red-500 to-pink-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-red-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-edit text-white"></i>
            </div>
            بيانات الشهادة
          </h2>
        </div>
      </div>

      <form action="<?php echo e(route('testimonials.update', $testimonial)); ?>" method="POST" enctype="multipart/form-data" class="p-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- User Name -->
        <div class="mb-8">
          <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.2rem;">
            <i class="ti ti-user mr-2 text-blue-600"></i>اسم العميل
          </label>
          <input type="text" name="user_name" value="<?php echo e(old('user_name', $testimonial->user_name)); ?>" 
                 class="w-full px-6 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-lg"
                 placeholder="أدخل اسم العميل" required>
          <?php $__errorArgs = ['user_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-600 mt-2 text-sm"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- User Image -->
        <div class="mb-8">
          <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.2rem;">
            <i class="ti ti-photo mr-2 text-green-600"></i>صورة العميل
          </label>
          
          <!-- Current Image -->
          <?php if($testimonial->user_image): ?>
            <div class="mb-4">
              <p class="text-gray-600 mb-2">الصورة الحالية:</p>
              <div class="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white shadow-lg">
                <img src="<?php echo e($testimonial->user_image_url); ?>" alt="<?php echo e($testimonial->user_name); ?>" 
                     class="w-full h-full object-cover">
              </div>
            </div>
          <?php endif; ?>

          <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-blue-500 transition-colors duration-300">
            <div class="mb-4">
              <i class="ti ti-cloud-upload text-4xl text-gray-400"></i>
            </div>
            <p class="text-gray-600 mb-4">اسحب الصورة هنا أو انقر للاختيار</p>
            <input type="file" name="user_image" id="user_image" accept="image/*" 
                   class="hidden" onchange="previewImage(this)">
            <label for="user_image" 
                   class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 cursor-pointer">
              <i class="ti ti-upload mr-2"></i><?php echo e($testimonial->user_image ? 'تغيير الصورة' : 'اختيار صورة'); ?>

            </label>
            <div id="image-preview" class="mt-4 hidden">
              <img id="preview-img" class="w-32 h-32 object-cover rounded-2xl mx-auto border-4 border-white shadow-lg">
            </div>
          </div>
          <?php $__errorArgs = ['user_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-600 mt-2 text-sm"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Rating -->
        <div class="mb-8">
          <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.2rem;">
            <i class="ti ti-star mr-2 text-yellow-600"></i>التقييم
          </label>
          <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <?php for($i = 1; $i <= 5; $i++): ?>
              <label class="cursor-pointer">
                <input type="radio" name="rating" value="<?php echo e($i); ?>" 
                       <?php echo e(old('rating', $testimonial->rating) == $i ? 'checked' : ''); ?>

                       class="sr-only rating-input">
                <i class="ti ti-star text-3xl rating-star <?php echo e(old('rating', $testimonial->rating) >= $i ? 'text-yellow-400' : 'text-gray-300'); ?>" 
                   data-rating="<?php echo e($i); ?>"></i>
              </label>
            <?php endfor; ?>
            <span class="mr-4 text-gray-600 font-semibold" id="rating-text"><?php echo e(old('rating', $testimonial->rating)); ?>/5</span>
          </div>
          <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-600 mt-2 text-sm"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Comment -->
        <div class="mb-8">
          <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.2rem;">
            <i class="ti ti-message-circle mr-2 text-purple-600"></i>التعليق
          </label>
          <textarea name="comment" rows="6" 
                    class="w-full px-6 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-lg resize-none"
                    placeholder="أدخل تعليق العميل" required><?php echo e(old('comment', $testimonial->comment)); ?></textarea>
          <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-600 mt-2 text-sm"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Settings Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Approval Status -->
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
            <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.1rem;">
              <i class="ti ti-check-circle mr-2 text-green-600"></i>حالة الموافقة
            </label>
            <div class="flex items-center">
              <input type="hidden" name="is_approved" value="0">
              <input type="checkbox" class="sr-only peer" id="is_approved" name="is_approved" value="1"
                     <?php echo e(old('is_approved', $testimonial->is_approved) ? 'checked' : ''); ?>>
              <label for="is_approved" 
                     class="relative w-12 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full cursor-pointer transition-colors duration-300">
                <span class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></span>
              </label>
              <span class="mr-3 text-gray-700 font-semibold">موافق عليه</span>
            </div>
          </div>

          <!-- Featured Status -->
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
            <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.1rem;">
              <i class="ti ti-star mr-2 text-purple-600"></i>حالة التمييز
            </label>
            <div class="flex items-center">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" class="sr-only peer" id="is_featured" name="is_featured" value="1"
                     <?php echo e(old('is_featured', $testimonial->is_featured) ? 'checked' : ''); ?>>
              <label for="is_featured" 
                     class="relative w-12 h-6 bg-gray-300 peer-checked:bg-purple-500 rounded-full cursor-pointer transition-colors duration-300">
                <span class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></span>
              </label>
              <span class="mr-3 text-gray-700 font-semibold">مميز</span>
            </div>
          </div>

          <!-- Sort Order -->
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
            <label class="block font-bold text-gray-800 mb-4" style="font-size: 1.1rem;">
              <i class="ti ti-sort-ascending mr-2 text-blue-600"></i>ترتيب العرض
            </label>
            <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $testimonial->sort_order)); ?>" min="0"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                   placeholder="0">
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-4 rtl:space-x-reverse">
          <a href="<?php echo e(route('testimonials.index')); ?>" 
             class="bg-gray-100 text-gray-700 px-8 py-4 rounded-2xl font-semibold hover:bg-gray-200 transition-all duration-300">
            <i class="ti ti-x mr-2"></i>إلغاء
          </a>
          <button type="submit" 
                  class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-4 rounded-2xl font-semibold hover:from-orange-700 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="ti ti-check mr-2"></i>حفظ التغييرات
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Rating functionality
document.querySelectorAll('.rating-star').forEach(star => {
  star.addEventListener('click', function() {
    const rating = this.getAttribute('data-rating');
    
    // Update radio button
    document.querySelector(`input[name="rating"][value="${rating}"]`).checked = true;
    
    // Update stars display
    document.querySelectorAll('.rating-star').forEach((s, index) => {
      if (index < rating) {
        s.classList.remove('text-gray-300');
        s.classList.add('text-yellow-400');
      } else {
        s.classList.remove('text-yellow-400');
        s.classList.add('text-gray-300');
      }
    });
    
    // Update rating text
    document.getElementById('rating-text').textContent = rating + '/5';
  });
});

// Image preview functionality
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
      const preview = document.getElementById('image-preview');
      const img = document.getElementById('preview-img');
      
      img.src = e.target.result;
      preview.classList.remove('hidden');
    };
    
    reader.readAsDataURL(input.files[0]);
  }
}

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('user_image');

dropZone.addEventListener('dragover', (e) => {
  e.preventDefault();
  dropZone.classList.add('border-blue-500', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', (e) => {
  e.preventDefault();
  dropZone.classList.remove('border-blue-500', 'bg-blue-50');
});

dropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  dropZone.classList.remove('border-blue-500', 'bg-blue-50');
  
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    fileInput.files = files;
    previewImage(fileInput);
  }
});

dropZone.addEventListener('click', () => {
  fileInput.click();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/testimonials/edit.blade.php ENDPATH**/ ?>