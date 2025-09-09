<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Course;
use App\Models\Degree;
use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_global_search_requires_search_term(): void
    {
        $response = $this->getJson('/api/search');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    public function test_global_search_validates_search_term_length(): void
    {
        $response = $this->getJson('/api/search?q=a');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    public function test_global_search_validates_type_parameter(): void
    {
        $response = $this->getJson('/api/search?q=test&type=invalid');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    public function test_global_search_returns_courses(): void
    {
        $category = Category::factory()->create();
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        $course = Course::factory()->create([
            'title' => 'Test Course',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'status' => 'approved',
        ]);

        $response = $this->getJson('/api/search?q=Test&type=courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'courses' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'description',
                        'type',
                        'instructor',
                        'category',
                    ],
                ],
                'summary',
            ]);

        $this->assertCount(1, $response->json('courses'));
        $this->assertEquals('Test Course', $response->json('courses.0.title'));
        $this->assertArrayHasKey('description', $response->json('courses.0'));
    }

    public function test_global_search_returns_degrees(): void
    {
        $category = Category::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Test Degree',
        ]);
        $course = Course::factory()->create([
            'category_id' => $category->id,
            'degree_id' => $degree->id,
            'status' => 'approved',
        ]);

        $response = $this->getJson('/api/search?q=Test&type=degrees');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'degrees' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'courses_count',
                    ],
                ],
                'summary',
            ]);

        $this->assertCount(1, $response->json('degrees'));
        $this->assertEquals('Test Degree', $response->json('degrees.0.name'));
    }

    public function test_global_search_returns_learning_paths(): void
    {
        $category = Category::factory()->create();
        $course = Course::factory()->create([
            'category_id' => $category->id,
            'status' => 'approved',
        ]);
        $learningPath = LearningPath::factory()->create([
            'name' => 'Test Learning Path',
        ]);
        $learningPath->courses()->attach($course, ['order' => 1]);

        $response = $this->getJson('/api/search?q=Test&type=learning_paths');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'learning_paths' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'description',
                        'image',
                        'image_url',
                        'type',
                        'courses_count',
                    ],
                ],
                'summary',
            ]);

        $this->assertCount(1, $response->json('learning_paths'));
        $this->assertEquals('Test Learning Path', $response->json('learning_paths.0.name'));
    }

    public function test_global_search_returns_categories(): void
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
        ]);

        $response = $this->getJson('/api/search?q=Test&type=categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'type',
                        'courses_count',
                    ],
                ],
                'summary',
            ]);

        $this->assertCount(1, $response->json('categories'));
        $this->assertEquals('Test Category', $response->json('categories.0.name'));
    }

    public function test_global_search_returns_instructors(): void
    {
        $instructor = User::factory()->create([
            'name' => 'Test Instructor',
        ]);
        $instructor->assignRole('instructor');

        $response = $this->getJson('/api/search?q=Test&type=instructors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'instructors' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'courses_count',
                    ],
                ],
                'summary',
            ]);

        $this->assertCount(1, $response->json('instructors'));
        $this->assertEquals('Test Instructor', $response->json('instructors.0.name'));
    }

    public function test_global_search_returns_all_types(): void
    {
        $category = Category::factory()->create(['name' => 'Test Category']);
        $instructor = User::factory()->create(['name' => 'Test Instructor']);
        $instructor->assignRole('instructor');
        $degree = Degree::factory()->create(['name' => 'Test Degree']);
        $course = Course::factory()->create([
            'title' => 'Test Course',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'degree_id' => $degree->id,
            'status' => 'approved',
        ]);
        $learningPath = LearningPath::factory()->create(['name' => 'Test Learning Path']);
        $learningPath->courses()->attach($course, ['order' => 1]);

        $response = $this->getJson('/api/search?q=Test');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'courses',
                'degrees',
                'learning_paths',
                'categories',
                'instructors',
                'summary',
            ]);

        $this->assertGreaterThan(0, count($response->json('courses')));
        $this->assertGreaterThan(0, count($response->json('degrees')));
        $this->assertGreaterThan(0, count($response->json('learning_paths')));
        $this->assertGreaterThan(0, count($response->json('categories')));
        $this->assertGreaterThan(0, count($response->json('instructors')));
    }

    public function test_global_search_with_category_filter(): void
    {
        $category1 = Category::factory()->create(['name' => 'Category 1']);
        $category2 = Category::factory()->create(['name' => 'Category 2']);

        $course1 = Course::factory()->create([
            'title' => 'Test Course 1',
            'category_id' => $category1->id,
            'status' => 'approved',
        ]);
        $course2 = Course::factory()->create([
            'title' => 'Test Course 2',
            'category_id' => $category2->id,
            'status' => 'approved',
        ]);

        $response = $this->getJson("/api/search?q=Test&category_id={$category1->id}");

        $response->assertStatus(200);

        $courses = $response->json('courses');
        $this->assertCount(1, $courses);
        $this->assertEquals($category1->id, $courses[0]['category']['id']);
    }

    public function test_global_search_returns_descriptions_for_all_types(): void
    {
        // Create test data with descriptions
        $category = Category::factory()->create([
            'name' => 'Primary Category',
            'description' => 'Primary category description',
        ]);

        $instructor = User::factory()->create([
            'name' => 'Primary Instructor',
            'bio' => 'Primary instructor bio',
        ]);
        $instructor->assignRole('instructor');

        $degree = Degree::factory()->create([
            'name' => 'Primary Degree',
            'description' => 'Primary degree description',
        ]);

        $course = Course::factory()->create([
            'title' => 'Primary Course',
            'description' => 'Primary course description',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'degree_id' => $degree->id,
            'status' => 'approved',
        ]);

        $learningPath = LearningPath::factory()->create([
            'name' => 'Primary Learning Path',
            'description' => 'Primary learning path description',
        ]);
        $learningPath->courses()->attach($course, ['order' => 1]);

        // Test the specific search query mentioned by the user
        $response = $this->getJson('/api/search?q=Primary&type=all&category_id=1&limit=2');

        $response->assertStatus(200);

        // Verify that all result types include descriptions
        if ($response->json('courses')) {
            foreach ($response->json('courses') as $course) {
                $this->assertArrayHasKey('description', $course);
                $this->assertNotNull($course['description']);
            }
        }

        if ($response->json('degrees')) {
            foreach ($response->json('degrees') as $degree) {
                $this->assertArrayHasKey('description', $degree);
                $this->assertNotNull($degree['description']);
            }
        }

        if ($response->json('learning_paths')) {
            foreach ($response->json('learning_paths') as $path) {
                $this->assertArrayHasKey('description', $path);
                $this->assertNotNull($path['description']);
            }
        }

        if ($response->json('categories')) {
            foreach ($response->json('categories') as $category) {
                $this->assertArrayHasKey('description', $category);
                $this->assertNotNull($category['description']);
            }
        }

        if ($response->json('instructors')) {
            foreach ($response->json('instructors') as $instructor) {
                $this->assertArrayHasKey('bio', $instructor);
                $this->assertNotNull($instructor['bio']);
            }
        }
    }
}
