<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\CourseLevel;
use App\Models\CourseMaterial;
use App\Models\CourseRating;
use App\Models\InstructorRating;
use App\Models\InVideoQuiz;
use App\Models\Quiz;
use App\Models\QuizQuestion; // Added this import
use App\Models\User; // Added this import
use Illuminate\Database\Seeder; // Added this import

class WebDevelopmentCourseSeeder extends Seeder
{
    public function run(): void
    {
        // Find the specific course (Web Development Course with ID 1)
        $course = Course::find(1);
        if (! $course) {
            $this->command->error('Course with ID 1 not found!');

            return;
        }

        // Get the course levels
        $levels = CourseLevel::where('course_id', $course->id)->orderBy('order')->get();
        if ($levels->isEmpty()) {
            $this->command->error('No course levels found for course ID 1!');

            return;
        }

        // Create some students for ratings and comments
        $students = User::factory(15)->create();
        foreach ($students as $student) {
            $student->assignRole('student');
        }

        // Add materials to each level
        $this->addMaterialsToLevels($course, $levels);

        // Add course ratings
        $this->addCourseRatings($course, $students);

        // Add instructor ratings
        $this->addInstructorRatings($course, $students);

        // Add course comments
        $this->addCourseComments($course, $students);

        // Create quizzes with questions
        $this->createQuizzes($course);

        $this->command->info('Web Development Course populated with sample data successfully!');
    }

    private function addMaterialsToLevels(Course $course, $levels): void
    {
        // Level 1: الأساسيات
        $level1 = $levels->where('order', 1)->first();
        if ($level1) {
            // Lesson 1: مقدمة في HTML
            $material1 = CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level1->id,
                'title' => 'مقدمة في HTML',
                'description' => 'تعلم أساسيات HTML وكيفية إنشاء هيكل صفحة الويب',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/html-intro.mp4',
                'order' => 1,
                'is_free' => true,
                'duration' => 1800,
                'file_size' => 150000000,
            ]);

            // Add in-video quiz for HTML lesson
            InVideoQuiz::create([
                'material_id' => $material1->id,
                'quiz_name' => 'ما هو HTML؟',
                'timestamp' => '00:05:00', // At 5 minutes
                'questions_count' => 1,
                'questions' => json_encode([
                    [
                        'question' => 'ماذا يعني HTML؟',
                        'options' => ['HyperText Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlink Text Markup Language'],
                        'correct_answer' => 0,
                        'explanation' => 'HTML تعني HyperText Markup Language وهي لغة ترميز النصوص التشعبية',
                    ],
                ]),
                'order' => 1,
            ]);

            // Add another in-video question at 12 minutes
            InVideoQuiz::create([
                'material_id' => $material1->id,
                'quiz_name' => 'عناصر HTML الأساسية',
                'timestamp' => '00:12:00',
                'questions_count' => 1,
                'questions' => json_encode([
                    [
                        'question' => 'أي من هذه العناصر يستخدم لإنشاء عنوان رئيسي؟',
                        'options' => ['<p>', '<h1>', '<div>', '<span>'],
                        'correct_answer' => 1,
                        'explanation' => '<h1> هو العنصر المستخدم لإنشاء العناوين الرئيسية',
                    ],
                ]),
                'order' => 2,
            ]);

