<?php ($title = 'تفاصيل المستخدم'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
          <i class="ti ti-check text-green-600 text-sm"></i>
        </div>
        <p class="text-green-800 font-medium"><?php echo e(session('success')); ?></p>
      </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
          <i class="ti ti-x text-red-600 text-sm"></i>
        </div>
        <p class="text-red-800 font-medium"><?php echo e(session('error')); ?></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
      <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
            <?php echo e(Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

          </div>
          <div class="min-w-0">
            <h1 class="text-2xl font-bold"><?php echo e($user->name); ?></h1>
            <p class="text-white/80 text-sm text-ltr"><?php echo e($user->email); ?></p>
          </div>
        </div>
        <a href="<?php echo e(route('users.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur-sm transition-all duration-300">
          <i class="ti ti-arrow-right text-lg ml-2"></i>
          عودة إلى المستخدمين
        </a>
      </div>
      <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
      <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- Profile card -->
      <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <div class="space-y-4">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 flex items-center justify-center text-white font-bold text-lg">
              <?php echo e(Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('')); ?>

            </div>
            <div>
              <h3 class="font-bold text-gray-800 text-lg">معلومات المستخدم</h3>
              <p class="text-gray-500 text-sm"><?php echo e($user->email); ?></p>
            </div>
          </div>
          
          <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-white/50 rounded-xl">
              <span class="text-gray-700 font-medium">الحالة</span>
              <span class="inline-flex px-3 py-1 rounded-full font-medium bg-emerald-100 text-emerald-800 text-sm">نشط</span>
            </div>
            
            <div class="p-3 bg-white/50 rounded-xl">
              <div class="text-gray-700 font-medium mb-2">الدور</div>
              <div class="flex flex-wrap gap-2">
                <?php if($user->roles->count() > 0): ?>
                  <?php $__currentLoopData = $user->roles->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($roleAr = ['admin'=>'مدير','instructor'=>'محاضر','student'=>'طالب','user'=>'مستخدم','super-admin'=>'سوبر أدمن'][$role->name] ?? $role->name); ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full font-medium bg-blue-100 text-blue-800 text-sm">
                      <i class="ti ti-shield text-sm ml-1"></i>
                      <?php echo e($roleAr); ?>

                    </span>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                  <span class="text-gray-500 text-sm">لا يوجد دور</span>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-white/50 rounded-xl">
              <span class="text-gray-700 font-medium">عدد الدورات</span>
              <span class="font-bold text-gray-800"><?php echo e(method_exists($user,'instructorCourses') ? $user->instructorCourses->count() : ($user->courses->count() ?? 0)); ?></span>
            </div>

            <?php ($isInstructor = $user->hasRole('instructor')); ?>
            <?php if($isInstructor): ?>
            <div class="p-4 bg-white/50 rounded-xl border border-gray-100">
              <div class="flex items-center gap-2 mb-3 text-gray-800">
                <i class="ti ti-chalkboard"></i>
                <span class="font-medium">بيانات المحاضر</span>
              </div>
              <div class="space-y-3 text-sm">
                <?php if(!empty($user->department)): ?>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-gray-600">
                    <i class="ti ti-building"></i>
                    <span>القسم</span>
                  </div>
                  <span class="font-medium text-gray-900"><?php echo e($user->department); ?></span>
                </div>
                <?php endif; ?>

                <?php if(!empty($user->specialization)): ?>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-gray-600">
                    <i class="ti ti-certificate"></i>
                    <span>التخصص</span>
                  </div>
                  <span class="font-medium text-gray-900"><?php echo e($user->specialization); ?></span>
                </div>
                <?php endif; ?>

                <?php ($phonesList = is_array($user->phones ?? null) ? array_values(array_filter($user->phones)) : []); ?>
                <?php if(count($phonesList)): ?>
                <div>
                  <div class="flex items-center gap-2 text-gray-600 mb-1">
                    <i class="ti ti-phone"></i>
                    <span>أرقام الهاتف</span>
                  </div>
                  <ul class="space-y-1">
                    <?php $__currentLoopData = $phonesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li>
                        <a href="tel:<?php echo e(preg_replace('/\s+/', '', $ph)); ?>" class="text-gray-800 hover:text-blue-600 transition" dir="ltr">
                          <?php echo e($ph); ?>

                        </a>
                      </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                </div>
                <?php endif; ?>

                <?php ($socialsList = is_array($user->social_links ?? null) ? $user->social_links : []); ?>
                <?php if(count($socialsList)): ?>
                <div>
                  <div class="flex items-center gap-2 text-gray-600 mb-1">
                    <i class="ti ti-world"></i>
                    <span>روابط السوشيال</span>
                  </div>
                  <ul class="space-y-1">
                    <?php $__currentLoopData = $socialsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php ($platform = $link['platform'] ?? ''); ?>
                      <?php ($url = $link['url'] ?? ''); ?>
                      <?php if(!empty($url)): ?>
                      <li>
                        <a href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all" dir="ltr">
                          <?php echo e($platform ?: $url); ?>

                          <i class="ti ti-external-link ml-1"></i>
                        </a>
                      </li>
                      <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Courses table -->
      <div class="xl:col-span-2 bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
            <i class="ti ti-book text-white text-lg"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800">الدورات</h3>
        </div>
        
        <?php if(method_exists($user,'instructorCourses') ? $user->instructorCourses->count() > 0 : $user->courses->count() > 0): ?>
        <div class="overflow-hidden rounded-xl border border-gray-200">
          <table class="w-full" id="user-courses-table">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-4 py-3 text-right text-gray-700 font-semibold">#</th>
                <th class="px-4 py-3 text-right text-gray-700 font-semibold">العنوان</th>
                <th class="px-4 py-3 text-right text-gray-700 font-semibold">الدور</th>
                <th class="px-4 py-3 text-right text-gray-700 font-semibold">الحالة</th>
                <th class="px-4 py-3 text-center text-gray-700 font-semibold">الإجراءات</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php ($courses = method_exists($user,'instructorCourses') ? $user->instructorCourses : $user->courses); ?>
              <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php ($statusAr = ['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'][$course->status] ?? ($course->status ?? 'مسودة')); ?>
              <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-4 py-3 text-center text-gray-600"><?php echo e($course->id); ?></td>
                <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($course->title); ?></td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    <?php echo e(method_exists($user,'instructorCourses') ? 'محاضر' : 'طالب'); ?>

                  </span>
                </td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700')); ?>">
                    <?php echo e($statusAr); ?>

                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <div class="inline-flex items-center gap-2">
                    <a class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200" 
                       href="<?php echo e(route('courses.show', $course)); ?>" 
                       title="عرض">
                      <i class="ti ti-eye text-sm"></i>
                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $course)): ?>
                    <a class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors duration-200" 
                       href="<?php echo e(route('courses.edit', $course)); ?>" 
                       title="تعديل">
                      <i class="ti ti-edit text-sm"></i>
                    </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
          <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
            <i class="ti ti-book-off text-3xl text-gray-400"></i>
          </div>
          <h3 class="text-lg font-semibold text-gray-600 mb-2">لا توجد دورات</h3>
          <p class="text-gray-500">هذا المستخدم غير مسجل في أي دورة حالياً</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Roles & Permissions management -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
          <i class="ti ti-lock text-white text-lg"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">الأدوار والصلاحيات</h3>
      </div>
      
      <!-- Assign Role Form -->
      <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 mb-6 border border-indigo-200">
        <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
          <i class="ti ti-user-plus text-indigo-600"></i>
          تعيين الدور
        </h4>
        <div class="mb-3 p-3 bg-blue-50 rounded-xl border border-blue-200">
          <div class="flex items-center gap-2">
            <i class="ti ti-info-circle text-blue-600 text-sm"></i>
            <p class="text-blue-800 text-sm font-medium">يمكن للمستخدم أن يكون له دور واحد فقط في كل مرة</p>
          </div>
        </div>
        <form method="POST" action="<?php echo e(route('users.roles.assign', $user)); ?>" class="space-y-4">
          <?php echo csrf_field(); ?>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 mb-2 text-sm font-medium">اختر دور جديد</label>
              <?php ($allRoles = class_exists(\Spatie\Permission\Models\Role::class) ? \Spatie\Permission\Models\Role::all() : collect()); ?>
              <select name="role_to_add" class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                <option value="">-- اختر دور جديد --</option>
                <?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if(!$user->roles->pluck('name')->contains($r->name)): ?>
                    <option value="<?php echo e($r->name); ?>"><?php echo e($r->name); ?></option>
                  <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="flex items-end">
              <button type="submit" name="action" value="add" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">
                <i class="ti ti-refresh text-sm ml-2"></i>
                تغيير الدور
              </button>
            </div>
          </div>
        </form>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Role -->
        <div class="bg-white/50 rounded-2xl p-6 border border-gray-200">
          <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ti ti-shield text-blue-600"></i>
            الدور الحالي
          </h4>
          <?php if($user->roles->count() > 0): ?>
          <div class="space-y-2">
            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                  <?php echo e(Str::substr($role->name, 0, 1)); ?>

                </div>
                <div>
                  <span class="font-bold text-gray-800 text-lg"><?php echo e($role->name); ?></span>
                  <p class="text-gray-500 text-sm"><?php echo e($role->permissions->count()); ?> صلاحية</p>
                </div>
              </div>
              <form method="POST" action="<?php echo e(route('users.roles.revoke', [$user, $role])); ?>" onsubmit="return confirm('هل أنت متأكد من إزالة هذا الدور؟');">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-all duration-300 text-sm">
                  <i class="ti ti-x text-sm ml-1"></i>
                  إزالة الدور
                </button>
              </form>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php else: ?>
          <div class="text-center py-8 text-gray-500">
            <i class="ti ti-shield-off text-3xl mb-2"></i>
            <p>لا يوجد دور مخصص</p>
            <p class="text-sm mt-1">يمكنك تعيين دور من القائمة أعلاه</p>
          </div>
          <?php endif; ?>
        </div>
        
        <!-- Direct Permissions -->
        <div class="bg-white/50 rounded-2xl p-6 border border-gray-200">
          <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ti ti-key text-emerald-600"></i>
            الصلاحيات المباشرة
          </h4>
          
          <!-- Grant Permissions Form -->
          <div class="mb-4 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
            <h5 class="font-medium text-emerald-800 mb-3 flex items-center gap-2">
              <i class="ti ti-plus text-emerald-600"></i>
              منح صلاحيات جديدة
            </h5>
            <form method="POST" action="<?php echo e(route('users.permissions.give', $user)); ?>" class="space-y-3">
              <?php echo csrf_field(); ?>
              <div>
                <label class="block text-gray-700 mb-2 text-sm font-medium">اختر الصلاحيات</label>
                <?php ($allPermissions = class_exists(\Spatie\Permission\Models\Permission::class) ? \Spatie\Permission\Models\Permission::all() : collect()); ?>
                <?php ($userDirectPerms = $user->getDirectPermissions()->pluck('name')->toArray()); ?>
                
                <?php (
                  $permissionGroups = [
                    'إدارة المستخدمين' => [
                      'users.read' => 'قراءة المستخدمين',
                      'users.create' => 'إنشاء مستخدمين',
                      'users.edit' => 'تعديل المستخدمين',
                      'users.delete' => 'حذف المستخدمين',
                      'instructors.read' => 'قراءة المحاضرين',
                      'instructors.create' => 'إنشاء محاضرين',
                      'instructors.edit' => 'تعديل المحاضرين',
                      'instructors.delete' => 'حذف المحاضرين',
                      'instructor-applications.read' => 'قراءة طلبات المحاضرين',
                      'instructor-applications.create' => 'إنشاء طلبات محاضرين',
                      'instructor-applications.edit' => 'تعديل طلبات المحاضرين',
                      'instructor-applications.delete' => 'حذف طلبات المحاضرين',
                      'roles-permissions.read' => 'قراءة الأدوار والصلاحيات',
                      'roles-permissions.create' => 'إنشاء أدوار وصلاحيات',
                      'roles-permissions.edit' => 'تعديل الأدوار والصلاحيات',
                      'roles-permissions.delete' => 'حذف الأدوار والصلاحيات',
                    ],
                    'إدارة الدورات' => [
                      'courses.read' => 'قراءة الدورات',
                      'courses.create' => 'إنشاء دورات',
                      'courses.edit' => 'تعديل الدورات',
                      'courses.delete' => 'حذف الدورات',
                      'categories.read' => 'قراءة الأقسام',
                      'categories.create' => 'إنشاء أقسام',
                      'categories.edit' => 'تعديل الأقسام',
                      'categories.delete' => 'حذف الأقسام',
                      'course-levels.read' => 'قراءة مستويات الدورات',
                      'course-levels.create' => 'إنشاء مستويات دورات',
                      'course-levels.edit' => 'تعديل مستويات الدورات',
                      'course-levels.delete' => 'حذف مستويات الدورات',
                      'topics.read' => 'قراءة المواضيع',
                      'topics.create' => 'إنشاء مواضيع',
                      'topics.edit' => 'تعديل المواضيع',
                      'topics.delete' => 'حذف المواضيع',
                    ],
                    'الاختبارات والتقييم' => [
                      'assessments.read' => 'قراءة الاختبارات',
                      'assessments.create' => 'إنشاء اختبارات',
                      'assessments.edit' => 'تعديل الاختبارات',
                      'assessments.delete' => 'حذف الاختبارات',
                      'quizzes.read' => 'قراءة اختبارات القبول',
                      'quizzes.create' => 'إنشاء اختبارات سريعة',
                      'quizzes.edit' => 'تعديل اختبارات القبول',
                      'quizzes.delete' => 'حذف اختبارات القبول',
                    ],
                    'إدارة الطلاب' => [
                      'enrollments.read' => 'قراءة التسجيلات',
                      'enrollments.create' => 'إنشاء تسجيلات',
                      'enrollments.edit' => 'تعديل التسجيلات',
                      'enrollments.delete' => 'حذف التسجيلات',
                      'student-progress.read' => 'قراءة تقدم الطلاب',
                    ],
                    'الكوبونات والخصومات' => [
                      'coupons.read' => 'قراءة الكوبونات',
                      'coupons.create' => 'إنشاء كوبونات',
                      'coupons.edit' => 'تعديل الكوبونات',
                      'coupons.delete' => 'حذف الكوبونات',
                    ],
                    'المسارات المهنية' => [
                      'degrees.read' => 'قراءة المسارات المهنية',
                      'degrees.create' => 'إنشاء مسارات مهنية',
                      'degrees.edit' => 'تعديل المسارات المهنية',
                      'degrees.delete' => 'حذف المسارات المهنية',
                      'learning-paths.read' => 'قراءة المسارات التعليمية',
                      'learning-paths.create' => 'إنشاء مسارات تعليمية',
                      'learning-paths.edit' => 'تعديل المسارات التعليمية',
                      'learning-paths.delete' => 'حذف المسارات التعليمية',
                    ],
                    'الشهادات' => [
                      'certificates.read' => 'قراءة الشهادات',
                      'certificates.create' => 'إنشاء شهادات',
                      'certificates.edit' => 'تعديل الشهادات',
                      'certificates.delete' => 'حذف الشهادات',
                    ],
                    'التعليقات والآراء' => [
                      'reviews.read' => 'قراءة التقييمات',
                      'reviews.create' => 'إنشاء تقييمات',
                      'reviews.edit' => 'تعديل التقييمات',
                      'reviews.delete' => 'حذف التقييمات',
                      'testimonials.read' => 'قراءة الشهادات',
                      'testimonials.create' => 'إنشاء شهادات',
                      'testimonials.edit' => 'تعديل الشهادات',
                      'testimonials.delete' => 'حذف الشهادات',
                      'comments.read' => 'قراءة التعليقات',
                      'comments.create' => 'إنشاء تعليقات',
                      'comments.edit' => 'تعديل التعليقات',
                      'comments.delete' => 'حذف التعليقات',
                    ],
                    'الملفات' => [
                      'files.read' => 'قراءة الملفات',
                      'files.create' => 'إنشاء ملفات',
                      'files.edit' => 'تعديل الملفات',
                      'files.delete' => 'حذف الملفات',
                    ],
                    'التحليلات والإعدادات' => [
                      'analytics.read' => 'قراءة التحليلات',
                      'system-settings.read' => 'قراءة إعدادات النظام',
                      'system-settings.edit' => 'تعديل إعدادات النظام',
                    ],
                  ]
                ); ?>
                
                <select name="permissions[]" multiple class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                  <?php $__currentLoopData = $permissionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e($groupName); ?>">
                      <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permKey => $permAr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!in_array($permKey, $userDirectPerms)): ?>
                          <option value="<?php echo e($permKey); ?>"><?php echo e($permAr); ?></option>
                        <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300">
                <i class="ti ti-plus text-sm ml-1"></i>
                منح الصلاحيات المحددة
              </button>
            </form>
          </div>
          
          <?php if($user->getDirectPermissions()->count() > 0): ?>
          <div class="space-y-2">
            <div class="flex items-center justify-between mb-3">
              <h5 class="font-medium text-gray-800">الصلاحيات الممنوحة</h5>
              <span class="text-sm text-gray-500"><?php echo e($user->getDirectPermissions()->count()); ?> صلاحية</span>
            </div>
            <div class="max-h-48 overflow-y-auto space-y-2">
              <?php $__currentLoopData = $user->getDirectPermissions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php (
                $permissionLabels = [
                  'users.read' => 'قراءة المستخدمين',
                  'users.create' => 'إنشاء مستخدمين',
                  'users.edit' => 'تعديل المستخدمين',
                  'users.delete' => 'حذف المستخدمين',
                  'instructors.read' => 'قراءة المحاضرين',
                  'instructors.create' => 'إنشاء محاضرين',
                  'instructors.edit' => 'تعديل المحاضرين',
                  'instructors.delete' => 'حذف المحاضرين',
                  'instructor-applications.read' => 'قراءة طلبات المحاضرين',
                  'instructor-applications.create' => 'إنشاء طلبات محاضرين',
                  'instructor-applications.edit' => 'تعديل طلبات المحاضرين',
                  'instructor-applications.delete' => 'حذف طلبات المحاضرين',
                  'roles-permissions.read' => 'قراءة الأدوار والصلاحيات',
                  'roles-permissions.create' => 'إنشاء أدوار وصلاحيات',
                  'roles-permissions.edit' => 'تعديل الأدوار والصلاحيات',
                  'roles-permissions.delete' => 'حذف الأدوار والصلاحيات',
                  'courses.read' => 'قراءة الدورات',
                  'courses.create' => 'إنشاء دورات',
                  'courses.edit' => 'تعديل الدورات',
                  'courses.delete' => 'حذف الدورات',
                  'categories.read' => 'قراءة الأقسام',
                  'categories.create' => 'إنشاء أقسام',
                  'categories.edit' => 'تعديل الأقسام',
                  'categories.delete' => 'حذف الأقسام',
                  'course-levels.read' => 'قراءة مستويات الدورات',
                  'course-levels.create' => 'إنشاء مستويات دورات',
                  'course-levels.edit' => 'تعديل مستويات الدورات',
                  'course-levels.delete' => 'حذف مستويات الدورات',
                  'topics.read' => 'قراءة المواضيع',
                  'topics.create' => 'إنشاء مواضيع',
                  'topics.edit' => 'تعديل المواضيع',
                  'topics.delete' => 'حذف المواضيع',
                  'assessments.read' => 'قراءة الاختبارات',
                  'assessments.create' => 'إنشاء اختبارات',
                  'assessments.edit' => 'تعديل الاختبارات',
                  'assessments.delete' => 'حذف الاختبارات',
                  'quizzes.read' => 'قراءة اختبارات القبول',
                  'quizzes.create' => 'إنشاء اختبارات سريعة',
                  'quizzes.edit' => 'تعديل اختبارات القبول',
                  'quizzes.delete' => 'حذف اختبارات القبول',
                  'enrollments.read' => 'قراءة التسجيلات',
                  'enrollments.create' => 'إنشاء تسجيلات',
                  'enrollments.edit' => 'تعديل التسجيلات',
                  'enrollments.delete' => 'حذف التسجيلات',
                  'student-progress.read' => 'قراءة تقدم الطلاب',
                  'coupons.read' => 'قراءة الكوبونات',
                  'coupons.create' => 'إنشاء كوبونات',
                  'coupons.edit' => 'تعديل الكوبونات',
                  'coupons.delete' => 'حذف الكوبونات',
                  'degrees.read' => 'قراءة المسارات المهنية',
                  'degrees.create' => 'إنشاء مسارات مهنية',
                  'degrees.edit' => 'تعديل المسارات المهنية',
                  'degrees.delete' => 'حذف المسارات المهنية',
                  'learning-paths.read' => 'قراءة المسارات التعليمية',
                  'learning-paths.create' => 'إنشاء مسارات تعليمية',
                  'learning-paths.edit' => 'تعديل المسارات التعليمية',
                  'learning-paths.delete' => 'حذف المسارات التعليمية',
                  'certificates.read' => 'قراءة الشهادات',
                  'certificates.create' => 'إنشاء شهادات',
                  'certificates.edit' => 'تعديل الشهادات',
                  'certificates.delete' => 'حذف الشهادات',
                  'reviews.read' => 'قراءة التقييمات',
                  'reviews.create' => 'إنشاء تقييمات',
                  'reviews.edit' => 'تعديل التقييمات',
                  'reviews.delete' => 'حذف التقييمات',
                  'testimonials.read' => 'قراءة الشهادات',
                  'testimonials.create' => 'إنشاء شهادات',
                  'testimonials.edit' => 'تعديل الشهادات',
                  'testimonials.delete' => 'حذف الشهادات',
                  'comments.read' => 'قراءة التعليقات',
                  'comments.create' => 'إنشاء تعليقات',
                  'comments.edit' => 'تعديل التعليقات',
                  'comments.delete' => 'حذف التعليقات',
                  'files.read' => 'قراءة الملفات',
                  'files.create' => 'إنشاء ملفات',
                  'files.edit' => 'تعديل الملفات',
                  'files.delete' => 'حذف الملفات',
                  'analytics.read' => 'قراءة التحليلات',
                  'system-settings.read' => 'قراءة إعدادات النظام',
                  'system-settings.edit' => 'تعديل إعدادات النظام',
                ]
              ); ?>
              <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200">
                <div class="flex items-center gap-3">
                  <div class="w-6 h-6 rounded bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                    <i class="ti ti-check text-white text-xs"></i>
                  </div>
                  <span class="font-medium text-gray-800 text-sm"><?php echo e($permissionLabels[$perm->name] ?? $perm->name); ?></span>
                </div>
                <form method="POST" action="<?php echo e(route('users.permissions.revoke', [$user, $perm])); ?>" onsubmit="return confirm('هل أنت متأكد من إزالة هذه الصلاحية؟');">
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button class="inline-flex items-center px-2 py-1 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-all duration-300 text-xs">
                    <i class="ti ti-x text-xs ml-1"></i>
                    إزالة
                  </button>
                </form>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
          <?php else: ?>
          <div class="text-center py-8 text-gray-500">
            <i class="ti ti-key-off text-3xl mb-2"></i>
            <p>لا توجد صلاحيات مباشرة</p>
            <p class="text-sm mt-1">يمكنك منح صلاحيات إضافية من القائمة أعلاه</p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#user-courses-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        language: {
          sProcessing: 'جاري التحميل...',
          sLengthMenu: 'أظهر _MENU_ سجل',
          sZeroRecords: 'لا توجد نتائج',
          sInfo: 'إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل',
          sInfoEmpty: 'يعرض 0 إلى 0 من أصل 0 سجل',
          sInfoFiltered: '(منتقاة من مجموع _MAX_ سجل)',
          sSearch: 'ابحث:',
          oPaginate: { sFirst: 'الأول', sPrevious: 'السابق', sNext: 'التالي', sLast: 'الأخير' }
        }
      });
    }
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/users/show.blade.php ENDPATH**/ ?>