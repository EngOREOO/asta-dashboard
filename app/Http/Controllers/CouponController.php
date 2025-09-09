<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query()->withCount('courses');

        if ($request->filled('code')) {
            $query->where('code', 'like', '%'.$request->input('code').'%');
        }
        if ($request->filled('applies_to')) {
            $query->where('applies_to', $request->input('applies_to'));
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }
        if ($request->filled('percentage_min')) {
            $query->where('percentage', '>=', (float) $request->input('percentage_min'));
        }
        if ($request->filled('percentage_max')) {
            $query->where('percentage', '<=', (float) $request->input('percentage_max'));
        }
        if ($request->filled('starts_from')) {
            $query->whereDate('starts_at', '>=', $request->input('starts_from'));
        }
        if ($request->filled('starts_to')) {
            $query->whereDate('starts_at', '<=', $request->input('starts_to'));
        }
        if ($request->filled('ends_from')) {
            $query->whereDate('ends_at', '>=', $request->input('ends_from'));
        }
        if ($request->filled('ends_to')) {
            $query->whereDate('ends_at', '<=', $request->input('ends_to'));
        }

        // Sorting newest first by default
        $query->latest();

        $coupons = $query->paginate(20)->withQueryString();

        return view('coupons.index', [
            'coupons' => $coupons,
            'filters' => $request->only(['code','applies_to','status','percentage_min','percentage_max','starts_from','starts_to','ends_from','ends_to'])
        ]);
    }

    public function create()
    {
        $courses = Course::with(['category:id,name', 'instructor:id,name'])
            ->orderBy('title')
            ->get(['id','title','category_id','instructor_id']);

        $categoriesList = $courses->pluck('category.name')->filter()->unique()->sort()->values();
        $instructorsList = $courses->pluck('instructor.name')->filter()->unique()->sort()->values();

        return view('coupons.create', compact('courses','categoriesList','instructorsList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','string','max:50','unique:coupons,code'],
            'percentage' => ['required','numeric','min:1','max:100'],
            'applies_to' => ['required','in:all,selected'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'description' => ['nullable','string'],
            'course_ids' => ['array'],
            'course_ids.*' => ['integer','exists:courses,id'],
        ]);

        $coupon = Coupon::create([
            'code' => $data['code'],
            'percentage' => $data['percentage'],
            'applies_to' => $data['applies_to'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);

        if (($data['applies_to'] ?? 'selected') === 'selected' && ! empty($data['course_ids'])) {
            $coupon->courses()->sync($data['course_ids']);
        }

        return redirect()->route('coupons.index')->with('success', 'تم إنشاء الكوبون بنجاح.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load('courses');
        return view('coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $courses = Course::orderBy('title')->get(['id','title']);
        $coupon->load('courses');
        return view('coupons.edit', compact('coupon','courses'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'percentage' => ['required','numeric','min:1','max:100'],
            'applies_to' => ['required','in:all,selected'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'description' => ['nullable','string'],
            'course_ids' => ['array'],
            'course_ids.*' => ['integer','exists:courses,id'],
        ]);
        $coupon->update($data);
        if (($data['applies_to'] ?? 'selected') === 'selected') {
            $coupon->courses()->sync($data['course_ids'] ?? []);
        } else {
            $coupon->courses()->detach();
        }
        return redirect()->route('coupons.index')->with('success', 'تم تحديث الكوبون.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'تم حذف الكوبون.');
    }
}


