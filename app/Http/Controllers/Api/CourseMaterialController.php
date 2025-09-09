<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,pdf,image,other',
            'file' => 'required|file|max:102400', // 100MB max
            'is_free' => 'boolean',
        ]);

        $path = $request->file('file')->store('course-materials', 'public');

        $material = $course->materials()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'file_path' => $path,
            'is_free' => $validated['is_free'] ?? false,
        ]);

        return response()->json($material, 201);
    }

    public function destroy(Course $course, CourseMaterial $material)
    {
        $this->authorize('update', $course);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return response()->json(null, 204);
    }

    public function signedUrl(Course $course, CourseMaterial $material)
    {
        $this->authorize('view', $course);
        $url = \URL::signedRoute('media.serve', [
            'path' => $material->file_path
        ], now()->addMinutes(30));
        return response()->json(['url' => $url]);
    }

    public function markCompleted(Course $course, CourseMaterial $material)
    {
        $user = auth()->user();
        $progress = \App\Models\CourseMaterialProgress::firstOrCreate([
            'user_id' => $user->id,
            'course_material_id' => $material->id,
        ]);
        $progress->completed_at = now();
        $progress->save();
        return response()->json(['message' => 'Material marked as completed']);
    }
}
