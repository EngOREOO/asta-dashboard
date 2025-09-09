<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentAttempt;
use App\Models\Course;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::with(['course.instructor'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->paginate(20);
            
        return view('assessments.index', compact('assessments'));
    }

    public function create()
    {
        $courses = Course::with('instructor')->get();
        return view('assessments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,exam,assignment,survey',
            'duration_minutes' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
        ]);

        Assessment::create($request->all());

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment created successfully.');
    }

    public function show(Assessment $assessment)
    {
        $assessment->load([
            'course.instructor', 
            'questions', 
            'attempts.user',
            'attempts' => function($query) {
                $query->latest()->take(10);
            }
        ]);
        
        return view('assessments.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        $courses = Course::with('instructor')->get();
        return view('assessments.edit', compact('assessment', 'courses'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,exam,assignment,survey',
            'duration_minutes' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
        ]);

        $assessment->update($request->all());

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment deleted successfully.');
    }

    public function questions(Assessment $assessment)
    {
        $questions = $assessment->questions()->latest()->paginate(20);
        return view('assessments.questions', compact('assessment', 'questions'));
    }

    public function attempts(Assessment $assessment)
    {
        $attempts = $assessment->attempts()
            ->with(['user', 'answers.question'])
            ->latest()
            ->paginate(20);
            
        return view('assessments.attempts', compact('assessment', 'attempts'));
    }

    public function analytics()
    {
        $totalAssessments = Assessment::count();
        $assessmentsByType = Assessment::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
        
        $popularAssessments = Assessment::withCount('attempts')
            ->with(['course.instructor'])
            ->orderBy('attempts_count', 'desc')
            ->take(10)
            ->get();

        $averageScores = AssessmentAttempt::selectRaw('assessment_id, AVG(score) as avg_score')
            ->with('assessment')
            ->groupBy('assessment_id')
            ->having('avg_score', '>', 0)
            ->orderBy('avg_score', 'desc')
            ->take(10)
            ->get();

        // Recent assessment attempts with proper joins
        $recentAttempts = AssessmentAttempt::select(
                'assessment_attempts.*',
                'assessments.title as assessment_title',
                'users.name as user_name'
            )
            ->join('assessments', 'assessment_attempts.assessment_id', '=', 'assessments.id')
            ->join('users', 'assessment_attempts.user_id', '=', 'users.id')
            ->orderBy('assessment_attempts.created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('assessments.analytics', compact(
            'totalAssessments', 
            'assessmentsByType', 
            'popularAssessments',
            'averageScores',
            'recentAttempts'
        ));
    }

    public function generalAnalytics()
    {
        $totalAssessments = Assessment::count();
        $assessmentsByType = Assessment::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
        
        $popularAssessments = Assessment::withCount('attempts')
            ->with(['course.instructor'])
            ->orderBy('attempts_count', 'desc')
            ->take(10)
            ->get();

        $averageScores = AssessmentAttempt::selectRaw('assessment_id, AVG(score) as avg_score')
            ->with('assessment')
            ->groupBy('assessment_id')
            ->having('avg_score', '>', 0)
            ->orderBy('avg_score', 'desc')
            ->take(10)
            ->get();

        // Recent assessment attempts with proper joins
        $recentAttempts = AssessmentAttempt::select(
                'assessment_attempts.*',
                'assessments.title as assessment_title',
                'users.name as user_name'
            )
            ->join('assessments', 'assessment_attempts.assessment_id', '=', 'assessments.id')
            ->join('users', 'assessment_attempts.user_id', '=', 'assessment_attempts.user_id')
            ->orderBy('assessment_attempts.created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('assessments.general-analytics', compact(
            'totalAssessments', 
            'assessmentsByType', 
            'popularAssessments',
            'averageScores',
            'recentAttempts'
        ));
    }
}
