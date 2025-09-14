<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'is_approved' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('user_image')) {
                $image = $request->file('user_image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'testimonials/' . $filename;
                $image->move(public_path('testimonials'), $filename);
                $validated['user_image'] = $imagePath;
            }

            // Set default values
            $validated['is_approved'] = $request->input('is_approved') == '1';
            $validated['is_featured'] = $request->input('is_featured') == '1';
            $validated['sort_order'] = $request->input('sort_order', 0);

            $testimonial = Testimonial::create($validated);

            Log::info('Testimonial created successfully', ['testimonial_id' => $testimonial->id]);

            return redirect()->route('testimonials.index')
                ->with('success', 'تم إنشاء الشهادة بنجاح');

        } catch (\Exception $e) {
            Log::error('Error creating testimonial', ['error' => $e->getMessage()]);
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الشهادة');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return view('testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'is_approved' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('user_image')) {
                // Delete old image if exists
                if ($testimonial->user_image && file_exists(public_path($testimonial->user_image))) {
                    unlink(public_path($testimonial->user_image));
                }
                
                $image = $request->file('user_image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'testimonials/' . $filename;
                $image->move(public_path('testimonials'), $filename);
                $validated['user_image'] = $imagePath;
            }

            // Set boolean values
            $validated['is_approved'] = $request->input('is_approved') == '1';
            $validated['is_featured'] = $request->input('is_featured') == '1';

            $testimonial->update($validated);

            Log::info('Testimonial updated successfully', ['testimonial_id' => $testimonial->id]);

            return redirect()->route('testimonials.index')
                ->with('success', 'تم تحديث الشهادة بنجاح');

        } catch (\Exception $e) {
            Log::error('Error updating testimonial', ['error' => $e->getMessage()]);
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الشهادة');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            // Delete image if exists
            if ($testimonial->user_image && file_exists(public_path($testimonial->user_image))) {
                unlink(public_path($testimonial->user_image));
            }

            $testimonial->delete();

            Log::info('Testimonial deleted successfully', ['testimonial_id' => $testimonial->id]);

            return redirect()->route('testimonials.index')
                ->with('success', 'تم حذف الشهادة بنجاح');

        } catch (\Exception $e) {
            Log::error('Error deleting testimonial', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء حذف الشهادة');
        }
    }

    /**
     * Toggle approval status
     */
    public function toggleApproval(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_approved' => !$testimonial->is_approved]);
            
            $status = $testimonial->is_approved ? 'موافق عليه' : 'غير موافق عليه';
            
            return response()->json([
                'success' => true,
                'message' => "تم تغيير حالة الموافقة إلى: {$status}",
                'is_approved' => $testimonial->is_approved
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة الموافقة'
            ], 500);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_featured' => !$testimonial->is_featured]);
            
            $status = $testimonial->is_featured ? 'مميز' : 'عادي';
            
            return response()->json([
                'success' => true,
                'message' => "تم تغيير حالة التمييز إلى: {$status}",
                'is_featured' => $testimonial->is_featured
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة التمييز'
            ], 500);
        }
    }
}
