<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Degree;
use App\Models\LearningPath;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        return response()->json($category);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        // Check if category has courses
        if ($category->courses()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with associated courses',
            ], 422);
        }

        $category->delete();

        return response()->json(null, 204);
    }

    /**
     * Get all courses for a specific category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function courses($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $courses = $category->courses()
            ->with(['instructor', 'category'])
            ->latest()
            ->paginate(12);

        return response()->json([
            'category' => $category,
            'courses' => $courses,
        ]);
    }

    /**
     * Get all degrees for a specific category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function degrees($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $degrees = Degree::with(['instructors:id,name,profile_photo_path'])
            ->withCount('courses')
            ->withAvg('courses', 'average_rating')
            ->withAvg('courses', 'price')
            ->whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'category' => $category,
            'degrees' => $degrees,
        ]);
    }

    /**
     * Get all learning paths for a specific category.
     *
     * @param  string  $id  Category ID or slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function learningPaths($id)
    {
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)
                ->firstOrFail();

        $learningPaths = LearningPath::with(['courses' => function ($query) {
            $query->select('courses.id', 'courses.title', 'courses.price', 'courses.average_rating', 'courses.category_id')
                ->with(['category:id,name,slug', 'instructor:id,name,profile_photo_path']);
        }])
            ->where('is_active', true)
            ->whereHas('courses', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->withCount('courses')
            ->orderBy('sort_order')
            ->get();

        $formattedPaths = $learningPaths->map(function ($path) {
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
            ];
        });

        return response()->json([
            'category' => $category,
            'learning_paths' => $formattedPaths,
        ]);
    }
}
