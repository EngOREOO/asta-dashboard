<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Get reviews for a specific course
     */
    public function index(Course $course)
    {
        $reviews = $course->reviews()
            ->with('user:id,name,profile_photo_path')
            ->latest()
            ->paginate(10);

        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::guard('sanctum')->user();

        // Check if user is enrolled in the course
        if (!$user->courses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'message' => 'You must be enrolled in this course to leave a review.'
            ], 403);
        }

        // Check if user already reviewed this course
        if ($course->reviews()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'You have already reviewed this course.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = $course->reviews()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'message' => $request->message,
            'is_approved' => false, // Requires admin approval
        ]);

        // Update course rating
        $this->updateCourseRating($course);

        return new ReviewResource($review->load('user'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        return new ReviewResource($review->load('user'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        $user = Auth::guard('sanctum')->user();

        // Only the review owner or admin can update
        if ($review->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'message' => 'sometimes|string|min:10|max:1000',
            'is_approved' => 'sometimes|boolean', // Only for admin
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Only admin can update approval status
        if (!$user->hasRole('admin') && $request->has('is_approved')) {
            unset($request['is_approved']);
        }

        $review->update($validator->validated());

        // Update course rating if rating was changed
        if ($request->has('rating')) {
            $this->updateCourseRating($review->course);
        }

        return new ReviewResource($review->load('user'));
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        $user = Auth::guard('sanctum')->user();

        // Only the review owner or admin can delete
        if ($review->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = $review->course;
        $review->delete();

        // Update course rating
        $this->updateCourseRating($course);


        return response()->json(['message' => 'Review deleted successfully']);
    }

    /**
     * Update course's average rating and total ratings count
     */
    private function updateCourseRating(Course $course)
    {
        $stats = $course->reviews()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_ratings')
            ->first();

        $course->update([
            'average_rating' => $stats->avg_rating,
            'total_ratings' => $stats->total_ratings,
        ]);
    }
}
