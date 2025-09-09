<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseLevelController extends Controller
{
    /**
     * Get all levels for a course
     */
    public function index(Course $course)
    {
        $user = Auth::user();
        
        // Check if user is instructor of this course or admin
        if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $levels = $course->levels()
            ->with(['materials' => function ($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return response()->json($levels);
    }

    /**
     * Create a new level for a course
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();
        
        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'level_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $level = $course->levels()->create([
            'level_name' => $validated['level_name'],
            'description' => $validated['description'],
            'order' => $validated['order'] ?? $course->levels()->count() + 1,
        ]);

        return response()->json($level, 201);
    }

    /**
     * Get a specific level
     */
    public function show(Course $course, CourseLevel $level)
    {
        $user = Auth::user();
        
        // Check if user is instructor of this course or admin
        if ($course->instructor_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure level belongs to this course
        if ($level->course_id !== $course->id) {
            return response()->json(['message' => 'Level not found'], 404);
        }

        $level->load(['materials' => function ($query) {
            $query->orderBy('order');
        }]);

        return response()->json($level);
    }

    /**
     * Update a level
     */
    public function update(Request $request, Course $course, CourseLevel $level)
    {
        $user = Auth::user();
        
        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure level belongs to this course
        if ($level->course_id !== $course->id) {
            return response()->json(['message' => 'Level not found'], 404);
        }

        $validated = $request->validate([
            'level_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $level->update($validated);

        return response()->json($level);
    }

    /**
     * Delete a level
     */
    public function destroy(Course $course, CourseLevel $level)
    {
        $user = Auth::user();
        
        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure level belongs to this course
        if ($level->course_id !== $course->id) {
            return response()->json(['message' => 'Level not found'], 404);
        }

        // Check if level has materials
        if ($level->materials()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete level with materials. Please move or delete materials first.'
            ], 422);
        }

        $level->delete();

        return response()->json(['message' => 'Level deleted successfully']);
    }

    /**
     * Reorder levels
     */
    public function reorder(Request $request, Course $course)
    {
        $user = Auth::user();
        
        // Check if user is instructor of this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'levels' => 'required|array',
            'levels.*.id' => 'required|exists:course_levels,id',
            'levels.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['levels'] as $levelData) {
            CourseLevel::where('id', $levelData['id'])
                ->where('course_id', $course->id)
                ->update(['order' => $levelData['order']]);
        }

        return response()->json(['message' => 'Levels reordered successfully']);
    }
}
