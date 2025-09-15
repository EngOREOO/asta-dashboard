<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        // Simple test data first
        $totalStudents = 0;
        $activeStudents = 0;
        $completedStudents = 0;
        $attendanceRate = 0;
        $completionRate = 0;
        $attendanceByMonth = collect();
        $attendanceByCourse = collect();
        $attendanceByInstructor = collect();
        $recentActivity = collect();
        $enrollments = collect();
        $courses = collect();
        $instructors = collect();
        $dateFrom = now()->subDays(30)->format('Y-m-d');
        $dateTo = now()->format('Y-m-d');
        $courseId = null;
        $status = 'all';
        $instructorId = null;
        $error = null;

        try {
            // Get filter parameters
            $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', now()->format('Y-m-d'));
            $courseId = $request->get('course_id');
            $status = $request->get('status', 'all');
            $instructorId = $request->get('instructor_id');

            // Get total students
            $totalStudents = User::role('student')->count();

            // Get active students (with progress)
            $activeStudents = DB::table('course_user')
                ->whereNotNull('progress')
                ->where('progress', '>', 0)
                ->distinct('user_id')
                ->count();

            // Get completed students
            $completedStudents = DB::table('course_user')
                ->whereNotNull('completed_at')
                ->distinct('user_id')
                ->count();

            // Get attendance rate
            $attendanceRate = $totalStudents > 0 ? round(($activeStudents / $totalStudents) * 100, 2) : 0;

            // Get completion rate
            $completionRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100, 2) : 0;

            // Get attendance by month for chart
            $attendanceByMonth = DB::table('course_user')
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', [now()->subMonths(12), now()])
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Get attendance by course
            $attendanceByCourse = DB::table('course_user')
                ->join('courses', 'course_user.course_id', '=', 'courses.id')
                ->select(
                    'courses.id',
                    'courses.title',
                    DB::raw('COUNT(*) as total_enrollments'),
                    DB::raw('COUNT(CASE WHEN progress > 0 THEN 1 END) as active_students'),
                    DB::raw('COUNT(CASE WHEN completed_at IS NOT NULL THEN 1 END) as completed_students'),
                    DB::raw('ROUND(AVG(progress), 2) as avg_progress')
                )
                ->groupBy('courses.id', 'courses.title')
                ->orderBy('total_enrollments', 'desc')
                ->limit(10)
                ->get();

            // Get attendance by instructor
            $attendanceByInstructor = DB::table('course_user')
                ->join('courses', 'course_user.course_id', '=', 'courses.id')
                ->join('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
                ->select(
                    'instructors.id',
                    'instructors.name as instructor_name',
                    DB::raw('COUNT(*) as total_enrollments'),
                    DB::raw('COUNT(CASE WHEN progress > 0 THEN 1 END) as active_students'),
                    DB::raw('COUNT(CASE WHEN completed_at IS NOT NULL THEN 1 END) as completed_students'),
                    DB::raw('ROUND(AVG(progress), 2) as avg_progress')
                )
                ->groupBy('instructors.id', 'instructors.name')
                ->orderBy('total_enrollments', 'desc')
                ->limit(10)
                ->get();

            // Get recent activity
            $recentActivity = DB::table('course_user')
                ->join('users', 'course_user.user_id', '=', 'users.id')
                ->join('courses', 'course_user.course_id', '=', 'courses.id')
                ->select(
                    'course_user.*',
                    'users.name as student_name',
                    'courses.title as course_title',
                    DB::raw('CASE 
                        WHEN completed_at IS NOT NULL THEN "completed"
                        WHEN progress > 0 THEN "in_progress"
                        ELSE "enrolled"
                    END as status')
                )
                ->orderBy('course_user.updated_at', 'desc')
                ->limit(20)
                ->get();

            // Build base query for course enrollments
            $enrollmentsQuery = DB::table('course_user')
                ->join('users', 'course_user.user_id', '=', 'users.id')
                ->join('courses', 'course_user.course_id', '=', 'courses.id')
                ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
                ->select(
                    'course_user.*',
                    'users.name as student_name',
                    'users.email as student_email',
                    'courses.title as course_title',
                    'courses.id as course_id',
                    'instructors.name as instructor_name'
                );

            // Apply filters
            if ($dateFrom && $dateTo) {
                $enrollmentsQuery->whereBetween('course_user.created_at', [$dateFrom, $dateTo]);
            }

            if ($courseId) {
                $enrollmentsQuery->where('courses.id', $courseId);
            }

            if ($instructorId) {
                $enrollmentsQuery->where('courses.instructor_id', $instructorId);
            }

            // Get detailed enrollments with filters applied
            $enrollments = $enrollmentsQuery->orderBy('course_user.created_at', 'desc')->paginate(20);

            // Get filter options
            $courses = Course::select('id', 'title')->orderBy('title')->get();
            $instructors = User::role('instructor')->select('id', 'name')->orderBy('name')->get();

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Attendance Report Error: ' . $e->getMessage());
            $error = $e->getMessage();
        }

        return view('reports.attendance.index', compact(
            'totalStudents',
            'activeStudents',
            'completedStudents',
            'attendanceRate',
            'completionRate',
            'attendanceByMonth',
            'attendanceByCourse',
            'attendanceByInstructor',
            'recentActivity',
            'enrollments',
            'courses',
            'instructors',
            'dateFrom',
            'dateTo',
            'courseId',
            'status',
            'instructorId',
            'error'
        ));
    }
}