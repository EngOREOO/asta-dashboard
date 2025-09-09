<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Course;
use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InstructorCoursesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'instructor']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);
    }

    public function test_instructor_can_get_their_courses_with_images_and_all_data(): void
    {
        // Create instructor
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        // Create category and degree
        $category = Category::factory()->create();
        $degree = Degree::factory()->create();

        // Create course with thumbnail
        $course = Course::factory()->create([
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'Test course description',
            'difficulty_level' => 'متوسط',
            'language' => 'العربية',
            'price' => 100,
            'thumbnail' => 'courses/test-thumbnail.jpg',
            'category_id' => $category->id,
            'degree_id' => $degree->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($instructor, 'sanctum')
            ->getJson('/api/instructor/courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'status',
                    'company',
                    'course_name_ar',
                    'rating',
                    'price',
                    'course_id',
                    'total_students',
                    'total_materials',
                    'total_reviews',
                    'created_at',
                    'updated_at',
                    'thumbnail',
                    'description',
                    'difficulty_level',
                    'language',
                    'slug',
                    'status',
                    'rejection_reason',
                    'category' => [
                        'id',
                        'name',
                        'slug',
                    ],
                    'degree' => [
                        'id',
                        'name',
                        'slug',
                    ],
                ],
            ]);

        // Verify the course data is returned
        $response->assertJsonFragment([
            'course_id' => $course->id,
            'thumbnail' => 'courses/test-thumbnail.jpg',
            'description' => 'Test course description',
            'difficulty_level' => 'متوسط',
            'language' => 'العربية',
        ]);
    }

    public function test_instructor_can_get_specific_course_with_images_and_all_data(): void
    {
        // Create instructor
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        // Create category and degree
        $category = Category::factory()->create();
        $degree = Degree::factory()->create();

        // Create course with thumbnail
        $course = Course::factory()->create([
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'Test course description',
            'difficulty_level' => 'متوسط',
            'language' => 'العربية',
            'price' => 100,
            'thumbnail' => 'courses/test-thumbnail.jpg',
            'category_id' => $category->id,
            'degree_id' => $degree->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($instructor, 'sanctum')
            ->getJson("/api/instructor/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'course' => [
                    'courseId',
                    'name',
                    'imageUrl',
                    'thumbnail',
                    'category' => [
                        'id',
                        'name',
                        'slug',
                    ],
                    'level',
                    'difficulty_level',
                    'language',
                    'price',
                    'currency',
                    'description',
                    'slug',
                    'status',
                    'rejection_reason',
                    'created_at',
                    'updated_at',
                    'degree' => [
                        'id',
                        'name',
                        'slug',
                    ],
                ],
                'courseStatistics',
            ]);

        // Verify the course data is returned
        $response->assertJsonFragment([
            'courseId' => $course->id,
            'thumbnail' => 'courses/test-thumbnail.jpg',
            'description' => 'Test course description',
            'difficulty_level' => 'متوسط',
            'language' => 'العربية',
        ]);
    }

    public function test_non_instructor_cannot_access_instructor_endpoints(): void
    {
        // Create regular user
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/instructor/courses');

        $response->assertStatus(403);
    }
}
