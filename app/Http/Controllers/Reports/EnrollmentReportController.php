<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnrollmentReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date filters
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Convert to Carbon instances if they're strings
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Total enrollments
        $totalEnrollments = DB::table('course_user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Enrollments by month for chart
        $enrollmentsByMonth = DB::table('course_user')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as enrollment_count')
            )
            ->whereBetween('created_at', [$startDate->copy()->subMonths(11), $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top enrolled courses
        $topCourses = Course::withCount(['students' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('course_user.created_at', [$startDate, $endDate]);
            }])
            ->orderBy('students_count', 'desc')
            ->limit(10)
            ->get();

        // Enrollments by category
        $enrollmentsByCategory = Course::select(
                'categories.name as category_name',
                DB::raw('COUNT(course_user.user_id) as enrollment_count')
            )
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->leftJoin('course_user', 'courses.id', '=', 'course_user.course_id')
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('enrollment_count', 'desc')
            ->get();

        // Recent enrollments
        $recentEnrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->select(
                'course_user.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.title as course_title',
                'categories.name as category_name'
            )
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->orderBy('course_user.created_at', 'desc')
            ->paginate(20);

        // Growth metrics
        $previousPeriodStart = $startDate->copy()->subDays($startDate->diffInDays($endDate) + 1);
        $previousPeriodEnd = $startDate->copy()->subDay();
        
        $previousEnrollments = DB::table('course_user')
            ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->count();

        $growthRate = $previousEnrollments > 0 
            ? (($totalEnrollments - $previousEnrollments) / $previousEnrollments) * 100 
            : 0;

        // Chart data
        $chartData = [
            'labels' => $enrollmentsByMonth->pluck('month')->map(function($month) {
                return Carbon::parse($month)->format('M Y');
            })->toArray(),
            'enrollments' => $enrollmentsByMonth->pluck('enrollment_count')->toArray()
        ];

        return view('reports.enrollments.index', compact(
            'totalEnrollments',
            'topCourses',
            'enrollmentsByCategory',
            'recentEnrollments',
            'growthRate',
            'chartData',
            'startDate',
            'endDate'
        ));
    }
}