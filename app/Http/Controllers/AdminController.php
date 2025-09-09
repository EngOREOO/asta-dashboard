<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\InstructorApplication;
use App\Models\Review;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct()
    {
        // Remove middleware from constructor since it's already applied in routes
    }

    /**
     * Admin Dashboard Index
     */
    public function index()
    {
        // Check if user has admin role
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        try {
            $stats = $this->getDashboardStats();
            $recentActivities = $this->getRecentActivities();
            $pendingApprovals = $this->getPendingApprovals();
            $topCourses = $this->getTopCourses();
            $userGrowth = $this->getUserGrowthData();
            $courseStats = $this->getCourseStats();

            // Debug information
            \Log::info('Admin Dashboard Data:', [
                'stats' => $stats,
                'recentActivities_count' => count($recentActivities),
                'pendingApprovals' => $pendingApprovals,
                'topCourses_count' => count($topCourses),
            ]);

            return Inertia::render('Admin/Dashboard', [
                'stats' => $stats,
                'recentActivities' => $recentActivities,
                'pendingApprovals' => $pendingApprovals,
                'topCourses' => $topCourses,
                'userGrowth' => $userGrowth,
                'courseStats' => $courseStats,
                'revenueData' => $this->getRevenueData(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: '.$e->getMessage());

            // Return with empty data if there's an error
            return Inertia::render('Admin/Dashboard', [
                'stats' => [
                    'totalUsers' => 0,
                    'totalStudents' => 0,
                    'totalInstructors' => 0,
                    'totalCourses' => 0,
                    'publishedCourses' => 0,
                    'pendingCourses' => 0,
                    'totalAssessments' => 0,
                    'totalCertificates' => 0,
                    'pendingApplications' => 0,
                    'totalRevenue' => 0,
                    'averageRating' => 0,
                ],
                'recentActivities' => [],
                'pendingApprovals' => ['courses' => [], 'applications' => []],
                'topCourses' => [],
                'userGrowth' => ['last30Days' => [], 'totalGrowth' => 0],
                'courseStats' => ['statuses' => [], 'byCategory' => []],
            ]);
        }
    }

    /**
     * Get comprehensive dashboard statistics
     */
    private function getDashboardStats()
    {
        $totalUsers = User::count();
        $totalStudents = User::role('student')->count();
        $totalInstructors = User::role('instructor')->count();
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'approved')->count();
        $pendingCourses = Course::where('status', 'pending')->count();
        $totalAssessments = Assessment::count();
        $totalCertificates = Certificate::count();
        $pendingApplications = InstructorApplication::where('status', 'pending')->count();
        $totalTestimonials = Testimonial::count();
        $approvedTestimonials = Testimonial::approved()->count();
        $pendingTestimonials = Testimonial::where('is_approved', false)->count();
        $featuredTestimonials = Testimonial::featured()->count();

        // Calculate revenue (if courses have prices)
        $totalRevenue = Course::where('status', 'approved')->sum('price');

        // Calculate average course rating
        $averageRating = Course::where('status', 'approved')
            ->whereNotNull('average_rating')
            ->avg('average_rating') ?? 0;

        // Calculate growth percentages (mock data for now)
        $userGrowth = 13.6;
        $courseGrowth = 15.4;
        $revenueGrowth = 15.4;

        return [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalInstructors' => $totalInstructors,
            'totalCourses' => $totalCourses,
            'publishedCourses' => $publishedCourses,
            'pendingCourses' => $pendingCourses,
            'totalAssessments' => $totalAssessments,
            'totalCertificates' => $totalCertificates,
            'pendingApplications' => $pendingApplications,
            'totalTestimonials' => $totalTestimonials,
            'approvedTestimonials' => $approvedTestimonials,
            'pendingTestimonials' => $pendingTestimonials,
            'featuredTestimonials' => $featuredTestimonials,
            'totalRevenue' => $totalRevenue,
            'averageRating' => round($averageRating, 1),
            'userGrowth' => $userGrowth,
            'courseGrowth' => $courseGrowth,
            'revenueGrowth' => $revenueGrowth,
        ];
    }

    /**
     * Get recent activities for admin dashboard
     */
    private function getRecentActivities()
    {
        $activities = collect();

        try {
            // Recent course creations
            $recentCourses = Course::with('instructor')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($course) {
                    return [
                        'type' => 'course_created',
                        'title' => "New course: {$course->title}",
                        'description' => 'Created by '.($course->instructor->name ?? 'Unknown'),
                        'timestamp' => $course->created_at,
                        'status' => $course->status,
                        'id' => $course->id,
                    ];
                });

            $activities = $activities->merge($recentCourses);
        } catch (\Exception $e) {
            // Handle error gracefully
        }

        try {
            // Recent user registrations
            $recentUsers = User::with('roles')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($user) {
                    return [
                        'type' => 'user_registered',
                        'title' => "New user: {$user->name}",
                        'description' => "Email: {$user->email}",
                        'timestamp' => $user->created_at,
                        'role' => $user->roles->first()?->name ?? 'student',
                        'id' => $user->id,
                    ];
                });

            $activities = $activities->merge($recentUsers);
        } catch (\Exception $e) {
            // Handle error gracefully
        }

        try {
            // Recent instructor applications
            $recentApplications = InstructorApplication::with('user')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($application) {
                    return [
                        'type' => 'instructor_application',
                        'title' => 'Instructor application: '.($application->user->name ?? 'Unknown'),
                        'description' => "Field: {$application->field}",
                        'timestamp' => $application->created_at,
                        'status' => $application->status,
                        'id' => $application->id,
                    ];
                });

            $activities = $activities->merge($recentApplications);
        } catch (\Exception $e) {
            // Handle error gracefully
        }

        try {
            // Recent reviews
            $recentReviews = Review::with(['user', 'course'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($review) {
                    return [
                        'type' => 'review_submitted',
                        'title' => 'Review for: '.($review->course->title ?? 'Unknown Course'),
                        'description' => 'By '.($review->user->name ?? 'Unknown')." - Rating: {$review->rating}/5",
                        'timestamp' => $review->created_at,
                        'rating' => $review->rating,
                        'id' => $review->id,
                    ];
                });

            $activities = $activities->merge($recentReviews);
        } catch (\Exception $e) {
            // Handle error gracefully
        }

        try {
            // Recent testimonials
            $recentTestimonials = Testimonial::latest()
                ->take(5)
                ->get()
                ->map(function ($testimonial) {
                    return [
                        'type' => 'testimonial_submitted',
                        'title' => 'Testimonial from: '.$testimonial->user_name,
                        'description' => "Rating: {$testimonial->rating}/5 - ".substr($testimonial->comment, 0, 50).'...',
                        'timestamp' => $testimonial->created_at,
                        'rating' => $testimonial->rating,
                        'is_approved' => $testimonial->is_approved,
                        'id' => $testimonial->id,
                    ];
                });

            $activities = $activities->merge($recentTestimonials);
        } catch (\Exception $e) {
            // Handle error gracefully
        }

        // Sort and limit activities
        $activities = $activities->sortByDesc('timestamp')->take(10)->values();

        return $activities;
    }

    /**
     * Get pending approvals
     */
    private function getPendingApprovals()
    {
        try {
            $pendingCourses = Course::with('instructor')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $pendingCourses = collect();
        }

        try {
            $pendingApplications = InstructorApplication::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $pendingApplications = collect();
        }

        try {
            $pendingTestimonials = Testimonial::where('is_approved', false)
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $pendingTestimonials = collect();
        }

        return [
            'courses' => $pendingCourses,
            'applications' => $pendingApplications,
            'testimonials' => $pendingTestimonials,
        ];
    }

    /**
     * Get top performing courses
     */
    private function getTopCourses()
    {
        try {
            return Course::with(['instructor', 'category'])
                ->where('status', 'approved')
                ->withCount('students')
                ->orderBy('students_count', 'desc')
                ->orderBy('average_rating', 'desc')
                ->take(10)
                ->get()
                ->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'instructor' => $course->instructor->name ?? 'Unknown',
                        'category' => $course->category->name ?? 'Uncategorized',
                        'students_count' => $course->students_count,
                        'average_rating' => $course->average_rating ?? 0,
                        'total_ratings' => $course->total_ratings ?? 0,
                        'price' => $course->price,
                        'status' => $course->status,
                    ];
                });
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get user growth data for charts
     */
    private function getUserGrowthData()
    {
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $usersCount = User::whereDate('created_at', $date)->count();

            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'users' => $usersCount,
            ]);
        }

        return [
            'last30Days' => $last30Days,
            'totalGrowth' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];
    }

    /**
     * Get course statistics
     */
    private function getCourseStats()
    {
        $courseStatuses = Course::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $coursesByCategory = Course::with('category')
            ->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Uncategorized',
                    'count' => $item->count,
                ];
            });

        return [
            'statuses' => $courseStatuses,
            'byCategory' => $coursesByCategory,
        ];
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $query = User::with('roles');

        // Filter by role
        if ($request->has('role')) {
            $query->role($request->role);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'filters' => $request->only(['role', 'search']),
        ]);
    }

    public function students()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        // Get students with basic info
        $students = User::role('student')
            ->withCount(['courses as enrolled_courses_count'])
            ->paginate(10);

        // Get testimonials statistics
        $testimonialsStats = [
            'total' => Testimonial::count(),
            'approved' => Testimonial::approved()->count(),
            'pending' => Testimonial::where('is_approved', false)->count(),
            'featured' => Testimonial::featured()->count(),
            'average_rating' => round(Testimonial::approved()->avg('rating'), 1),
        ];

        // Get recent testimonials for the ratings section
        $recentTestimonials = Testimonial::approved()
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Admin/Students', [
            'students' => $students,
            'testimonialsStats' => $testimonialsStats,
            'recentTestimonials' => $recentTestimonials,
        ]);
    }

    public function instructors()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Instructors', [
            'instructors' => User::role('instructor')->paginate(10),
        ]);
    }

    public function categories()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Categories', [
            'categories' => Category::paginate(10),
        ]);
    }

    public function materials()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Materials', [
            'materials' => CourseMaterial::with('course')->paginate(10),
        ]);
    }

    public function assessments()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Assessments', [
            'assessments' => Assessment::with('course')->paginate(10),
        ]);
    }

    public function certificates()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Certificates', [
            'certificates' => Certificate::with(['user', 'course'])->paginate(10),
        ]);
    }

    public function analyticsRevenue()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Analytics/Revenue', [
            'revenueData' => $this->getRevenueData(),
        ]);
    }

    public function analyticsUsers()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Analytics/Users', [
            'userData' => $this->getUserGrowthData(),
        ]);
    }

    public function analyticsCourses()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Analytics/Courses', [
            'courseData' => $this->getCourseStats(),
        ]);
    }

    public function settingsGeneral()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Settings/General');
    }

    public function settingsSecurity()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Settings/Security');
    }

    public function settingsNotifications()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Settings/Notifications');
    }

    public function profile()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return Inertia::render('Admin/Profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Course Management
     */
    public function courses(Request $request)
    {
        $query = Course::with(['instructor', 'category', 'degree']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('instructor', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $courses = $query->latest()->paginate(20);

        return Inertia::render('Admin/Courses', [
            'courses' => $courses,
            'filters' => $request->only(['status', 'category_id', 'search']),
        ]);
    }

    /**
     * Instructor Applications Management
     */
    public function applications(Request $request)
    {
        $query = InstructorApplication::with(['user', 'reviewer']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20);

        return Inertia::render('Admin/Applications', [
            'applications' => $applications,
            'filters' => $request->only(['status']),
        ]);
    }

    /**
     * Testimonials Management
     */
    public function testimonials(Request $request)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $query = Testimonial::query();

        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'approved':
                    $query->where('is_approved', true);
                    break;
                case 'pending':
                    $query->where('is_approved', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $testimonials = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get testimonials statistics
        $testimonialsStats = [
            'total' => Testimonial::count(),
            'approved' => Testimonial::where('is_approved', true)->count(),
            'pending' => Testimonial::where('is_approved', false)->count(),
            'featured' => Testimonial::where('is_featured', true)->count(),
            'average_rating' => round(Testimonial::where('is_approved', true)->avg('rating'), 1),
        ];

        return view('testimonials.index', [
            'testimonials' => $testimonials,
            'testimonialsStats' => $testimonialsStats,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    /**
     * Show create testimonial form
     */
    public function createTestimonial()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return view('testimonials.create');
    }

    /**
     * Store new testimonial
     */
    public function storeTestimonial(Request $request)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials')
            ->with('success', 'تم إضافة الرأي بنجاح.');
    }

    /**
     * Show edit testimonial form
     */
    public function editTestimonial(Testimonial $testimonial)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * System Settings
     */
    public function settings()
    {
        return Inertia::render('Admin/Settings', [
            'systemStats' => [
                'totalStorage' => $this->getStorageUsage(),
                'databaseSize' => $this->getDatabaseSize(),
                'lastBackup' => $this->getLastBackupDate(),
            ],
        ]);
    }

    private function getRevenueData()
    {
        // Mock revenue data
        return [
            'total' => 125000,
            'monthly' => [15000, 18000, 22000, 19000, 25000, 28000, 32000, 35000, 38000, 42000, 45000, 48000],
            'growth' => 15.4,
        ];
    }

    /**
     * Get storage usage
     */
    private function getStorageUsage()
    {
        $storagePath = storage_path('app/public');
        $totalSize = 0;

        if (is_dir($storagePath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $totalSize += $file->getSize();
            }
        }

        return $this->formatBytes($totalSize);
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        $databaseName = config('database.connections.mysql.database');
        $result = DB::select('SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS size FROM information_schema.tables WHERE table_schema = ?', [$databaseName]);

        return $result[0]->size ?? 0 .' MB';
    }

    /**
     * Get last backup date
     */
    private function getLastBackupDate()
    {
        // This would typically check your backup system
        // For now, return a placeholder
        return 'Not configured';
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}
