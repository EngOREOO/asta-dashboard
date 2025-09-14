<?php
    $title = 'تعديل الدورة';
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Success/Error Notifications -->
    <?php if(session('success')): ?>
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-check-circle text-green-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-alert-circle text-red-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-red-800"><?php echo e(session('error')); ?></p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent">
          تعديل الدورة
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-book mr-2 text-cyan-500"></i>
          تحديث معلومات الدورة
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <a href="<?php echo e(route('courses.show', $course)); ?>" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          عرض
        </a>
        <a href="<?php echo e(route('courses.index')); ?>" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للدورات
        </a>
      </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="text-2xl font-bold text-white flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-book text-white text-xl"></i>
            </div>
            معلومات الدورة
          </h2>
          <p class="text-blue-100 mt-2">تحديث بيانات الدورة</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <form method="POST" action="<?php echo e(route('courses.update', $course)); ?>" enctype="multipart/form-data" class="space-y-8" style="font-size: 1.3rem;">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          
          <!-- Validation Errors -->
          <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 animate-shake">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-alert-circle text-red-400 text-xl"></i>
                </div>
                <div class="mr-3">
                  <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                  <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Basic Information -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-info-circle mr-2 text-blue-500"></i>
              المعلومات الأساسية
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="title" class="block text-gray-700 mb-2">عنوان الدورة</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="<?php echo e(old('title', $course->title)); ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       required>
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
              
              <div>
                <label for="price" class="block text-gray-700 mb-2">السعر</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <img src="<?php echo e(asset('riyal.svg')); ?>" alt="ريال" class="w-4 h-4 text-gray-500">
                  </div>
                  <input type="number" 
                         step="0.01" 
                         id="price" 
                         name="price" 
                         value="<?php echo e(old('price', $course->price)); ?>"
                         class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                         required>
                </div>
                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            </div>
            
            <div>
              <label for="code" class="block text-gray-700 mb-2">كود الدورة *</label>
              <input type="text" 
                     id="code" 
                     name="code" 
                     value="<?php echo e(old('code', $course->code)); ?>"
                     placeholder="CRS-001"
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                     style="font-family: Arial, sans-serif;"
                     required>
              <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              <small class="text-gray-500">معرف قصير للدورة</small>
            </div>
            
            <div>
              <label for="description" class="block text-gray-700 mb-2">الوصف</label>
              <textarea id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required><?php echo e(old('description', $course->description)); ?></textarea>
              <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>

          <!-- Course Details -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-settings mr-2 text-purple-500"></i>
              تفاصيل الدورة
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="estimated_duration" class="block text-gray-700 mb-2">المدة المقدرة (ساعات)</label>
                <input type="number" 
                       id="estimated_duration" 
                       name="estimated_duration" 
                       value="<?php echo e(old('estimated_duration', $course->estimated_duration)); ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['estimated_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['estimated_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
              
              <div>
                <label for="difficulty_level" class="block text-gray-700 mb-2">المستوى</label>
                <select id="difficulty_level" 
                        name="difficulty_level" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['difficulty_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                  <option value="">اختر المستوى</option>
                  <option value="beginner" <?php if(old('difficulty_level', $course->difficulty_level) === 'beginner'): echo 'selected'; endif; ?>>مبتدئ</option>
                  <option value="intermediate" <?php if(old('difficulty_level', $course->difficulty_level) === 'intermediate'): echo 'selected'; endif; ?>>متوسط</option>
                  <option value="advanced" <?php if(old('difficulty_level', $course->difficulty_level) === 'advanced'): echo 'selected'; endif; ?>>متقدم</option>
                </select>
                <?php $__errorArgs = ['difficulty_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="category_id" class="block text-gray-700 mb-2">القسم</label>
                <select id="category_id" 
                        name="category_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                  <option value="">اختر القسم</option>
                  <?php $__currentLoopData = \App\Models\Category::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $course->category_id) == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
              
              <div>
                <label for="status" class="block text-gray-700 mb-2">الحالة</label>
                <select id="status" 
                        name="status" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm">
                  <option value="draft" <?php if(old('status', $course->status) === 'draft'): echo 'selected'; endif; ?>>مسودة</option>
                  <option value="pending" <?php if(old('status', $course->status) === 'pending'): echo 'selected'; endif; ?>>في انتظار الموافقة</option>
                  <?php if(auth()->user()->hasRole('admin')): ?>
                  <option value="approved" <?php if(old('status', $course->status) === 'approved'): echo 'selected'; endif; ?>>موافق عليها</option>
                  <option value="rejected" <?php if(old('status', $course->status) === 'rejected'): echo 'selected'; endif; ?>>مرفوضة</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
          </div>


          <!-- Image Upload -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-photo mr-2 text-indigo-500"></i>
              صورة الدورة
            </h3>
            
            <div>
              <label for="image" class="block text-gray-700 mb-2">قم برفع صورة واضحة للدورة</label>
              <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 hover:border-indigo-300 transition-colors p-6 text-center">
                <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewCourseImage(event)">
                <label for="image" class="cursor-pointer inline-flex items-center px-5 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700">
                  <i class="ti ti-upload mr-2"></i>
                  اختر صورة
                </label>
                <p class="text-gray-500 mt-3">PNG أو JPG، بحد أقصى 2MB</p>
                <div id="course-image-preview" class="mt-4 flex justify-center">
                  <?php if($course->thumbnail && file_exists(public_path($course->thumbnail))): ?>
                    <img src="<?php echo e(asset($course->thumbnail)); ?>" alt="الصورة الحالية" class="w-64 h-40 object-cover rounded-2xl border border-gray-200 shadow-sm">
                  <?php endif; ?>
                </div>
              </div>
              <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-8 border-t border-gray-200">
            <a href="<?php echo e(route('courses.show', $course)); ?>" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="ti ti-x mr-2"></i>
              إلغاء
            </a>
            <?php if($course->status === 'draft' && $course->instructor_id === auth()->id()): ?>
            <button type="submit" 
                    name="submit_for_approval" 
                    value="1"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
              <i class="ti ti-send mr-2"></i>
              إرسال للموافقة
            </button>
            <?php endif; ?>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
              <i class="ti ti-check mr-2"></i>
              حفظ التغييرات
            </button>
          </div>
        </form>

        <!-- Admin Actions -->
        <?php if(auth()->user()->hasRole('admin') && $course->status === 'pending'): ?>
        <div class="mt-8 pt-8 border-t border-gray-200">
          <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-shield text-blue-400 text-xl"></i>
              </div>
              <div class="mr-4">
                <h3 class="text-lg font-medium text-blue-800">إجراءات الإدارة</h3>
                <p class="text-sm text-blue-600 mt-1">موافقة أو رفض الدورة</p>
              </div>
            </div>
            <div class="mt-4 flex gap-3">
              <form method="POST" action="<?php echo e(route('courses.approve', $course)); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                  <i class="ti ti-check mr-2"></i>
                  موافقة على الدورة
                </button>
              </form>
              <form method="POST" action="<?php echo e(route('courses.reject', $course)); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                  <i class="ti ti-x mr-2"></i>
                  رفض الدورة
                </button>
              </form>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Danger Zone -->
        <div class="mt-8 pt-8 border-t border-red-200">
          <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-alert-triangle text-red-400 text-xl"></i>
              </div>
              <div class="mr-4">
                <h3 class="text-lg font-medium text-red-800">منطقة الخطر</h3>
                <p class="text-sm text-red-600 mt-1">حذف هذه الدورة نهائياً. لا يمكن التراجع عن هذا الإجراء.</p>
              </div>
            </div>
            <div class="mt-4">
              <form method="POST" action="<?php echo e(route('courses.destroy', $course)); ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدورة؟ لا يمكن التراجع عن هذا الإجراء.')" class="inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                  <i class="ti ti-trash mr-2"></i>
                  حذف الدورة
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
  20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-slide-down {
  animation: slideDown 0.4s ease-out;
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to right, #2563eb, #7c3aed);
}
</style>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    // Auto-hide notifications after 5 seconds
    setTimeout(function() {
      const notifications = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
      notifications.forEach(notification => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
      });
    }, 5000);

    // Form validation
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
      submitButton.disabled = true;
      submitButton.innerHTML = '<i class="ti ti-loader-2 mr-2 animate-spin"></i>جاري الحفظ...';
    });
  });

  function previewCourseImage(event) {
    const container = document.getElementById('course-image-preview');
    container.innerHTML = '';
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const img = document.createElement('img');
    img.className = 'w-64 h-40 object-cover rounded-2xl border border-gray-200 shadow-sm';
    img.src = URL.createObjectURL(file);
    container.appendChild(img);
  }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/courses/edit.blade.php ENDPATH**/ ?>