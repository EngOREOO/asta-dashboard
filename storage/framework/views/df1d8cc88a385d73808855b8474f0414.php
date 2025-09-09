<?php ($title = 'قسائم الخصم'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Header Card with Gradient like courses page -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10 flex items-center justify-between">
          <div class="text-right">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
                <i class="ti ti-ticket text-white text-xl"></i>
              </div>
              قائمة القسائم
            </h2>
            <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">إدارة الكوبونات والخصومات للدورات</p>
          </div>
          <a href="<?php echo e(route('coupons.create')); ?>" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl shadow hover:shadow-xl hover:from-emerald-700 hover:to-teal-700 transition" style="font-size: 1.3rem;">
            <i class="ti ti-square-plus mr-2 group-hover:scale-110 transition-transform"></i>
            إنشاء كوبون
          </a>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      <div class="p-6">
        <div class="bg-white/70 border border-white/20 rounded-2xl p-2"></div>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6">
      <!-- Filters like courses -->
      <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
          <label class="block text-sm text-gray-600 mb-1">الكود</label>
          <input type="text" name="code" value="<?php echo e($filters['code'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">الخصم من (%)</label>
          <input type="number" step="0.01" name="percentage_min" value="<?php echo e($filters['percentage_min'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">الخصم إلى (%)</label>
          <input type="number" step="0.01" name="percentage_max" value="<?php echo e($filters['percentage_max'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">النطاق</label>
          <select name="applies_to" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;">
            <option value="">الكل</option>
            <option value="all" <?php if(($filters['applies_to'] ?? '')==='all'): echo 'selected'; endif; ?>>كل الدورات</option>
            <option value="selected" <?php if(($filters['applies_to'] ?? '')==='selected'): echo 'selected'; endif; ?>>دورات محددة</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">الحالة</label>
          <select name="status" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;">
            <option value="">الكل</option>
            <option value="active" <?php if(($filters['status'] ?? '')==='active'): echo 'selected'; endif; ?>>نشط</option>
            <option value="inactive" <?php if(($filters['status'] ?? '')==='inactive'): echo 'selected'; endif; ?>>متوقف</option>
          </select>
        </div>
        <div class="md:col-span-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">من تاريخ (بداية)</label>
            <input type="date" name="starts_from" value="<?php echo e($filters['starts_from'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">إلى تاريخ (بداية)</label>
            <input type="date" name="starts_to" value="<?php echo e($filters['starts_to'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">من تاريخ (نهاية)</label>
            <input type="date" name="ends_from" value="<?php echo e($filters['ends_from'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">إلى تاريخ (نهاية)</label>
            <input type="date" name="ends_to" value="<?php echo e($filters['ends_to'] ?? ''); ?>" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" style="font-size: 1.15rem;" />
          </div>
        </div>
        <div class="md:col-span-6 flex items-end gap-3">
          <button class="coupon-action-btn px-5 py-2.5 rounded-xl bg-cyan-600 text-white hover:bg-cyan-700">تصفية</button>
          <a href="<?php echo e(route('coupons.index')); ?>" class="coupon-action-btn coupon-action-btn--secondary px-5 py-2.5 rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200">مسح</a>
        </div>
      </form>

      <?php if($coupons->count() === 0): ?>
        <div class="p-12 text-center">
          <div class="w-24 h-24 bg-gradient-to-r from-emerald-100 to-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-ticket text-4xl text-emerald-600"></i>
          </div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.6rem;">لا توجد قسائم</h3>
          <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">ابدأ بإنشاء أول كوبون خصم للدورات على المنصة.</p>
          <a href="<?php echo e(route('coupons.create')); ?>" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl shadow hover:shadow-xl hover:from-emerald-700 hover:to-teal-700 transition" style="font-size: 1.3rem;">
            <i class="ti ti-square-plus mr-2 group-hover:scale-110 transition-transform"></i>
            إنشاء كوبون
          </a>
        </div>
      <?php else: ?>
        <div class="admin-table-container">
          <table class="admin-table coupon-table-lg" id="coupons-table">
            <thead class="bg-gray-50">
              <tr>
                <th>#</th>
                <th>الكود</th>
                <th>الخصم</th>
                <th>النطاق</th>
                <th>الدورات المختارة</th>
                <th>الاستخدام</th>
                <th>الفترة</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td class="text-center"><?php echo e($coupon->id); ?></td>
                <td class="font-mono"><?php echo e($coupon->code); ?></td>
                <td class="text-center"><?php echo e($coupon->percentage); ?>%</td>
                <td class="text-center"><?php echo e($coupon->applies_to === 'all' ? 'كل الدورات' : 'دورات محددة'); ?></td>
                <td class="text-center"><?php echo e($coupon->applies_to === 'all' ? '—' : $coupon->courses_count); ?></td>
                <td class="text-center">0</td>
                <td class="text-center"><?php echo e(optional($coupon->starts_at)->format('Y-n-j')); ?> - <?php echo e(optional($coupon->ends_at)->format('Y-n-j')); ?></td>
                <td class="text-center">
                  <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium <?php echo e($coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>"><?php echo e($coupon->is_active ? 'نشط' : 'متوقف'); ?></span>
                </td>
                <td class="text-center">
                  <div class="inline-flex items-center gap-2">
                    <a href="<?php echo e(route('coupons.show', $coupon)); ?>" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" title="عرض"><i class="ti ti-eye text-base"></i></a>
                    <a href="<?php echo e(route('coupons.edit', $coupon)); ?>" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200" title="تعديل"><i class="ti ti-edit text-base"></i></a>
                    <form action="<?php echo e(route('coupons.destroy', $coupon)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف الكوبون؟')">
                      <?php echo csrf_field(); ?>
                      <?php echo method_field('DELETE'); ?>
                      <button type="submit" class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-red-700 bg-red-100 hover:bg-red-200" title="حذف"><i class="ti ti-trash text-base"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>


<style>
.coupon-table-lg, .coupon-table-lg th, .coupon-table-lg td {
  font-size: 1.3rem !important;
}
/* Match action buttons to table font and add cute hover */
.coupon-action-btn {
  font-size: 1.3rem !important;
  transition: transform .15s ease, box-shadow .2s ease, background-color .2s ease;
}
.coupon-action-btn:hover {
  transform: translateY(-1px) scale(1.02);
  box-shadow: 0 10px 18px rgba(59,130,246,0.18);
}
.coupon-action-btn--secondary:hover {
  box-shadow: 0 10px 18px rgba(0,0,0,0.06);
}
</style>

<script>
  (function(){
    const table = document.getElementById('coupons-table');
    if(!table) return;
    const inputs = table.querySelectorAll('.filter-input, .filter-select');
    const tbody = table.querySelector('tbody');

    function normalize(v){ return (v||'').toString().trim().toLowerCase(); }

    function applyFilters(){
      const filters = Array.from(inputs).reduce((acc, el)=>{
        acc[parseInt(el.dataset.col,10)] = normalize(el.value);
        return acc;
      }, {});
      Array.from(tbody.rows).forEach(row=>{
        let show = true;
        Array.from(row.cells).forEach((cell, idx)=>{
          if(filters[idx] !== undefined && filters[idx] !== ''){
            const cellText = normalize(cell.innerText || cell.textContent);
            if(!cellText.includes(filters[idx])){ show = false; }
          }
        });
        row.style.display = show ? '' : 'none';
      });
    }

    inputs.forEach(el=>{
      el.addEventListener('input', ()=>{});
      el.addEventListener('change', ()=>{});
    });
    const applyBtn = document.getElementById('apply-filters');
    const clearBtn = document.getElementById('clear-filters');
    if(applyBtn) applyBtn.addEventListener('click', applyFilters);
    if(clearBtn) clearBtn.addEventListener('click', ()=>{
      inputs.forEach(i=>{ i.value=''; });
      applyFilters();
    });
  })();
</script>


<?php echo $__env->make('layouts.dash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ahmed\Documents\asta\asss\resources\views/coupons/index.blade.php ENDPATH**/ ?>