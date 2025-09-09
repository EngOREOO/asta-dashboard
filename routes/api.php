    <?php

    use App\Http\Controllers\Api\AssessmentAttemptController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseLevelController;
use App\Http\Controllers\Api\CourseMaterialController;
use App\Http\Controllers\Api\DegreeController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\GlobalSearchController;
use App\Http\Controllers\Api\InstructorApplicationController;
use App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\InVideoQuizController;
use App\Http\Controllers\Api\LearningPathController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\QuizAttemptController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);
Route::apiResource('categories', CategoryController::class)->names([
    'index' => 'api.categories.index',
    'store' => 'api.categories.store',
    'show' => 'api.categories.show',
    'update' => 'api.categories.update',
    'destroy' => 'api.categories.destroy',
    'create' => 'api.categories.create',
    'edit' => 'api.categories.edit',
]);
Route::prefix('admin/testimonials')->group(function () {
    Route::get('/', [TestimonialController::class, 'adminIndex']);
    Route::get('/stats', [TestimonialController::class, 'stats']);
    Route::put('/{testimonial}', [TestimonialController::class, 'update']);
    Route::post('/{testimonial}/approve', [TestimonialController::class, 'approve']);
    Route::post('/{testimonial}/reject', [TestimonialController::class, 'reject']);
    Route::post('/{testimonial}/toggle-featured', [TestimonialController::class, 'toggleFeatured']);
    Route::delete('/{testimonial}', [TestimonialController::class, 'destroy']);
});
Route::get('categories/{category}/courses', [CategoryController::class, 'courses']);

// Public Instructor Endpoints (No Auth Required)
Route::get('/instructors', [InstructorController::class, 'getAllInstructors']);
Route::get('/instructors/{id}', [InstructorController::class, 'getInstructorDetails']);

// Public Course Search Endpoint (No Auth Required)
Route::get('/courses/search', [CourseController::class, 'superSearch']);

// Global Search Endpoint (No Auth Required)
Route::get('/search', [GlobalSearchController::class, 'search']);

// Public basic courses listing and show (restricted fields for non-enrolled/non-auth)
Route::get('/courses', [CourseController::class, 'index']);

// Public specific course routes (MUST be before dynamic route)
Route::get('courses/recent', [CourseController::class, 'recent']);
Route::get('courses/top-rated', [CourseController::class, 'topRated']);
Route::get('courses/popular', [CourseController::class, 'popular']);
Route::get('courses/free', [CourseController::class, 'free']);

// Dynamic course route (MUST be after specific routes)
Route::get('/courses/{course}', [CourseController::class, 'show']);

// Public Partner Endpoints (No Auth Required)
Route::apiResource('partners', PartnerController::class)->names([
    'index' => 'api.partners.index',
    'store' => 'api.partners.store',
    'show' => 'api.partners.show',
    'update' => 'api.partners.update',
    'destroy' => 'api.partners.destroy',
    'create' => 'api.partners.create',
    'edit' => 'api.partners.edit',
]);

// Public Degrees Endpoints (No Auth Required)
Route::apiResource('degrees', DegreeController::class)->only(['index', 'show'])->names([
    'index' => 'api.degrees.index',
    'show' => 'api.degrees.show',
]);
Route::get('categories/{category}/degrees', [CategoryController::class, 'degrees']);

// Public Learning Paths (list and show)
Route::apiResource('learning-paths', LearningPathController::class)->only(['index', 'show'])->names([
    'index' => 'api.learning-paths.index',
    'show' => 'api.learning-paths.show',
]);
Route::get('categories/{category}/learning-paths', [CategoryController::class, 'learningPaths']);

