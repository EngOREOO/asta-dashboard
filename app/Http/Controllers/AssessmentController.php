<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentAssignment;
use App\Models\Course;
use App\Models\User;
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
        $students = User::role('student')->select('id','name','email')->orderBy('name')->get();
        return view('assessments.create', compact('courses', 'students'));
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
            'total_questions' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
            // Assign to students
            'assign_all' => 'sometimes|boolean',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'exists:users,id',
            // Inline questions
            'questions' => 'sometimes|array',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.type' => 'required_with:questions|in:multiple_choice,multiple_choice_two,true_false,text',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_answer' => 'nullable',
            'questions.*.points' => 'nullable|integer|min:1',
        ], [
            'questions.*.correct_answer.required' => 'يجب اختيار إجابتين صحيحتين لهذا النوع من الأسئلة.',
        ]);

        $data = $request->except(['user_ids', 'questions']);
        $data['created_by'] = auth()->id();

        $assessment = Assessment::create($data);

        // Assign to students if provided
        $assignAll = filter_var($request->input('assign_all', false), FILTER_VALIDATE_BOOL);
        if ($assignAll) {
            $allStudentIds = \App\Models\User::role('student')->pluck('id');
            $assessment->assignments()->createMany(
                $allStudentIds->map(fn ($userId) => [
                    'user_id' => $userId,
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ])->values()->all()
            );
        } elseif ($request->filled('user_ids')) {
            $assessment->assignments()->createMany(
                collect($request->input('user_ids'))
                    ->unique()
                    ->map(fn ($userId) => [
                        'user_id' => $userId,
                        'assigned_by' => auth()->id(),
                        'assigned_at' => now(),
                    ])->values()->all()
            );
        }

        // Create questions if provided
        if ($request->filled('questions')) {
            foreach ($request->input('questions') as $questionData) {
                $uiType = $questionData['type'];
                $dbType = $uiType === 'text' ? 'text' : 'mcq';

                $options = $questionData['options'] ?? null;
                // Normalize options for MCQ/True-False
                if ($dbType === 'mcq') {
                    if ($uiType === 'true_false') {
                        $options = ['true', 'false'];
                    } else {
                        if (is_string($options)) {
                            $options = collect(explode(',', $options))
                                ->map(fn ($opt) => trim($opt))
                                ->filter(fn ($opt) => $opt !== '')
                                ->values()
                                ->all();
                        } elseif (is_array($options)) {
                            $options = collect($options)
                                ->map(fn ($opt) => is_string($opt) ? trim($opt) : $opt)
                                ->filter(fn ($opt) => $opt !== '' && $opt !== null)
                                ->values()
                                ->all();
                        }
                    }
                } else {
                    $options = null;
                }

                $correctAnswer = $questionData['correct_answer'] ?? null;
                // Normalize two-answer selection to comma-separated indexes
                if ($uiType === 'multiple_choice_two') {
                    // Enforce exactly two answers
                    if (!is_array($correctAnswer) || count(array_filter($correctAnswer, fn($v) => $v !== null && $v !== '')) !== 2) {
                        return back()->withInput()->withErrors(['questions' => 'يجب اختيار إجابتين صحيحتين لكل سؤال من نوع "اختيارات متعددة إجابتين".']);
                    }
                    if (is_array($correctAnswer)) {
                        $correctAnswer = collect($correctAnswer)
                            ->map(fn($i) => (string)$i)
                            ->unique()
                            ->sort()
                            ->implode(',');
                    } elseif (is_string($correctAnswer)) {
                        $correctAnswer = collect(explode(',', $correctAnswer))
                            ->map(fn($i) => trim((string)$i))
                            ->filter(fn($i) => $i !== '')
                            ->unique()
                            ->sort()
                            ->implode(',');
                    }
                }
                $assessment->questions()->create([
                    'question' => $questionData['question'],
                    'type' => $dbType,
                    'options' => $options,
                    'correct_answer' => $correctAnswer,
                    'points' => $questionData['points'] ?? null,
                ]);
            }
        }

        return redirect()->route('assessments.index')
            ->with('success', 'Assessment created and configured successfully.');
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

    public function attemptDetail(\App\Models\AssessmentAttempt $attempt)
    {
        $attempt->load(['assessment.questions', 'user', 'answers.question']);
        return response()->json([
            'attempt' => $attempt,
        ]);
    }

    public function gradeAttempt(\Illuminate\Http\Request $request, \App\Models\AssessmentAttempt $attempt)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.id' => 'required|exists:assessment_answers,id',
            'answers.*.points_earned' => 'required|numeric|min:0',
        ]);

        \DB::transaction(function () use ($request, $attempt) {
            $totalPoints = 0;
            $earnedPoints = 0;

            foreach ($request->input('answers') as $answerData) {
                $answer = $attempt->answers()->with('question')->findOrFail($answerData['id']);
                $question = $answer->question;

                $pointsEarned = (float) $answerData['points_earned'];
                $answer->update([
                    'points_earned' => $pointsEarned,
                    'is_correct' => $question->type === 'mcq' ? ((string) $question->correct_answer === (string) $answer->answer) : null,
                ]);

                $totalPoints += (int) ($question->points ?? 0);
                $earnedPoints += $pointsEarned;
            }

            $attempt->update([
                'score' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0,
            ]);
        });

        return response()->json(['message' => 'Graded successfully']);
    }

    public function regradeAttempt(\App\Models\AssessmentAttempt $attempt)
    {
        \DB::transaction(function () use ($attempt) {
            $totalPoints = 0;
            $earnedPoints = 0;

            $answers = $attempt->answers()->with('question')->get();
            foreach ($answers as $answer) {
                $question = $answer->question;
                $fullPoints = (int) ($question->points ?? 0);
                $isCorrect = $question->type === 'mcq' && (string) $question->correct_answer === (string) $answer->answer;
                $pointsEarned = $isCorrect ? $fullPoints : (float) ($answer->points_earned ?? 0);
                $answer->update([
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned,
                ]);
                $totalPoints += $fullPoints;
                $earnedPoints += $pointsEarned;
            }

            $attempt->update([
                'score' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0,
            ]);
        });

        return response()->json(['message' => 'Regraded successfully']);
    }

    public function edit(Assessment $assessment)
    {
        $courses = Course::with('instructor')->get();
        $students = User::role('student')->select('id','name','email')->orderBy('name')->get();
        $assessment->load(['questions', 'assignments']);
        return view('assessments.edit', compact('assessment', 'courses', 'students'));
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
            'total_questions' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
            'show_results_immediately' => 'boolean',
            // Assign to students
            'assign_all' => 'sometimes|boolean',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'exists:users,id',
            // Inline questions (append-only on update)
            'questions' => 'sometimes|array',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.type' => 'required_with:questions|in:multiple_choice,multiple_choice_two,true_false,text',
            'questions.*.options' => 'nullable',
            'questions.*.correct_answer' => 'nullable',
            'questions.*.points' => 'nullable|integer|min:1',
        ]);

        $data = $request->except(['user_ids', 'questions']);
        $assessment->update($data);

        // Sync assigned students if provided
        if ($request->has('assign_all') || $request->has('user_ids')) {
            $assignAll = filter_var($request->input('assign_all', false), FILTER_VALIDATE_BOOL);
            $newUserIds = $assignAll
                ? \App\Models\User::role('student')->pluck('id')
                : collect($request->input('user_ids', []))->unique()->values();

            // Delete assignments not in the new list (or all if empty array provided)
            $assessment->assignments()
                ->when($newUserIds->isNotEmpty(), function ($q) use ($newUserIds) {
                    $q->whereNotIn('user_id', $newUserIds);
                }, function ($q) {
                    $q->whereNotNull('user_id');
                })
                ->delete();

            // Create missing assignments
            $existingUserIds = $assessment->assignments()->pluck('user_id')->all();
            $toCreate = collect($newUserIds)->diff($existingUserIds)->map(fn ($userId) => [
                'user_id' => $userId,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
            ])->values()->all();
            if (!empty($toCreate)) {
                $assessment->assignments()->createMany($toCreate);
            }
        }

        // Append new questions if provided
        if ($request->filled('questions')) {
            foreach ($request->input('questions') as $questionData) {
                $uiType = $questionData['type'];
                $dbType = $uiType === 'text' ? 'text' : 'mcq';

                $options = $questionData['options'] ?? null;
                if ($dbType === 'mcq') {
                    if ($uiType === 'true_false') {
                        $options = ['true', 'false'];
                    } else {
                        if (is_string($options)) {
                            $options = collect(explode(',', $options))
                                ->map(fn ($opt) => trim($opt))
                                ->filter(fn ($opt) => $opt !== '')
                                ->values()
                                ->all();
                        } elseif (is_array($options)) {
                            $options = collect($options)
                                ->map(fn ($opt) => is_string($opt) ? trim($opt) : $opt)
                                ->filter(fn ($opt) => $opt !== '' && $opt !== null)
                                ->values()
                                ->all();
                        }
                    }
                } else {
                    $options = null;
                }

                $correctAnswer = $questionData['correct_answer'] ?? null;
                if ($uiType === 'multiple_choice_two') {
                    if (is_array($correctAnswer)) {
                        $correctAnswer = collect($correctAnswer)
                            ->map(fn($i) => (string)$i)
                            ->unique()
                            ->sort()
                            ->implode(',');
                    } elseif (is_string($correctAnswer)) {
                        $correctAnswer = collect(explode(',', $correctAnswer))
                            ->map(fn($i) => trim((string)$i))
                            ->filter(fn($i) => $i !== '')
                            ->unique()
                            ->sort()
                            ->implode(',');
                    }
                }
                $assessment->questions()->create([
                    'question' => $questionData['question'],
                    'type' => $dbType,
                    'options' => $options,
                    'correct_answer' => $correctAnswer,
                    'points' => $questionData['points'] ?? null,
                ]);
            }
        }

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

    /**
     * Validate a single question payload via AJAX and return normalized data.
     */
    public function validateQuestion(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,multiple_choice_two,true_false,text',
            'options' => 'nullable|array',
            'points' => 'nullable|integer|min:1',
            'correct_answer' => 'nullable',
        ]);

        $uiType = $data['type'];
        $dbType = $uiType === 'text' ? 'text' : 'mcq';

        // Normalize options
        $options = $data['options'] ?? null;
        if ($dbType === 'mcq') {
            if ($uiType === 'true_false') {
                $options = ['true', 'false'];
            } else {
                if (is_string($options)) {
                    $options = collect(explode(',', $options))
                        ->map(fn ($opt) => trim($opt))
                        ->filter(fn ($opt) => $opt !== '')
                        ->values()
                        ->all();
                } elseif (is_array($options)) {
                    $options = collect($options)
                        ->map(fn ($opt) => is_string($opt) ? trim($opt) : $opt)
                        ->filter(fn ($opt) => $opt !== '' && $opt !== null)
                        ->values()
                        ->all();
                }
            }
        } else {
            $options = null;
        }

        // Normalize correct answer
        $correctAnswer = $data['correct_answer'] ?? null;
        if ($uiType === 'multiple_choice_two') {
            if (!is_array($correctAnswer) || count(array_filter($correctAnswer, fn($v) => $v !== null && $v !== '')) !== 2) {
                return response()->json(['message' => 'يجب اختيار إجابتين صحيحتين.'], 422);
            }
            $correctAnswer = collect($correctAnswer)
                ->map(fn($i) => (string)$i)
                ->unique()
                ->sort()
                ->implode(',');
        } elseif ($uiType === 'true_false') {
            // Accept index (0/1) or literal 'true'/'false'
            if (is_array($correctAnswer)) {
                $correctAnswer = $correctAnswer[0] ?? null;
            }
            if ($correctAnswer === '0' || $correctAnswer === 0) {
                $correctAnswer = 'true';
            } elseif ($correctAnswer === '1' || $correctAnswer === 1) {
                $correctAnswer = 'false';
            }
            if (!in_array($correctAnswer, ['true', 'false'], true)) {
                return response()->json(['message' => 'قيمة غير صالحة لصح/خطأ.'], 422);
            }
        } elseif ($uiType === 'multiple_choice') {
            if (!is_null($correctAnswer) && !is_string($correctAnswer) && !is_numeric($correctAnswer)) {
                return response()->json(['message' => 'الإجابة الصحيحة غير صالحة.'], 422);
            }
            $correctAnswer = (string)$correctAnswer;
        }

        $normalized = [
            'question' => $data['question'],
            'type' => $dbType,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'points' => $data['points'] ?? null,
        ];

        // Persist temporarily in session for the create flow
        $list = (array) session('assessment_build.questions', []);
        $list[] = $normalized;
        session(['assessment_build.questions' => $list]);

        return response()->json([
            'saved' => true,
            'index' => count($list) - 1,
            'question' => $normalized,
            'total' => count($list),
        ]);
    }

    /**
     * Return temporarily saved questions for the current session.
     */
    public function getTempQuestions()
    {
        return response()->json([
            'questions' => (array) session('assessment_build.questions', []),
            'total' => count((array) session('assessment_build.questions', [])),
        ]);
    }

    /**
     * Update an existing temp question in the session by index.
     */
    public function updateTempQuestion(int $index, \Illuminate\Http\Request $request)
    {
        $questions = (array) session('assessment_build.questions', []);
        if (! array_key_exists($index, $questions)) {
            return response()->json(['message' => 'العنصر غير موجود.'], 404);
        }

        $data = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,multiple_choice_two,true_false,text',
            'options' => 'nullable|array',
            'points' => 'nullable|integer|min:1',
            'correct_answer' => 'nullable',
        ]);

        $uiType = $data['type'];
        $dbType = $uiType === 'text' ? 'text' : 'mcq';

        $options = $data['options'] ?? null;
        if ($dbType === 'mcq') {
            if ($uiType === 'true_false') {
                $options = ['true', 'false'];
            } else {
                if (is_string($options)) {
                    $options = collect(explode(',', $options))
                        ->map(fn ($opt) => trim($opt))
                        ->filter(fn ($opt) => $opt !== '')
                        ->values()
                        ->all();
                } elseif (is_array($options)) {
                    $options = collect($options)
                        ->map(fn ($opt) => is_string($opt) ? trim($opt) : $opt)
                        ->filter(fn ($opt) => $opt !== '' && $opt !== null)
                        ->values()
                        ->all();
                }
            }
        } else {
            $options = null;
        }

        $correctAnswer = $data['correct_answer'] ?? null;
        if ($uiType === 'multiple_choice_two') {
            if (!is_array($correctAnswer) || count(array_filter($correctAnswer, fn($v) => $v !== null && $v !== '')) !== 2) {
                return response()->json(['message' => 'يجب اختيار إجابتين صحيحتين.'], 422);
            }
            $correctAnswer = collect($correctAnswer)
                ->map(fn($i) => (string)$i)
                ->unique()
                ->sort()
                ->implode(',');
        } elseif ($uiType === 'true_false') {
            if (!in_array($correctAnswer, ['true', 'false'], true)) {
                return response()->json(['message' => 'قيمة غير صالحة لصح/خطأ.'], 422);
            }
        } elseif ($uiType === 'multiple_choice') {
            if (!is_null($correctAnswer) && !is_string($correctAnswer) && !is_numeric($correctAnswer)) {
                return response()->json(['message' => 'الإجابة الصحيحة غير صالحة.'], 422);
            }
            $correctAnswer = (string)$correctAnswer;
        }

        $normalized = [
            'question' => $data['question'],
            'type' => $dbType,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'points' => $data['points'] ?? null,
        ];

        $questions[$index] = $normalized;
        session(['assessment_build.questions' => $questions]);

        return response()->json([
            'saved' => true,
            'index' => $index,
            'question' => $normalized,
            'total' => count($questions),
        ]);
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
