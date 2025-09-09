<?php

require_once 'vendor/autoload.php';

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\User;
use App\Models\Category;
use App\Models\Degree;
use Illuminate\Support\Facades\Storage;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Creating welcome course...\n";

try {
    // Get or create a category for the welcome course
    $category = Category::firstOrCreate(
        ['name' => 'الدورات الترحيبية'],
        [
            'slug' => 'welcome-courses',
            'description' => 'الدورات الترحيبية للطلاب الجدد'
        ]
    );

    // Get an existing degree or create a new one
    $degree = Degree::where('is_active', true)->first();
    if (!$degree) {
        $degree = Degree::create([
            'name' => 'General',
            'name_ar' => 'عام',
            'description' => 'للجميع',
            'level' => Degree::max('level') + 1,
            'is_active' => true,
            'sort_order' => Degree::max('sort_order') + 1
        ]);
    }

    // Get an instructor (or create one if needed)
    $instructor = User::role('instructor')->first();
    if (!$instructor) {
        $instructor = User::role('admin')->first();
    }

    if (!$instructor) {
        throw new Exception('No instructor or admin found');
    }

    // Create the welcome course
    $course = Course::create([
        'instructor_id' => $instructor->id,
        'category_id' => $category->id,
        'degree_id' => $degree->id,
        'title' => 'الدورة الترحيبية',
        'description' => 'دورة ترحيبية لجميع الطلاب الجدد في المنصة',
        'price' => 0, // Free course
        'status' => 'approved', // Published immediately
    ]);

    echo "Course created with ID: {$course->id}\n";

    // Copy the video file to the storage
    $sourceVideoPath = 'public/video/Ta-Hamp4.mp4';
    $destinationPath = 'course-materials/course-' . $course->id . '/welcome-video.mp4';
    
    if (file_exists($sourceVideoPath)) {
        // Create directory if it doesn't exist
        $directory = dirname('storage/app/public/' . $destinationPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Copy the video file
        copy($sourceVideoPath, 'storage/app/public/' . $destinationPath);
        
        // Create course material for the video
        $material = CourseMaterial::create([
            'course_id' => $course->id,
            'title' => 'الفيديو الترحيبي',
            'description' => 'فيديو ترحيبي للطلاب الجدد',
            'type' => 'video',
            'file_path' => $destinationPath,
            'order' => 1,
            'is_free' => true,
            'duration' => 5, // 5 minutes estimated
            'file_size' => filesize($sourceVideoPath),
        ]);
        
        echo "Video material created with ID: {$material->id}\n";
    } else {
        echo "Warning: Video file not found at {$sourceVideoPath}\n";
    }

    // Create assessment (quiz)
    $assessment = Assessment::create([
        'course_id' => $course->id,
        'created_by' => $instructor->id,
        'title' => 'اختبار الدورة الترحيبية',
        'description' => 'اختبار بسيط للدورة الترحيبية',
        'type' => 'quiz',
    ]);

    echo "Assessment created with ID: {$assessment->id}\n";

    // Create questions for the quiz
    $questions = [
        [
            'question' => 'هل شاهدت الفيديو؟',
            'type' => 'mcq',
            'options' => ['نعم', 'لا', 'معظمه', 'البداية فقط'],
            'correct_answer' => 'نعم',
            'points' => 10,
        ],
        [
            'question' => 'هل استمتعت بالفيديو؟',
            'type' => 'mcq',
            'options' => ['نعم', 'لا'],
            'correct_answer' => 'نعم',
            'points' => 10,
        ]
    ];

    foreach ($questions as $questionData) {
        $question = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question' => $questionData['question'],
            'type' => $questionData['type'],
            'options' => $questionData['options'],
            'correct_answer' => $questionData['correct_answer'],
            'points' => $questionData['points'],
        ]);
        
        echo "Question created: {$question->question}\n";
    }

    // Auto-enroll all students in this course
    $students = User::role('student')->get();
    $enrollmentCount = 0;
    
    foreach ($students as $student) {
        // Check if already enrolled
        if (!$student->enrolledCourses()->where('course_id', $course->id)->exists()) {
            $student->enrolledCourses()->attach($course->id, ['enrolled_at' => now()]);
            $enrollmentCount++;
        }
    }
    
    echo "Enrolled {$enrollmentCount} students in the welcome course\n";

    // Also enroll new students automatically (future enrollments)
    // This will be handled by the enrollment logic in the CourseController

    echo "\n✅ Welcome course created successfully!\n";
    echo "Course ID: {$course->id}\n";
    echo "Course Title: {$course->title}\n";
    echo "Assessment ID: {$assessment->id}\n";
    echo "Students enrolled: {$enrollmentCount}\n";

} catch (Exception $e) {
    echo "❌ Error creating welcome course: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