            // Lesson 2: أساسيات CSS
            $material2 = CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level1->id,
                'title' => 'أساسيات CSS',
                'description' => 'تعلم كيفية تنسيق وتصميم صفحات الويب باستخدام CSS',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/css-basics.mp4',
                'order' => 2,
                'is_free' => false,
                'duration' => 2100,
                'file_size' => 180000000,
            ]);

            // Add in-video questions for CSS lesson
            InVideoQuiz::create([
                'material_id' => $material2->id,
                'quiz_name' => 'خصائص CSS',
                'timestamp' => '00:08:00',
                'questions_count' => 1,
                'questions' => json_encode([
                    [
                        'question' => 'ما هي الخاصية المستخدمة لتغيير لون النص؟',
                        'options' => ['text-color', 'font-color', 'color', 'text-style'],
                        'correct_answer' => 2,
                        'explanation' => 'color هي الخاصية المستخدمة لتغيير لون النص في CSS',
                    ],
                ]),
                'order' => 1,
            ]);

            InVideoQuiz::create([
                'material_id' => $material2->id,
                'quiz_name' => 'وحدات القياس',
                'timestamp' => '00:18:00',
                'questions_count' => 1,
                'questions' => json_encode([
                    [
                        'question' => 'أي من هذه الوحدات نسبية؟',
                        'options' => ['px', 'em', 'cm', 'mm'],
                        'correct_answer' => 1,
                        'explanation' => 'em هي وحدة نسبية تعتمد على حجم الخط الأب',
                    ],
                ]),
                'order' => 2,
            ]);

            // Lesson 3: ملف PDF للمراجعة
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level1->id,
                'title' => 'ملخص HTML و CSS',
                'description' => 'ملف PDF يحتوي على ملخص شامل لأساسيات HTML و CSS',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/html-css-summary.mp4',
                'order' => 3,
                'is_free' => false,
                'duration' => 900,
                'file_size' => 5000000,
            ]);

            // Quiz 1: اختبار HTML و CSS
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level1->id,
                'title' => 'اختبار HTML و CSS الأساسي',
                'description' => 'اختبار شامل لأساسيات HTML و CSS',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/html-css-quiz.mp4',
                'order' => 4,
                'is_free' => false,
                'duration' => 1800, // 30 minutes
                'file_size' => 0,
            ]);
        }

        // Level 2: المتقدم
        $level2 = $levels->where('order', 2)->first();
        if ($level2) {
            // Lesson 1: JavaScript للمبتدئين
            $material4 = CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level2->id,
                'title' => 'JavaScript للمبتدئين',
                'description' => 'تعلم أساسيات البرمجة بلغة JavaScript',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/javascript-intro.mp4',
                'order' => 1,
                'is_free' => false,
                'duration' => 2700,
                'file_size' => 220000000,
            ]);

            // Add interactive in-video questions for JavaScript
            InVideoQuiz::create([
                'material_id' => $material4->id,
                'quiz_name' => 'المتغيرات في JavaScript',
                'timestamp' => '00:10:00',
                'questions_count' => 2,
                'questions' => json_encode([
                    [
                        'question' => 'أي من هذه الكلمات تستخدم لإعلان متغير في JavaScript؟',
                        'options' => ['var', 'variable', 'declare', 'set'],
                        'correct_answer' => 0,
                        'explanation' => 'var هي الكلمة المحجوزة لإعلان المتغيرات في JavaScript',
                    ],
                    [
                        'question' => 'ما هو نوع البيانات الصحيح للنص في JavaScript؟',
                        'options' => ['text', 'string', 'char', 'varchar'],
                        'correct_answer' => 1,
                        'explanation' => 'string هو نوع البيانات المستخدم للنصوص في JavaScript',
                    ],
                ]),
                'order' => 1,
            ]);

            InVideoQuiz::create([
                'material_id' => $material4->id,
                'quiz_name' => 'الدوال Functions',
                'timestamp' => '00:22:00',
                'questions_count' => 1,
                'questions' => json_encode([
                    [
                        'question' => 'كيف يتم تعريف دالة في JavaScript؟',
                        'options' => ['function name()', 'def name()', 'func name()', 'func name()'],
                        'correct_answer' => 0,
                        'explanation' => 'function هي الكلمة المحجوزة لتعريف الدوال في JavaScript',
                    ],
                ]),
                'order' => 2,
            ]);

            // Lesson 2: DOM Manipulation
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level2->id,
                'title' => 'التحكم في DOM',
                'description' => 'تعلم كيفية التفاعل مع عناصر الصفحة باستخدام JavaScript',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/dom-manipulation.mp4',
                'order' => 2,
                'is_free' => false,
                'duration' => 2400,
                'file_size' => 200000000,
            ]);

            // Add DOM manipulation questions
            InVideoQuiz::create([
                'material_id' => $material4->id + 1, // DOM material ID
                'quiz_name' => 'اختيار العناصر',
                'timestamp' => '00:15:00',
                'questions_count' => 1,
                'questions' => json_encode([
                    [
                        'question' => 'أي من هذه الطرق تستخدم لاختيار عنصر بواسطة المعرف؟',
                        'options' => ['getElementById()', 'getElementByClass()', 'getElementByTag()', 'getElementByName()'],
                        'correct_answer' => 0,
                        'explanation' => 'getElementById() تستخدم لاختيار عنصر بواسطة المعرف الفريد',
                    ],
                ]),
                'order' => 1,
            ]);

            // Quiz 2: اختبار JavaScript
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level2->id,
                'title' => 'اختبار JavaScript الأساسي',
                'description' => 'اختبار شامل لأساسيات JavaScript',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/javascript-quiz.mp4',
                'order' => 3,
                'is_free' => false,
                'duration' => 2400, // 40 minutes
                'file_size' => 0,
            ]);
        }

        // Level 3: الاحترافي
        $level3 = $levels->where('order', 3)->first();
        if ($level3) {
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level3->id,
                'title' => 'مقدمة في PHP',
                'description' => 'تعلم أساسيات البرمجة الخلفية باستخدام PHP',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/php-intro.mp4',
                'order' => 1,
                'is_free' => false,
                'duration' => 3000,
                'file_size' => 250000000,

            ]);

            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level3->id,
                'title' => 'قواعد البيانات MySQL',
                'description' => 'تعلم كيفية إنشاء وإدارة قواعد البيانات',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/mysql-basics.mp4',
                'order' => 2,
                'is_free' => false,
                'duration' => 3300,
                'file_size' => 280000000,

            ]);
        }

        // Level 4: التطبيق العملي
        $level4 = $levels->where('order', 4)->first();
        if ($level4) {
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level4->id,
                'title' => 'مشروع تطبيقي - موقع شركة',
                'description' => 'بناء موقع شركة كامل باستخدام جميع التقنيات المتعلمة',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/company-website-project.mp4',
                'order' => 1,
                'is_free' => false,
                'duration' => 3600,
                'file_size' => 300000000,

            ]);
        }

        // Level 5: المشاريع
        $level5 = $levels->where('order', 5)->first();
        if ($level5) {
            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level5->id,
                'title' => 'مشروع موقع شخصي',
                'description' => 'بناء موقع شخصي كامل باستخدام HTML, CSS, وJavaScript',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/personal-website-project.mp4',
                'order' => 1,
                'is_free' => false,
                'duration' => 4200,
                'file_size' => 350000000,

            ]);

            CourseMaterial::create([
                'course_id' => $course->id,
                'level_id' => $level5->id,
                'title' => 'ملفات المشروع والموارد',
                'description' => 'جميع الملفات والموارد المطلوبة لإكمال المشروع',
                'type' => 'video',
                'file_path' => 'course-materials/course-1/project-resources.mp4',
                'order' => 2,
                'is_free' => false,
                'duration' => 600,
                'file_size' => 15000000,

            ]);
        }
    }

    private function addCourseRatings(Course $course, $students): void
    {
        $ratings = [5, 4, 5, 4, 3, 5, 4, 5, 4, 3, 4, 5, 4, 5, 3, 4, 5, 4, 3, 5];

        foreach ($ratings as $index => $rating) {
            if (isset($students[$index])) {
                CourseRating::create([
                    'course_id' => $course->id,
                    'user_id' => $students[$index]->id,
                    'rating' => $rating,
                    'comment' => $this->getRandomRatingComment($rating),
                ]);
            }
        }

        $averageRating = collect($ratings)->average();
        $course->update([
            'average_rating' => round($averageRating, 1),
            'total_ratings' => count($ratings),
        ]);
    }

    private function addInstructorRatings(Course $course, $students): void
    {
        $ratings = [4, 4, 5, 4, 3, 5, 4, 5, 4, 3, 4, 5, 4, 3, 5];

        foreach ($ratings as $index => $rating) {
            if (isset($students[$index])) {
                InstructorRating::create([
                    'instructor_id' => $course->instructor_id,
                    'user_id' => $students[$index]->id,
                    'course_id' => $course->id,
                    'rating' => $rating,
                    'comment' => $this->getRandomInstructorComment($rating),
                ]);
            }
        }
    }

    private function addCourseComments(Course $course, $students): void
    {
        $comments = [
            'دورة ممتازة! استفدت كثيراً من الشرح الواضح والأمثلة العملية.',
            'المحتوى مفيد جداً، لكن أتمنى لو كان هناك المزيد من التمارين العملية.',
            'شرح رائع ومفصل، أنصح بهذه الدورة لكل من يريد تعلم تطوير الويب.',
            'الدورة جيدة ولكن بعض الدروس تحتاج إلى تحديث.',
            'ممتاز! تمكنت من بناء موقعي الأول بعد إكمال هذه الدورة.',
        ];

        $instructorReplies = [
            'شكراً لك! سعيد أن الدورة أفادتك. استمر في التطبيق العملي.',
            'شكراً على ملاحظتك. سأعمل على إضافة المزيد من التمارين في التحديث القادم.',
            'أشكرك على كلماتك المشجعة! هذا يحفزني لتقديم المزيد.',
            'نقطة مهمة، أعمل حالياً على تحديث المحتوى ليواكب أحدث التقنيات.',
            'هذا رائع! أتمنى لك التوفيق في مشاريعك القادمة.',
        ];

        // Course-level comments
        foreach ($comments as $index => $commentText) {
            if (isset($students[$index])) {
                $comment = CourseComment::create([
                    'course_id' => $course->id,
                    'level_id' => null,
                    'material_id' => null,
                    'user_id' => $students[$index]->id,
                    'text' => $commentText,
                    'is_approved' => true,
                ]);

                if ($index < 3) {
                    $comment->update([
                        'instructor_reply' => $instructorReplies[$index],
                        'replied_at' => now()->subDays(rand(1, 7)),
                    ]);
                }
            }
        }

        // Level-specific comments (for topics)
        $levels = $course->levels;
        foreach ($levels->take(3) as $level) {
            foreach ($students->take(3) as $student) {
                CourseComment::create([
                    'course_id' => $course->id,
                    'level_id' => $level->id,
                    'material_id' => null,
                    'user_id' => $student->id,
                    'text' => 'ممتاز! لقد فهمت جيداً المفاهيم الأساسية في هذا المستوى.',
                    'is_approved' => true,
                ]);
            }
        }

        // Lesson-specific comments (for materials)
        $materials = $course->materials()->take(5)->get();
        foreach ($materials as $material) {
            foreach ($students->take(2) as $student) {
                CourseComment::create([
                    'course_id' => $course->id,
                    'level_id' => null,
                    'material_id' => $material->id,
                    'user_id' => $student->id,
                    'text' => 'ممتاز! لقد فهمت جيداً هذا الدرس.',
                    'is_approved' => true,
                ]);
            }
        }
    }

    private function getRandomRatingComment(int $rating): string
    {
        $comments = [
            5 => ['ممتاز!', 'دورة رائعة', 'أنصح بها بشدة', 'استثنائية'],
            4 => ['جيد جداً', 'مفيدة', 'أعجبتني', 'جودة عالية'],
            3 => ['جيد', 'مقبول', 'يحتاج تحسين', 'عادي'],
        ];

        return $comments[$rating][array_rand($comments[$rating])] ?? 'تقييم جيد';
    }

    private function getRandomInstructorComment(int $rating): string
    {
        $comments = [
            5 => ['محاضرممتاز', 'شرح واضح ومفصل', 'أسلوب تدريس رائع'],
            4 => ['محاضرجيد', 'شرح واضح', 'أسلوب جيد'],
            3 => ['مقبول', 'يحتاج تحسين', 'عادي'],
        ];

        return $comments[$rating][array_rand($comments[$rating])] ?? 'محاضرجيد';
    }

    private function createQuizzes(Course $course): void
    {
        // Quiz 1: HTML & CSS Quiz
        $quiz1 = Quiz::create([
            'course_id' => $course->id,
            'title' => 'اختبار HTML و CSS الأساسي',
            'description' => 'اختبار شامل لأساسيات HTML و CSS',
            'passing_score' => 70,
            'max_attempts' => 3,
            'is_active' => true,
        ]);

        // Add HTML & CSS quiz questions
        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'أي من هذه العناصر يستخدم لإنشاء قائمة غير مرتبة؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'ما هي الخاصية المستخدمة لتغيير لون النص في CSS؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 2,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'أي من هذه العناصر يستخدم لإنشاء رابط؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 3,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'CSS تعني Cascading Style Sheets',
            'type' => 'true_false',
            'points' => 10,
            'order' => 4,
        ]);

        // Quiz 2: JavaScript Quiz
        $quiz2 = Quiz::create([
            'course_id' => $course->id,
            'title' => 'اختبار JavaScript الأساسي',
            'description' => 'اختبار شامل لأساسيات JavaScript',
            'passing_score' => 75,
            'max_attempts' => 3,
            'is_active' => true,
        ]);

        // Add JavaScript quiz questions
        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'أي من هذه الكلمات تستخدم لإعلان متغير في JavaScript؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'ما هو نوع البيانات الصحيح للنص في JavaScript؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 2,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'كيف يتم تعريف دالة في JavaScript؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 3,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'JavaScript هي لغة برمجة تعمل فقط في المتصفح',
            'type' => 'true_false',
            'points' => 10,
            'order' => 4,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'أي من هذه الطرق تستخدم لاختيار عنصر بواسطة المعرف؟',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 5,
        ]);
    }
}
