<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    /**
     * Get all approved testimonials (public API)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Testimonial::approved()->ordered();

        // Filter by featured only if requested
        if ($request->has('featured') && $request->boolean('featured')) {
            $query->featured();
        }

        // Limit results if specified
        $limit = $request->get('limit', 10);
        if ($limit > 0) {
            $query->limit($limit);
        }

        $testimonials = $query->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'message' => 'Testimonials retrieved successfully',
        ]);
    }

    /**
     * Get a specific testimonial (public API)
     */
    public function show(Testimonial $testimonial): JsonResponse
    {
        if (! $testimonial->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Testimonial retrieved successfully',
        ]);
    }

    /**
     * Store a new testimonial (public API)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'testimonials/' . $filename;
            $image->move(public_path('testimonials'), $filename);
            $data['user_image'] = $imagePath;
        }

        // Set default values
        $data['is_approved'] = false; // Require admin approval
        $data['is_featured'] = false;

        $testimonial = Testimonial::create($data);

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Testimonial submitted successfully. It will be reviewed before being published.',
        ], 201);
    }

    /**
     * Get all testimonials for admin management
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Testimonial::query();

        // Filter by approval status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'approved':
                    $query->approved();
                    break;
                case 'pending':
                    $query->where('is_approved', false);
                    break;
                case 'featured':
                    $query->featured();
                    break;
            }
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->ordered()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'message' => 'Testimonials retrieved successfully',
        ]);
    }

    /**
     * Update testimonial (admin only)
     */
    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'sometimes|required|string|max:255',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'sometimes|required|string|min:10|max:1000',
            'is_approved' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

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
            $data['user_image'] = $imagePath;
        }

        $testimonial->update($data);

        return response()->json([
            'success' => true,
            'data' => $testimonial->fresh(),
            'message' => 'Testimonial updated successfully',
        ]);
    }

    /**
     * Approve testimonial (admin only)
     */
    public function approve(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update(['is_approved' => true]);

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Testimonial approved successfully',
        ]);
    }

    /**
     * Reject testimonial (admin only)
     */
    public function reject(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update(['is_approved' => false]);

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Testimonial rejected successfully',
        ]);
    }

    /**
     * Toggle featured status (admin only)
     */
    public function toggleFeatured(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update(['is_featured' => ! $testimonial->is_featured]);

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Featured status updated successfully',
        ]);
    }

    /**
     * Delete testimonial (admin only)
     */
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        // Delete associated image if exists
        if ($testimonial->user_image && file_exists(public_path($testimonial->user_image))) {
            unlink(public_path($testimonial->user_image));
        }

        $testimonial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully',
        ]);
    }

    /**
     * Get testimonials statistics (admin only)
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Testimonial::count(),
            'approved' => Testimonial::approved()->count(),
            'pending' => Testimonial::where('is_approved', false)->count(),
            'featured' => Testimonial::featured()->count(),
            'average_rating' => Testimonial::approved()->avg('rating'),
            'rating_distribution' => Testimonial::approved()
                ->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating')
                ->get()
                ->pluck('count', 'rating')
                ->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistics retrieved successfully',
        ]);
    }
}
