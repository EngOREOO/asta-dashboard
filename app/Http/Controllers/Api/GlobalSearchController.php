<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Degree;
use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    /**
     * Perform a global search across all major entities
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
            'type' => 'sometimes|string|in:courses,degrees,learning_paths,categories,instructors,all',
            'limit' => 'sometimes|integer|min:1|max:100',
            'category_id' => 'sometimes|integer|exists:categories,id',
        ]);

        $searchTerm = $request->q;
        $type = $request->type ?? 'all';
        $limit = $request->limit ?? 20;
        $categoryId = $request->category_id;

        $results = [];

        // Search courses
        if (in_array($type, ['courses', 'all'])) {
            $courses = $this->searchCourses($searchTerm, $categoryId, $limit);
            $results['courses'] = $courses;
        }

        // Search degrees
        if (in_array($type, ['degrees', 'all'])) {
            $degrees = $this->searchDegrees($searchTerm, $categoryId, $limit);
            $results['degrees'] = $degrees;
        }

        // Search learning paths
        if (in_array($type, ['learning_paths', 'all'])) {
            $learningPaths = $this->searchLearningPaths($searchTerm, $categoryId, $limit);
            $results['learning_paths'] = $learningPaths;
        }

        // Search categories
        if (in_array($type, ['categories', 'all'])) {
            $categories = $this->searchCategories($searchTerm, $limit);
            $results['categories'] = $categories;
        }

        // Search instructors
        if (in_array($type, ['instructors', 'all'])) {
            $instructors = $this->searchInstructors($searchTerm, $limit);
            $results['instructors'] = $instructors;
        }

        // Add summary statistics
        $results['summary'] = [
            'total_results' => collect($results)->sum(fn ($items) => count($items)),
            'search_term' => $searchTerm,
            'filters_applied' => [
                'type' => $type,
                'category_id' => $categoryId,
            ],
        ];

        return response()->json($results);
    }

    /**
     * Search courses with category filtering
     */
    private function searchCourses(string $searchTerm, ?int $categoryId, int $limit): array
    {
        $query = Course::with([
            'instructor:id,name,profile_photo_path',
            'category:id,name,slug',
            'degree:id,name,name_ar',
        ])
            ->whereIn('status', ['approved', 'approved'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('overview', 'like', "%{$searchTerm}%")
                    ->orWhereHas('instructor', function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('category', function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('degree', function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('name_ar', 'like', "%{$searchTerm}%");
                    });
            });

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('average_rating', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'thumbnail' => $course->thumbnail,
                    'price' => $course->price,
                    'average_rating' => $course->average_rating,
                    'total_ratings' => $course->total_ratings,
                    'instructor' => $course->instructor ? [
                        'id' => $course->instructor->id,
                        'name' => $course->instructor->name,
                        'profile_photo_path' => $course->instructor->profile_photo_path,
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name' => $course->category->name,
                        'slug' => $course->category->slug,
                    ] : null,
                    'degree' => $course->degree ? [
                        'id' => $course->degree->id,
                        'name' => $course->degree->name,
                        'name_ar' => $course->degree->name_ar,
                    ] : null,
                    'type' => 'course',
                ];
            })
            ->toArray();
    }

    /**
     * Search degrees with category filtering
     */
    private function searchDegrees(string $searchTerm, ?int $categoryId, int $limit): array
    {
        $query = Degree::with(['instructors:id,name,profile_photo_path'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('name_ar', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('provider', 'like', "%{$searchTerm}%");
            });

        if ($categoryId) {
            $query->whereHas('courses', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        return $query->withCount('courses')
            ->withAvg('courses', 'average_rating')
            ->withAvg('courses', 'price')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(function ($degree) {
                return [
                    'id' => $degree->id,
                    'name' => $degree->name,
                    'name_ar' => $degree->name_ar,
                    'provider' => $degree->provider,
                    'level' => $degree->level,
                    'description' => $degree->description,
                    'courses_count' => $degree->courses_count,
                    'average_rating' => $degree->average_rating,
                    'average_price' => $degree->average_price,
                    'instructors_count' => $degree->instructors->count(),
                    'type' => 'degree',
                ];
            })
            ->toArray();
    }

    /**
     * Search learning paths with category filtering
     */
    private function searchLearningPaths(string $searchTerm, ?int $categoryId, int $limit): array
    {
        $query = LearningPath::with(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.price', 'courses.average_rating', 'courses.category_id')
                ->with(['category:id,name,slug', 'instructor:id,name,profile_photo_path']);
        }])
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });

        if ($categoryId) {
            $query->whereHas('courses', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        return $query->withCount('courses')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(function ($path) {
                return [
                    'id' => $path->id,
                    'name' => $path->name,
                    'slug' => $path->slug,
                    'description' => $path->description,
                    'image' => $path->image,
                    'image_url' => $path->image_url,
                    'courses_count' => $path->courses_count,
                    'average_rating' => $path->average_rating,
                    'total_price' => $path->total_price,
                    'formatted_price' => $path->formatted_price,
                    'type' => 'learning_path',
                ];
            })
            ->toArray();
    }

    /**
     * Search categories
     */
    private function searchCategories(string $searchTerm, int $limit): array
    {
        return Category::where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
        })
            ->withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'courses_count' => $category->courses_count,
                    'type' => 'category',
                ];
            })
            ->toArray();
    }

    /**
     * Search instructors
     */
    private function searchInstructors(string $searchTerm, int $limit): array
    {
        return User::role('instructor')
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('bio', 'like', "%{$searchTerm}%")
                    ->orWhere('job_title', 'like', "%{$searchTerm}%");
            })
            ->withCount('instructorCourses')
            ->withAvg('instructorCourses', 'average_rating')
            ->orderBy('instructor_courses_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($instructor) {
                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'profile_photo_path' => $instructor->profile_photo_path,
                    'bio' => $instructor->bio,
                    'job_title' => $instructor->job_title,
                    'courses_count' => $instructor->instructor_courses_count,
                    'average_rating' => $instructor->instructor_courses_avg_average_rating,
                    'type' => 'instructor',
                ];
            })
            ->toArray();
    }
}
