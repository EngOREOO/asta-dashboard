<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
            ->select(
                'course_user.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.title as course_title',
                'courses.price',
                'instructors.name as instructor_name'
            )
            ->latest('course_user.created_at')
            ->paginate(20);

        return view('enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $students = User::role('student')->get();
        // Show all courses (not only approved)
        $courses = Course::with('instructor')->get();

        return view('enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'progress' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);
        $course = Course::findOrFail($request->course_id);

        // Check if already enrolled
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->withErrors(['course_id' => 'Student is already enrolled in this course.']);
        }

        $user->enrolledCourses()->attach($course->id, [
            'enrolled_at' => now(),
            'progress' => $request->progress ?? 0,
            'grade' => $request->grade,
            'notes' => $request->notes,
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Student enrolled successfully.');
    }

    public function show($userId, $courseId)
    {
        $enrollment = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
            ->where('course_user.user_id', $userId)
            ->where('course_user.course_id', $courseId)
            ->select(
                'course_user.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.title as course_title',
                'courses.description as course_description',
                'courses.price',
                'instructors.name as instructor_name'
            )
            ->first();

        if (! $enrollment) {
            abort(404);
        }

        // Get course materials progress
        $materialsProgress = DB::table('course_materials')
            ->leftJoin('material_completions', function ($join) use ($userId) {
                $join->on('course_materials.id', '=', 'material_completions.course_material_id')
                    ->where('material_completions.user_id', '=', $userId);
            })
            ->where('course_materials.course_id', $courseId)
            ->select(
                'course_materials.*',
                'material_completions.completed_at',
                DB::raw('NULL as progress')
            )
            ->orderBy('course_materials.order')
            ->get();

        return view('enrollments.show', compact('enrollment', 'materialsProgress'));
    }

    public function edit($userId, $courseId)
    {
        $enrollment = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
            ->where('course_user.user_id', $userId)
            ->where('course_user.course_id', $courseId)
            ->select(
                'course_user.*',
                'users.name as student_name',
                'users.email as student_email',
                'courses.title as course_title',
                'instructors.name as instructor_name'
            )
            ->first();

        if (! $enrollment) {
            abort(404);
        }

        return view('enrollments.edit', compact('enrollment'));
    }

    public function update(Request $request, $userId, $courseId)
    {
        $request->validate([
            'progress' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);

        $updateData = [
            'progress' => $request->progress ?? 0,
            'grade' => $request->grade,
            'notes' => $request->notes,
        ];

        if ($request->completed_at) {
            $updateData['completed_at'] = $request->completed_at;
        } elseif ($request->progress == 100) {
            $updateData['completed_at'] = now();
        }

        DB::table('course_user')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update($updateData);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy($userId, $courseId)
    {
        DB::table('course_user')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment removed successfully.');
    }

    public function analytics()
    {
        $totalEnrollments = DB::table('course_user')->count();
        $completedEnrollments = DB::table('course_user')->whereNotNull('completed_at')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 2) : 0;

        $enrollmentsByMonth = DB::table('course_user')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $popularCourses = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->selectRaw('courses.title, COUNT(*) as enrollment_count')
            ->groupBy('courses.id', 'courses.title')
            ->orderBy('enrollment_count', 'desc')
            ->take(10)
            ->get();

        $averageProgress = DB::table('course_user')
            ->selectRaw('AVG(progress) as avg_progress')
            ->first();

        return view('enrollments.analytics', compact(
            'totalEnrollments',
            'completedEnrollments',
            'completionRate',
            'enrollmentsByMonth',
            'popularCourses',
            'averageProgress'
        ));
    }
}
