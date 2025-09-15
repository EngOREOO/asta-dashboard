<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseWiseEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $courseEnrollments = Course::select(
                'courses.id',
                'courses.title',
                'courses.price',
                'categories.name as category_name',
                DB::raw('COUNT(course_user.user_id) as enrollment_count'),
                DB::raw('SUM(courses.price) as total_revenue')
            )
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->leftJoin('course_user', 'courses.id', '=', 'course_user.course_id')
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->groupBy('courses.id', 'courses.title', 'courses.price', 'categories.name')
            ->orderBy('enrollment_count', 'desc')
            ->paginate(20);

        return view('reports.course-wise-enrollments.index', compact(
            'courseEnrollments',
            'startDate',
            'endDate'
        ));
    }
}