<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use App\Models\Certificate;
use App\Models\InstructorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get overview statistics
        $totalUsers = User::count();
        $totalCourses = Course::count();
        $totalReviews = Review::count();
        $totalCertificates = Certificate::count();

        // User growth over last 12 months
        $userGrowth = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Course enrollment statistics
        $courseStats = Course::withCount(['students' => function($query) {
            // Assuming you have a pivot table for course enrollments
        }])
        ->orderBy('students_count', 'desc')
        ->take(10)
        ->get();

        // Review ratings distribution
        $ratingDistribution = Review::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentCourses = Course::with('instructor')->latest()->take(5)->get();
        $recentReviews = Review::with(['user', 'course'])->latest()->take(5)->get();

        return view('analytics.index', compact(
            'totalUsers', 'totalCourses', 'totalReviews', 'totalCertificates',
            'userGrowth', 'courseStats', 'ratingDistribution',
            'recentUsers', 'recentCourses', 'recentReviews'
        ));
    }

    public function users()
    {
        // User analytics
        $usersByRole = User::selectRaw('roles.name as role, COUNT(*) as count')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('roles.name')
            ->get();

        $userRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('analytics.users', compact('usersByRole', 'userRegistrations'));
    }

    public function courses()
    {
        // Course analytics
        // Use category relationship or difficulty_level as fallback
        $coursesByCategory = Course::selectRaw('
            CASE 
                WHEN categories.name IS NOT NULL THEN categories.name
                WHEN courses.difficulty_level IS NOT NULL THEN courses.difficulty_level
                ELSE "Uncategorized"
            END as category, 
            COUNT(*) as count')
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->groupByRaw('
                CASE 
                    WHEN categories.name IS NOT NULL THEN categories.name
                    WHEN courses.difficulty_level IS NOT NULL THEN courses.difficulty_level
                    ELSE "Uncategorized"
                END')
            ->get();

        $coursesByStatus = Course::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        try {
            $topInstructors = User::role('instructor')
                ->withCount('instructorCourses')
                ->orderBy('instructor_courses_count', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Fallback for role issues
            $topInstructors = User::whereHas('roles', function($q) {
                $q->where('name', 'instructor');
            })
            ->withCount('instructorCourses')
            ->orderBy('instructor_courses_count', 'desc')
            ->take(10)
            ->get();
        }

        return view('analytics.courses', compact('coursesByCategory', 'coursesByStatus', 'topInstructors'));
    }

    public function reviews()
    {
        // Review analytics
        $averageRating = Review::avg('rating');
        $totalReviews = Review::count();

        $reviewsByMonth = Review::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, AVG(rating) as avg_rating')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topRatedCourses = Course::selectRaw('courses.id, courses.title, courses.difficulty_level as category, courses.instructor_id, AVG(reviews.rating) as avg_rating, COUNT(reviews.id) as review_count')
            ->leftJoin('reviews', 'courses.id', '=', 'reviews.course_id')
            ->with('instructor')
            ->groupBy('courses.id', 'courses.title', 'courses.difficulty_level', 'courses.instructor_id')
            ->having('review_count', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        return view('analytics.reviews', compact('averageRating', 'totalReviews', 'reviewsByMonth', 'topRatedCourses'));
    }
}
