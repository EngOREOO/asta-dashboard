<?php

namespace Tests\Unit\Api;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseStructureTest extends TestCase
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

    public function test_instructor_can_create_course_structure_with_topics_and_lessons()
    {
        $courseStructure = [
            'topics' => [
                [
                    'title' => 'مقدمة في البرمجة',
                    'description' => 'أساسيات البرمجة للمبتدئين',
                    'order' => 1,
                    'lessons' => [
                        [
                            'title' => 'ما هي البرمجة؟',
                            'type' => 'video',
                            'order' => 1,
                            'fields' => [
                                'lesson_title' => 'تعريف البرمجة',
                                'description' => 'شرح مبسط للبرمجة',
                            ],
                            'video' => [
                                'uploads' => [
                                    [
                                        'file_name' => 'intro_video.mp4',
                                        'status' => 'مكتمل',
                                        'uploader' => 'محمد ابراهيم فحصي',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'title' => 'اختبار الفصل الأول',
                            'type' => 'quiz',
                            'order' => 2,
                            'fields' => [
                                'description' => 'اختبار شامل للفصل الأول',
                            ],
                            'questions' => [
                                [
                                    'question_number' => 1,
                                    'question_text' => 'ما هو تعريف البرمجة؟',
                                    'answers' => [
                                        [
                                            'answer_text' => 'كتابة تعليمات للحاسوب',
                                            'is_correct' => true,
                                        ],
                                        [
                                            'answer_text' => 'تصميم الويب فقط',
                                            'is_correct' => false,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'title' => 'أساسيات HTML',
                    'description' => 'تعلم لغة ترميز النص التشعبي',
                    'order' => 2,
                    'lessons' => [
                        [
                            'title' => 'ملاحظات HTML',
                            'type' => 'files',
                            'order' => 1,
                            'fields' => [
                                'lesson_title' => 'ملاحظات شاملة لـ HTML',
                                'description' => 'ملاحظات مفصلة مع أمثلة عملية',
                            ],
                            'files' => [
                                'list' => [
                                    [
                                        'file_name' => 'html_notes.pdf',
                                        'status' => 'مكتمل',
                                        'uploader' => 'محمد ابراهيم فحصي',
                                    ],
                                    [
                                        'file_name' => 'html_examples.pdf',
                                        'status' => 'مكتمل',
                                        'uploader' => 'محمد ابراهيم فحصي',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/structure", $courseStructure);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم إنشاء هيكل الدورة بنجاح',
                'course_id' => $this->course->id,
            ]);

        // Verify topics were created
        $this->assertDatabaseHas('topics', [
            'course_id' => $this->course->id,
            'title' => 'مقدمة في البرمجة',
            'order' => 1,
        ]);

        $this->assertDatabaseHas('topics', [
            'course_id' => $this->course->id,
            'title' => 'أساسيات HTML',
            'order' => 2,
        ]);

        // Verify lessons were created
        $this->assertDatabaseHas('course_materials', [
            'course_id' => $this->course->id,
            'title' => 'ما هي البرمجة؟',
            'type' => 'video',
            'order' => 1,
        ]);

        $this->assertDatabaseHas('course_materials', [
            'course_id' => $this->course->id,
            'title' => 'اختبار الفصل الأول',
            'type' => 'quiz',
            'order' => 2,
        ]);

        $this->assertDatabaseHas('course_materials', [
            'course_id' => $this->course->id,
            'title' => 'ملاحظات HTML',
            'type' => 'files',
            'order' => 1,
        ]);
    }

    public function test_instructor_can_get_course_structure()
    {
        // Create a topic with lessons
        $topic = Topic::create([
            'course_id' => $this->course->id,
            'title' => 'مقدمة في البرمجة',
            'description' => 'أساسيات البرمجة',
            'order' => 1,
            'is_active' => true,
        ]);

        $lesson = CourseMaterial::create([
            'course_id' => $this->course->id,
            'topic_id' => $topic->id,
            'title' => 'ما هي البرمجة؟',
            'type' => 'video',
            'order' => 1,
            'is_active' => true,
            'file_path' => 'videos/intro.mp4',
        ]);

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->getJson("/api/instructor/courses/{$this->course->id}/structure");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'course' => [
                    'id',
                    'title',
                    'description',
                    'instructor',
                ],
                'topics' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'order',
                        'is_active',
                        'lessons' => [
                            '*' => [
                                'id',
                                'title',
                                'type',
                                'order',
                                'is_active',
                                'duration',
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertCount(1, $response->json('topics'));
        $this->assertCount(1, $response->json('topics.0.lessons'));
    }

    public function test_non_instructor_cannot_create_course_structure()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/structure", []);

        $response->assertStatus(403);
    }

    public function test_instructor_cannot_modify_other_instructor_course()
    {
        $otherInstructor = User::factory()->create();
        $otherInstructor->assignRole('instructor');

        $otherCourse = Course::factory()->create([
            'instructor_id' => $otherInstructor->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$otherCourse->id}/structure", []);

        $response->assertStatus(403);
    }

    public function test_course_structure_validation_requires_topics()
    {
        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/structure", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topics']);
    }

    public function test_course_structure_validation_requires_lessons()
    {
        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/instructor/courses/{$this->course->id}/structure", [
                'topics' => [
                    [
                        'title' => 'Test Topic',
                        'description' => 'Test Description',
                        'order' => 1,
                        'lessons' => [], // Empty lessons array
                    ],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topics.0.lessons']);
    }

    public function test_topic_model_relationships_work_correctly()
    {
        $topic = Topic::create([
            'course_id' => $this->course->id,
            'title' => 'Test Topic',
            'description' => 'Test Description',
            'order' => 1,
            'is_active' => true,
        ]);

        $lesson = CourseMaterial::create([
            'course_id' => $this->course->id,
            'topic_id' => $topic->id,
            'title' => 'Test Lesson',
            'type' => 'video',
            'order' => 1,
            'is_active' => true,
        ]);

        // Test topic -> course relationship
        $this->assertEquals($this->course->id, $topic->course->id);

        // Test topic -> lessons relationship
        $this->assertCount(1, $topic->lessons);
        $this->assertEquals($lesson->id, $topic->lessons->first()->id);

        // Test course -> topics relationship
        $this->assertCount(1, $this->course->topics);
        $this->assertEquals($topic->id, $this->course->topics->first()->id);
    }

    public function test_course_material_model_has_topic_relationship()
    {
        $topic = Topic::create([
            'course_id' => $this->course->id,
            'title' => 'Test Topic',
            'order' => 1,
        ]);

        $lesson = CourseMaterial::create([
            'course_id' => $this->course->id,
            'topic_id' => $topic->id,
            'title' => 'Test Lesson',
            'type' => 'video',
            'order' => 1,
        ]);

        // Test lesson -> topic relationship
        $this->assertEquals($topic->id, $lesson->topic->id);
    }
}
