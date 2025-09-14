<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DegreeResource;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Degree::with([
            'courses' => function ($query) {
                $query->with(['instructor:id,name,profile_photo_path', 'category:id,name,slug'])
                    ->withCount(['students', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->whereIn('status', ['draft', 'pending', 'approved'])
                    ->orderBy('created_at', 'desc');
            },
            'instructors:id,name,profile_photo_path'
        ])
        ->withCount('courses')
        ->withAvg('courses', 'price')
        ->orderBy('sort_order')
        ->where('is_active', true);

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $degrees = $query->get();

        // Enhance each degree with comprehensive data
        $degrees = $degrees->map(function ($degree) {
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
                'created_at' => $degree->created_at,
                'updated_at' => $degree->updated_at,
                
                // Related courses data
                'courses' => $degree->courses->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'code' => $course->code,
                        'description' => $course->description,
                        'price' => $course->price,
                        'is_free' => $course->is_free,
                        'status' => $course->status,
                        'level' => $course->level,
                        'duration' => $course->duration,
                        'language' => $course->language,
                        'estimated_duration' => $course->estimated_duration,
                        'thumbnail_url' => $course->thumbnail_url,
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
                        'students_count' => $course->students_count,
                        'reviews_count' => $course->reviews_count,
                        'reviews_avg_rating' => round($course->reviews_avg_rating ?? 0, 1),
                        'created_at' => $course->created_at,
                        'updated_at' => $course->updated_at,
                    ];
                }),
                
                // Statistics
                'stats' => [
                    'total_courses' => $degree->courses->count(),
                    'total_students' => $degree->courses->sum('students_count'),
                    'total_reviews' => $degree->courses->sum('reviews_count'),
                    'average_rating' => $degree->courses->avg('reviews_avg_rating') ? round($degree->courses->avg('reviews_avg_rating'), 1) : 0,
                    'average_price' => $degree->courses->avg('price') ? round($degree->courses->avg('price'), 2) : 0,
                    'free_courses' => $degree->courses->where('is_free', true)->count(),
                    'paid_courses' => $degree->courses->where('is_free', false)->count(),
                    'approved_courses' => $degree->courses->where('status', 'approved')->count(),
                    'pending_courses' => $degree->courses->where('status', 'pending')->count(),
                    'draft_courses' => $degree->courses->where('status', 'draft')->count(),
                ],
                
                // Instructors data
                'instructors' => $degree->instructors->map(function ($instructor) {
                    return [
                        'id' => $instructor->id,
                        'name' => $instructor->name,
                        'profile_photo_path' => $instructor->profile_photo_path,
                        'avatar' => $instructor->profile_photo_path ? asset('storage/' . $instructor->profile_photo_path) : null,
                    ];
                }),
                
                // Recent courses (last 5)
                'recent_courses' => $degree->courses->take(5)->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'price' => $course->price,
                        'is_free' => $course->is_free,
                        'thumbnail' => $course->thumbnail_url,
                        'status' => $course->status,
                        'difficulty_level' => $course->level,
                        'created_at' => $course->created_at,
                        'instructor' => $course->instructor ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                            'avatar' => $course->instructor->profile_photo_path ? asset('storage/' . $course->instructor->profile_photo_path) : null,
                        ] : null,
                        'stats' => [
                            'students_count' => $course->students_count,
                            'reviews_count' => $course->reviews_count,
                            'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                        ],
                    ];
                }),
                
                // Featured courses
                'featured_courses' => $degree->courses->where('is_featured', true)->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'price' => $course->price,
                        'is_free' => $course->is_free,
                        'thumbnail' => $course->thumbnail_url,
                        'status' => $course->status,
                        'difficulty_level' => $course->level,
                        'created_at' => $course->created_at,
                        'instructor' => $course->instructor ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                            'avatar' => $course->instructor->profile_photo_path ? asset('storage/' . $course->instructor->profile_photo_path) : null,
                        ] : null,
                        'stats' => [
                            'students_count' => $course->students_count,
                            'reviews_count' => $course->reviews_count,
                            'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                        ],
                    ];
                }),
                
                // Analytics
                'analytics' => $this->getDegreeAnalytics($degree),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $degrees,
            'message' => 'Degrees retrieved successfully',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'level' => 'required|integer|unique:degrees,level',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $degree = Degree::create($validator->validated());

        return new DegreeResource($degree);
    }

    /**
     * Display the specified resource.
     */
    public function show(Degree $degree)
    {
        // Load degree with all related data
        $degree->load([
            'courses' => function ($query) {
                $query->with(['instructor:id,name,profile_photo_path', 'category:id,name,slug'])
                    ->withCount(['students', 'reviews'])
                    ->withAvg('reviews', 'rating')
                    ->whereIn('status', ['draft', 'pending', 'approved'])
                    ->orderBy('created_at', 'desc');
            },
            'instructors:id,name,profile_photo_path'
        ]);

        // Get degree statistics
        $stats = $this->getDegreeStats($degree);

        // Get related categories
        $categories = $this->getRelatedCategories($degree);

        // Get top instructors
        $topInstructors = $this->getTopInstructors($degree);

        // Get recent courses
        $recentCourses = $this->getRecentCourses($degree);

        // Get featured courses
        $featuredCourses = $this->getFeaturedCourses($degree);

        // Get course analytics
        $analytics = $this->getDegreeAnalytics($degree);

        return response()->json([
            'degree' => [
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
                'created_at' => $degree->created_at,
                'updated_at' => $degree->updated_at,
            ],
            'stats' => $stats,
            'categories' => $categories,
            'top_instructors' => $topInstructors,
            'recent_courses' => $recentCourses,
            'featured_courses' => $featuredCourses,
            'analytics' => $analytics,
            'all_courses' => $degree->courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'code' => $course->code,
                    'description' => $course->description,
                    'price' => $course->price,
                    'is_free' => $course->is_free,
                    'thumbnail' => $course->thumbnail_url,
                    'status' => $course->status,
                    'difficulty_level' => $course->level,
                    'language' => $course->language,
                    'estimated_duration' => $course->estimated_duration,
                    'created_at' => $course->created_at,
                    'updated_at' => $course->updated_at,
                    'instructor' => $course->instructor ? [
                        'id' => $course->instructor->id,
                        'name' => $course->instructor->name,
                        'avatar' => $course->instructor->profile_photo_path ? asset('storage/' . $course->instructor->profile_photo_path) : null,
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name' => $course->category->name,
                        'slug' => $course->category->slug,
                        'code' => $course->category->code,
                    ] : null,
                    'stats' => [
                        'students_count' => $course->students_count,
                        'reviews_count' => $course->reviews_count,
                        'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                    ],
                ];
            }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Degree $degree)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'name_ar' => 'sometimes|string|max:255',
            'provider' => 'nullable|string|max:255',
            'level' => 'sometimes|integer|unique:degrees,level,'.$degree->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $degree->update($validator->validated());

        return new DegreeResource($degree);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Degree $degree)
    {
        // Check if the degree is being used by any courses
        if ($degree->courses()->exists()) {
            return response()->json([
                'message' => 'Cannot delete degree as it is being used by one or more courses.',
            ], 422);
        }

        $degree->delete();

        return response()->json(['message' => 'Degree deleted successfully']);
    }

    /**
     * Get degree statistics
     */
    private function getDegreeStats($degree)
    {
        $courses = $degree->courses;
        
        return [
            'total_courses' => $courses->count(),
            'total_students' => $courses->sum('students_count'),
            'total_reviews' => $courses->sum('reviews_count'),
            'average_rating' => $courses->avg('reviews_avg_rating') ? round($courses->avg('reviews_avg_rating'), 1) : 0,
            'average_price' => $courses->avg('price') ? round($courses->avg('price'), 2) : 0,
            'free_courses' => $courses->where('is_free', true)->count(),
            'paid_courses' => $courses->where('is_free', false)->count(),
            'approved_courses' => $courses->where('status', 'approved')->count(),
            'pending_courses' => $courses->where('status', 'pending')->count(),
            'draft_courses' => $courses->where('status', 'draft')->count(),
            'completion_rate' => $this->calculateCompletionRate($degree),
            'popularity_score' => $this->calculatePopularityScore($degree),
        ];
    }

    /**
     * Get related categories
     */
    private function getRelatedCategories($degree)
    {
        return $degree->courses->pluck('category')->filter()->unique('id')->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'code' => $category->code,
            ];
        })->values();
    }

    /**
     * Get top instructors for this degree
     */
    private function getTopInstructors($degree)
    {
        return $degree->instructors->map(function ($instructor) use ($degree) {
            $coursesCount = $instructor->courses()->where('degree_id', $degree->id)->count();
            
            // Get average rating from reviews table
            $avgRating = $instructor->courses()
                ->where('degree_id', $degree->id)
                ->join('reviews', 'courses.id', '=', 'reviews.course_id')
                ->avg('reviews.rating');
            
            return [
                'id' => $instructor->id,
                'name' => $instructor->name,
                'avatar' => $instructor->profile_photo_path ? asset('storage/' . $instructor->profile_photo_path) : null,
                'bio' => $instructor->bio ?? null,
                'stats' => [
                    'courses_count' => $coursesCount,
                    'average_rating' => round($avgRating ?? 0, 1),
                ],
            ];
        })->sortByDesc('stats.courses_count')->take(5)->values();
    }

    /**
     * Get recent courses for this degree
     */
    private function getRecentCourses($degree)
    {
        return $degree->courses->take(5)->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'price' => $course->price,
                'is_free' => $course->is_free,
                'thumbnail' => $course->thumbnail_url,
                'status' => $course->status,
                'difficulty_level' => $course->level,
                'created_at' => $course->created_at,
                'instructor' => $course->instructor ? [
                    'id' => $course->instructor->id,
                    'name' => $course->instructor->name,
                    'avatar' => $course->instructor->profile_photo_path ? asset('storage/' . $course->instructor->profile_photo_path) : null,
                ] : null,
                'stats' => [
                    'students_count' => $course->students_count,
                    'reviews_count' => $course->reviews_count,
                    'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                ],
            ];
        });
    }

    /**
     * Get featured courses for this degree
     */
    private function getFeaturedCourses($degree)
    {
        return $degree->courses->where('is_featured', true)->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'price' => $course->price,
                'is_free' => $course->is_free,
                'thumbnail' => $course->thumbnail_url,
                'status' => $course->status,
                'difficulty_level' => $course->level,
                'created_at' => $course->created_at,
                'instructor' => $course->instructor ? [
                    'id' => $course->instructor->id,
                    'name' => $course->instructor->name,
                    'avatar' => $course->instructor->profile_photo_path ? asset('storage/' . $course->instructor->profile_photo_path) : null,
                ] : null,
                'stats' => [
                    'students_count' => $course->students_count,
                    'reviews_count' => $course->reviews_count,
                    'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                ],
            ];
        });
    }

    /**
     * Get degree analytics
     */
    private function getDegreeAnalytics($degree)
    {
        $courses = $degree->courses;
        
        return [
            'status_distribution' => $courses->groupBy('status')->map->count(),
            'difficulty_distribution' => $courses->groupBy('level')->map->count(),
            'price_range_distribution' => [
                'free' => $courses->where('is_free', true)->count(),
                'low' => $courses->where('is_free', false)->where('price', '<', 100)->count(),
                'medium' => $courses->where('is_free', false)->whereBetween('price', [100, 500])->count(),
                'high' => $courses->where('is_free', false)->where('price', '>', 500)->count(),
            ],
            'monthly_course_creation_trend' => $courses->groupBy(function ($course) {
                return $course->created_at->format('Y-m');
            })->map->count(),
            'growth_rate' => $this->calculateGrowthRate($degree),
            'engagement_score' => $this->calculateEngagementScore($degree),
        ];
    }

    /**
     * Calculate completion rate for degree courses
     */
    private function calculateCompletionRate($degree)
    {
        $totalEnrollments = $degree->courses->sum('students_count');
        if ($totalEnrollments === 0) return 0;
        
        // This is a simplified calculation - in a real scenario, you'd check actual completion data
        $completedEnrollments = $degree->courses->sum(function ($course) {
            return $course->students_count * 0.75; // Assume 75% completion rate
        });
        
        return round(($completedEnrollments / $totalEnrollments) * 100, 1);
    }

    /**
     * Calculate popularity score for degree
     */
    private function calculatePopularityScore($degree)
    {
        $courses = $degree->courses;
        $totalStudents = $courses->sum('students_count');
        $totalReviews = $courses->sum('reviews_count');
        $avgRating = $courses->avg('reviews_avg_rating') ?? 0;
        
        // Simple popularity calculation based on students, reviews, and ratings
        $score = ($totalStudents * 0.4) + ($totalReviews * 0.3) + ($avgRating * 10 * 0.3);
        
        return round(min($score / 100, 1), 2); // Normalize to 0-1
    }

    /**
     * Calculate growth rate for degree
     */
    private function calculateGrowthRate($degree)
    {
        $courses = $degree->courses;
        $thisMonth = $courses->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonth = $courses->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        
        if ($lastMonth === 0) return $thisMonth > 0 ? 100 : 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Calculate engagement score for degree
     */
    private function calculateEngagementScore($degree)
    {
        $courses = $degree->courses;
        $totalStudents = $courses->sum('students_count');
        $totalReviews = $courses->sum('reviews_count');
        
        if ($totalStudents === 0) return 0;
        
        // Engagement based on review rate
        $reviewRate = $totalReviews / $totalStudents;
        
        return round(min($reviewRate, 1), 2); // Normalize to 0-1
    }
}
