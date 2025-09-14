<?php

namespace App\Http\Controllers;

use App\Models\AssessmentQuestion;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of all questions in the bank.
     */
    public function index()
    {
        $questions = AssessmentQuestion::with(['assessment.course'])
            ->latest()
            ->paginate(20);
            
        return view('question-bank.index', compact('questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('question-bank.create', compact('categories'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
    {
        // Dynamic validation based on question type
        $rules = [
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,multiple_choice_two,true_false,text',
            'points' => 'nullable|integer|min:1',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'category' => 'nullable|string|max:255',
        ];

        // Add conditional validation based on question type
        if (in_array($request->type, ['multiple_choice', 'multiple_choice_two', 'true_false'])) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*'] = 'required|string';
            $rules['correct_answer'] = 'required|array';
        } elseif ($request->type === 'text') {
            $rules['correct_answer'] = 'nullable|string';
        }

        $request->validate($rules);

        $data = $request->all();
        
        // Debug: Log the incoming data
        \Log::info('Question Bank Store - Incoming Data:', $data);
        
        // Normalize data based on question type
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
                return back()->withErrors(['correct_answer' => 'يجب اختيار إجابتين صحيحتين.'])->withInput();
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
                return back()->withErrors(['correct_answer' => 'قيمة غير صالحة لصح/خطأ.'])->withInput();
            }
        } elseif ($uiType === 'multiple_choice') {
            if (!is_null($correctAnswer) && !is_string($correctAnswer) && !is_numeric($correctAnswer)) {
                return back()->withErrors(['correct_answer' => 'الإجابة الصحيحة غير صالحة.'])->withInput();
            }
            $correctAnswer = (string)$correctAnswer;
        }

        $questionData = [
            'question' => $data['question'],
            'type' => $dbType,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'points' => $data['points'] ?? null,
            'difficulty' => $data['difficulty'] ?? null,
            'category' => $data['category'] ?? null,
            'assessment_id' => null, // Bank questions are not tied to specific assessments
            'created_by' => auth()->id(),
        ];

        try {
            $question = AssessmentQuestion::create($questionData);
            \Log::info('Question created successfully:', ['question_id' => $question->id]);
            return redirect()->route('question-bank.index')->with('success', 'تم إضافة السؤال إلى بنك الأسئلة بنجاح.');
        } catch (\Exception $e) {
            \Log::error('Error creating question:', ['error' => $e->getMessage(), 'data' => $questionData]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء إضافة السؤال. يرجى المحاولة مرة أخرى.'])->withInput();
        }
    }

    /**
     * Display the specified question.
     */
    public function show(AssessmentQuestion $questionBank)
    {
        return view('question-bank.show', compact('questionBank'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(AssessmentQuestion $questionBank)
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('question-bank.edit', compact('questionBank', 'categories'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, AssessmentQuestion $questionBank)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,multiple_choice_two,true_false,text',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable',
            'points' => 'nullable|integer|min:1',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'category' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        
        // Normalize data based on question type (same logic as store)
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
                return back()->withErrors(['correct_answer' => 'يجب اختيار إجابتين صحيحتين.'])->withInput();
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
                return back()->withErrors(['correct_answer' => 'قيمة غير صالحة لصح/خطأ.'])->withInput();
            }
        } elseif ($uiType === 'multiple_choice') {
            if (!is_null($correctAnswer) && !is_string($correctAnswer) && !is_numeric($correctAnswer)) {
                return back()->withErrors(['correct_answer' => 'الإجابة الصحيحة غير صالحة.'])->withInput();
            }
            $correctAnswer = (string)$correctAnswer;
        }

        $questionData = [
            'question' => $data['question'],
            'type' => $dbType,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'points' => $data['points'] ?? null,
            'difficulty' => $data['difficulty'] ?? null,
            'category' => $data['category'] ?? null,
            'tags' => $data['tags'] ?? null,
        ];

        $questionBank->update($questionData);

        return redirect()->route('question-bank.index')->with('success', 'تم تحديث السؤال بنجاح.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(AssessmentQuestion $questionBank)
    {
        $questionBank->delete();
        
        return redirect()->route('question-bank.index')->with('success', 'تم حذف السؤال بنجاح.');
    }
}