<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseComment;
use App\Models\CourseMaterial;
use App\Models\CourseNote;
use App\Models\Degree;
use App\Models\InstructorApplication;
use App\Models\Partner;
use App\Models\Quiz;
use App\Models\Review;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get dashboard statistics
        $totalUsers = User::count();
        $totalCourses = Course::count();

        try {
            $totalInstructors = User::role('instructor')->count();
        } catch (\Exception $e) {
            $totalInstructors = User::whereHas('roles', function ($q) {
                $q->where('name', 'instructor');
            })->count();
        }

        $pendingCourses = Course::where('status', 'pending')->count();
        $pendingApplications = 0;
        $pendingReviews = 0;
        $totalCertificates = 0;
        $totalPartners = 0;
        $totalMaterials = 0;
        $totalAssessments = 0;
        $totalEnrollments = 0;
        $totalWishlists = 0;
        $totalComments = 0;
        $totalNotes = 0;
        $totalDegrees = 0;
        $totalQuizzes = 0;
        $totalReviews = 0;
        $totalTestimonials = 0;
        $approvedTestimonials = 0;
        $pendingTestimonials = 0;
        $featuredTestimonials = 0;
        $averageRating = 0;

        try {
            $pendingApplications = InstructorApplication::where('status', 'pending')->count();
            $pendingReviews = Review::whereNull('is_approved')->count();
            $totalReviews = Review::count();
            $totalCertificates = Certificate::count();
            $totalPartners = Partner::where('is_active', true)->count();
            $totalMaterials = CourseMaterial::count();
            $totalAssessments = Assessment::count();
            $totalEnrollments = DB::table('course_user')->count();
            $totalWishlists = DB::table('user_wishlist')->count();
            $totalComments = CourseComment::count();
            $totalNotes = CourseNote::count();
            $totalDegrees = Degree::count();
            $totalQuizzes = Quiz::count();

            // Testimonials statistics
            $totalTestimonials = Testimonial::count();
            $approvedTestimonials = Testimonial::where('is_approved', true)->count();
            $pendingTestimonials = Testimonial::where('is_approved', false)->count();
            $featuredTestimonials = Testimonial::where('is_featured', true)->count();
            $averageRating = round(Testimonial::where('is_approved', true)->avg('rating'), 1);
        } catch (\Exception $e) {
            // Tables might not exist
        }

        // Get recent courses
        $recentCourses = Course::with(['instructor'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent testimonials
        $recentTestimonials = collect();
        try {
            $recentTestimonials = Testimonial::where('is_approved', true)
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // Testimonials table might not exist
        }

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalInstructors' => $totalInstructors,
            'pendingCourses' => $pendingCourses,
            'pendingApplications' => $pendingApplications,
            'pendingReviews' => $pendingReviews,
            'totalCertificates' => $totalCertificates,
            'totalPartners' => $totalPartners,
            'totalMaterials' => $totalMaterials,
            'totalAssessments' => $totalAssessments,
            'totalEnrollments' => $totalEnrollments,
            'totalWishlists' => $totalWishlists,
            'totalComments' => $totalComments,
            'totalNotes' => $totalNotes,
            'totalDegrees' => $totalDegrees,
            'totalQuizzes' => $totalQuizzes,
            'totalReviews' => $totalReviews,
            'totalTestimonials' => $totalTestimonials,
            'approvedTestimonials' => $approvedTestimonials,
            'pendingTestimonials' => $pendingTestimonials,
            'featuredTestimonials' => $featuredTestimonials,
            'averageRating' => $averageRating,
            'recentCourses' => $recentCourses,
            'recentTestimonials' => $recentTestimonials,
            'recentActivities' => $recentActivities,
        ]);
    }

    private function getRecentActivities()
    {
        $activities = collect();

        try {
            // Get recent course activities
            $recentCourses = Course::latest()->take(3)->get();
            foreach ($recentCourses as $course) {
                $activities->push((object) [
                    'type' => 'course',
                    'description' => "تم إضافة دورة جديدة: {$course->title}",
                    'created_at' => $course->created_at,
                    'icon' => 'book',
                ]);
            }

            // Get recent user registrations
            $recentUsers = User::latest()->take(3)->get();
            foreach ($recentUsers as $user) {
                $activities->push((object) [
                    'type' => 'user',
                    'description' => "انضم مستخدم جديد: {$user->name}",
                    'created_at' => $user->created_at,
                    'icon' => 'user',
                ]);
            }

            // Get recent reviews
            try {
                $recentReviews = Review::with(['user', 'course'])->latest()->take(2)->get();
                foreach ($recentReviews as $review) {
                    if ($review->user && $review->user->name) {
                        $activities->push((object) [
                            'type' => 'review',
                            'description' => "تقييم جديد من {$review->user->name}",
                            'created_at' => $review->created_at,
                            'icon' => 'star',
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Reviews table might not exist
            }

            // Get recent testimonials
            try {
                $recentTestimonials = Testimonial::latest()->take(2)->get();
                foreach ($recentTestimonials as $testimonial) {
                    $activities->push((object) [
                        'type' => 'testimonial',
                        'description' => "شهادة جديدة من {$testimonial->user_name}",
                        'created_at' => $testimonial->created_at,
                        'icon' => 'message-circle',
                    ]);
                }
            } catch (\Exception $e) {
                // Testimonials table might not exist
            }

            // Sort by creation date and take only 5
            $activities = $activities->sortByDesc('created_at')->take(5);

        } catch (\Exception $e) {
            // Return empty collection if there's an error
        }

        return $activities;
    }

    private function getInstructorActivities($user)
    {
        // Implement instructor-specific activity logging and retrieval
        return [];
    }
}
