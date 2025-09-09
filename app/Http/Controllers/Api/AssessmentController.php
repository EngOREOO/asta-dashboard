<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // إضافة هذا السطر


class AssessmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Assessment::with(['course', 'creator']);

        if ($request->user()->hasRole('student')) {
            $query->whereHas('course.students', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        } elseif ($request->user()->hasRole('instructor')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('instructor_id', $request->user()->id);
            });
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Assessment::class);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', Rule::in(['quiz', 'exam', 'assignment'])],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($request->user()->hasRole('instructor')) {
            $this->authorize('update', $course);
        }

        $assessment = Assessment::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($assessment->load(['course', 'creator']), 201);
    }

    public function show(Assessment $assessment)
    {
        $this->authorize('view', $assessment);

        return response()->json($assessment->load(['course', 'creator', 'questions']));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $this->authorize('update', $assessment);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes', Rule::in(['quiz', 'exam', 'assignment'])],
        ]);

        $assessment->update($validated);

        return response()->json($assessment->load(['course', 'creator']));
    }

    public function destroy(Assessment $assessment)
    {
        $this->authorize('delete', $assessment);

        $assessment->delete();

        return response()->json(null, 204);
    }

    public function assign(Request $request, Assessment $assessment)
    {
        $this->authorize('assign', $assessment);

        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $assessment->assignments()->createMany(
            collect($validated['user_ids'])->map(fn ($userId) => [
                'user_id' => $userId,
                'assigned_by' => $request->user()->id,
                'assigned_at' => now(),
            ])
        );

        return response()->json(['message' => 'Assessment assigned successfully']);
    }
}
