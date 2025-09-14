<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['course.instructor'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->paginate(20);
            
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::with('instructor')->get();
        return view('quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
        ]);

        $quiz = Quiz::create($request->all());

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully! Now add questions to complete your quiz.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load([
            'course.instructor', 
            'questions.answers',
            'attempts.user'
        ]);
        
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $courses = Course::with('instructor')->get();
        return view('quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
        ]);

        $quiz->update($request->all());

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    public function questions(Quiz $quiz)
    {
        $quiz->load('questions.answers');
        return view('quizzes.questions', compact('quiz'));
    }

    public function addQuestion(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
            'answers.*.feedback' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $quiz) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('quiz-questions', 'public');
            }

            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $request->question,
                'type' => $request->type,
                'points' => $request->points,
                'explanation' => $request->explanation,
                'image' => $imagePath,
                'order' => $quiz->questions()->count() + 1,
            ]);

            foreach ($request->answers as $index => $answerData) {
                QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                    'feedback' => $answerData['feedback'] ?? null,
                    'order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('quizzes.questions', $quiz)
            ->with('success', 'Question added successfully.');
    }

    public function editQuestion(Quiz $quiz, QuizQuestion $question)
    {
        $question->load('answers');
        return view('quizzes.edit-question', compact('quiz', 'question'));
    }

    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
            'answers.*.feedback' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $question) {
            if ($request->hasFile('image')) {
                if ($question->image) {
                    Storage::disk('public')->delete($question->image);
                }
                $imagePath = $request->file('image')->store('quiz-questions', 'public');
                $question->image = $imagePath;
            }

            $question->update([
                'question' => $request->question,
                'type' => $request->type,
                'points' => $request->points,
                'explanation' => $request->explanation,
            ]);

            // Delete existing answers and create new ones
            $question->answers()->delete();

            foreach ($request->answers as $index => $answerData) {
                QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                    'feedback' => $answerData['feedback'] ?? null,
                    'order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('quizzes.questions', $question->quiz)
            ->with('success', 'Question updated successfully.');
    }

    public function deleteQuestion(Quiz $quiz, QuizQuestion $question)
    {
        if ($question->image) {
            Storage::disk('public')->delete($question->image);
        }
        
        $question->delete();

        return redirect()->route('quizzes.questions', $quiz)
            ->with('success', 'Question deleted successfully.');
    }

    public function attempts(Quiz $quiz)
    {
        $attempts = $quiz->attempts()
            ->with('user')
            ->latest()
            ->paginate(20);
            
        return view('quizzes.attempts', compact('quiz', 'attempts'));
    }

    public function analytics(Quiz $quiz)
    {
        $totalAttempts = $quiz->attempts()->count();
        $completedAttempts = $quiz->attempts()->whereNotNull('completed_at')->count();
        $averageScore = $quiz->attempts()->whereNotNull('completed_at')->avg('score') ?? 0;
        $passRate = $quiz->passing_score && $completedAttempts > 0 
            ? ($quiz->attempts()->where('is_passed', true)->count() / $completedAttempts * 100)
            : 0;

        $questionAnalytics = $quiz->questions()
            ->with('answers')
            ->get()
            ->map(function ($question) {
                $totalAnswers = 0;
                $correctAnswers = 0;
                
                // This would require analyzing attempt answers
                // For now, we'll show basic question info
                return [
                    'question' => $question,
                    'difficulty' => 'Medium', // Could be calculated based on correct rate
                    'correct_rate' => 0, // Would need to analyze attempts
                ];
            });

        // Calculate score ranges for distribution chart
        $scoreRanges = [
            '0-20' => 0,
            '21-40' => 0,
            '41-60' => 0,
            '61-80' => 0,
            '81-100' => 0
        ];
        
        $attempts = $quiz->attempts()->whereNotNull('completed_at')->get();
        foreach ($attempts as $attempt) {
            $score = $attempt->score ?? 0;
            if ($score <= 20) $scoreRanges['0-20']++;
            elseif ($score <= 40) $scoreRanges['21-40']++;
            elseif ($score <= 60) $scoreRanges['41-60']++;
            elseif ($score <= 80) $scoreRanges['61-80']++;
            else $scoreRanges['81-100']++;
        }

        return view('quizzes.analytics', compact(
            'quiz', 
            'totalAttempts', 
            'completedAttempts', 
            'averageScore', 
            'passRate', 
            'questionAnalytics',
            'scoreRanges',
            'attempts'
        ));
    }

    public function generalAnalytics()
    {
        $totalQuizzes = Quiz::count();
        $activeQuizzes = Quiz::where('is_active', true)->count();
        $totalQuestions = QuizQuestion::count();
        $totalAttempts = QuizAttempt::count();
        $averageScore = QuizAttempt::whereNotNull('completed_at')->avg('score') ?? 0;
        $passRate = QuizAttempt::whereNotNull('completed_at')->where('is_passed', true)->count();

        $recentQuizzes = Quiz::with('category')
            ->latest()
            ->take(5)
            ->get();

        $topQuizzes = Quiz::withCount('attempts')
            ->orderBy('attempts_count', 'desc')
            ->take(10)
            ->get();

        return view('quizzes.general-analytics', compact(
            'totalQuizzes',
            'activeQuizzes', 
            'totalQuestions',
            'totalAttempts',
            'averageScore',
            'passRate',
            'recentQuizzes',
            'topQuizzes'
        ));
    }
}
