<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\InVideoQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InVideoQuizController extends Controller
{
    /**
     * Get all in-video quizzes for a material
     */
    public function index(Course $course, CourseMaterial $material)
    {
        $user = Auth::user();

        // Check if user is instructor of this course or admin
        if ($course->instructor_id !== $user->id && ! $user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        $quizzes = $material->inVideoQuizzes()
            ->orderBy('order')
            ->get();

        return response()->json($quizzes);
    }

    /**
     * Create a new in-video quiz
     */
    public function store(Request $request, Course $course, CourseMaterial $material)
    {
        $user = Auth::user();

        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        // Ensure material is a video
        if ($material->type !== 'video') {
            return response()->json(['message' => 'In-video quizzes can only be added to video materials'], 422);
        }

        $validated = $request->validate([
            'quiz_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'timestamp' => 'required|string|regex:/^\d{2}:\d{2}:\d{2}$/', // HH:MM:SS format
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string|in:multiple_choice,single_choice,true_false',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice,single_choice|array',
            'questions.*.correct_answer' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'order' => 'nullable|integer|min:0',
        ]);

        $quiz = $material->inVideoQuizzes()->create([
            'quiz_name' => $validated['quiz_name'],
            'description' => $validated['description'],
            'timestamp' => $validated['timestamp'],
            'questions' => $validated['questions'],
            'questions_count' => count($validated['questions']),
            'order' => $validated['order'] ?? $material->inVideoQuizzes()->count() + 1,
        ]);

        return response()->json($quiz, 201);
    }

    /**
     * Get a specific in-video quiz
     */
    public function show(Course $course, CourseMaterial $material, InVideoQuiz $quiz)
    {
        $user = Auth::user();

        // Check if user is instructor of this course or admin
        if ($course->instructor_id !== $user->id && ! $user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        // Ensure quiz belongs to this material
        if ($quiz->material_id !== $material->id) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        return response()->json($quiz);
    }

    /**
     * Update an in-video quiz
     */
    public function update(Request $request, Course $course, CourseMaterial $material, InVideoQuiz $quiz)
    {
        $user = Auth::user();

        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        // Ensure quiz belongs to this material
        if ($quiz->material_id !== $material->id) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $validated = $request->validate([
            'quiz_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'timestamp' => 'sometimes|required|string|regex:/^\d{2}:\d{2}:\d{2}$/',
            'questions' => 'sometimes|required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string|in:multiple_choice,single_choice,true_false',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice,single_choice|array',
            'questions.*.correct_answer' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['questions'])) {
            $validated['questions_count'] = count($validated['questions']);
        }

        $quiz->update($validated);

        return response()->json($quiz);
    }

    /**
     * Delete an in-video quiz
     */
    public function destroy(Course $course, CourseMaterial $material, InVideoQuiz $quiz)
    {
        $user = Auth::user();

        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        // Ensure quiz belongs to this material
        if ($quiz->material_id !== $material->id) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $quiz->delete();

        return response()->json(['message' => 'In-video quiz deleted successfully']);
    }

    /**
     * Reorder in-video quizzes for a material
     */
    public function reorder(Request $request, Course $course, CourseMaterial $material)
    {
        $user = Auth::user();

        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        $validated = $request->validate([
            'quizzes' => 'required|array',
            'quizzes.*.id' => 'required|exists:in_video_quizzes,id',
            'quizzes.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['quizzes'] as $quizData) {
            InVideoQuiz::where('id', $quizData['id'])
                ->where('material_id', $material->id)
                ->update(['order' => $quizData['order']]);
        }

        return response()->json(['message' => 'In-video quizzes reordered successfully']);
    }

    /**
     * Submit answers for an in-video quiz (Student endpoint)
     */
    public function submitAnswers(Request $request, Course $course, CourseMaterial $material, InVideoQuiz $quiz)
    {
        $user = Auth::user();

        // Check if user is enrolled in this course
        if (! $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You must be enrolled in this course to take the quiz'], 403);
        }

        // Ensure material belongs to this course
        if ($material->course_id !== $course->id) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        // Ensure quiz belongs to this material
        if ($quiz->material_id !== $material->id) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        // Check if quiz is active
        if (! $quiz->is_active) {
            return response()->json(['message' => 'This quiz is not available'], 400);
        }

        // Validate request
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required', // Can be string, array, or other types based on question type
        ]);

        // Check if user already answered this quiz
        $existingAnswer = \App\Models\InVideoQuizAnswer::where('user_id', $user->id)
            ->where('in_video_quiz_id', $quiz->id)
            ->first();

        if ($existingAnswer) {
            return response()->json(['message' => 'You have already completed this quiz'], 400);
        }

        // Create quiz answer record
        $quizAnswer = \App\Models\InVideoQuizAnswer::create([
            'user_id' => $user->id,
            'in_video_quiz_id' => $quiz->id,
            'course_material_id' => $material->id,
            'course_id' => $course->id,
            'answers' => $validated['answers'],
        ]);

        // Calculate score
        $quizAnswer->calculateScore();

        // Return results
        return response()->json([
            'message' => 'Quiz submitted successfully',
            'result' => [
                'id' => $quizAnswer->id,
                'score' => $quizAnswer->score,
                'percentage' => $quizAnswer->percentage,
                'correct_answers' => $quizAnswer->correct_answers,
                'total_questions' => $quizAnswer->total_questions,
                'is_passed' => $quizAnswer->is_passed,
                'completed_at' => $quizAnswer->completed_at,
            ],
        ]);
    }
}
