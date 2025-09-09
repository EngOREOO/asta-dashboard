<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\InstructorApplication;
use App\Models\Review;
use App\Models\Certificate;
use App\Models\Category;
use App\Models\Degree;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing Admin Dashboard Data...\n\n";

// Check if we have any data
$totalUsers = User::count();
$totalCourses = Course::count();
$totalAssessments = Assessment::count();
$totalApplications = InstructorApplication::count();
$totalReviews = Review::count();
$totalCertificates = Certificate::count();

echo "📊 Current Data Counts:\n";
echo "- Users: {$totalUsers}\n";
echo "- Courses: {$totalCourses}\n";
echo "- Assessments: {$totalAssessments}\n";
echo "- Applications: {$totalApplications}\n";
echo "- Reviews: {$totalReviews}\n";
echo "- Certificates: {$totalCertificates}\n\n";

// Check if we have admin users
$adminUsers = User::role('admin')->count();
echo "👑 Admin Users: {$adminUsers}\n";

if ($adminUsers === 0) {
    echo "⚠️  No admin users found! Creating one...\n";
    
    // Create an admin user
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
    
    $admin->assignRole('admin');
    echo "✅ Admin user created: admin@example.com / password\n";
}

// Check if we have any courses
if ($totalCourses === 0) {
    echo "⚠️  No courses found! Creating sample data...\n";
    
    // Create a category if none exists
    $category = Category::firstOrCreate([
        'name' => 'Technology',
        'description' => 'Technology courses',
        'is_active' => true,
    ]);
    
    // Create a degree if none exists
    $degree = Degree::firstOrCreate([
        'name' => 'Bachelor',
        'name_ar' => 'بكالوريوس',
        'level' => 1,
        'is_active' => true,
        'sort_order' => 1,
    ]);
    
    // Get or create an instructor
    $instructor = User::role('instructor')->first();
    if (!$instructor) {
        $instructor = User::create([
            'name' => 'Sample Instructor',
            'email' => 'instructor@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $instructor->assignRole('instructor');
    }
    
    // Create sample courses
    $courses = [
        [
            'title' => 'Introduction to Web Development',
            'description' => 'Learn the basics of web development',
            'price' => 99.99,
            'status' => 'approved',
        ],
        [
            'title' => 'Advanced JavaScript',
            'description' => 'Master JavaScript programming',
            'price' => 149.99,
            'status' => 'pending',
        ],
        [
            'title' => 'Laravel Framework',
            'description' => 'Build web applications with Laravel',
            'price' => 199.99,
            'status' => 'approved',
        ],
    ];
    
    foreach ($courses as $courseData) {
        Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'degree_id' => $degree->id,
            'title' => $courseData['title'],
            'description' => $courseData['description'],
            'price' => $courseData['price'],
            'status' => $courseData['status'],
        ]);
    }
    
    echo "✅ Sample courses created\n";
}

// Check if we have any applications
if ($totalApplications === 0) {
    echo "⚠️  No applications found! Creating sample data...\n";
    
    // Get or create a regular user
    $user = User::role('student')->first();
    if (!$user) {
        $user = User::create([
            'name' => 'Sample Student',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('student');
    }
    
    // Create sample applications
    InstructorApplication::create([
        'user_id' => $user->id,
        'field' => 'Web Development',
        'experience' => '5 years of experience in web development',
        'motivation' => 'I want to share my knowledge with others',
        'status' => 'pending',
    ]);
    
    echo "✅ Sample application created\n";
}

// Check if we have any reviews
if ($totalReviews === 0) {
    echo "⚠️  No reviews found! Creating sample data...\n";
    
    $course = Course::first();
    $user = User::role('student')->first();
    
    if ($course && $user) {
        Review::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'rating' => 5,
            'comment' => 'Excellent course! Highly recommended.',
        ]);
        
        Review::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'rating' => 4,
            'comment' => 'Great content and well structured.',
        ]);
        
        echo "✅ Sample reviews created\n";
    }
}

// Check if we have any assessments
if ($totalAssessments === 0) {
    echo "⚠️  No assessments found! Creating sample data...\n";
    
    $course = Course::first();
    if ($course) {
        Assessment::create([
            'course_id' => $course->id,
            'created_by' => $course->instructor_id,
            'title' => 'Final Exam',
            'description' => 'Comprehensive final assessment',
            'type' => 'quiz',
        ]);
        
        echo "✅ Sample assessment created\n";
    }
}

// Final data check
echo "\n📊 Updated Data Counts:\n";
echo "- Users: " . User::count() . "\n";
echo "- Courses: " . Course::count() . "\n";
echo "- Assessments: " . Assessment::count() . "\n";
echo "- Applications: " . InstructorApplication::count() . "\n";
echo "- Reviews: " . Review::count() . "\n";
echo "- Certificates: " . Certificate::count() . "\n";

echo "\n✅ Admin Dashboard should now have data to display!\n";
echo "🔗 Access the admin dashboard at: /admin/dashboard\n";
echo "👤 Login with: admin@example.com / password\n";
