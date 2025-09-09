<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LearningPath;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningPathController extends Controller
{
    public function index(Request $request)
    {
        $paths = LearningPath::with(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.price', 'courses.average_rating', 'courses.total_ratings', 'courses.category_id')
                ->with(['category:id,name,slug', 'instructor:id,name,profile_photo_path']);
        }])->withCount('courses')->orderBy('sort_order')->paginate(20);

        // Check if this is an API request
        if ($request->expectsJson()) {
            $formattedPaths = $paths->getCollection()->map(function ($path) {
                return [
                    'id' => $path->id,
                    'name' => $path->name,
                    'slug' => $path->slug,
                    'description' => $path->description,
                    'is_active' => $path->is_active,
                    'sort_order' => $path->sort_order,
                    'courses_count' => $path->courses_count,
                    'average_rating' => $path->average_rating,
                    'total_price' => $path->total_price,
                    'formatted_price' => $path->formatted_price,
                    'courses' => $path->courses->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'title' => $course->title,
                            'price' => $course->price,
                            'average_rating' => $course->average_rating,
                            'total_ratings' => $course->total_ratings,
                            'category' => $course->category ? [
                                'id' => $course->category->id,
                                'name' => $course->category->name,
                                'slug' => $course->category->slug,
                            ] : null,
                            'instructor' => $course->instructor ? [
                                'id' => $course->instructor->id,
                                'name' => $course->instructor->name,
                                'profile_photo_path' => $course->instructor->profile_photo_path,
                            ] : null,
                        ];
                    }),
                    'created_at' => $path->created_at,
                    'updated_at' => $path->updated_at,
                ];
            });

            return response()->json([
                'data' => $formattedPaths,
                'pagination' => [
                    'current_page' => $paths->currentPage(),
                    'last_page' => $paths->lastPage(),
                    'per_page' => $paths->perPage(),
                    'total' => $paths->total(),
                    'from' => $paths->firstItem(),
                    'to' => $paths->lastItem(),
                ],
            ]);
        }

        return view('learning-paths.index', compact('paths'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get(['id', 'title']);

        return view('learning-paths.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:learning_paths,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'course_ids' => 'sometimes|array',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        $path = LearningPath::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if (! empty($validated['course_ids'])) {
            $sync = collect($validated['course_ids'])->mapWithKeys(fn ($id, $index) => [$id => ['order' => $index + 1]])->toArray();
            $path->courses()->sync($sync);
        }

        return redirect()->route('learning-paths.index')->with('success', 'Learning path created successfully.');
    }

    public function show(Request $request, LearningPath $learning_path)
    {
        $learning_path->load(['courses' => function ($q) {
            $q->select('courses.id', 'courses.title', 'courses.description', 'courses.thumbnail', 'courses.price', 'courses.average_rating', 'courses.total_ratings', 'courses.instructor_id', 'courses.category_id')
                ->with(['instructor:id,name,profile_photo_path', 'category:id,name,slug'])
                ->orderBy('course_learning_path.order');
        }]);

        // Check if this is an API request
        if ($request->expectsJson()) {
            $formattedPath = [
                'id' => $learning_path->id,
                'name' => $learning_path->name,
                'slug' => $learning_path->slug,
                'description' => $learning_path->description,
                'is_active' => $learning_path->is_active,
                'sort_order' => $learning_path->sort_order,
                'average_rating' => $learning_path->average_rating,
                'total_price' => $learning_path->total_price,
                'formatted_price' => $learning_path->formatted_price,
                'courses' => $learning_path->courses->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'thumbnail' => $course->thumbnail,
                        'price' => $course->price,
                        'average_rating' => $course->average_rating,
                        'total_ratings' => $course->total_ratings,
                        'category' => $course->category ? [
                            'id' => $course->category->id,
                            'name' => $course->category->name,
                            'slug' => $course->category->slug,
                        ] : null,
                        'instructor' => $course->instructor ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                            'profile_photo_path' => $course->instructor->profile_photo_path,
                        ] : null,
                    ];
                }),
                'created_at' => $learning_path->created_at,
                'updated_at' => $learning_path->updated_at,
            ];

            return response()->json(['data' => $formattedPath]);
        }

        return view('learning-paths.show', compact('learning_path'));
    }

    public function edit(LearningPath $learning_path)
    {
        $courses = Course::orderBy('title')->get(['id', 'title']);
        $learning_path->load('courses:id,title');

        return view('learning-paths.edit', compact('learning_path', 'courses'));
    }

    public function update(Request $request, LearningPath $learning_path)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:learning_paths,name,'.$learning_path->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'course_ids' => 'sometimes|array',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        if (isset($validated['name'])) {
            $learning_path->name = $validated['name'];
            $learning_path->slug = Str::slug($validated['name']);
        }
        if (array_key_exists('description', $validated)) {
            $learning_path->description = $validated['description'];
        }
        if (array_key_exists('is_active', $validated)) {
            $learning_path->is_active = $validated['is_active'];
        }
        if (array_key_exists('sort_order', $validated)) {
            $learning_path->sort_order = $validated['sort_order'];
        }
        $learning_path->save();

        if (! empty($validated['course_ids'])) {
            $sync = collect($validated['course_ids'])->mapWithKeys(fn ($id, $index) => [$id => ['order' => $index + 1]])->toArray();
            $learning_path->courses()->sync($sync);
        }

        return redirect()->route('learning-paths.index')->with('success', 'Learning path updated successfully.');
    }

    public function destroy(LearningPath $learning_path)
    {
        $learning_path->delete();

        return redirect()->route('learning-paths.index')->with('success', 'Learning path deleted successfully.');
    }
}