// Public Testimonials API
Route::get('testimonials', [TestimonialController::class, 'index']);
Route::get('testimonials/{testimonial}', [TestimonialController::class, 'show']);
Route::post('testimonials', [TestimonialController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // Degree Routes (Admin only for modifications)
    Route::apiResource('degrees', DegreeController::class)->except(['index', 'show'])->names([
        'store' => 'api.degrees.store',
        'update' => 'api.degrees.update',
        'destroy' => 'api.degrees.destroy',
        'create' => 'api.degrees.create',
        'edit' => 'api.degrees.edit',
    ]);

    // Course Routes (protected write operations)
    Route::apiResource('courses', CourseController::class)->except(['index', 'show'])->names([
        'store' => 'api.courses.store',
        'update' => 'api.courses.update',
        'destroy' => 'api.courses.destroy',
        'create' => 'api.courses.create',
        'edit' => 'api.courses.edit',
    ]);
    // Admin-only Learning Paths management
    Route::apiResource('learning-paths', LearningPathController::class)->except(['index', 'show'])->middleware('role:admin')->names([
        'store' => 'api.learning-paths.store',
        'update' => 'api.learning-paths.update',
        'destroy' => 'api.learning-paths.destroy',
        'create' => 'api.learning-paths.create',
        'edit' => 'api.learning-paths.edit',
    ]);
    Route::post('courses/create-complete', [CourseController::class, 'createCompleteCourse']);

    // Course Reviews
    Route::get('courses/{course}/reviews', [ReviewController::class, 'index']);
    Route::post('courses/{course}/reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy']);

    Route::get('courses/{course}/materials', [CourseController::class, 'materials']);
    Route::get('courses/{course}/materials/{material}/signed-url', [CourseController::class, 'signedUrl']);
    Route::post('courses/{course}/reject', [CourseController::class, 'reject']);
    Route::post('courses/{course}/materials', [CourseMaterialController::class, 'store'])->name('api.course-materials.store');
    Route::delete('courses/{course}/materials/{material}', [CourseMaterialController::class, 'destroy'])->name('api.course-materials.destroy');
    Route::get('courses/{course}/materials/{material}/signed-url', [CourseMaterialController::class, 'signedUrl']);
    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll']);
    Route::get('my-courses', [CourseController::class, 'myCourses']);
    Route::post('courses/{course}/materials/{material}/complete', [CourseMaterialController::class, 'markCompleted']);
    Route::get('courses/{course}/progress', [CourseController::class, 'progress']);

    // Course Settings & Interactions
    Route::get('courses/{course}/settings', [CourseController::class, 'getSettings']);
    Route::put('courses/{course}/settings', [CourseController::class, 'updateSettings']);
    Route::post('courses/{course}/quizzes', [CourseController::class, 'addQuiz']);
    Route::get('courses/{course}/comments', [CourseController::class, 'getComments']);
    Route::post('courses/{course}/comments/{comment}/reply', [CourseController::class, 'replyToComment']);
    Route::get('courses/{course}/notes', [CourseController::class, 'getNotes']);
    Route::post('courses/{course}/notes', [CourseController::class, 'addNote']);
    Route::post('courses/{course}/rate', [CourseController::class, 'rateCourse']);
    Route::get('courses/{course}/detailed-progress', [CourseController::class, 'getDetailedProgress']);

    // Course Edit Details (Instructor only)
    Route::get('courses/{course}/edit-details', [CourseController::class, 'getEditDetails']);
    Route::put('courses/{course}/edit-details', [CourseController::class, 'updateDetails']);

    // Course Levels Management (Instructor only)
    Route::prefix('courses/{course}/levels')->group(function () {
        Route::get('/', [CourseLevelController::class, 'index']);
        Route::post('/', [CourseLevelController::class, 'store']);
        Route::get('/{level}', [CourseLevelController::class, 'show']);
        Route::put('/{level}', [CourseLevelController::class, 'update']);
        Route::delete('/{level}', [CourseLevelController::class, 'destroy']);
        Route::post('/reorder', [CourseLevelController::class, 'reorder']);
    });

    // In-Video Quizzes Management (Instructor only)
    Route::prefix('courses/{course}/materials/{material}/in-video-quizzes')->group(function () {
        Route::get('/', [InVideoQuizController::class, 'index']);
        Route::post('/', [InVideoQuizController::class, 'store']);
        Route::get('/{quiz}', [InVideoQuizController::class, 'show']);
        Route::put('/{quiz}', [InVideoQuizController::class, 'update']);
        Route::delete('/{quiz}', [InVideoQuizController::class, 'destroy']);
        Route::post('/reorder', [InVideoQuizController::class, 'reorder']);

        // Student submission endpoint
        Route::post('/{quiz}/submit', [InVideoQuizController::class, 'submitAnswers'])->name('in-video-quizzes.submit');
    });

    // Assessment Management
    Route::apiResource('assessments', AssessmentController::class)->names([
        'index' => 'api.assessments.index',
        'store' => 'api.assessments.store',
        'show' => 'api.assessments.show',
        'update' => 'api.assessments.update',
        'destroy' => 'api.assessments.destroy',
        'create' => 'api.assessments.create',
        'edit' => 'api.assessments.edit',
    ]);
    Route::post('assessments/{assessment}/assign', [AssessmentController::class, 'assign']);

    // Assessment Attempts
    Route::get('attempts', [AssessmentAttemptController::class, 'index']);
    Route::post('assessments/{assessment}/attempts', [AssessmentAttemptController::class, 'store']);
    Route::get('attempts/{attempt}', [AssessmentAttemptController::class, 'show']);
    Route::post('attempts/{attempt}/submit', [AssessmentAttemptController::class, 'submit']);
    Route::post('attempts/{attempt}/grade', [AssessmentAttemptController::class, 'grade']);

    // Quiz Attempts
    Route::get('/quizzes/my-quizzes', [QuizAttemptController::class, 'myQuizzes']);
    Route::post('/quizzes/{quiz}/start', [QuizAttemptController::class, 'start']);
    Route::post('/quizzes/attempts/{attempt}/submit', [QuizAttemptController::class, 'submit'])->name('quizzes.attempts.submit');
    Route::get('/quizzes/attempts/{attempt}', [QuizAttemptController::class, 'show']);
    Route::get('/quizzes/{quiz}/current-attempt', [QuizAttemptController::class, 'currentAttempt']);

    // Category Routes

    // Student Routes
    Route::prefix('student')->group(function () {
        Route::get('enrolled-courses', [StudentController::class, 'enrolledCourses']);
        Route::get('completed-courses', [StudentController::class, 'completedCourses']);
        Route::get('under-studying-courses', [StudentController::class, 'underStudyingCourses']);
        Route::get('wishlist', [StudentController::class, 'wishlist']);
        Route::post('wishlist/{course}', [StudentController::class, 'addToWishlist']);
        Route::delete('wishlist/{course}', [StudentController::class, 'removeFromWishlist']);
        Route::get('suggestions', [StudentController::class, 'suggestedCourses']);
        Route::get('dashboard-stats', [StudentController::class, 'dashboardStats']);
    });

    // Certificate Routes
    Route::prefix('certificates')->group(function () {
        Route::get('/', [CertificateController::class, 'index']);
        Route::get('/stats', [CertificateController::class, 'stats']);
        Route::get('/{certificate}', [CertificateController::class, 'show']);
        Route::get('/{certificate}/download', [CertificateController::class, 'download']);
        Route::post('/courses/{course}/generate', [CertificateController::class, 'generate']);
        Route::get('/courses/{course}/status', [CertificateController::class, 'status']);
    });

    // Instructor Routes
    Route::prefix('instructor')->group(function () {
        Route::get('courses', [InstructorController::class, 'myCourses']);
        Route::post('courses', [InstructorController::class, 'createCourse']);
        Route::get('courses/{course}', [InstructorController::class, 'showCourse']);
        Route::put('courses/{course}/content', [InstructorController::class, 'addCourseContent']);
        Route::get('courses/{course}/content', [InstructorController::class, 'getCourseContent']);
        Route::post('courses/{course}/lessons', [InstructorController::class, 'addLesson']);
        Route::post('courses/{course}/lessons/{material}/questions', [InstructorController::class, 'addQuizQuestions']);

        // New Course Structure Routes
        Route::post('courses/{course}/structure', [InstructorController::class, 'createCourseStructure']);
        Route::get('courses/{course}/structure', [InstructorController::class, 'getCourseStructure']);

        Route::get('timeline', [InstructorController::class, 'getTimeline']);
        Route::get('stats', [InstructorController::class, 'courseStats']);
        Route::get('dashboard', [InstructorController::class, 'dashboard']);
        Route::get('courses/{course}/analytics', [InstructorController::class, 'courseAnalytics']);
        Route::get('courses/{course}/student-progress', [InstructorController::class, 'studentProgress']);
        Route::get('students', [InstructorController::class, 'getStudentsList']);
        Route::get('students/{studentId}', [InstructorController::class, 'getStudentDetails']);
        Route::get('revenue', [InstructorController::class, 'getRevenue']);
    });

    // Instructor Account Settings
    Route::get('/instructor/account-settings', [InstructorController::class, 'getAccountSettings']);
    Route::put('/instructor/account-settings', [InstructorController::class, 'updateAccountSettings']);

    // Instructor Application Routes
    Route::prefix('instructor-applications')->group(function () {
        Route::post('/', [InstructorApplicationController::class, 'store']);
        Route::get('/my-application', [InstructorApplicationController::class, 'show']);
        Route::get('/available-fields', [InstructorApplicationController::class, 'getAvailableFields']);
    });

    // Admin Routes for Instructor Applications
    Route::prefix('admin/instructor-applications')->group(function () {
        Route::get('/', [InstructorApplicationController::class, 'index']);
        Route::get('/{application}', [InstructorApplicationController::class, 'showAdmin']);
        Route::patch('/{application}/review', [InstructorApplicationController::class, 'review']);
    });

    // File Upload Routes
    Route::prefix('file-uploads')->group(function () {
        Route::post('courses/{course}/initiate', [FileUploadController::class, 'initiateUpload']);
        Route::post('{upload}/chunk', [FileUploadController::class, 'uploadChunk']);
        Route::get('{upload}/status', [FileUploadController::class, 'getStatus']);
        Route::delete('{upload}/cancel', [FileUploadController::class, 'cancelUpload']);
        Route::get('courses/{course}/uploads', [FileUploadController::class, 'getCourseUploads']);
    });

    // Learning Path Routes

    // Admin Testimonials Management

});

Route::get('/hello', function () {
    return ['message' => 'Hello from API!'];
});
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
