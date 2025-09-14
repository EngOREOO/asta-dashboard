<?php ($title = 'إنشاء مسار مهني'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إنشاء مسار مهني</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">أدخل معلومات المسار</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="<?php echo e(route('degrees.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للمسارات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-certificate text-white text-xl"></i>
            </div>
            نموذج إنشاء مسار
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="<?php echo e(route('degrees.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <?php echo csrf_field(); ?>

          <div class="md:col-span-2">
            <label for="name" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">اسم المسار *</label>
            <input id="name" name="name" type="text" value="<?php echo e(old('name')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="code" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">كود المسار</label>
            <input id="code" name="code" type="text" value="<?php echo e(old('code')); ?>" placeholder="EX-123" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem; font-family: Arial, sans-serif;">
            <small class="text-gray-500">اختياري: معرف قصير للمسار</small>
          </div>

          <div>
            <label for="level" class="block text-gray-700 mb-2" style="font-size: 1.3rem;"> المستوي المهني *</label>
            <select id="level" name="level" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر المستوي المهني</option>
              <?php $__currentLoopData = ($careerLevels ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cl->id); ?>" <?php echo e(old('level') == $cl->id ? 'selected' : ''); ?>><?php echo e($cl->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="category_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الاقسام</label>
            <select id="category_id" name="category_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
              <option value="">اختر القسم</option>
              <?php $__currentLoopData = ($categories ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="duration_months" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">المدة (أشهر)</label>
            <input id="duration_months" name="duration_months" type="number" value="<?php echo e(old('duration_months')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">المدة المتوقعة لإكمال البرنامج</small>
            <?php $__errorArgs = ['duration_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label for="credit_hours" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">عدد الساعات المعتمدة</label>
            <input id="credit_hours" name="credit_hours" type="number" value="<?php echo e(old('credit_hours')); ?>" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0">
            <small class="text-gray-500">إجمالي الساعات المعتمدة للمسار (اختياري)</small>
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;"><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-3">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدورات التدريبية</label>
            <div class="relative">
              <div class="multiselect-dropdown border border-gray-300 rounded-xl bg-white">
                <div class="dropdown-trigger flex items-center justify-between p-3 cursor-pointer hover:bg-gray-50 rounded-xl">
                  <div class="selected-items flex flex-wrap gap-1">
                    <span class="placeholder text-gray-500" style="font-size: 1.2rem;">اختر الدورات...</span>
                  </div>
                  <i class="ti ti-chevron-down text-gray-400"></i>
                </div>
                <div class="dropdown-content hidden absolute z-50 w-full bg-white border border-gray-300 rounded-xl shadow-lg mt-1 max-h-64 overflow-y-auto">
                  <div class="p-2 border-b border-gray-200">
                    <input type="text" class="search-input w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الدورات..." style="font-size: 1.2rem;">
                  </div>
                  <div class="course-list">
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <label class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                        <input type="checkbox" name="courses[]" value="<?php echo e($course->id); ?>" 
                               <?php echo e(in_array($course->id, old('courses', [])) ? 'checked' : ''); ?>

                               class="course-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3">
                        <span class="course-title" style="font-size: 1.2rem;"><?php echo e($course->title); ?></span>
                      </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                </div>
              </div>
            </div>
            <small class="text-gray-500">انقر لفتح قائمة الدورات واختر ما تريد</small>
            <?php $__errorArgs = ['courses'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['courses.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 mt-1" style="font-size: 1.3rem;"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نشط</label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
              <span style="font-size: 1.3rem;">نشط</span>
            </label>
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">إنشاء المسار</button>
            <a href="<?php echo e(route('degrees.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
.multiselect-dropdown {
  position: relative;
}

.dropdown-content {
  display: none;
}

.dropdown-content.show {
  display: block;
}

.selected-item {
  background: #3b82f6;
  color: white;
  padding: 2px 8px;
  border-radius: 6px;
  font-size: 1.1rem;
  display: inline-flex;
  align-items: center;
  margin: 2px;
}

.selected-item .remove {
  margin-right: 4px;
  cursor: pointer;
  font-weight: bold;
}

.selected-item .remove:hover {
  color: #fbbf24;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const dropdown = document.querySelector('.multiselect-dropdown');
  const trigger = dropdown.querySelector('.dropdown-trigger');
  const content = dropdown.querySelector('.dropdown-content');
  const searchInput = dropdown.querySelector('.search-input');
  const courseList = dropdown.querySelector('.course-list');
  const selectedItemsContainer = dropdown.querySelector('.selected-items');
  const placeholder = dropdown.querySelector('.placeholder');
  
  const courses = Array.from(dropdown.querySelectorAll('.course-checkbox')).map(checkbox => ({
    id: checkbox.value,
    title: checkbox.nextElementSibling.textContent.trim(),
    checkbox: checkbox
  }));
  
  // Toggle dropdown
  trigger.addEventListener('click', function(e) {
    e.stopPropagation();
    content.classList.toggle('show');
    if (content.classList.contains('show')) {
      searchInput.focus();
    }
  });
  
  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    if (!dropdown.contains(e.target)) {
      content.classList.remove('show');
    }
  });
  
  // Search functionality
  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    courses.forEach(course => {
      const isVisible = course.title.toLowerCase().includes(searchTerm);
      course.checkbox.closest('label').style.display = isVisible ? 'flex' : 'none';
    });
  });
  
  // Handle checkbox changes
  courses.forEach(course => {
    course.checkbox.addEventListener('change', function() {
      updateSelectedItems();
    });
  });
  
  // Update selected items display
  function updateSelectedItems() {
    const selectedCourses = courses.filter(course => course.checkbox.checked);
    
    if (selectedCourses.length === 0) {
      placeholder.style.display = 'block';
      selectedItemsContainer.innerHTML = '<span class="placeholder text-gray-500" style="font-size: 1.2rem;">اختر الدورات...</span>';
    } else {
      placeholder.style.display = 'none';
      selectedItemsContainer.innerHTML = selectedCourses.map(course => 
        `<span class="selected-item">
          <span class="remove" onclick="removeCourse(${course.id})">&times;</span>
          ${course.title}
        </span>`
      ).join('');
    }
  }
  
  // Remove course function
  window.removeCourse = function(courseId) {
    const course = courses.find(c => c.id == courseId);
    if (course) {
      course.checkbox.checked = false;
      updateSelectedItems();
    }
  };
  
  // Initialize display
  updateSelectedItems();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/degrees/create.blade.php ENDPATH**/ ?>