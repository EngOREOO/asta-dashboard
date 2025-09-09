<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningPath;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningPathController extends Controller
{
    public function index(Request $request)
    {
        $query = LearningPath::query()
            ->where('is_active', true)
            ->with(['courses' => function ($query) {
                $query->select('courses.id', 'courses.title', 'courses.price', 'courses.average_rating', 'courses.total_ratings', 'courses.category_id')
                    ->with(['category:id,name,slug', 'instructor:id,name,profile_photo_path']);
            }])
            ->withCount('courses')
            ->orderBy('sort_order');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $categoryId = $request->category;
            $query->whereHas('courses', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $paths = $query->get(['id', 'name', 'slug', 'description', 'image', 'is_active', 'sort_order']);

        $formattedPaths = $paths->map(function ($path) {
            return [
                'id' => $path->id,
                'name' => $path->name,
                'slug' => $path->slug,
                'description' => $path->description,
                'image' => $path->image,
                'image_url' => $path->image_url,
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

        return response()->json($formattedPaths);
    }

    public function show(LearningPath $learning_path)
    {
        $learning_path->load(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.slug', 'courses.price', 'courses.thumbnail', 'courses.average_rating', 'courses.total_ratings', 'courses.instructor_id', 'courses.category_id')
                ->with(['instructor:id,name,profile_photo_path', 'category:id,name,slug'])
                ->orderBy('course_learning_path.order');
        }]);

        $formattedPath = [
            'id' => $learning_path->id,
            'name' => $learning_path->name,
            'slug' => $learning_path->slug,
            'description' => $learning_path->description,
            'image' => $learning_path->image,
            'image_url' => $learning_path->image_url,
            'is_active' => $learning_path->is_active,
            'sort_order' => $learning_path->sort_order,
            'average_rating' => $learning_path->average_rating,
            'total_price' => $learning_path->total_price,
            'formatted_price' => $learning_path->formatted_price,
            'courses' => $learning_path->courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
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

        return response()->json($formattedPath);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:learning_paths,name',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'course_ids' => 'sometimes|array',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $path = LearningPath::create($validated);

        if (! empty($validated['course_ids'])) {
            $sync = collect($validated['course_ids'])->mapWithKeys(fn ($id, $index) => [$id => ['order' => $index + 1]])->toArray();
            $path->courses()->sync($sync);
        }

        return response()->json($path->load(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.slug', 'courses.price', 'courses.average_rating', 'courses.total_ratings')
                ->orderBy('course_learning_path.order');
        }]), 201);
    }

    public function update(Request $request, LearningPath $learning_path)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:learning_paths,name,'.$learning_path->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
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
        if (array_key_exists('image', $validated)) {
            $learning_path->image = $validated['image'];
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

        return response()->json($learning_path->load(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.slug', 'courses.price', 'courses.average_rating', 'courses.total_ratings')
                ->orderBy('course_learning_path.order');
        }]));
    }

    public function destroy(LearningPath $learning_path)
    {
        $learning_path->delete();

        return response()->json(['message' => 'Learning path deleted']);
    }
}
