<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentProgressController extends Controller
{
    public function index()
    {
        $students = User::role('student')
            ->with(['enrolledCourses'])
            ->withCount(['enrolledCourses', 'materialCompletions'])
            ->latest()
            ->paginate(20);

        // Calculate progress statistics for each student
        $students->getCollection()->transform(function ($student) {
            $enrollments = DB::table('course_user')
                ->where('user_id', $student->id)
                ->get();

            $totalProgress = $enrollments->avg('progress') ?? 0;
            $completedCourses = $enrollments->where('completed_at', '!=', null)->count();

            $student->total_progress = round($totalProgress, 1);
            $student->completed_courses = $completedCourses;
            $student->in_progress_courses = $enrollments->where('progress', '>', 0)
                ->where('completed_at', null)->count();

            return $student;
        });

        return view('student-progress.index', compact('students'));
    }

    public function show(User $user)
    {
        // Get detailed progress for a specific student
        $enrollments = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
            ->where('course_user.user_id', $user->id)
            ->select(
                'course_user.*',
                'courses.title as course_title',
                'courses.description as course_description',
                'instructors.name as instructor_name'
            )
            ->get();

        // Get material completions for this student
        $materialCompletions = DB::table('material_completions')
            ->join('course_materials', 'material_completions.course_material_id', '=', 'course_materials.id')
            ->join('courses', 'course_materials.course_id', '=', 'courses.id')
            ->where('material_completions.user_id', $user->id)
            ->select(
                'material_completions.*',
                'course_materials.title as material_title',
                'course_materials.type as material_type',
                'courses.title as course_title'
            )
            ->latest('material_completions.completed_at')
            ->get();

        // Get assessment attempts
        $assessmentAttempts = DB::table('assessment_attempts')
            ->join('assessments', 'assessment_attempts.assessment_id', '=', 'assessments.id')
            ->join('courses', 'assessments.course_id', '=', 'courses.id')
            ->where('assessment_attempts.user_id', $user->id)
            ->select(
                'assessment_attempts.*',
                'assessments.title as assessment_title',
                'courses.title as course_title'
            )
            ->latest('assessment_attempts.created_at')
            ->get();

        return view('student-progress.show', compact('user', 'enrollments', 'materialCompletions', 'assessmentAttempts'));
    }

    public function analytics()
    {
        $totalStudents = User::role('student')->count();
        $activeStudents = DB::table('course_user')
            ->distinct('user_id')
            ->count();

        $averageProgress = DB::table('course_user')
            ->avg('progress') ?? 0;

        $completionRate = DB::table('course_user')
            ->whereNotNull('completed_at')
            ->count() / max(DB::table('course_user')->count(), 1) * 100;

        $progressByMonth = DB::table('material_completions')
            ->selectRaw('DATE_FORMAT(completed_at, "%Y-%m") as month, COUNT(*) as completions')
            ->whereNotNull('completed_at')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $topPerformers = User::role('student')
            ->join('course_user', 'users.id', '=', 'course_user.user_id')
            ->select('users.id', 'users.name', 'users.email')
            ->selectRaw('users.profile_photo_path as avatar')
            ->selectRaw('AVG(course_user.progress) as avg_progress, COUNT(course_user.course_id) as course_count')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.profile_photo_path')
            ->having('course_count', '>', 0)
            ->orderBy('avg_progress', 'desc')
            ->take(10)
            ->get();

        return view('student-progress.analytics', compact(
            'totalStudents',
            'activeStudents',
            'averageProgress',
            'completionRate',
            'progressByMonth',
            'topPerformers'
        ));
    }
}
