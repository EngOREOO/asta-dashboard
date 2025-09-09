<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentAttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessmentAttempt::with(['assessment', 'user']);

        if ($request->user()->hasRole('student')) {
            $query->where('user_id', $request->user()->id);
        } elseif ($request->user()->hasRole('instructor')) {
            $query->whereHas('assessment.course', function ($q) use ($request) {
                $q->where('instructor_id', $request->user()->id);
            });
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request, Assessment $assessment)
    {
        $this->authorize('create', AssessmentAttempt::class);

        // Check if user is assigned to this assessment
        if (!$assessment->assignments()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not assigned to this assessment'], 403);
        }

        // Check if user already has an in-progress attempt
        if ($assessment->attempts()
            ->where('user_id', $request->user()->id)
            ->where('status', 'in_progress')
            ->exists()) {
            return response()->json(['message' => 'You already have an in-progress attempt'], 400);
        }

        $attempt = $assessment->attempts()->create([
            'user_id' => $request->user()->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        return response()->json($attempt->load(['assessment', 'user']), 201);
    }

    public function show(AssessmentAttempt $attempt)
    {
        $this->authorize('view', $attempt);

        return response()->json($attempt->load(['assessment', 'user', 'answers.question']));
    }

    public function submit(Request $request, AssessmentAttempt $attempt)
    {
        $this->authorize('update', $attempt);

        if ($attempt->status !== 'in_progress') {
            return response()->json(['message' => 'This attempt is already submitted'], 400);
        }

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'exists:assessment_questions,id'],
            'answers.*.answer' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($attempt, $validated) {
            $totalPoints = 0;
            $earnedPoints = 0;

            foreach ($validated['answers'] as $answerData) {
                $question = $attempt->assessment->questions()->findOrFail($answerData['question_id']);
                $isCorrect = $question->correct_answer === $answerData['answer'];
                $pointsEarned = $isCorrect ? $question->points : 0;

                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'answer' => $answerData['answer'],
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned,
                ]);

                $totalPoints += $question->points;
                $earnedPoints += $pointsEarned;
            }

            $attempt->update([
                'completed_at' => now(),
                'status' => 'completed',
                'score' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0,
            ]);
        });

        return response()->json($attempt->load(['assessment', 'user', 'answers.question']));
    }

    public function grade(Request $request, AssessmentAttempt $attempt)
    {
        $this->authorize('grade', $attempt);

        if ($attempt->status !== 'completed') {
            return response()->json(['message' => 'This attempt is not completed yet'], 400);
        }

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*.id' => ['required', 'exists:assessment_answers,id'],
            'answers.*.points_earned' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($attempt, $validated) {
            $totalPoints = 0;
            $earnedPoints = 0;

            foreach ($validated['answers'] as $answerData) {
                $answer = $attempt->answers()->findOrFail($answerData['id']);
                $question = $answer->question;

                $answer->update([
                    'points_earned' => $answerData['points_earned'],
                    'is_correct' => $answerData['points_earned'] === $question->points,
                ]);

                $totalPoints += $question->points;
                $earnedPoints += $answerData['points_earned'];
            }

            $attempt->update([
                'score' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0,
            ]);
        });

        return response()->json($attempt->load(['assessment', 'user', 'answers.question']));
    }
}
