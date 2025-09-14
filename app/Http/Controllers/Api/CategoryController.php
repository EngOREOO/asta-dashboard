<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Degree;
use App\Models\LearningPath;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::with([
            'courses' => function ($query) {
                $query->with(['instructor:id,name,profile_photo_path', 'degree:id,name'])
                    ->withCount(['students', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->whereIn('status', ['draft', 'pending', 'approved']);
            }
        ])->get();

        // Enhance each category with related data
        $categories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'code' => $category->code,
                'description' => $category->description,
                'image_url' => $category->image_url,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                
                // Related data
                'courses' => $category->courses->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'description' => $course->description,
                        'price' => $course->price,
                        'is_free' => $course->is_free,
                        'status' => $course->status,
                        'level' => $course->level,
                        'duration' => $course->duration,
                        'thumbnail_url' => $course->thumbnail_url,
                        'instructor' => $course->instructor ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                            'profile_photo_path' => $course->instructor->profile_photo_path,
                        ] : null,
                        'degree' => $course->degree ? [
                            'id' => $course->degree->id,
                            'name' => $course->degree->name,
                        ] : null,
                        'students_count' => $course->students_count,
                        'reviews_count' => $course->reviews_count,
                        'reviews_avg_rating' => round($course->reviews_avg_rating ?? 0, 1),
                        'created_at' => $course->created_at,
                        'updated_at' => $course->updated_at,
                    ];
                }),
                
                // Statistics
                'stats' => [
                    'total_courses' => $category->courses->count(),
                    'total_students' => $category->courses->sum('students_count'),
                    'total_reviews' => $category->courses->sum('reviews_count'),
                    'average_rating' => $category->courses->avg('reviews_avg_rating') ? round($category->courses->avg('reviews_avg_rating'), 1) : 0,
                    'free_courses' => $category->courses->where('is_free', true)->count(),
                    'paid_courses' => $category->courses->where('is_free', false)->count(),
                    'approved_courses' => $category->courses->where('status', 'approved')->count(),
                    'pending_courses' => $category->courses->where('status', 'pending')->count(),
                    'draft_courses' => $category->courses->where('status', 'draft')->count(),
                ],
                
                // Related degrees
                'degrees' => $this->getRelatedDegrees($category),
                
                // Related learning paths
                'learning_paths' => $this->getRelatedLearningPaths($category),
                
                // Top instructors
                'top_instructors' => $this->getTopInstructors($category),
                
                // Recent courses (last 5)
                'recent_courses' => $this->getRecentCourses($category),
                
                // Featured courses
                'featured_courses' => $this->getFeaturedCourses($category),
                
                // Analytics
                'analytics' => $this->getCategoryAnalytics($category),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully',
        ]);
    }

    /**
     * Store a newly created category in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        // Load category with all related data
        $category->load([
            'courses' => function ($query) {
                $query->with(['instructor:id,name,profile_photo_path', 'degree:id,name'])
                    ->withCount(['students', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->whereIn('status', ['draft', 'pending', 'approved'])
                    ->orderBy('created_at', 'desc');
            }
        ]);

        // Get category statistics
        $stats = $this->getCategoryStats($category);

        // Get related degrees
        $degrees = $this->getRelatedDegrees($category);

        // Get related learning paths
        $learningPaths = $this->getRelatedLearningPaths($category);

        // Get top instructors in this category
        $topInstructors = $this->getTopInstructors($category);

        // Get recent courses
        $recentCourses = $this->getRecentCourses($category);

        // Get featured courses
        $featuredCourses = $this->getFeaturedCourses($category);

        // Get course analytics
        $analytics = $this->getCategoryAnalytics($category);

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'code' => $category->code,
                'description' => $category->description,
                'image_url' => $category->image_url,
                'created_at' => $category->created_at->toISOString(),
                'updated_at' => $category->updated_at->toISOString(),
            ],
            'stats' => $stats,
            'degrees' => $degrees,
            // 'learning_paths' => $learningPaths,
            'top_instructors' => $topInstructors,
            'recent_courses' => $recentCourses,
            'featured_courses' => $featuredCourses,
            'analytics' => $analytics,
            'all_courses' => $category->courses->map(function ($course) {
                return [
                    'id' => 'course_' . $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'code' => $course->code,
                    'description' => $course->description,
                    'price' => $course->price,
                    'is_free' => $course->price == 0,
                    'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : null,
                    'status' => $course->status,
                    'difficulty_level' => $course->difficulty_level,
                    'language' => $course->language,
                    'estimated_duration' => $course->estimated_duration,
                    'created_at' => $course->created_at->toISOString(),
                    'updated_at' => $course->updated_at->toISOString(),
                    'instructor' => $course->instructor ? [
                        'id' => $course->instructor->id,
                        'name' => $course->instructor->name,
                        'avatar' => $course->instructor->profile_photo_url,
                    ] : null,
                    'degree' => $course->degree ? [
                        'id' => $course->degree->id,
                        'name' => $course->degree->name,
                        'code' => $course->degree->code,
                    ] : null,
                    'stats' => [
                        'students_count' => $course->students_count ?? 0,
                        'reviews_count' => $course->reviews_count ?? 0,
                        'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                    ],
                ];
            }),
        ]);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        // Check if category has courses
        if ($category->courses()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with associated courses',
            ], 422);
        }

        $category->delete();

        return response()->json(null, 204);
    }

    /**
     * Get all courses for a specific category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function courses($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $courses = $category->courses()
            ->with(['instructor', 'category'])
            ->latest()
            ->paginate(12);

        return response()->json([
            'category' => $category,
            'courses' => $courses,
        ]);
    }

    /**
     * Get all degrees for a specific category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function degrees($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $degrees = Degree::with(['instructors:id,name,profile_photo_path'])
            ->withCount('courses')
            ->withAvg('courses', 'average_rating')
            ->withAvg('courses', 'price')
            ->whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'category' => $category,
            'degrees' => $degrees,
        ]);
    }

    /**
     * Get all learning paths for a specific category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function learningPaths($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)
                ->firstOrFail();

        $learningPaths = LearningPath::with(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.price', 'courses.average_rating', 'courses.category_id')
                ->with(['category:id,name,slug', 'instructor:id,name,profile_photo_path']);
        }])
            ->where('is_active', true)
            ->whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->withCount('courses')
            ->orderBy('sort_order')
            ->get();

        $formattedPaths = $learningPaths->map(function ($path) {
            return [
                'id' => $path->id,
                'name' => $path->name,
                'slug' => $path->slug,
                'description' => $path->description,
                'is_active' => $path->is_active,
                'sort_order' => $path->sort_order,
                'courses_count' => $path->courses_count,
                'average_rating' => $path->average_rating,
                'total_price' => $path->total_price,
                'formatted_price' => $path->formatted_price,
                'courses' => $path->courses->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'price' => $course->price,
                        'average_rating' => $course->average_rating,
                        'category' => $course->category ? [
                            'id' => $course->category->id,
                            'name' => $course->category->name,
                            'slug' => $course->category->slug,
                        ] : null,
                        'instructor' => $course->instructor ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                            'profile_photo_path' => $course->instructor->profile_photo_path,
                        ] : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'category' => $category,
            // 'learning_paths' => $formattedPaths,
        ]);
    }

    /**
     * Get category statistics
     */
    private function getCategoryStats($category)
    {
        $courses = $category->courses;
        $totalCourses = $courses->count();
        $totalStudents = $courses->sum('students_count');
        $totalReviews = $courses->sum('reviews_count');
        $averageRating = $courses->where('reviews_avg_rating', '>', 0)->avg('reviews_avg_rating');
        $freeCourses = $courses->where('price', 0)->count();
        $paidCourses = $courses->where('price', '>', 0)->count();
        $averagePrice = $courses->where('price', '>', 0)->avg('price');

        return [
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating ?? 0, 1),
            'free_courses' => $freeCourses,
            'paid_courses' => $paidCourses,
            'average_price' => round($averagePrice ?? 0, 2),
            'completion_rate' => $this->calculateCompletionRate($category),
            'popularity_score' => $this->calculatePopularityScore($category),
        ];
    }

    /**
     * Get related degrees for the category
     */
    private function getRelatedDegrees($category)
    {
        return Degree::with(['instructors:id,name,profile_photo_path'])
            ->withCount('courses')
            ->withAvg('courses', 'average_rating')
            ->withAvg('courses', 'price')
            ->whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($degree) {
                return [
                    'id' => $degree->id,
                    'name' => $degree->name,
                    'code' => $degree->code,
                    'name_ar' => $degree->name_ar,
                    'provider' => $degree->provider,
                    'level' => $degree->level,
                    'description' => $degree->description,
                    'duration_months' => $degree->duration_months,
                    'credit_hours' => $degree->credit_hours,
                    'is_active' => $degree->is_active,
                    'sort_order' => $degree->sort_order,
                    'created_at' => $degree->created_at->toISOString(),
                    'updated_at' => $degree->updated_at->toISOString(),
                    'stats' => [
                        'courses_count' => $degree->courses_count,
                        'average_rating' => round($degree->courses_avg_average_rating ?? 0, 1),
                        'average_price' => round($degree->courses_avg_price ?? 0, 2),
                    ],
                    'instructors' => $degree->instructors->map(function ($instructor) {
                        return [
                            'id' => $instructor->id,
                            'name' => $instructor->name,
                            'avatar' => $instructor->profile_photo_url,
                        ];
                    }),
                ];
            });
    }

    /**
     * Get related learning paths for the category
     */
    private function getRelatedLearningPaths($category)
    {
        return LearningPath::with(['courses' => function ($query) use ($category) {
                $query->where('category_id', $category->id);
            }])
            ->whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($learningPath) {
                return [
                    'id' => $learningPath->id,
                    'name' => $learningPath->name,
                    'slug' => $learningPath->slug,
                    'description' => $learningPath->description,
                    'image' => $learningPath->image,
                    'image_url' => $learningPath->image_url,
                    'is_active' => $learningPath->is_active,
                    'sort_order' => $learningPath->sort_order,
                    'created_at' => $learningPath->created_at->toISOString(),
                    'updated_at' => $learningPath->updated_at->toISOString(),
                    'stats' => [
                        'courses_count' => $learningPath->courses->count(),
                        'average_rating' => $learningPath->average_rating,
                        'total_price' => $learningPath->total_price,
                        'formatted_price' => $learningPath->formatted_price,
                    ],
                    'courses' => $learningPath->courses->map(function ($course) {
                        return [
                            'id' => 'course_' . $course->id,
                            'title' => $course->title,
                            'slug' => $course->slug,
                            'price' => $course->price,
                            'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : null,
                            'order' => $course->pivot->order ?? 0,
                        ];
                    }),
                ];
            });
    }

    /**
     * Get top instructors in this category
     */
    private function getTopInstructors($category)
    {
        return \App\Models\User::whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->withCount(['courses' => function ($query) use ($category) {
                $query->where('category_id', $category->id);
            }])
            ->withAvg(['courses' => function ($query) use ($category) {
                $query->where('category_id', $category->id);
            }], 'average_rating')
            ->orderBy('courses_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($instructor) {
                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    // 'email' => $instructor->email,
                    'avatar' => $instructor->profile_photo_url,
                    'bio' => $instructor->bio,
                    'stats' => [
                        'courses_count' => $instructor->courses_count,
                        'average_rating' => round($instructor->courses_avg_average_rating ?? 0, 1),
                    ],
                ];
            });
    }

    /**
     * Get recent courses in this category
     */
    private function getRecentCourses($category)
    {
        return $category->courses
            ->sortByDesc('created_at')
            ->take(5)
            ->map(function ($course) {
                return [
                    'id' => 'course_' . $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'price' => $course->price,
                    'is_free' => $course->price == 0,
                    'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : null,
                    'status' => $course->status,
                    'difficulty_level' => $course->difficulty_level,
                    'created_at' => $course->created_at->toISOString(),
                    'instructor' => $course->instructor ? [
                        'id' => $course->instructor->id,
                        'name' => $course->instructor->name,
                        'avatar' => $course->instructor->profile_photo_url,
                    ] : null,
                    'stats' => [
                        'students_count' => $course->students_count ?? 0,
                        'reviews_count' => $course->reviews_count ?? 0,
                        'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                    ],
                ];
            });
    }

    /**
     * Get featured courses in this category
     */
    private function getFeaturedCourses($category)
    {
        return $category->courses
            ->where('is_featured', true)
            ->sortByDesc('average_rating')
            ->take(5)
            ->map(function ($course) {
                return [
                    'id' => 'course_' . $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'price' => $course->price,
                    'is_free' => $course->price == 0,
                    'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : null,
                    'status' => $course->status,
                    'difficulty_level' => $course->difficulty_level,
                    'is_featured' => $course->is_featured,
                    'created_at' => $course->created_at->toISOString(),
                    'instructor' => $course->instructor ? [
                        'id' => $course->instructor->id,
                        'name' => $course->instructor->name,
                        'avatar' => $course->instructor->profile_photo_url,
                    ] : null,
                    'stats' => [
                        'students_count' => $course->students_count ?? 0,
                        'reviews_count' => $course->reviews_count ?? 0,
                        'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                    ],
                ];
            });
    }

    /**
     * Get category analytics
     */
    private function getCategoryAnalytics($category)
    {
        $courses = $category->courses;
        
        // Course status distribution
        $statusDistribution = $courses->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        // Difficulty level distribution
        $difficultyDistribution = $courses->groupBy('difficulty_level')->map(function ($group) {
            return $group->count();
        });

        // Price range distribution
        $priceRanges = [
            'free' => $courses->where('price', 0)->count(),
            'low' => $courses->whereBetween('price', [1, 100])->count(),
            'medium' => $courses->whereBetween('price', [101, 500])->count(),
            'high' => $courses->where('price', '>', 500)->count(),
        ];

        // Monthly course creation trend (last 12 months)
        $monthlyTrend = $courses->groupBy(function ($course) {
            return $course->created_at->format('Y-m');
        })->map(function ($group) {
            return $group->count();
        });

        return [
            'status_distribution' => $statusDistribution,
            'difficulty_distribution' => $difficultyDistribution,
            'price_range_distribution' => $priceRanges,
            'monthly_course_creation_trend' => $monthlyTrend,
            'growth_rate' => $this->calculateGrowthRate($category),
            'engagement_score' => $this->calculateEngagementScore($category),
        ];
    }

    /**
     * Calculate completion rate for courses in this category
     */
    private function calculateCompletionRate($category)
    {
        // This would require enrollment and completion data
        // For now, return a placeholder
        return 75.5; // Placeholder value
    }

    /**
     * Calculate popularity score for this category
     */
    private function calculatePopularityScore($category)
    {
        $courses = $category->courses;
        $totalStudents = $courses->sum('students_count');
        $totalReviews = $courses->sum('reviews_count');
        $averageRating = $courses->where('reviews_avg_rating', '>', 0)->avg('reviews_avg_rating');
        
        // Simple popularity calculation
        $score = ($totalStudents * 0.4) + ($totalReviews * 0.3) + (($averageRating ?? 0) * 10 * 0.3);
        return round($score, 1);
    }

    /**
     * Calculate growth rate for this category
     */
    private function calculateGrowthRate($category)
    {
        $courses = $category->courses;
        $currentMonth = $courses->where('created_at', '>=', now()->subMonth())->count();
        $previousMonth = $courses->whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
        
        if ($previousMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }
        
        return round((($currentMonth - $previousMonth) / $previousMonth) * 100, 1);
    }

    /**
     * Calculate engagement score for this category
     */
    private function calculateEngagementScore($category)
    {
        $courses = $category->courses;
        $totalStudents = $courses->sum('students_count');
        $totalReviews = $courses->sum('reviews_count');
        $totalCourses = $courses->count();
        
        if ($totalCourses == 0) {
            return 0;
        }
        
        $avgStudentsPerCourse = $totalStudents / $totalCourses;
        $avgReviewsPerCourse = $totalReviews / $totalCourses;
        
        // Engagement score based on student enrollment and review activity
        $score = ($avgStudentsPerCourse * 0.6) + ($avgReviewsPerCourse * 0.4);
        return round($score, 1);
    }
}
