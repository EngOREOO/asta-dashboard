<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterialProgress;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Get student's enrolled courses
     */
    public function enrolledCourses()
    {
        $user = auth('sanctum')->user();
        $courses = $user->enrolledCourses()
            ->with(['instructor', 'category', 'degree'])
            ->get();

        return response()->json($courses);
    }

    /**
     * Get student's completed courses
     */
    public function completedCourses()
    {
        $user = auth('sanctum')->user();

        // Get all enrolled courses with materials
        $enrolledCourses = $user->enrolledCourses()
            ->with(['instructor', 'category', 'degree', 'materials'])
            ->get();

        // Calculate statistics
        $totalEnrolledCourses = $enrolledCourses->count();
        $coursesWithMaterials = 0;
        $coursesWithCompletedMaterials = 0;
        $completedCoursesCount = 0;
        $inProgressCount = 0;
        $notStartedCount = 0;

        $completedCourses = [];
        $allCourses = [];

        foreach ($enrolledCourses as $course) {
            $totalMaterials = $course->materials->count();

            if ($totalMaterials > 0) {
                $coursesWithMaterials++;

                $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $course->materials->pluck('id'))
                    ->where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $remainingMaterials = $totalMaterials - $completedMaterials;
                $progressPercent = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100, 2) : 0;

                // Determine course status
                if ($completedMaterials >= $totalMaterials) {
                    $status = 'completed';
                    $completedCoursesCount++;
                    $coursesWithCompletedMaterials++;

                    $completedCourses[] = [
                        'courseId' => $course->id,
                        'title' => $course->title,
                        'instructor' => [
                            'id' => $course->instructor->id ?? null,
                            'name' => $course->instructor->name ?? 'غير محدد',
                        ],
                        'category' => [
                            'id' => $course->category->id ?? null,
                            'name' => $course->category->name ?? 'غير محدد',
                        ],
                        'degree' => [
                            'id' => $course->degree->id ?? null,
                            'name' => $course->degree->name ?? 'غير محدد',
                        ],
                        'materials' => [
                            'total' => $totalMaterials,
                            'completed' => $completedMaterials,
                            'remaining' => $remainingMaterials,
                            'progressPercent' => $progressPercent,
                        ],
                        'status' => $status,
                        'completedAt' => now()->toISOString(),
                        'links' => [
                            'details' => "/courses/{$course->id}",
                            'materials' => "/courses/{$course->id}/materials?fields=id,title,status",
                        ],
                    ];
                } elseif ($completedMaterials > 0) {
                    $status = 'in_progress';
                    $inProgressCount++;
                } else {
                    $status = 'not_started';
                    $notStartedCount++;
                }

                $allCourses[] = [
                    'courseId' => $course->id,
                    'title' => $course->title,
                    'instructor' => [
                        'id' => $course->instructor->id ?? null,
                        'name' => $course->instructor->name ?? 'غير محدد',
                    ],
                    'category' => [
                        'id' => $course->category->id ?? null,
                        'name' => $course->category->name ?? 'غير محدد',
                    ],
                    'degree' => [
                        'id' => $course->degree->id ?? null,
                        'name' => $course->degree->name ?? 'غير محدد',
                    ],
                    'materials' => [
                        'total' => $totalMaterials,
                        'completed' => $completedMaterials,
                        'remaining' => $remainingMaterials,
                        'progressPercent' => $progressPercent,
                    ],
                    'status' => $status,
                    'links' => [
                        'details' => "/courses/{$course->id}",
                        'materials' => "/courses/{$course->id}/materials?fields=id,title,status",
                    ],
                ];
            }
        }

        return response()->json([
            'meta' => [
                'generatedAt' => now()->toISOString(),
                'locale' => 'ar',
                'currency' => 'ر.س',
                'pagination' => [
                    'page' => 1,
                    'perPage' => $totalEnrolledCourses,
                    'total' => $totalEnrolledCourses,
                ],
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
            ],
            'summary' => [
                'totalEnrolledCourses' => $totalEnrolledCourses,
                'coursesWithMaterials' => $coursesWithMaterials,
                'coursesWithCompletedMaterials' => $coursesWithCompletedMaterials,
                'completedCoursesCount' => $completedCoursesCount,
                'inProgressCount' => $inProgressCount,
                'notStartedCount' => $notStartedCount,
            ],
            'completedCourses' => $completedCourses,
            'courses' => $allCourses,
        ]);
    }

    /**
     * Get student's courses under study (in progress)
     */
    public function underStudyingCourses()
    {
        $user = auth('sanctum')->user();

        $underStudyingCourses = $user->enrolledCourses()
            ->with(['instructor', 'category', 'degree'])
            ->get()
            ->filter(function ($course) use ($user) {
                $totalMaterials = $course->materials()->count();
                $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $course->materials()->pluck('id'))
                    ->where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->count();

                return $totalMaterials > 0 && $completedMaterials > 0 && $completedMaterials < $totalMaterials;
            });

        return response()->json($underStudyingCourses->values());
    }

    /**
     * Get student's wishlist
     */
    public function wishlist()
    {
        $user = auth('sanctum')->user();
        $wishlist = $user->wishlist()->with(['instructor', 'category', 'degree'])->get();

        return response()->json($wishlist);
    }

    /**
     * Add course to wishlist
     */
    public function addToWishlist(Course $course)
    {
        $user = auth('sanctum')->user();

        if ($user->wishlist()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Course already in wishlist'], 409);
        }

        $user->wishlist()->attach($course->id);

        return response()->json(['message' => 'Course added to wishlist']);
    }

    /**
     * Remove course from wishlist
     */
    public function removeFromWishlist(Course $course)
    {
        $user = auth('sanctum')->user();
        $user->wishlist()->detach($course->id);

        return response()->json(['message' => 'Course removed from wishlist']);
    }

    /**
     * Get course suggestions based on student's behavior
     */
    public function suggestedCourses(Request $request)
    {
        $user = auth('sanctum')->user();
        $search = $request->get('search', '');

        // Get user's enrolled course categories
        $enrolledCategoryIds = $user->enrolledCourses()
            ->pluck('category_id')
            ->unique()
            ->filter()
            ->values();

        // Get user's completed course categories
        $completedCategoryIds = $user->enrolledCourses()
            ->get()
            ->filter(function ($course) use ($user) {
                $totalMaterials = $course->materials()->count();
                $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $course->materials()->pluck('id'))
                    ->where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->count();

                return $totalMaterials > 0 && $completedMaterials >= $totalMaterials;
            })
            ->pluck('category_id')
            ->unique()
            ->filter()
            ->values();

        // Build query for suggestions
        $query = Course::with(['instructor', 'category', 'degree'])
            ->whereIn('status', ['approved', 'published'])
            ->whereNotIn('id', $user->enrolledCourses()->pluck('course_id'));

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Prioritize courses from categories the user has completed
        if ($completedCategoryIds->isNotEmpty()) {
            $query->orderByRaw('CASE WHEN category_id IN ('.$completedCategoryIds->implode(',').') THEN 1 ELSE 2 END');
        }
        // Then prioritize courses from categories the user is enrolled in
        elseif ($enrolledCategoryIds->isNotEmpty()) {
            $query->orderByRaw('CASE WHEN category_id IN ('.$enrolledCategoryIds->implode(',').') THEN 1 ELSE 2 END');
        }

        // Order by rating and popularity
        $query->orderBy('average_rating', 'desc')
            ->orderBy('total_ratings', 'desc');

        $suggestions = $query->take(10)->get();

        // Fallback: if no personalized suggestions, return randomized courses (2 to 4) from each category
        if ($suggestions->isEmpty()) {
            $fallback = collect();
            $categories = Category::select('id')->get();
            foreach ($categories as $category) {
                $take = random_int(2, 4);
                $randomCourses = Course::with(['instructor', 'category', 'degree'])
                    ->where('category_id', $category->id)
                    ->whereIn('status', ['approved', 'published'])
                    ->inRandomOrder()
                    ->take($take)
                    ->get();
                $fallback = $fallback->concat($randomCourses);
            }
            // Optionally limit total fallback size
            $suggestions = $fallback->values();
        }

        return response()->json([
            'suggestions' => $suggestions,
            'search' => $search,
            'enrolled_categories' => $enrolledCategoryIds,
            'completed_categories' => $completedCategoryIds,
        ]);
    }

    /**
     * Get student dashboard statistics
     */
    public function dashboardStats()
    {
        $user = auth('sanctum')->user();

        $enrolledCoursesCount = $user->enrolledCourses()->count();
        $wishlistCount = $user->wishlist()->count();

        // Compute counts and average progress without calling other controller methods
        $enrolledCourses = $user->enrolledCourses()
            ->with(['materials:id,course_id'])
            ->get();

        $completedCoursesCount = 0;
        $underStudyingCoursesCount = 0;
        $totalProgressPercentAcrossCourses = 0;
        $coursesWithAtLeastOneMaterial = 0;

        foreach ($enrolledCourses as $course) {
            $materialIds = $course->materials->pluck('id');
            $totalMaterials = $materialIds->count();
            if ($totalMaterials === 0) {
                continue;
            }

            $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $materialIds)
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->count();

            if ($completedMaterials >= $totalMaterials) {
                $completedCoursesCount++;
            } elseif ($completedMaterials > 0) {
                $underStudyingCoursesCount++;
            }

            $totalProgressPercentAcrossCourses += ($completedMaterials / $totalMaterials) * 100;
            $coursesWithAtLeastOneMaterial++;
        }

        $averageProgress = $coursesWithAtLeastOneMaterial > 0
            ? round($totalProgressPercentAcrossCourses / $coursesWithAtLeastOneMaterial, 2)
            : 0;

        return response()->json([
            'enrolled_courses' => $enrolledCoursesCount,
            'completed_courses' => $completedCoursesCount,
            'under_studying_courses' => $underStudyingCoursesCount,
            'wishlist_count' => $wishlistCount,
            'average_progress' => $averageProgress,
            'total_courses' => $coursesWithAtLeastOneMaterial,
        ]);
    }
}
