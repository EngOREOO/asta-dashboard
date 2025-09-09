<?php ($title = 'إنشاء كوبون'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Header Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10 flex items-center justify-between">
          <div class="text-right">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
                <i class="ti ti-ticket text-white text-xl"></i>
              </div>
              إنشاء كوبون جديد
            </h2>
            <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">إنشاء كوبونات خصم وتحديد نطاقها وفترتها</p>
          </div>
          <a href="<?php echo e(route('coupons.index')); ?>" class="group inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl shadow hover:shadow-xl transition" style="font-size: 1.3rem;"><i class="ti ti-arrow-right mr-2"></i>عودة</a>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      <div class="p-8">
        <form method="POST" action="<?php echo e(route('coupons.store')); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{mode:'selected'}">
          <?php echo csrf_field(); ?>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الكود</label>
            <input name="code" type="text" class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500" style="font-size: 1.3rem;" placeholder="ASTA2025" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نسبة الخصم (%)</label>
            <input name="percentage" type="number" class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500" style="font-size: 1.3rem;" min="1" max="100" placeholder="20" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">ينطبق على</label>
            <select name="applies_to" x-model="mode" class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500" style="font-size: 1.3rem;">
              <option value="selected">دورات محددة</option>
              <option value="all">كل الدورات</option>
            </select>
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">تاريخ البداية</label>
            <input name="starts_at" type="date" class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500" style="font-size: 1.3rem;">
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">تاريخ النهاية</label>
            <input name="ends_at" type="date" class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus-border-teal-500" style="font-size: 1.3rem;">
          </div>
          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف (اختياري)</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500" style="font-size: 1.3rem;"></textarea>
          </div>
          <div id="course-picker" class="md:col-span-2" x-show="mode !== 'all'" x-cloak>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">اختر الدورات (يمكن اختيار عدة دورات)</label>
            <div x-data="{open:false,search:'',selected:[], toggle(){this.open=!this.open}, isChecked(id){return this.selected.includes(id)}, remove(id){ this.selected=this.selected.filter(x=>x!==id) }}" class="relative">
              <div class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white/80 cursor-pointer" @click="toggle()">
                <div class="flex flex-wrap gap-2">
                  <template x-if="selected.length===0">
                    <span class="text-gray-400">اختر الدورات...</span>
                  </template>
                  <template x-for="id in selected" :key="id">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                      <span x-text="document.querySelector(`#course-opt-${id}`)?.dataset.title || id"></span>
                      <button type="button" class="ml-1 text-emerald-700" @click.stop="remove(id)">×</button>
                    </span>
                  </template>
                </div>
              </div>
              <div x-show="open" @click.outside="open=false" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                <div class="p-2">
                  <input type="text" x-model="search" placeholder="ابحث عن دورة..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" />
                </div>
                <div class="p-2 space-y-1">
                  <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-50 cursor-pointer" id="course-opt-<?php echo e($course->id); ?>" data-title="<?php echo e($course->title); ?>" :class="{'bg-gray-50': isChecked(<?php echo e($course->id); ?>)}" x-show="'<?php echo e(Str::lower($course->title)); ?>'.includes(search.toLowerCase())">
                      <input type="checkbox" class="w-4 h-4 text-emerald-600 rounded border-gray-300" :checked="isChecked(<?php echo e($course->id); ?>)" @change="isChecked(<?php echo e($course->id); ?>) ? selected=selected.filter(x=>x!==<?php echo e($course->id); ?>) : selected.push(<?php echo e($course->id); ?>);" />
                      <span class="text-gray-700"><?php echo e($course->title); ?></span>
                    </label>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              </div>
              <!-- Hidden inputs for form submit -->
              <template x-for="id in selected" :key="'sel-'+id">
                <input type="hidden" name="course_ids[]" :value="id">
              </template>
            </div>
          </div>
          <div class="md:col-span-2 flex items-center justify-end gap-3">
            <a href="<?php echo e(route('coupons.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl shadow hover:shadow-xl hover:from-emerald-700 hover:to-teal-700" style="font-size: 1.3rem;"><i class="ti ti-device-floppy mr-2"></i>حفظ</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/coupons/create.blade.php ENDPATH**/ ?>