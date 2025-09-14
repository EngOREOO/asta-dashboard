<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\CourseMaterial;
use App\Models\User;

class CourseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $baseQuery = auth()->user()->hasAnyRole(['admin', 'super-admin'])
            ? Course::query()
            : Course::query()->where('instructor_id', auth()->id());

        $baseQuery = $baseQuery
            ->with(['instructor', 'category', 'coupons'])
            ->withCount('students');

        // Filters: category
        if ($request->filled('category_id')) {
            $baseQuery->where('category_id', $request->integer('category_id'));
        }

        // Filters: price range
        if ($request->filled('price_min')) {
            $baseQuery->where('price', '>=', (float) $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $baseQuery->where('price', '<=', (float) $request->input('price_max'));
        }

        // Filter: instructor (admin only)
        if (auth()->user()->hasAnyRole(['admin', 'super-admin']) && $request->filled('instructor_id')) {
            $baseQuery->where('instructor_id', $request->integer('instructor_id'));
        }

        // Filters: status
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->input('status'));
        }

        // Sorting by students count
        if ($request->filled('students_order') && in_array($request->input('students_order'), ['asc','desc'], true)) {
            $baseQuery->orderBy('students_count', $request->input('students_order'));
        } else {
            $baseQuery->latest();
        }

        // Fetch all pre-filtered results (query-level filters), then apply discount filter in-memory
        $collection = $baseQuery->get();

        if ($request->filled('discount')) {
            if ($request->input('discount') === 'yes') {
                $collection = $collection->filter(function (Course $c) {
                    $original = (float) ($c->price ?? 0);
                    return $original > 0 && (float) $c->discounted_price < $original;
                });
            } elseif ($request->input('discount') === 'no') {
                $collection = $collection->filter(function (Course $c) {
                    $original = (float) ($c->price ?? 0);
                    return !($original > 0 && (float) $c->discounted_price < $original);
                });
            }
        }

        // Manual pagination after in-memory filtering
        $perPage = 20;
        $currentPage = (int) ($request->input('page', 1));
        $items = $collection->forPage($currentPage, $perPage)->values();
        $courses = new LengthAwarePaginator($items, $collection->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Categories and instructors for filter UI
        $categories = \App\Models\Category::orderBy('name')->get(['id','name']);
        $instructors = auth()->user()->hasAnyRole(['admin', 'super-admin'])
            ? User::role(['instructor','admin'])->orderBy('name')->get(['id','name'])
            : collect();

        return view('courses.index', [
            'courses' => $courses,
            'categories' => $categories,
            'instructors' => $instructors,
            'filters' => $request->only(['category_id','price_min','price_max','discount','status','students_order','instructor_id']),
        ]);
    }

    public function requests()
    {
        // Show unpublished courses (draft, pending, rejected) with instructor names
        $courseRequests = Course::with(['instructor', 'category'])
            ->whereIn('status', ['draft', 'pending', 'rejected'])
            ->latest()
            ->paginate(20);

        return view('courses.requests', [
            'courseRequests' => $courseRequests,
        ]);
    }

    public function create()
    {
        $careerLevels = \App\Models\Degree::orderBy('name')->get(['id','name']);
        $instructors = \App\Models\User::role('instructor')->orderBy('name')->get(['id','name']);
        $isAdmin = auth()->user()->hasRole('admin');
        return view('courses.create', compact('careerLevels', 'instructors', 'isAdmin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'awarding_institution' => 'nullable|string|max:255',
            'difficulty_level' => 'nullable|string|in:beginner,intermediate,advanced',
            'category_id' => 'nullable|integer|exists:categories,id',
            'instructor_id' => 'required|integer|exists:users,id',
            'image' => 'nullable|image|max:2048',
            // new: degrees from checkboxes
            'degree_ids' => 'sometimes|array',
            'degree_ids.*' => 'integer|exists:degrees,id',
            'learning_path_ids' => 'sometimes|array',
            'learning_path_ids.*' => 'integer|exists:learning_paths,id',

            // Wizard Step 2: Lessons
            'lessons' => 'sometimes|array',
            'lessons.title' => 'sometimes|array',
            'lessons.type' => 'sometimes|array',
            'lessons.video' => 'sometimes|array',
            'lessons.title.*' => 'nullable|string|max:255',
            'lessons.type.*' => 'nullable|in:video,quiz',
            'lessons.video.*' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:512000',

            // Wizard Step 3: Materials
            'materials' => 'sometimes|array',
            'materials.file' => 'sometimes|array',
            'materials.file.*' => 'nullable|file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx|max:102400',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/courses'), $filename);
            $validated['thumbnail'] = 'images/courses/'.$filename;
            unset($validated['image']); // Remove image key since we store as thumbnail
        }

        // Only set instructor_id to current user if it's not already set from the form
        if (!isset($validated['instructor_id'])) {
            $validated['instructor_id'] = auth()->id();
        }
        
        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'draft';
        }

        $course = Course::create($validated);
        if (! empty($validated['learning_path_ids'])) {
            $sync = collect($validated['learning_path_ids'])->mapWithKeys(fn ($id, $index) => [$id => ['order' => $index + 1]])->toArray();
            $course->learningPaths()->sync($sync);
        }

        // Attach degrees (learning tracks)
        if (! empty($validated['degree_ids'])) {
            if (method_exists($course, 'degrees')) {
                $course->degrees()->sync($validated['degree_ids']);
            } else {
                // fallback: if no relation, store primary degree_id
                $course->update(['degree_id' => $validated['degree_ids'][0] ?? null]);
            }
        }

        // Persist Step 2: Lessons (as CourseMaterial)
        $orderCounter = 1;
        if ($request->has('lessons')) {
            $titles = $request->input('lessons.title', []);
            $types = $request->input('lessons.type', []);
            $videos = $request->file('lessons.video', []);

            $max = max(count($titles), count($types), count($videos));
            for ($i = 0; $i < $max; $i++) {
                $title = $titles[$i] ?? null;
                $type = $types[$i] ?? 'video';
                $filePath = null;

                if ($type === 'video' && isset($videos[$i]) && $videos[$i]) {
                    $filePath = Storage::disk('public')->putFile('course-materials/videos', $videos[$i]);
                }

                CourseMaterial::create([
                    'course_id' => $course->id,
                    'title' => $title ?: ($type === 'video' ? 'درس فيديو' : 'اختبار'),
                    'type' => $type === 'quiz' ? 'quiz' : 'video',
                    'file_path' => $filePath,
                    'order' => $orderCounter++,
                    'is_active' => true,
                ]);
            }
        }

        // Persist Step 3: Materials (documents)
        if ($request->hasFile('materials.file')) {
            foreach ($request->file('materials.file', []) as $file) {
                if (! $file) { continue; }
                $stored = Storage::disk('public')->putFile('course-materials/docs', $file);
                CourseMaterial::create([
                    'course_id' => $course->id,
                    'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'type' => 'document',
                    'file_path' => $stored,
                    'order' => $orderCounter++,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        return view('courses.show', [
            'course' => $course->load(['materials', 'instructor', 'category']),
        ]);
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $learningPaths = \App\Models\LearningPath::orderBy('name')->get(['id', 'name']);
        $instructors = \App\Models\User::role('instructor')->orderBy('name')->get(['id','name']);
        $isAdmin = auth()->user()->hasRole('admin');

        return view('courses.edit', [
            'course' => $course->load(['materials', 'instructor', 'category', 'learningPaths']),
            'learningPaths' => $learningPaths,
            'instructors' => $instructors,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,'.$course->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|numeric|min:0',
            'difficulty_level' => 'nullable|string|in:beginner,intermediate,advanced',
            'category_id' => 'nullable|integer|exists:categories,id',
            'instructor_id' => 'required|integer|exists:users,id',
            'duration_days' => 'nullable|integer|min:1',
            'awarding_institution' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:draft,pending,approved,rejected',
            'image' => 'nullable|image|max:2048',
            'learning_path_ids' => 'sometimes|array',
            'learning_path_ids.*' => 'integer|exists:learning_paths,id',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($course->thumbnail && file_exists(public_path($course->thumbnail))) {
                unlink(public_path($course->thumbnail));
            }

            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/courses'), $filename);
            $validated['thumbnail'] = 'images/courses/'.$filename;
            unset($validated['image']); // Remove image key since we store as thumbnail
        }

        // Handle submit for approval
        if ($request->has('submit_for_approval')) {
            $validated['status'] = 'pending';
        }

        $course->update($validated);
        if ($request->has('learning_path_ids')) {
            $ids = $validated['learning_path_ids'] ?? [];
            $sync = collect($ids)->mapWithKeys(fn ($id, $index) => [$id => ['order' => $index + 1]])->toArray();
            $course->learningPaths()->sync($sync);
        }

        if ($request->has('submit_for_approval')) {
            return redirect()->route('courses.show', $course)
                ->with('success', 'Course submitted for approval successfully.');
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function submitForApproval(Course $course)
    {
        $this->authorize('update', $course);

        $course->update(['status' => 'pending']);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course submitted for approval.');
    }

    public function approve(Course $course)
    {
        $this->authorize('approve', $course);

        $course->update(['status' => 'approved']);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course approved successfully.');
    }

    public function reject(Request $request, Course $course)
    {
        $this->authorize('approve', $course);

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $course->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course rejected successfully.');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
