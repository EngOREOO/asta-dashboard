<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseComment;
use App\Models\CourseMaterialProgress;
use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get a filtered list of courses based on various criteria.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category', 'degree'])
            ->where('status', 'approved'); // Only approved by default

        // ===== Filter: my_comments (courses I commented on) =====
        if ($request->boolean('my_comments')) {
            if (! auth('sanctum')->check()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            $userId = auth('sanctum')->id();
            $query->whereHas('comments', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        // Filter by degree (academic level)
        if ($request->filled('degree_id')) {
            $query->where('degree_id', $request->degree_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price (free/paid)
        if ($request->boolean('is_free')) {
            $query->where('price', 0);
        }

        // Ordering
        switch ($request->get('sort', 'newest')) {
            case 'top_rated':
                $query->orderBy('average_rating', 'desc')
                    ->orderBy('total_ratings', 'desc');
                break;
            case 'most_popular':
                $query->withCount('students')
                    ->orderBy('students_count', 'desc');
                break;
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
        }

        // Visibility by role
        if (auth('sanctum')->check() && auth('sanctum')->user()->hasRole('admin')) {
            $query->withTrashed();
        } elseif (auth('sanctum')->check() && auth('sanctum')->user()->hasRole('instructor')) {
            $query->where('instructor_id', auth('sanctum')->id())
                ->withTrashed();
        }

        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        $courses = $query->paginate($perPage);

        return response()->json($courses);
    }

    /**
     * Get recently added courses
     */
    public function recent()
    {
        $courses = Course::with(['instructor', 'category', 'degree'])
            ->where('status', 'approved')
            ->latest()
            ->take(10)
            ->get();

        return response()->json($courses);
    }

    /**
     * Get top rated courses
     */
    public function topRated()
    {
        $courses = Course::with(['instructor', 'category', 'degree'])
            ->where('status', 'approved')
            ->where('average_rating', '>=', 4.0) // Only highly rated courses
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->take(10)
            ->get();

        return response()->json($courses);
    }

    /**
     * Get free courses
     */
    public function free()
    {
        $courses = Course::with(['instructor', 'category', 'degree'])
            ->where('status', 'approved')
            ->where('price', 0)
            ->latest()
            ->take(10)
            ->get();

        return response()->json($courses);
    }

    /**
     * Get most popular courses based on enrollment
     */
    public function popular()
    {
        $courses = Course::with(['instructor', 'category', 'degree'])
            ->where('status', 'approved')
            ->withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(10)
            ->get();

        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'degree_id' => 'nullable|exists:degrees,id',
            'category_id' => 'required|exists:categories,id',
            'learning_path_ids' => 'sometimes|array',
            'learning_path_ids.*' => 'integer|exists:learning_paths,id',
        ]);

        $course = Course::create($validated);
        if (! empty($validated['learning_path_ids'])) {
            $sync = collect($validated['learning_path_ids'])->mapWithKeys(fn ($id, $index) => [$id => ['order' => $index + 1]])->toArray();
            $course->learningPaths()->sync($sync);
        }

        return response()->json($course->load(['learningPaths:id,name,slug']), 201);
    }

    public function show(Course $course)
    {
        $user = auth()->user();

        // Load course with all relationships
        $course->load([
            'instructor',
            'category',
            'degree',
            'levels.materials.inVideoQuizzes' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'materials' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'quizzes.questions' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'reviews' => function ($query) {
                $query->with('user:id,name,profile_photo_path')
                    ->where('is_approved', true)
                    ->latest()
                    ->take(10);
            },
            'comments.user:id,name,profile_photo_path',
        ]);

        // Calculate course statistics
        $totalMaterials = $course->materials->count();
        $totalDuration = $course->materials->sum('duration') ?? ($course->duration ?? 0);
        $totalQuizzes = $course->quizzes ? $course->quizzes->count() : 0;
        $totalLevels = $course->levels->count();

        // Get user progress if authenticated
        $userProgress = null;
        $isEnrolled = false;

        if ($user) {
            $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();

            if ($isEnrolled) {
                $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $course->materials->pluck('id'))
                    ->where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $overallProgress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100, 2) : 0;

                $userProgress = [
                    'overallPercent' => $overallProgress,
                    'completed' => $overallProgress >= 100,
                    'lastLessonId' => $this->getLastAccessedLesson($course, $user),
                ];
            }
        }

        // Prepare instructor information
        $instructorInfo = $course->instructor ? [
            'id' => 'inst_'.$course->instructor->id,
            'name' => $course->instructor->name,
            'avatarUrl' => $course->instructor->profile_photo_url,
            'ratingAvg' => round($course->instructor->instructorRatings()->avg('rating') ?? 0, 1),
            'ratingCount' => $course->instructor->instructorRatings()->count(),
        ] : null;

        // Prepare course overview
        $overview = [
            'description' => $course->description,
            'level' => $this->getDifficultyLevelInEnglish($course->difficulty_level ?? 'intermediate'),
            'estimatedHours' => round($totalDuration / 60, 1),
        ];

        // Prepare course stats
        $stats = [
            'topicsCount' => $totalLevels,
            'lessonsCount' => $totalMaterials,
            'materialsCount' => $totalMaterials,
            'quizzesCount' => $totalQuizzes,
        ];

        // Prepare topics (levels) with lessons
        $topics = $course->levels->map(function ($level) use ($course, $user) {
            $levelMaterials = $level->materials;
            $totalLessons = $levelMaterials->count();

            if ($user && $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
                $completedLessons = CourseMaterialProgress::whereIn('course_material_id', $levelMaterials->pluck('id'))
                    ->where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $topicProgress = [
                    'percent' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0,
                    'completedLessons' => $completedLessons,
                    'totalLessons' => $totalLessons,
                    'completed' => $completedLessons >= $totalLessons,
                ];
            } else {
                $topicProgress = [
                    'percent' => 0,
                    'completedLessons' => 0,
                    'totalLessons' => $totalLessons,
                    'completed' => false,
                ];
            }

            $lessons = $levelMaterials->map(function ($material) use ($user, $course, $level) {
                $lessonData = [
                    'id' => 'lesson_'.$level->id.'_'.$material->id,
                    'type' => $this->getLessonType($material),
                    'title' => $material->title,
                    'order' => $material->order,
                    'durationSec' => $material->duration ?? 0,
                    'materials' => $this->getLessonMaterials($material),
                    'allowComments' => $course->allow_comments ?? false,
                ];

                // Add video information if it's a video
                if ($material->type === 'video') {
                    $lessonData['video'] = [
                        'url' => $material->file_path ? asset($material->file_path) : null,
                        'poster' => $course->thumbnail ? asset($course->thumbnail) : null,
                    ];

                    // Check if this video has in-video questions and add them
                    if ($material->inVideoQuizzes && $material->inVideoQuizzes->isNotEmpty()) {
                        $lessonData['inVideoQuestions'] = $this->formatInVideoQuizzes($material->inVideoQuizzes);
                    }
                }

                // Add quiz information if it's a quiz
                if ($material->type === 'quiz' || ($material->quizzes && $material->quizzes->isNotEmpty())) {
                    $lessonData['type'] = 'quiz';
                    $lessonData['quiz'] = $this->formatQuizData($material);
                }

                // Add progress information
                if ($user && $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
                    $isCompleted = CourseMaterialProgress::where('course_material_id', $material->id)
                        ->where('user_id', $user->id)
                        ->whereNotNull('completed_at')
                        ->exists();

                    $lessonData['progress'] = [
                        'percent' => $isCompleted ? 100 : 0,
                        'completed' => $isCompleted,
                    ];
                } else {
                    $lessonData['progress'] = [
                        'percent' => 0,
                        'completed' => false,
                    ];
                }

                return $lessonData;
            });

            return [
                'id' => 'topic_'.$level->id,
                'title' => $level->level_name,
                'order' => $level->order,
                'progress' => $topicProgress,
                'lessons' => $lessons,
            ];
        });

        // Calculate total course progress
        $totalProgress = 0;
        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($topics as $topic) {
            $totalLessons += $topic['progress']['totalLessons'];
            $completedLessons += $topic['progress']['completedLessons'];
        }

        if ($totalLessons > 0) {
            $totalProgress = round(($completedLessons / $totalLessons) * 100);
        }

        // Prepare ratings
        $ratings = [
            'course' => [
                'average' => round($course->average_rating ?? 0, 1),
                'count' => $course->total_ratings ?? 0,
            ],
            'instructor' => [
                'average' => round($course->instructor->instructorRatings()->avg('rating') ?? 0, 1),
                'count' => $course->instructor->instructorRatings()->count(),
            ],
        ];

        // Format comments with proper target structure
        $comments = collect();

        // Add comments from the comments table
        $comments = $comments->merge($course->comments->map(function ($comment) use ($course) {
            $target = [
                'type' => 'course',
                'id' => 'course_'.$course->id,
            ];

            // If comment has level_id, it's a level comment
            if ($comment->level_id) {
                $target = [
                    'type' => 'level',
                    'id' => 'topic_'.$comment->level_id,
                ];
            }

            // If comment has material_id, it's a lesson comment
            if ($comment->material_id) {
                $target = [
                    'type' => 'lesson',
                    'id' => 'lesson_'.$comment->material_id,  // Fixed: was showing course_1 instead of lesson_ID
                ];
            }

            return [
                'id' => 'cmt_'.$comment->id,
                'userId' => 'u_'.$comment->user_id,
                'target' => $target,
                'text' => $comment->text,
                'createdAt' => $comment->created_at->toISOString(),
                'user' => [
                    'id' => 'u_'.$comment->user_id,
                    'name' => $comment->user->name ?? 'Unknown User',
                    'avatarUrl' => $comment->user->profile_photo_path ? asset('storage/'.$comment->user->profile_photo_path) : null,
                ],
                'instructorReply' => $comment->instructor_reply ? [
                    'text' => $comment->instructor_reply,
                    'repliedAt' => $comment->replied_at ? $comment->replied_at->toISOString() : now()->toISOString(),
                    'instructor' => [
                        'id' => 'inst_'.$course->instructor_id,
                        'name' => $course->instructor->name ?? 'Unknown Instructor',
                        'avatarUrl' => $course->instructor->profile_photo_url,
                    ],
                ] : null,
            ];
        }));

        // Add comments from ratings (course comments)
        $ratingComments = $course->ratings()
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->with('user:id,name,profile_photo_path')
            ->get()
            ->map(function ($rating) use ($course) {
                return [
                    'id' => 'rating_cmt_'.$rating->id,
                    'userId' => 'u_'.$rating->user_id,
                    'target' => [
                        'type' => 'course',
                        'id' => 'course_'.$course->id,
                    ],
                    'text' => $rating->comment,
                    'createdAt' => $rating->created_at->toISOString(),
                    'user' => [
                        'id' => 'u_'.$rating->user_id,
                        'name' => $rating->user->name ?? 'Unknown User',
                        'avatarUrl' => $rating->user->profile_photo_path ? asset('storage/'.$rating->user->profile_photo_path) : null,
                    ],
                    'instructorReply' => null, // Ratings don't have instructor replies
                ];
            });

        $comments = $comments->merge($ratingComments)->sortByDesc('createdAt')->values();

        // Filter comments if "my comments only" is requested
        if (request()->has('my_comments') && request()->boolean('my_comments') && $user) {
            $comments = $comments->filter(function ($comment) use ($user) {
                return $comment['userId'] === 'u_'.$user->id;
            })->values();
        }

        // Prepare filters
        $filters = [
            'comments' => [
                'byScope' => ['course', 'level', 'lesson'],
                'myCommentsOnly' => request()->boolean('my_comments', false), // Can be toggled via query parameter
            ],
            'ratings' => [
                'targets' => ['course', 'instructor'],
            ],
        ];

        return response()->json([
            'course' => [
                'id' => 'course_'.$course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'total_progress' => $totalProgress.'%',
                'instructor' => $instructorInfo,
                'overview' => $overview,
                'progress' => $userProgress,
                'stats' => $stats,
                'topics' => $topics,
                'ratings' => $ratings,
                'comments' => $comments,
                'filters' => $filters,
            ],
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('thumbnail')->store('course-thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return response()->json($course);
    }

    public function submitForApproval(Course $course)
    {
        $this->authorize('update', $course);

        $course->update(['status' => 'pending']);

        return response()->json(['message' => 'Course submitted for approval']);
    }

    public function approve(Course $course)
    {

        $this->authorize('approve', $course);

        $course->update(['status' => 'approved']);

        return response()->json(['message' => 'Course approved successfully']);
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

        return response()->json(['message' => 'Course rejected successfully']);
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return response()->json(null, 204);
    }

    public function enroll(Course $course)
    {
        $user = auth()->user();
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 409);
        }
        $user->enrolledCourses()->attach($course->id, ['enrolled_at' => now()]);

        return response()->json(['message' => 'Enrolled successfully']);
    }

    public function myCourses()
    {
        $user = auth()->user();
        $courses = $user->enrolledCourses()->with('instructor')->get();

        return response()->json($courses);
    }

    public function progress(Course $course)
    {
        $user = auth()->user();
        $materials = $course->materials()->count();
        $completed = \App\Models\CourseMaterialProgress::whereIn('course_material_id', $course->materials()->pluck('id'))
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();
        $percent = $materials > 0 ? round(($completed / $materials) * 100, 2) : 0;

        return response()->json([
            'total_materials' => $materials,
            'completed' => $completed,
            'percent' => $percent,
        ]);
    }

    /**
     * Update course settings (instructor only)
     */
    public function updateSettings(Request $request, Course $course)
    {
        $user = auth()->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'allow_comments' => 'boolean',
            'allow_notes' => 'boolean',
            'allow_ratings' => 'boolean',
            'overview' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'difficulty_level' => 'nullable|string|in:beginner,intermediate,advanced',
            'language' => 'nullable|string',
            'estimated_duration' => 'nullable|integer', // in minutes
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course settings updated successfully',
            'course' => $course->fresh(),
        ]);
    }

    /**
     * Get course settings (instructor only)
     */
    public function getSettings(Course $course)
    {
        $user = auth()->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'allow_comments' => $course->allow_comments ?? false,
            'allow_notes' => $course->allow_notes ?? false,
            'allow_ratings' => $course->allow_ratings ?? false,
            'overview' => $course->overview,
            'prerequisites' => $course->prerequisites,
            'learning_objectives' => $course->learning_objectives,
            'target_audience' => $course->target_audience,
            'difficulty_level' => $course->difficulty_level,
            'language' => $course->language,
            'estimated_duration' => $course->estimated_duration,
        ]);
    }

    /**
     * Add quiz to course material
     */
    public function addQuiz(Request $request, Course $course)
    {
        $user = auth()->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'material_id' => 'required|exists:course_materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string|in:multiple_choice,single_choice,true_false',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice,single_choice|array',
            'questions.*.correct_answer' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1', // in minutes
            'passing_score' => 'required|integer|min:1',
        ]);

        // Create assessment for the quiz
        $assessment = Assessment::create([
            'course_id' => $course->id,
            'created_by' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => 'quiz',
            'time_limit' => $validated['time_limit'],
            'passing_score' => $validated['passing_score'],
            'questions' => $validated['questions'],
        ]);

        return response()->json([
            'message' => 'Quiz added successfully',
            'assessment' => $assessment,
        ], 201);
    }

    /**
     * Get course comments (if allowed)
     */
    /**
     * Get course materials
     */
    public function materials(Course $course)
    {
        $user = auth('sanctum')->user();

        // Manual authorization check
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Ensure user roles are loaded
        $user->load('roles');

        // Check if user can view the course
        if (! $user->can('view', $course)) {
            return response()->json([
                'message' => 'This action is unauthorized.',
                'debug' => [
                    'user_id' => $user->id,
                    'user_roles' => $user->roles->pluck('name'),
                    'course_id' => $course->id,
                    'course_status' => $course->status,
                    'course_instructor_id' => $course->instructor_id,
                ],
            ], 403);
        }

        $materials = $course->materials()
            ->orderBy('order')
            ->get()
            ->map(function ($material) {
                $data = [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'type' => $material->type,
                    'file_path' => $material->file_path === 'course-materials/course-28/welcome-video.mp4' ? 'video/Ta-Hamp4.mp4' : $material->file_path,
                    'order' => $material->order,
                    'is_free' => $material->is_free,
                    'duration' => $material->duration,
                    'file_size' => $material->file_size,
                ];

                // Add progress info if user is authenticated
                if (auth('sanctum')->check()) {
                    $progress = $material->progress()
                        ->where('user_id', auth('sanctum')->id())
                        ->first();

                    $data['progress'] = [
                        'is_completed' => $progress && $progress->completed_at !== null,
                        'completed_at' => $progress ? $progress->completed_at : null,
                        'last_accessed' => $progress ? $progress->updated_at : null,
                    ];
                }

                return $data;
            });

        return response()->json($materials);
    }

    public function getComments(Course $course)
    {
        if (! $course->allow_comments) {
            return response()->json(['message' => 'Comments are disabled for this course'], 403);
        }

        $comments = $course->comments()
            ->with('user:id,name,profile_photo_path')
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }

    /**
     * Get course notes (student's own notes)
     */
    public function getNotes(Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $course->allow_notes) {
            return response()->json(['message' => 'Notes are disabled for this course'], 403);
        }

        $notes = $course->notes()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json($notes);
    }

    /**
     * Add note to course
     */
    public function addNote(Request $request, Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $course->allow_notes) {
            return response()->json(['message' => 'Notes are disabled for this course'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'material_id' => 'nullable|exists:course_materials,id',
        ]);

        $note = $course->notes()->create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'material_id' => $validated['material_id'],
        ]);

        return response()->json([
            'message' => 'Note added successfully',
            'note' => $note,
        ], 201);
    }

    /**
     * Rate course and instructor with comments and replies support
     */
    public function rateCourse(Request $request, Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $course->allow_ratings) {
            return response()->json(['message' => 'Ratings are disabled for this course'], 403);
        }

        // Check if user is enrolled (for students) or is instructor (for instructors)
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        $isInstructor = $user->id === $course->instructor_id;

        if (! $isEnrolled && ! $isInstructor) {
            return response()->json(['message' => 'You must be enrolled to rate this course'], 403);
        }

        $validated = $request->validate([
            'course_rating' => 'nullable|integer|min:1|max:5',
            'instructor_rating' => 'nullable|integer|min:1|max:5',
            'course_comment' => 'nullable|string|max:1000',
            'instructor_comment' => 'nullable|string|max:1000',
        ]);

        $response = ['message' => 'Rating submitted successfully'];

        // Handle course rating and comment
        if (isset($validated['course_rating']) || isset($validated['course_comment'])) {
            $courseRating = $course->ratings()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'rating' => $validated['course_rating'] ?? 0,
                    'comment' => $validated['course_comment'] ?? null,
                ]
            );
            $response['course_rating'] = $courseRating;
        }

        // Handle instructor rating and comment
        if (isset($validated['instructor_rating']) || isset($validated['instructor_comment'])) {
            $instructorRating = $course->instructor->instructorRatings()->updateOrCreate(
                ['user_id' => $user->id, 'course_id' => $course->id],
                [
                    'rating' => $validated['instructor_rating'] ?? 0,
                    'comment' => $validated['instructor_comment'] ?? null,
                ]
            );
            $response['instructor_rating'] = $instructorRating;
        }

        return response()->json($response);
    }

    /**
     * Get course progress with detailed breakdown
     */
    public function getDetailedProgress(Course $course)
    {
        $user = auth()->user();

        // Check if enrolled
        if (! $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Not enrolled in this course'], 403);
        }

        $materials = $course->materials()->with(['progress' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $assessments = Assessment::where('course_id', $course->id)
            ->with(['attempts' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get();

        $progress = [
            'materials' => $materials->map(function ($material) {
                $isCompleted = $material->progress->where('completed_at', '!=', null)->count() > 0;
                $lastActivity = $material->progress->max('updated_at');

                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'type' => $material->type,
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? $material->progress->where('completed_at', '!=', null)->first()->completed_at : null,
                    'last_activity' => $lastActivity,
                    'can_download' => $material->file_path ? true : false,
                ];
            }),
            'assessments' => $assessments->map(function ($assessment) {
                $attempt = $assessment->attempts->first();
                $isCompleted = $attempt && $attempt->status === 'completed';

                return [
                    'id' => $assessment->id,
                    'title' => $assessment->title,
                    'type' => $assessment->type,
                    'is_completed' => $isCompleted,
                    'score' => $isCompleted ? $attempt->score : null,
                    'completed_at' => $isCompleted ? $attempt->completed_at : null,
                    'passing_score' => $assessment->passing_score,
                    'passed' => $isCompleted ? $attempt->score >= $assessment->passing_score : false,
                ];
            }),
            'summary' => [
                'total_materials' => $materials->count(),
                'completed_materials' => $materials->where('progress.0.completed_at', '!=', null)->count(),
                'total_assessments' => $assessments->count(),
                'completed_assessments' => $assessments->where('attempts.0.status', 'completed')->count(),
                'overall_progress' => $this->calculateOverallProgress($materials, $assessments),
            ],
        ];

        return response()->json($progress);
    }

    /**
     * Calculate overall progress percentage
     */
    private function calculateOverallProgress($materials, $assessments)
    {
        $totalItems = $materials->count() + $assessments->count();
        if ($totalItems === 0) {
            return 0;
        }

        $completedMaterials = $materials->where('progress.0.completed_at', '!=', null)->count();
        $completedAssessments = $assessments->where('attempts.0.status', 'completed')->count();

        return round((($completedMaterials + $completedAssessments) / $totalItems) * 100, 2);
    }

    /**
     * Get difficulty level in Arabic
     */
    private function getDifficultyLevelInArabic($level)
    {
        $levels = [
            'beginner' => 'للمبتدئين',
            'intermediate' => 'متوسط',
            'advanced' => 'متقدم',
            'expert' => 'خبير',
        ];

        return $levels[$level] ?? 'متوسط';
    }

    /**
     * Get difficulty level in English
     */
    private function getDifficultyLevelInEnglish($level)
    {
        $levels = [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert',
        ];

        return $levels[$level] ?? 'Intermediate';
    }

    /**
     * Extract skills from course description
     */
    private function extractSkillsFromDescription($description)
    {
        // Default skills based on common course types
        $defaultSkills = [
            'بناء تطبيقات Flutter',
            'التعامل مع قواعد البيانات',
            'إدارة الحالة',
            'تطوير واجهات المستخدم',
            'برمجة كائنية التوجه',
            'أدوات التطوير الحديثة',
        ];

        // You can implement more sophisticated skill extraction logic here
        // For now, return default skills
        return $defaultSkills;
    }

    /**
     * Extract career opportunities from course description
     */
    private function extractCareerOpportunities($description)
    {
        // Default career opportunities based on common course types
        $defaultCareers = [
            'مطور تطبيقات Flutter',
            'مطور واجهات تفاعلية',
            'مطور تطبيقات الجوال',
            'مطور ويب',
            'مطور برمجيات',
        ];

        // You can implement more sophisticated career extraction logic here
        // For now, return default careers
        return $defaultCareers;
    }

    /**
     * Super Search for Courses (Public endpoint - no auth required)
     */
    public function superSearch(Request $request)
    {
        $query = Course::with([
            'instructor:id,name,profile_photo_path,bio,job_title',
            'category:id,name',
            'degree:id,name,name_ar,provider,level,description',
            'learningPaths:id,name,slug',
            'reviews' => function ($q) {
                $q->select('id', 'course_id', 'rating', 'message as comment', 'created_at');
            },
        ])->whereIn('status', ['approved', 'approved']);

        // 🔍 Text Search (Multi-field)
        if ($request->has('q') && ! empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('overview', 'like', "%{$searchTerm}%")
                    ->orWhere('prerequisites', 'like', "%{$searchTerm}%")
                    ->orWhere('learning_objectives', 'like', "%{$searchTerm}%")
                    ->orWhere('target_audience', 'like', "%{$searchTerm}%")
                    ->orWhere('language', 'like', "%{$searchTerm}%")
                    ->orWhereHas('instructor', function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('bio', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('category', function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('degree', function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('materials', function ($subQ) use ($searchTerm) {
                        $subQ->where('title', 'like', "%{$searchTerm}%")
                            ->orWhere('description', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // 🏷️ Category Filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 🎓 Degree Filter
        if ($request->has('degree_id')) {
            $query->where('degree_id', $request->degree_id);
        }

        // 👨‍🏫 Instructor Filter
        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        // 💰 Price Range Filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ⭐ Rating Filter
        if ($request->has('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        // 📊 Difficulty Level Filter
        if ($request->has('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        // 🌍 Language Filter
        if ($request->has('language')) {
            $query->where('language', $request->language);
        }

        // ⏱️ Duration Filter
        if ($request->has('min_duration')) {
            $query->where('estimated_duration', '>=', $request->min_duration);
        }
        if ($request->has('max_duration')) {
            $query->where('estimated_duration', '<=', $request->max_duration);
        }

        // 📅 Date Filters
        if ($request->has('created_after')) {
            $query->where('created_at', '>=', $request->created_after);
        }
        if ($request->has('created_before')) {
            $query->where('created_at', '<=', $request->created_before);
        }

        // 🆕 New Courses Filter
        if ($request->has('is_new') && $request->is_new) {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // 🔥 Popular Courses Filter
        if ($request->has('is_popular') && $request->is_popular) {
            $query->withCount('students')->having('students_count', '>', 10);
        }

        // 🏆 Featured Courses Filter
        if ($request->has('is_featured') && $request->is_featured) {
            $query->where('is_featured', true);
        }

        // 📚 Free/Paid Filter
        if ($request->has('price_type')) {
            switch ($request->price_type) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'paid':
                    $query->where('price', '>', 0);
                    break;
            }
        }

        // 🔍 Advanced Search Options
        if ($request->has('search_in')) {
            $searchFields = explode(',', $request->search_in);
            if (! empty($request->q)) {
                $searchTerm = $request->q;
                $query->where(function ($q) use ($searchTerm, $searchFields) {
                    foreach ($searchFields as $field) {
                        switch ($field) {
                            case 'title':
                                $q->orWhere('title', 'like', "%{$searchTerm}%");
                                break;
                            case 'description':
                                $q->orWhere('description', 'like', "%{$searchTerm}%");
                                break;
                            case 'instructor':
                                $q->orWhereHas('instructor', function ($subQ) use ($searchTerm) {
                                    $subQ->where('name', 'like', "%{$searchTerm}%");
                                });
                                break;
                            case 'materials':
                                $q->orWhereHas('materials', function ($subQ) use ($searchTerm) {
                                    $subQ->where('title', 'like', "%{$searchTerm}%");
                                });
                                break;
                        }
                    }
                });
            }
        }

        // 📈 Sorting Options
        $sortBy = $request->get('sort_by', 'relevance');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', $sortOrder);
                break;
            case 'reviews':
                $query->orderBy('total_ratings', $sortOrder);
                break;
            case 'students':
                $query->withCount('students')->orderBy('students_count', $sortOrder);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'duration':
                $query->orderBy('estimated_duration', $sortOrder);
                break;
            case 'relevance':
            default:
                // Relevance scoring based on search term match
                if ($request->has('q') && ! empty($request->q)) {
                    $searchTerm = $request->q;
                    $query->orderByRaw("
                        CASE 
                            WHEN title LIKE '{$searchTerm}' THEN 100
                            WHEN title LIKE '{$searchTerm}%' THEN 90
                            WHEN title LIKE '%{$searchTerm}%' THEN 80
                            WHEN description LIKE '%{$searchTerm}%' THEN 60
                            WHEN overview LIKE '%{$searchTerm}%' THEN 50
                            ELSE 0
                        END DESC
                    ");
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                break;
        }

        // 📄 Pagination
        $perPage = $request->get('per_page', 20);
        $courses = $query->paginate($perPage);

        // 🎯 Transform results with enhanced data
        $courses->getCollection()->transform(function ($course) {
            $totalStudents = property_exists($course, 'students_count') ? $course->students_count : $course->students()->count();
            $averageRating = $course->reviews->avg('rating') ?? 0;
            $totalReviews = $course->reviews->count();
            $totalMaterials = $course->materials()->count();
            $totalDuration = $course->materials()->sum('duration');

            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'thumbnail' => $course->thumbnail_url,
                'price' => $course->price,
                'is_free' => $course->price == 0,
                'instructor' => $course->instructor ? [
                    'id' => $course->instructor->id,
                    'name' => $course->instructor->name,
                    'profile_photo' => $course->instructor->profile_photo_url,
                    'bio' => $course->instructor->bio,
                    'job_title' => $course->instructor->job_title,
                ] : null,
                'category' => $course->category,
                'degree' => $course->degree,
                'stats' => [
                    'total_students' => $totalStudents,
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                    'total_materials' => $totalMaterials,
                    'total_duration' => $totalDuration,
                    'total_hours' => round($totalDuration / 60, 1),
                ],
                'settings' => [
                    'difficulty_level' => $course->difficulty_level,
                    'language' => $course->language,
                    'estimated_duration' => $course->estimated_duration,
                    'allow_comments' => $course->allow_comments,
                    'allow_notes' => $course->allow_notes,
                    'allow_ratings' => $course->allow_ratings,
                ],
                'overview' => [
                    'overview' => $course->overview,
                    'prerequisites' => $course->prerequisites,
                    'learning_objectives' => $course->learning_objectives,
                    'target_audience' => $course->target_audience,
                ],
                'learning_paths' => $course->learningPaths ? $course->learningPaths->map(function ($lp) {
                    return [
                        'id' => $lp->id,
                        'name' => $lp->name,
                        'slug' => $lp->slug,
                    ];
                }) : [],
                'recent_reviews' => $course->reviews->take(3)->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at,
                    ];
                }),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
            ];
        });

        // 📊 Search Statistics
        $searchStats = [
            'total_results' => $courses->total(),
            'current_page' => $courses->currentPage(),
            'per_page' => $courses->perPage(),
            'last_page' => $courses->lastPage(),
            'filters_applied' => $this->getAppliedFilters($request),
        ];

        // 🎯 Search Suggestions (if no results or few results)
        $suggestions = [];
        if ($courses->total() < 5 && $request->has('q')) {
            $suggestions = $this->getSearchSuggestions($request->q);
        }

        return response()->json([
            'courses' => $courses,
            'search_stats' => $searchStats,
            'suggestions' => $suggestions,
            'filters' => $this->getAvailableFilters(),
        ]);
    }

    /**
     * Get applied filters for search statistics
     */
    private function getAppliedFilters(Request $request)
    {
        $filters = [];

        if ($request->has('q')) {
            $filters['search_term'] = $request->q;
        }
        if ($request->has('category_id')) {
            $categoryName = optional(Category::find($request->category_id))->name;
            $filters['category'] = $categoryName ?? (string) $request->category_id;
        }
        if ($request->has('degree_id')) {
            $degreeName = optional(Degree::find($request->degree_id))->name;
            $filters['degree'] = $degreeName ?? (string) $request->degree_id;
        }
        if ($request->has('instructor_id')) {
            $instructorName = optional(User::find($request->instructor_id))->name;
            $filters['instructor'] = $instructorName ?? (string) $request->instructor_id;
        }
        if ($request->has('min_price')) {
            $filters['min_price'] = $request->min_price;
        }
        if ($request->has('max_price')) {
            $filters['max_price'] = $request->max_price;
        }
        if ($request->has('min_rating')) {
            $filters['min_rating'] = $request->min_rating;
        }
        if ($request->has('difficulty_level')) {
            $filters['difficulty_level'] = $request->difficulty_level;
        }
        if ($request->has('language')) {
            $filters['language'] = $request->language;
        }
        if ($request->has('price_type')) {
            $filters['price_type'] = $request->price_type;
        }
        if ($request->has('sort_by')) {
            $filters['sort_by'] = $request->sort_by;
        }
        if ($request->has('is_new')) {
            $filters['is_new'] = $request->is_new;
        }
        if ($request->has('is_popular')) {
            $filters['is_popular'] = $request->is_popular;
        }
        if ($request->has('is_featured')) {
            $filters['is_featured'] = $request->is_featured;
        }

        return $filters;
    }

    /**
     * Get search suggestions when few results found
     */
    private function getSearchSuggestions($searchTerm)
    {
        // Get similar course titles
        $similarTitles = Course::where('title', 'like', "%{$searchTerm}%")
            ->limit(5)
            ->pluck('title')
            ->toArray();

        // Get popular categories
        $popularCategories = Category::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->limit(3)
            ->pluck('name')
            ->toArray();

        // Get trending search terms (placeholder - could be implemented with search logs)
        $trendingTerms = ['Web Development', 'Mobile Apps', 'Data Science', 'AI', 'Machine Learning'];

        return [
            'similar_titles' => $similarTitles,
            'popular_categories' => $popularCategories,
            'trending_terms' => $trendingTerms,
        ];
    }

    /**
     * Get available filters for the search interface
     */
    private function getAvailableFilters()
    {
        return [
            'categories' => Category::select('id', 'name')->get(),
            'degrees' => Degree::select('id', 'name')->get(),
            'difficulty_levels' => [
                ['value' => 'beginner', 'label' => 'للمبتدئين'],
                ['value' => 'intermediate', 'label' => 'متوسط'],
                ['value' => 'advanced', 'label' => 'متقدم'],
            ],
            'languages' => [
                ['value' => 'Arabic', 'label' => 'العربية'],
                ['value' => 'English', 'label' => 'English'],
                ['value' => 'French', 'label' => 'Français'],
            ],
            'price_types' => [
                ['value' => 'free', 'label' => 'مجاني'],
                ['value' => 'paid', 'label' => 'مدفوع'],
            ],
            'sort_options' => [
                ['value' => 'relevance', 'label' => 'الأكثر صلة'],
                ['value' => 'newest', 'label' => 'الأحدث'],
                ['value' => 'rating', 'label' => 'الأعلى تقييماً'],
                ['value' => 'students', 'label' => 'الأكثر طلاباً'],
                ['value' => 'price_low', 'label' => 'السعر: من الأقل'],
                ['value' => 'price_high', 'label' => 'السعر: من الأعلى'],
            ],
        ];
    }

    /**
     * Get course edit details with statistics (instructor only)
     */
    public function getEditDetails(Course $course)
    {
        $user = auth()->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Calculate statistics
        $totalMaterials = $course->materials()->count();
        $totalDuration = $course->materials()->sum('duration');
        $totalStudents = $course->students()->count();
        $averageRating = $course->average_rating ?? 0;
        $totalAssessments = Assessment::whereHas('course', function ($q) use ($course) {
            $q->where('id', $course->id);
        })->count();

        // Calculate watch hours (estimated based on enrollments and course duration)
        $totalWatchHours = $totalStudents * ($totalDuration / 60); // Convert minutes to hours

        // Calculate days this year (course duration in days)
        $daysThisYear = $totalDuration > 0 ? ceil($totalDuration / (24 * 60)) : 0; // Convert minutes to days

        return response()->json([
            'course_name' => $course->title,
            'course_image_limitations' => 'JPG, PNG, Max 2MB',
            'type' => $course->category ? $course->category->name : '',
            'level' => $course->difficulty_level ?? 'متقدم',
            'language' => $course->language ?? 'العربية',
            'price' => $course->price ?? 200.00,
            'description' => $course->description ?? '',
            'statistics' => [
                'total_watch_hours' => round($totalWatchHours, 2),
                'course_hours_total' => round($totalDuration / 60, 1),
                'total_participants' => $totalStudents,
                'attraction_rating' => round($averageRating, 1),
                'days_this_year' => $daysThisYear,
                'price_per_rial_saudi' => $course->price ?? null,
                'lessons_count' => $totalMaterials,
                'levels_count' => 10, // Default value, can be calculated based on course structure
                'tests_count' => $totalAssessments,
            ],
        ]);
    }

    /**
     * Update course details (instructor only)
     */
    public function updateDetails(Request $request, Course $course)
    {
        $user = auth()->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'level' => 'nullable|string|in:مبتدئ,متوسط,متقدم',
            'language' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        // Update course fields
        $course->title = $validated['course_name'];
        $course->difficulty_level = $validated['level'] ?? $course->difficulty_level;
        $course->language = $validated['language'] ?? $course->language;
        $course->price = $validated['price'];
        $course->description = $validated['description'];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('thumbnail')->store('course-thumbnails', 'public');
            $course->thumbnail = $path;
        }

        // Update category if type is provided
        if ($validated['type']) {
            $category = Category::firstOrCreate(['name' => $validated['type']]);
            $course->category_id = $category->id;
        }

        $course->save();

        return response()->json([
            'message' => 'تم تحديث تفاصيل الدورة بنجاح',
            'course' => $course->fresh(['category', 'materials']),
        ]);
    }

    /**
     * Complete course creation cycle with levels and content
     */
    public function createCompleteCourse(Request $request)
    {
        $user = auth()->user();

        if (! $user->hasRole('instructor')) {
            return response()->json(['message' => 'Only instructors can create courses'], 403);
        }

        $validated = $request->validate([
            'course_details' => 'required|array',
            'course_details.name' => 'required|string|max:255',
            'course_details.category' => 'nullable|string|max:255',
            'course_details.level' => 'nullable|string|in:مبتدئ,متوسط,متقدم',
            'course_details.language' => 'nullable|string|max:255',
            'course_details.price_egp' => 'required|numeric|min:0',
            'course_details.description' => 'required|string',
            'course_details.image_upload' => 'nullable|array',
            'course_details.image_upload.limitations' => 'nullable|string',
            'course_details.image_upload.aspect_ratio' => 'nullable|string',
            'course_details.image_upload.selected_image_url' => 'nullable|string',
            'course_content' => 'required|array',
            'course_content.*.level_name' => 'required|string|max:255',
            'course_content.*.lessons' => 'required|array|min:1',
            'course_content.*.lessons.*.lesson_name' => 'required|string|max:255',
            'course_content.*.lessons.*.lesson_type' => 'required|string|in:فيديو,ملفات,امتحان',
            'course_content.*.lessons.*.lesson_title' => 'required|string|max:255',
            'course_content.*.lessons.*.lesson_description' => 'nullable|string',
            'course_content.*.lessons.*.video_content' => 'nullable|array',
            'course_content.*.lessons.*.video_content.upload_status' => 'nullable|string|in:Uploaded,Pending,None',
            'course_content.*.lessons.*.video_content.video_url' => 'nullable|string',
            'course_content.*.lessons.*.files_content' => 'nullable|array',
            'course_content.*.lessons.*.files_content.upload_status' => 'nullable|string|in:Uploaded,None',
            'course_content.*.lessons.*.files_content.files' => 'nullable|array',
            'course_content.*.lessons.*.quiz_content' => 'nullable|array',
            'course_content.*.lessons.*.quiz_content.status' => 'nullable|string|in:Added,Not Added',
            'course_content.*.lessons.*.quiz_content.quiz_details' => 'nullable|array',
            'course_content.*.lessons.*.uploaded_files_list' => 'nullable|array',
            'course_content.*.lessons.*.in_video_quizzes' => 'nullable|array',
            'course_content.*.lessons.*.exam_details' => 'nullable|array',
            'course_content.*.lessons.*.exam_details.exam_name' => 'nullable|string|max:255',
            'course_content.*.lessons.*.exam_details.questions' => 'nullable|array',
        ]);

        try {
            \DB::beginTransaction();

            // Create the course
            $courseData = $validated['course_details'];

            // Handle category
            $category = null;
            if (! empty($courseData['category'])) {
                $category = \App\Models\Category::firstOrCreate(['name' => $courseData['category']]);
            }

            $course = Course::create([
                'instructor_id' => $user->id,
                'category_id' => $category ? $category->id : null,
                'title' => $courseData['name'],
                'description' => $courseData['description'],
                'price' => $courseData['price_egp'],
                'difficulty_level' => $courseData['level'] ?? 'متوسط',
                'language' => $courseData['language'] ?? 'العربية',
                'status' => 'draft',
            ]);

            // Handle image upload if provided
            if ($request->hasFile('course_details.image_upload.selected_image_url')) {
                $path = $request->file('course_details.image_upload.selected_image_url')->store('course-thumbnails', 'public');
                $course->update(['thumbnail' => $path]);
            }

            // Create levels and materials
            foreach ($validated['course_content'] as $levelIndex => $levelData) {
                $level = $course->levels()->create([
                    'level_name' => $levelData['level_name'],
                    'order' => $levelIndex + 1,
                ]);

                foreach ($levelData['lessons'] as $lessonIndex => $lessonData) {
                    $materialType = $this->mapLessonTypeToMaterialType($lessonData['lesson_type']);

                    $material = $course->materials()->create([
                        'level_id' => $level->id,
                        'title' => $lessonData['lesson_title'],
                        'description' => $lessonData['lesson_description'],
                        'type' => $materialType,
                        'order' => $lessonIndex + 1,
                        'is_free' => false, // Default to paid
                    ]);

                    // Handle video content
                    if ($lessonData['lesson_type'] === 'فيديو' && isset($lessonData['video_content'])) {
                        if ($request->hasFile("course_content.{$levelIndex}.lessons.{$lessonIndex}.video_content.video_url")) {
                            $videoPath = $request->file("course_content.{$levelIndex}.lessons.{$lessonIndex}.video_content.video_url")
                                ->store('course-materials', 'public');
                            $material->update(['file_path' => $videoPath]);
                        }
                    }

                    // Handle files content
                    if ($lessonData['lesson_type'] === 'ملفات' && isset($lessonData['files_content'])) {
                        if ($request->hasFile("course_content.{$levelIndex}.lessons.{$lessonIndex}.files_content.files")) {
                            $files = $request->file("course_content.{$levelIndex}.lessons.{$lessonIndex}.files_content.files");
                            $filePaths = [];
                            foreach ($files as $file) {
                                $filePaths[] = $file->store('course-materials', 'public');
                            }
                            $material->update(['file_path' => json_encode($filePaths)]);
                        }
                    }

                    // Handle exam/quiz content
                    if ($lessonData['lesson_type'] === 'امتحان' && isset($lessonData['exam_details'])) {
                        $examData = $lessonData['exam_details'];

                        $assessment = \App\Models\Assessment::create([
                            'course_id' => $course->id,
                            'created_by' => $user->id,
                            'title' => $examData['exam_name'] ?? 'Exam for '.$lessonData['lesson_title'],
                            'description' => $lessonData['lesson_description'],
                            'type' => 'exam',
                        ]);

                        // Create questions for the exam
                        if (isset($examData['questions'])) {
                            foreach ($examData['questions'] as $questionData) {
                                \App\Models\AssessmentQuestion::create([
                                    'assessment_id' => $assessment->id,
                                    'question' => $questionData['question_text'],
                                    'type' => 'mcq',
                                    'options' => collect($questionData['answers'])->pluck('answer_text')->toArray(),
                                    'correct_answer' => collect($questionData['answers'])
                                        ->where('is_correct', true)
                                        ->first()['answer_text'] ?? '',
                                    'points' => 10, // Default points
                                ]);
                            }
                        }
                    }

                    // Handle in-video quizzes
                    if (isset($lessonData['in_video_quizzes']) && is_array($lessonData['in_video_quizzes'])) {
                        foreach ($lessonData['in_video_quizzes'] as $quizIndex => $quizData) {
                            $material->inVideoQuizzes()->create([
                                'quiz_name' => $quizData['quiz_name'] ?? 'Quiz '.($quizIndex + 1),
                                'timestamp' => $quizData['timestamp'] ?? '00:00:00',
                                'questions_count' => $quizData['questions_count'] ?? 0,
                                'questions' => $quizData['questions'] ?? [],
                                'order' => $quizIndex + 1,
                            ]);
                        }
                    }
                }
            }

            \DB::commit();

            return response()->json([
                'message' => 'Course created successfully',
                'course' => $course->load(['levels.materials', 'materials.inVideoQuizzes']),
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => 'Failed to create course',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Map lesson type to material type
     */
    private function mapLessonTypeToMaterialType($lessonType)
    {
        $mapping = [
            'فيديو' => 'video',
            'ملفات' => 'pdf',
            'امتحان' => 'other', // Exams are handled separately via Assessment model
        ];

        return $mapping[$lessonType] ?? 'other';
    }

    /**
     * Get lesson type for a material
     */
    private function getLessonType($material)
    {
        if ($material->type === 'video') {
            return 'video';
        } elseif ($material->type === 'pdf') {
            return 'pdf';
        } elseif ($material->type === 'quiz' || ($material->quizzes && $material->quizzes->isNotEmpty())) {
            return 'quiz';
        } elseif ($material->type === 'other') {
            return 'other';
        }

        return 'other'; // Default
    }

    /**
     * Get materials for a lesson (video, pdf, quiz)
     */
    private function getLessonMaterials($material)
    {
        $materials = [];

        if ($material->type === 'pdf') {
            $materials[] = [
                'id' => 'mat_'.$material->id,
                'type' => 'pdf',
                'title' => $material->title,
                'url' => $material->file_path ? asset($material->file_path) : null,
                'sizeBytes' => $material->file_size ?? 0,
            ];
        }

        // Add additional materials if they exist
        if ($material->type === 'video' && $material->file_path) {
            $materials[] = [
                'id' => 'mat_'.$material->id,
                'type' => 'video',
                'title' => $material->title,
                'url' => asset($material->file_path),
                'sizeBytes' => $material->file_size ?? 0,
            ];
        }

        return $materials;
    }

    /**
     * Format in-video quizzes for the frontend
     */
    private function formatInVideoQuizzes($inVideoQuizzes)
    {
        return $inVideoQuizzes->map(function ($quiz) {
            $questions = $quiz->questions ?? [];

            // Decode JSON if questions is a string
            if (is_string($questions)) {
                $questions = json_decode($questions, true) ?? [];
            }

            $formattedQuestions = [];

            foreach ($questions as $index => $question) {
                $formattedQuestions[] = [
                    'id' => 'pop_'.$quiz->id.'_'.($index + 1),
                    'startSec' => $this->parseTimestampToSeconds($quiz->timestamp ?? '00:00:00'),
                    'endSec' => $this->parseTimestampToSeconds($quiz->timestamp ?? '00:00:00') + 15,
                    'question' => $question['question'] ?? 'Question '.($index + 1),
                    'options' => $question['options'] ?? ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
                    'correctIndex' => $question['correct_answer'] ?? 0,
                    'explanation' => $question['explanation'] ?? null,
                ];
            }

            // If no questions in the quiz, create a default one
            if (empty($formattedQuestions)) {
                $formattedQuestions[] = [
                    'id' => 'pop_'.$quiz->id,
                    'startSec' => $this->parseTimestampToSeconds($quiz->timestamp ?? '00:00:00'),
                    'endSec' => $this->parseTimestampToSeconds($quiz->timestamp ?? '00:00:00') + 15,
                    'question' => $quiz->quiz_name ?? 'Quiz Question',
                    'options' => ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
                    'correctIndex' => 0,
                    'explanation' => null,
                ];
            }

            return $formattedQuestions;
        })->flatten(1); // Flatten the array to get all questions in one level
    }

    /**
     * Format quiz data for a material
     */
    private function formatQuizData($material)
    {
        if (! $material->quizzes) {
            return [
                'passingPercent' => 70,
                'attemptsAllowed' => 3,
                'questions' => [],
            ];
        }

        $quiz = $material->quizzes->first();

        if (! $quiz) {
            return [
                'passingPercent' => 70,
                'attemptsAllowed' => 3,
                'questions' => [],
            ];
        }

        $questions = $quiz->questions->map(function ($question) {
            return [
                'id' => 'q_'.$question->id,
                'text' => $question->question,
                'type' => $this->mapQuestionType($question->type ?? 'mcq'),
                'options' => $question->options ?? ['Option 1', 'Option 2', 'Option 3', 'Option 4'],
                'correctIndices' => [$question->correct_answer ?? 0],
                'score' => $question->points ?? 10,
            ];
        });

        return [
            'passingPercent' => $quiz->passing_score ?? 70,
            'attemptsAllowed' => $quiz->max_attempts ?? 3,
            'questions' => $questions,
        ];
    }

    /**
     * Parse timestamp string to seconds
     */
    private function parseTimestampToSeconds($timestamp)
    {
        $parts = explode(':', $timestamp);
        if (count($parts) === 3) {
            return ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
        }

        return 0;
    }

    /**
     * Map question type to frontend format
     */
    private function mapQuestionType($type)
    {
        $mapping = [
            'mcq' => 'single',
            'multiple_choice' => 'single',
            'single_choice' => 'single',
            'true_false' => 'boolean',
            'boolean' => 'boolean',
            'multiple' => 'multiple',
        ];

        return $mapping[$type] ?? 'single';
    }

    /**
     * Get the last accessed lesson ID for a user
     */
    private function getLastAccessedLesson($course, $user)
    {
        $lastAccessedMaterial = CourseMaterialProgress::where('user_id', $user->id)
            ->whereIn('course_material_id', $course->materials->pluck('id'))
            ->latest()
            ->first();

        if ($lastAccessedMaterial) {
            return 'lesson_'.$lastAccessedMaterial->level_id.'_'.$lastAccessedMaterial->course_material_id;
        }

        return null;
    }

    /**
     * Reply to a course comment (students and instructors can reply)
     */
    public function replyToComment(Request $request, Course $course, CourseComment $comment)
    {
        $user = auth('sanctum')->user();

        // Check if user is enrolled in the course or is the instructor
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        $isInstructor = $user->id === $course->instructor_id;

        if (! $isEnrolled && ! $isInstructor) {
            return response()->json(['message' => 'You must be enrolled to reply to comments'], 403);
        }

        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $comment->update([
            'instructor_reply' => $validated['reply'],
            'replied_at' => now(),
        ]);

        return response()->json([
            'message' => 'Reply added successfully',
            'comment' => [
                'id' => $comment->id,
                'text' => $comment->text,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
                'reply' => [
                    'text' => $comment->instructor_reply,
                    'replied_at' => $comment->replied_at,
                    'replied_by' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'role' => $user->hasRole('instructor') ? 'instructor' : 'student',
                    ],
                ],
            ],
        ]);
    }
}
