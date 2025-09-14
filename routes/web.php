<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CareerLevelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Public testimonial form
Route::get('/testimonial', function () {
    return Inertia::render('TestimonialForm');
})->name('testimonial.form');

// Public testimonials listing
Route::get('/testimonials', [\App\Http\Controllers\TestimonialController::class, 'publicIndex'])->name('testimonials.public');

Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckDashboardAccess::class, \App\Http\Middleware\SessionTimeout::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users CRUD - Full CRUD functionality
    Route::resource('users', UsersController::class);

    // Roles & Permissions management
    Route::resource('roles', \App\Http\Controllers\RolesController::class);
    Route::post('users/{user}/roles', [\App\Http\Controllers\RolesController::class, 'assignUserRoles'])->name('users.roles.assign');
    Route::delete('users/{user}/roles/{role}', [\App\Http\Controllers\RolesController::class, 'revokeUserRole'])->name('users.roles.revoke');
    Route::post('users/{user}/permissions', [\App\Http\Controllers\RolesController::class, 'giveUserPermission'])->name('users.permissions.give');
    Route::delete('users/{user}/permissions/{permission}', [\App\Http\Controllers\RolesController::class, 'revokeUserPermission'])->name('users.permissions.revoke');

    // Instructors - View and show only (edit through users)
    Route::get('/instructors', [\App\Http\Controllers\InstructorsController::class, 'index'])->name('instructors.index');
    Route::get('/instructors/{instructor}', [\App\Http\Controllers\InstructorsController::class, 'show'])->name('instructors.show');
    Route::get('/admin/instructors/datatable', [\App\Http\Controllers\InstructorsController::class, 'datatable'])->name('instructors.datatable');

    // Courses - Full CRUD functionality with approval actions
    Route::resource('courses', CourseController::class);
    Route::resource('career-levels', CareerLevelController::class)->parameters(['career-levels' => 'career_level']);
    Route::get('courses-requests', [CourseController::class, 'requests'])->name('courses.requests');
    Route::post('courses/{course}/submit-for-approval', [CourseController::class, 'submitForApproval'])->name('courses.submit-for-approval');
    Route::post('courses/{course}/approve', [CourseController::class, 'approve'])->name('courses.approve');
    Route::post('courses/{course}/reject', [CourseController::class, 'reject'])->name('courses.reject');

    // Categories - Full CRUD functionality
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);

    // Instructor Applications - View and approval actions
    Route::get('instructor-applications', [\App\Http\Controllers\InstructorApplicationController::class, 'index'])->name('instructor-applications.index');
    Route::get('instructor-applications/{instructorApplication}', [\App\Http\Controllers\InstructorApplicationController::class, 'show'])->name('instructor-applications.show');
    Route::post('instructor-applications/{instructorApplication}/approve', [\App\Http\Controllers\InstructorApplicationController::class, 'approve'])->name('instructor-applications.approve');
    Route::post('instructor-applications/{instructorApplication}/reject', [\App\Http\Controllers\InstructorApplicationController::class, 'reject'])->name('instructor-applications.reject');

    // Reviews - Moderation and management
    Route::get('reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::post('reviews/{review}/approve', [\App\Http\Controllers\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [\App\Http\Controllers\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Certificates - Full CRUD functionality
    Route::resource('certificates', \App\Http\Controllers\CertificateController::class)->except(['edit', 'update']);
    Route::post('certificates/bulk-issue', [\App\Http\Controllers\CertificateController::class, 'bulkIssue'])->name('certificates.bulk-issue');

    // Partners - Full CRUD functionality
    Route::resource('partners', \App\Http\Controllers\PartnerController::class);


    // Active Users Management
    Route::get('active-users', [\App\Http\Controllers\ActiveUsersController::class, 'index'])->name('active-users.index');
    Route::post('active-users/{user}/force-logout', [\App\Http\Controllers\ActiveUsersController::class, 'forceLogout'])->name('active-users.force-logout');
    Route::get('api/active-users/count', [\App\Http\Controllers\ActiveUsersController::class, 'getActiveUsersCount'])->name('active-users.count');

    // Analytics - Dashboard and reports
    Route::get('analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/users', [\App\Http\Controllers\AnalyticsController::class, 'users'])->name('analytics.users');
    Route::get('analytics/courses', [\App\Http\Controllers\AnalyticsController::class, 'courses'])->name('analytics.courses');
    Route::get('analytics/reviews', [\App\Http\Controllers\AnalyticsController::class, 'reviews'])->name('analytics.reviews');
    Route::get('analytics/materials', [\App\Http\Controllers\AnalyticsController::class, 'materials'])->name('analytics.materials');
    Route::get('analytics/assessments', [\App\Http\Controllers\AnalyticsController::class, 'assessments'])->name('analytics.assessments');
    Route::get('analytics/quizzes', [\App\Http\Controllers\AnalyticsController::class, 'quizzes'])->name('analytics.quizzes');
    Route::get('analytics/enrollments', [\App\Http\Controllers\AnalyticsController::class, 'enrollments'])->name('analytics.enrollments');
    Route::get('analytics/wishlists', [\App\Http\Controllers\AnalyticsController::class, 'wishlists'])->name('analytics.wishlists');

    // Course Materials - Full CRUD functionality
    Route::resource('course-materials', \App\Http\Controllers\CourseMaterialController::class);
    Route::get('course-materials-analytics', [\App\Http\Controllers\CourseMaterialController::class, 'analytics'])->name('course-materials.analytics');

    // Assessments - helper endpoints must be defined BEFORE resource to avoid route conflicts with /assessments/{assessment}
    Route::post('assessments/validate-question', [\App\Http\Controllers\AssessmentController::class, 'validateQuestion'])->name('assessments.validate-question');
    Route::get('assessments/temp-questions', [\App\Http\Controllers\AssessmentController::class, 'getTempQuestions'])->name('assessments.temp-questions');
    Route::put('assessments/temp-questions/{index}', [\App\Http\Controllers\AssessmentController::class, 'updateTempQuestion'])->name('assessments.temp-questions.update');
    // Assessments - Full CRUD functionality
    Route::resource('assessments', \App\Http\Controllers\AssessmentController::class);
    Route::get('assessments/{assessment}/questions', [\App\Http\Controllers\AssessmentController::class, 'questions'])->name('assessments.questions');
    Route::get('assessments/{assessment}/attempts', [\App\Http\Controllers\AssessmentController::class, 'attempts'])->name('assessments.attempts');
    Route::get('assessment-attempts/{attempt}', [\App\Http\Controllers\AssessmentController::class, 'attemptDetail'])->name('assessment-attempts.show');
    Route::post('assessment-attempts/{attempt}/grade', [\App\Http\Controllers\AssessmentController::class, 'gradeAttempt'])->name('assessment-attempts.grade');
    Route::post('assessment-attempts/{attempt}/regrade', [\App\Http\Controllers\AssessmentController::class, 'regradeAttempt'])->name('assessment-attempts.regrade');
    Route::get('assessments-analytics', [\App\Http\Controllers\AssessmentController::class, 'analytics'])->name('assessments.analytics');
    Route::get('assessments-general-analytics', [\App\Http\Controllers\AssessmentController::class, 'generalAnalytics'])->name('assessments.general-analytics');

    // Question Bank - Full CRUD for managing questions
    Route::resource('question-bank', \App\Http\Controllers\QuestionBankController::class);

    // Degrees - Full CRUD functionality
    Route::resource('degrees', \App\Http\Controllers\DegreeController::class);

    // Learning Paths - Full CRUD functionality
    Route::resource('learning-paths', \App\Http\Controllers\LearningPathController::class);

    // Student Enrollments - Full CRUD functionality
    Route::get('enrollments', [\App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('enrollments/create', [\App\Http\Controllers\EnrollmentController::class, 'create'])->name('enrollments.create');
    Route::post('enrollments', [\App\Http\Controllers\EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('enrollments/{userId}/{courseId}', [\App\Http\Controllers\EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::get('enrollments/{userId}/{courseId}/edit', [\App\Http\Controllers\EnrollmentController::class, 'edit'])->name('enrollments.edit');
    Route::put('enrollments/{userId}/{courseId}', [\App\Http\Controllers\EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::delete('enrollments/{userId}/{courseId}', [\App\Http\Controllers\EnrollmentController::class, 'destroy'])->name('enrollments.destroy');

    // Course Creation Simulation (for testing)
    Route::get('instructor/course-simulation', function () {
        return Inertia::render('Instructor/CourseSimulation');
    })->name('instructor.course-simulation');

    // Demo page for course simulation
    Route::get('demo/course-simulation', function () {
        return Inertia::render('Demo/SimulationDemo');
    })->name('demo.course-simulation');
    Route::get('enrollments-analytics', [\App\Http\Controllers\EnrollmentController::class, 'analytics'])->name('enrollments.analytics');

    // Student Progress Tracking
    Route::get('student-progress', [\App\Http\Controllers\StudentProgressController::class, 'index'])->name('student-progress.index');
    Route::get('student-progress/{user}', [\App\Http\Controllers\StudentProgressController::class, 'show'])->name('student-progress.show');
    Route::get('student-progress-analytics', [\App\Http\Controllers\StudentProgressController::class, 'analytics'])->name('student-progress.analytics');

    // Wishlist Management
    Route::get('wishlists', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlists.index');
    Route::get('wishlists-analytics', [\App\Http\Controllers\WishlistController::class, 'analytics'])->name('wishlists.analytics');

    // Comments Moderation
    Route::resource('comments', \App\Http\Controllers\CommentController::class);

    // Coupons & Discounts (simple pages)
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');
    Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

    // Notes Management
    Route::resource('notes', \App\Http\Controllers\NoteController::class);

    // Ratings Management (TODO: Create RatingController)
    // Route::get('ratings', [\App\Http\Controllers\RatingController::class, 'index'])->name('ratings.index');

    // In-Video Quizzes (TODO: Create VideoQuizController)
    // Route::resource('video-quizzes', \App\Http\Controllers\VideoQuizController::class);

    // Course Levels Management (TODO: Create CourseLevelController)
    // Route::resource('course-levels', \App\Http\Controllers\CourseLevelController::class);

    // Quiz System - Full CRUD functionality
    Route::resource('quizzes', \App\Http\Controllers\QuizController::class);
    Route::get('quizzes/{quiz}/questions', [\App\Http\Controllers\QuizController::class, 'questions'])->name('quizzes.questions');
    Route::post('quizzes/{quiz}/questions', [\App\Http\Controllers\QuizController::class, 'addQuestion'])->name('quizzes.questions.store');
    Route::get('quizzes/{quiz}/questions/{question}/edit', [\App\Http\Controllers\QuizController::class, 'editQuestion'])->name('quizzes.questions.edit');
    Route::put('quizzes/{quiz}/questions/{question}', [\App\Http\Controllers\QuizController::class, 'updateQuestion'])->name('quizzes.questions.update');
    Route::delete('quizzes/{quiz}/questions/{question}', [\App\Http\Controllers\QuizController::class, 'deleteQuestion'])->name('quizzes.questions.destroy');
    Route::get('quizzes/{quiz}/attempts', [\App\Http\Controllers\QuizController::class, 'attempts'])->name('quizzes.attempts')->where('quiz', '[0-9]+');
    Route::get('quizzes/{quiz}/analytics', [\App\Http\Controllers\QuizController::class, 'analytics'])->name('quizzes.analytics');
    Route::get('quizzes-analytics', [\App\Http\Controllers\QuizController::class, 'generalAnalytics'])->name('quizzes.general-analytics');

    // System Settings
    Route::get('system-settings', [\App\Http\Controllers\SystemSettingsController::class, 'index'])->name('system-settings.index');
    Route::put('system-settings', [\App\Http\Controllers\SystemSettingsController::class, 'update'])->name('system-settings.update');
    Route::post('system-settings/clear-cache', [\App\Http\Controllers\SystemSettingsController::class, 'clearCache'])->name('system-settings.clear-cache');
    Route::get('system-settings/export', [\App\Http\Controllers\SystemSettingsController::class, 'exportSettings'])->name('system-settings.export');
    Route::get('system-settings/info', [\App\Http\Controllers\SystemSettingsController::class, 'systemInfo'])->name('system-settings.info');
    Route::post('system-settings/maintenance/enable', [\App\Http\Controllers\SystemSettingsController::class, 'enableMaintenanceMode'])->name('system-settings.maintenance.enable');
    Route::post('system-settings/maintenance/disable', [\App\Http\Controllers\SystemSettingsController::class, 'disableMaintenanceMode'])->name('system-settings.maintenance.disable');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Social Login Routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Course Management Routes are now inside the auth middleware group above

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/students', [App\Http\Controllers\AdminController::class, 'students'])->name('students');
    Route::get('/instructors', [App\Http\Controllers\AdminController::class, 'instructors'])->name('instructors');
    Route::get('/courses', [App\Http\Controllers\AdminController::class, 'courses'])->name('admin.courses');
    Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/materials', [App\Http\Controllers\AdminController::class, 'materials'])->name('materials');
    Route::get('/applications', [App\Http\Controllers\AdminController::class, 'applications'])->name('applications');
    Route::get('/assessments', [App\Http\Controllers\AdminController::class, 'assessments'])->name('assessments');
    Route::get('/certificates', [App\Http\Controllers\AdminController::class, 'certificates'])->name('certificates');
    // Testimonials - Full CRUD functionality (Admin only)
    Route::resource('testimonials', \App\Http\Controllers\TestimonialController::class);
    Route::post('testimonials/{testimonial}/toggle-approval', [\App\Http\Controllers\TestimonialController::class, 'toggleApproval'])->name('testimonials.toggle-approval');
    Route::post('testimonials/{testimonial}/toggle-featured', [\App\Http\Controllers\TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
    Route::get('/partners', [App\Http\Controllers\AdminController::class, 'partners'])->name('partners');
    Route::get('/degrees', [App\Http\Controllers\AdminController::class, 'degrees'])->name('degrees');
    Route::get('/learning-paths', [App\Http\Controllers\AdminController::class, 'learningPaths'])->name('learning-paths');
    Route::get('/quizzes', [App\Http\Controllers\AdminController::class, 'quizzes'])->name('quizzes');
    Route::get('/enrollments', [App\Http\Controllers\AdminController::class, 'enrollments'])->name('enrollments');
    Route::get('/student-progress', [App\Http\Controllers\AdminController::class, 'studentProgress'])->name('student-progress');
    Route::get('/wishlists', [App\Http\Controllers\AdminController::class, 'wishlists'])->name('wishlists');
    Route::get('/comments', [App\Http\Controllers\AdminController::class, 'comments'])->name('comments');
    Route::get('/notes', [App\Http\Controllers\AdminController::class, 'notes'])->name('notes');
    Route::get('/analytics/revenue', [App\Http\Controllers\AdminController::class, 'analyticsRevenue'])->name('analytics.revenue');
    Route::get('/analytics/users', [App\Http\Controllers\AdminController::class, 'analyticsUsers'])->name('analytics.users');
    Route::get('/analytics/courses', [App\Http\Controllers\AdminController::class, 'analyticsCourses'])->name('analytics.courses');
    Route::get('/analytics/reviews', [App\Http\Controllers\AdminController::class, 'analyticsReviews'])->name('analytics.reviews');
    Route::get('/analytics/materials', [App\Http\Controllers\AdminController::class, 'analyticsMaterials'])->name('analytics.materials');
    Route::get('/analytics/assessments', [App\Http\Controllers\AdminController::class, 'analyticsAssessments'])->name('analytics.assessments');
    Route::get('/analytics/quizzes', [App\Http\Controllers\AdminController::class, 'analyticsQuizzes'])->name('analytics.quizzes');
    Route::get('/analytics/enrollments', [App\Http\Controllers\AdminController::class, 'analyticsEnrollments'])->name('analytics.enrollments');
    Route::get('/analytics/wishlists', [App\Http\Controllers\AdminController::class, 'analyticsWishlists'])->name('analytics.wishlists');
    Route::get('/settings/general', [App\Http\Controllers\AdminController::class, 'settingsGeneral'])->name('settings.general');
    Route::get('/settings/security', [App\Http\Controllers\AdminController::class, 'settingsSecurity'])->name('settings.security');
    Route::get('/settings/notifications', [App\Http\Controllers\AdminController::class, 'settingsNotifications'])->name('settings.notifications');
    Route::get('/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
});

// Test route for admin dashboard (temporary)
Route::get('/admin-test', function () {
    return response()->json([
        'message' => 'Admin test route working',
        'user' => auth()->user(),
        'hasAdminRole' => auth()->user() ? auth()->user()->hasRole('admin') : false,
    ]);
})->middleware('auth');

Route::get('/media/{path}', function ($path) {
    // Authorization: Only allow access to logged-in users (customize as needed)
    if (! Auth::check()) {
        abort(403);
    }
    // Optionally, add more checks (e.g., user is enrolled in the course)
    $file = Storage::disk('public')->path($path);
    if (! file_exists($file)) {
        abort(404);
    }

    return Response::file($file);
})->where('path', '.*')->name('media.serve')->middleware('signed');

// Serve Vuexy template assets for static dashboard rendering
Route::get('/prompts/dash/assets/{path}', function ($path) {
    $full = base_path('prompts/dash/assets/'.$path);
    if (! file_exists($full)) {
        abort(404);
    }
    $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
    $mimeMap = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'map' => 'application/json',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'woff2' => 'font/woff2',
        'woff' => 'font/woff',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'html' => 'text/html',
    ];
    $mime = $mimeMap[$ext] ?? (\Illuminate\Support\Facades\File::mimeType($full) ?: 'application/octet-stream');

    return response()->file($full, ['Content-Type' => $mime, 'Cache-Control' => 'public, max-age=31536000']);
})->where('path', '.*');

// Session activity ping route
Route::post('/session/activity', function() {
    session(['last_activity' => time()]);
    return response()->json(['status' => 'success']);
})->middleware(['auth', \App\Http\Middleware\SessionTimeout::class]);

require __DIR__.'/auth.php';
