<?php

namespace Tests\Unit\Api;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\CourseMaterial;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorCourseContentTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;

    protected $course;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Run the roles seeder to create the instructor role
        $this->seed(RolesAndPermissionsSeeder::class);

        // Create instructor user
        $this->instructor = User::factory()->create();
        $this->instructor->assignRole('instructor');

        // Create category
        $this->category = Category::factory()->create([
            'name' => 'Programming',
            'slug' => 'programming',
        ]);

        // Create course
        $this->course = Course::factory()->create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->category->id,
            'title' => 'Test Course',
            'description' => 'Test Description',
            'price' => 99.99,
            'status' => 'draft',
        ]);
    }

    public function test_debug_course_level_creation()
    {
        // Simple test to debug the issue
        $this->assertTrue(true);

        // Check if we can create a level manually
        $level = CourseLevel::create([
            'course_id' => $this->course->id,
            'level_name' => 'Test Level',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertNotNull($level);
        $this->assertEquals($this->course->id, $level->course_id);

        // Check if the relationship works
        $course = Course::find($this->course->id);
        $this->assertNotNull($course);
        $this->assertTrue($course->levels()->exists());
    }

    public function test_debug_method_call()
    {
        // Test if the method can be called directly
        $controller = new \App\Http\Controllers\Api\InstructorController;

        // Create a simple request
        $request = new \Illuminate\Http\Request;
        $request->merge([
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 1',
                'type' => 'فيديو',
                'fields' => [
                    'lesson_title' => 'ما هي البرمجة كائنية التوجه؟',
                    'description' => 'وصف تعريفي عن الدرس',
                ],
            ],
        ]);

        // Test if we can access the course
        $this->assertNotNull($this->course);
        $this->assertEquals($this->instructor->id, $this->course->instructor_id);

        // Test if we can create a level manually
        $level = CourseLevel::create([
            'course_id' => $this->course->id,
            'level_name' => 'المسار الأول',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertNotNull($level);

        // Test if the course has the level
        $this->assertTrue($this->course->levels()->exists());
    }

    public function test_existing_add_course_content_endpoint()
    {
        $contentData = [
            'name' => 'Updated Course Name',
            'description' => 'Updated Description',
            'category' => 'Programming',
            'level' => 'متوسط',
            'language' => 'العربية',
            'price' => 199.99,
            'currency' => 'ريال سعودي',
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->putJson("/api/instructor/courses/{$this->course->id}/content", $contentData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'course' => [
                    'courseId',
                    'name',
                    'imageUrl',
                    'category',
                    'level',
                    'language',
                    'price',
                    'currency',
                    'description',
                ],
            ]);
    }

    public function test_instructor_can_add_lesson_to_course()
    {
        $lessonData = [
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 1',
                'type' => 'فيديو',
                'fields' => [
                    'lesson_title' => 'ما هي البرمجة كائنية التوجه؟',
                    'description' => 'وصف تعريفي عن الدرس',
                ],
            ],
            'video' => [
                'preview_placeholder' => 'Video Preview (gray box)',
                'uploads' => [
                    [
                        'file_name' => 'test_video.mp4',
                        'status' => 'مكتمل',
                        'uploader' => 'Test Instructor',
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/lessons", $lessonData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'lesson' => [
                    'id',
                    'level',
                    'title',
                    'type',
                    'fields' => [
                        'lesson_title',
                        'description',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('course_materials', [
            'course_id' => $this->course->id,
            'title' => 'ما هي البرمجة كائنية التوجه؟',
            'type' => 'video',
        ]);
    }

    public function test_instructor_can_add_quiz_lesson()
    {
        $lessonData = [
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 2',
                'type' => 'امتحان',
                'fields' => [
                    'lesson_title' => 'اختبار البرمجة',
                    'description' => 'اختبار في البرمجة كائنية التوجه',
                ],
            ],
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/lessons", $lessonData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('course_materials', [
            'course_id' => $this->course->id,
            'title' => 'اختبار البرمجة',
            'type' => 'quiz',
        ]);

        $this->assertDatabaseHas('assessments', [
            'course_id' => $this->course->id,
            'title' => 'اختبار البرمجة',
            'type' => 'exam',
        ]);
    }

    public function test_instructor_can_add_questions_to_quiz()
    {
        // First create a quiz lesson
        $lessonData = [
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 2',
                'type' => 'امتحان',
                'fields' => [
                    'lesson_title' => 'اختبار البرمجة',
                    'description' => 'اختبار في البرمجة كائنية التوجه',
                ],
            ],
        ];

        $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/lessons", $lessonData);

        $material = CourseMaterial::where('course_id', $this->course->id)->first();

        $questionsData = [
            'questions' => [
                [
                    'question_number' => 1,
                    'question_text' => 'السؤال الأول',
                    'answers' => [
                        [
                            'answer_text' => 'الاجابة الأولى',
                            'is_correct' => false,
                        ],
                        [
                            'answer_text' => 'الاجابة الثانية',
                            'is_correct' => true,
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/lessons/{$material->id}/questions", $questionsData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم إضافة الأسئلة بنجاح',
                'questions_count' => 1,
            ]);
    }

    public function test_instructor_can_get_course_content()
    {
        // Create a lesson first
        $lessonData = [
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 1',
                'type' => 'فيديو',
                'fields' => [
                    'lesson_title' => 'ما هي البرمجة كائنية التوجه؟',
                    'description' => 'وصف تعريفي عن الدرس',
                ],
            ],
        ];

        $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/lessons", $lessonData);

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->getJson("/api/instructor/courses/{$this->course->id}/content");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'course_id',
                'course_name',
                'content' => [
                    '*' => [
                        'level_name',
                        'lessons' => [
                            '*' => [
                                'id',
                                'title',
                                'type',
                                'fields' => [
                                    'lesson_title',
                                    'description',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function test_non_instructor_cannot_add_lesson()
    {
        $user = User::factory()->create();

        $lessonData = [
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 1',
                'type' => 'فيديو',
                'fields' => [
                    'lesson_title' => 'ما هي البرمجة كائنية التوجه؟',
                    'description' => 'وصف تعريفي عن الدرس',
                ],
            ],
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/lessons", $lessonData);

        $response->assertStatus(403);
    }

    public function test_instructor_cannot_add_lesson_to_other_instructor_course()
    {
        $otherInstructor = User::factory()->create();
        $otherInstructor->assignRole('instructor');

        $otherCourse = Course::factory()->create([
            'instructor_id' => $otherInstructor->id,
            'category_id' => $this->category->id,
            'title' => 'Other Course',
            'description' => 'Other Description',
            'price' => 99.99,
            'status' => 'draft',
        ]);

        $lessonData = [
            'lesson' => [
                'level' => 'المسار الأول',
                'title' => 'الدرس 1',
                'type' => 'فيديو',
                'fields' => [
                    'lesson_title' => 'ما هي البرمجة كائنية التوجه؟',
                    'description' => 'وصف تعريفي عن الدرس',
                ],
            ],
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$otherCourse->id}/lessons", $lessonData);

        $response->assertStatus(403);
    }
}
