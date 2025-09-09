<?php

namespace App\Http\Controllers;

use App\Models\CourseMaterial;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\MaterialCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CourseMaterialController extends Controller
{
    public function index()
    {
        $materials = CourseMaterial::with(['course.instructor', 'level'])
            ->latest()
            ->paginate(20);
            
        return view('course-materials.index', compact('materials'));
    }

    public function create()
    {
        $courses = Course::with('instructor')->get();
        $levels = CourseLevel::all();
        
        return view('course-materials.create', compact('courses', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'nullable|exists:course_levels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,document,quiz,assignment',
            'content_url' => 'nullable|url',
            'file_path' => 'nullable|file|max:102400', // 100MB
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_free' => 'boolean',
        ]);

        $data = $request->except(['file_path']);
        
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('course-materials', 'public');
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize();
        }

        CourseMaterial::create($data);

        return redirect()->route('course-materials.index')
            ->with('success', 'Course material created successfully.');
    }

    public function show(CourseMaterial $courseMaterial)
    {
        $courseMaterial->load(['course.instructor', 'level', 'completions.user']);
        return view('course-materials.show', compact('courseMaterial'));
    }

    public function edit(CourseMaterial $courseMaterial)
    {
        $courses = Course::with('instructor')->get();
        $levels = CourseLevel::all();
        
        return view('course-materials.edit', compact('courseMaterial', 'courses', 'levels'));
    }

    public function update(Request $request, CourseMaterial $courseMaterial)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'nullable|exists:course_levels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,document,quiz,assignment',
            'content_url' => 'nullable|url',
            'file_path' => 'nullable|file|max:102400',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_free' => 'boolean',
        ]);

        $data = $request->except(['file_path']);
        
        if ($request->hasFile('file_path')) {
            // Delete old file
            if ($courseMaterial->file_path) {
                Storage::disk('public')->delete($courseMaterial->file_path);
            }
            
            $file = $request->file('file_path');
            $path = $file->store('course-materials', 'public');
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize();
        }

        $courseMaterial->update($data);

        return redirect()->route('course-materials.index')
            ->with('success', 'Course material updated successfully.');
    }

    public function destroy(CourseMaterial $courseMaterial)
    {
        if ($courseMaterial->file_path) {
            Storage::disk('public')->delete($courseMaterial->file_path);
        }
        
        $courseMaterial->delete();

        return redirect()->route('course-materials.index')
            ->with('success', 'Course material deleted successfully.');
    }

    public function analytics()
    {
        $totalMaterials = CourseMaterial::count();
        $materialsByType = CourseMaterial::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
        
        // Get popular materials with pagination
        $popularMaterials = CourseMaterial::with(['course.instructor'])
            ->withCount('completions')
            ->orderBy('completions_count', 'desc')
            ->paginate(15);
        
        // Transform the data without breaking pagination
        $popularMaterials->getCollection()->transform(function ($material) {
            $material->course_title = optional($material->course)->title ?? 'دورة غير محددة';
            $material->completion_count = $material->completions_count;
            return $material;
        });
            
        return view('course-materials.analytics', compact(
            'totalMaterials', 
            'materialsByType', 
            'popularMaterials'
        ));
    }
}
