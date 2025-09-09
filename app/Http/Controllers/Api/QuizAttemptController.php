<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizAttemptController extends Controller
{
    /**
     * Get all quizzes taken by the authenticated user with results
     */
    public function myQuizzes()
    {
        $user = Auth::user();

        $attempts = QuizAttempt::with(['quiz.course.instructor'])
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function ($attempt) {
                return [
                    'id' => 'attempt_'.$attempt->id,
                    'quiz' => [
                        'id' => 'quiz_'.$attempt->quiz->id,
                        'title' => $attempt->quiz->title,
                        'description' => $attempt->quiz->description,
                        'course' => [
                            'id' => 'course_'.$attempt->quiz->course->id,
                            'title' => $attempt->quiz->course->title,
                            'instructor' => [
                                'id' => 'inst_'.$attempt->quiz->course->instructor->id,
                                'name' => $attempt->quiz->course->instructor->name,
                            ],
                        ],
                    ],
                    'attemptNumber' => $attempt->attempt_number,
                    'score' => $attempt->score,
                    'totalQuestions' => $attempt->total_questions,
                    'correctAnswers' => $attempt->correct_answers,
                    'percentage' => $attempt->percentage,
                    'isPassed' => $attempt->is_passed,
                    'timeTaken' => $attempt->time_taken_minutes,
                    'startedAt' => $attempt->started_at->toISOString(),
                    'completedAt' => $attempt->completed_at->toISOString(),
                    'canReview' => $attempt->quiz->allow_review,
                ];
            });

        return response()->json([
            'myQuizzes' => $attempts,
            'stats' => [
                'totalAttempts' => $attempts->count(),
                'passedAttempts' => $attempts->where('isPassed', true)->count(),
                'averageScore' => $attempts->avg('score'),
                'totalQuizzes' => $attempts->unique('quiz.id')->count(),
            ],
        ]);
    }

    /**
     * Start a new quiz attempt
     */
    public function start(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        // Check if quiz is available
        if (! $quiz->isAvailable()) {
            return response()->json(['message' => 'This quiz is not available'], 400);
        }

        // Check if user is enrolled in the course
        if (! $user->enrolledCourses()->where('course_id', $quiz->course_id)->exists()) {
            return response()->json(['message' => 'You must be enrolled in this course to take the quiz'], 403);
        }

        // Check attempt limits
        $userAttempts = $quiz->attempts()->where('user_id', $user->id)->count();
        if ($quiz->max_attempts && $userAttempts >= $quiz->max_attempts) {
            return response()->json([
                'message' => 'You have reached the maximum number of attempts for this quiz',
                'maxAttempts' => $quiz->max_attempts,
                'currentAttempts' => $userAttempts,
            ], 400);
        }

        // Check if user has an in-progress attempt
        $inProgressAttempt = $quiz->attempts()
            ->where('user_id', $user->id)
            ->whereNull('completed_at')
            ->first();

        if ($inProgressAttempt) {
            return response()->json([
                'message' => 'You have an in-progress attempt. Please complete it first.',
                'attemptId' => 'attempt_'.$inProgressAttempt->id,
            ], 400);
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'attempt_number' => $userAttempts + 1,
            'total_questions' => $quiz->questions->count(),
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Quiz attempt started successfully',
            'attempt' => [
                'id' => 'attempt_'.$attempt->id,
                'quizId' => 'quiz_'.$quiz->id,
                'attemptNumber' => $attempt->attempt_number,
                'totalQuestions' => $attempt->total_questions,
                'startedAt' => $attempt->started_at->toISOString(),
                'timeLimit' => $quiz->duration_minutes,
                'maxAttempts' => $quiz->max_attempts,
                'currentAttempts' => $userAttempts + 1,
            ],
        ], 201);
    }

    /**
     * Submit quiz answers and complete the attempt
     */
    public function submit(Request $request, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // Verify ownership
        if ($attempt->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if already completed
        if ($attempt->completed_at) {
            return response()->json(['message' => 'This attempt is already completed'], 400);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.answer' => 'required',
            'timeTaken' => 'nullable|integer|min:1',
        ]);

        // Calculate time taken
        $timeTaken = $validated['timeTaken'] ??
            $attempt->started_at->diffInMinutes(now());

        DB::transaction(function () use ($attempt, $validated, $timeTaken) {
            // Store answers
            $attempt->answers = $validated['answers'];
            $attempt->time_taken_minutes = $timeTaken;
            $attempt->completed_at = now();

            // Calculate score
            $attempt->calculateScore();

            $attempt->save();
        });

        // Load the completed attempt with results
        $attempt->refresh();

        return response()->json([
            'message' => 'Quiz submitted successfully',
            'attempt' => [
                'id' => 'attempt_'.$attempt->id,
                'score' => $attempt->score,
                'percentage' => $attempt->percentage,
                'totalQuestions' => $attempt->total_questions,
                'correctAnswers' => $attempt->correct_answers,
                'isPassed' => $attempt->is_passed,
                'timeTaken' => $attempt->time_taken_minutes,
                'completedAt' => $attempt->completed_at->toISOString(),
                'showResults' => $attempt->quiz->show_results_immediately,
            ],
            'quiz' => [
                'id' => 'quiz_'.$attempt->quiz->id,
                'title' => $attempt->quiz->title,
                'passingScore' => $attempt->quiz->passing_score,
                'allowReview' => $attempt->quiz->allow_review,
            ],
        ]);
    }

    /**
     * View quiz attempt results
     */
    public function show(QuizAttempt $attempt)
    {
        $user = Auth::user();

        // Verify ownership
        if ($attempt->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if attempt is completed
        if (! $attempt->completed_at) {
            return response()->json(['message' => 'This attempt is not completed yet'], 400);
        }

        $quiz = $attempt->quiz;
        $questions = $quiz->questions;

        // Prepare question details with user answers
        $questionResults = $questions->map(function ($question) use ($attempt) {
            $userAnswer = $attempt->answers[$question->id] ?? null;
            $isCorrect = $question->isCorrectAnswer($userAnswer);

            return [
                'id' => 'q_'.$question->id,
                'question' => $question->question,
                'type' => $question->type,
                'points' => $question->points,
                'userAnswer' => $userAnswer,
                'isCorrect' => $isCorrect,
                'correctAnswer' => $question->correct_answer,
                'explanation' => $question->explanation,
                'image' => $question->image ? asset('storage/'.$question->image) : null,
            ];
        });

        return response()->json([
            'attempt' => [
                'id' => 'attempt_'.$attempt->id,
                'quizId' => 'quiz_'.$attempt->quiz->id,
                'attemptNumber' => $attempt->attempt_number,
                'score' => $attempt->score,
                'percentage' => $attempt->percentage,
                'totalQuestions' => $attempt->total_questions,
                'correctAnswers' => $attempt->correct_answers,
                'isPassed' => $attempt->is_passed,
                'timeTaken' => $attempt->time_taken_minutes,
                'startedAt' => $attempt->started_at->toISOString(),
                'completedAt' => $attempt->completed_at->toISOString(),
            ],
            'quiz' => [
                'id' => 'quiz_'.$attempt->quiz->id,
                'title' => $attempt->quiz->title,
                'description' => $attempt->quiz->description,
                'passingScore' => $attempt->quiz->passing_score,
                'allowReview' => $attempt->quiz->allow_review,
                'course' => [
                    'id' => 'course_'.$attempt->quiz->course->id,
                    'title' => $attempt->quiz->course->title,
                ],
            ],
            'questions' => $questionResults,
            'summary' => [
                'totalPoints' => $questions->sum('points'),
                'earnedPoints' => $attempt->correct_answers,
                'passingScore' => $attempt->quiz->passing_score,
                'status' => $attempt->is_passed ? 'Passed' : 'Failed',
            ],
        ]);
    }

    /**
     * Get current in-progress attempt for a quiz
     */
    public function currentAttempt(Quiz $quiz)
    {
        $user = Auth::user();

        $attempt = $quiz->attempts()
            ->where('user_id', $user->id)
            ->whereNull('completed_at')
            ->first();

        if (! $attempt) {
            return response()->json(['message' => 'No in-progress attempt found'], 404);
        }

        return response()->json([
            'attempt' => [
                'id' => 'attempt_'.$attempt->id,
                'quizId' => 'quiz_'.$quiz->id,
                'attemptNumber' => $attempt->attempt_number,
                'totalQuestions' => $attempt->total_questions,
                'startedAt' => $attempt->started_at->toISOString(),
                'timeLimit' => $quiz->duration_minutes,
                'elapsedTime' => $attempt->started_at->diffInMinutes(now()),
            ],
        ]);
    }
}
