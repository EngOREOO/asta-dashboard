<?php

namespace Tests\Unit\Api;

use App\Models\Category;
use App\Models\Course;
use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningPathTest extends TestCase
{
    use RefreshDatabase;

    public function test_learning_paths_can_be_filtered_by_category()
    {
        // Create categories
        $programmingCategory = Category::factory()->create([
            'name' => 'Programming',
            'slug' => 'programming',
        ]);

        $designCategory = Category::factory()->create([
            'name' => 'Design',
            'slug' => 'design',
        ]);

        // Create an instructor
        $instructor = User::factory()->create([
            'name' => 'John Doe',
            'profile_photo_path' => 'profile.jpg',
        ]);

        // Create courses in different categories
        $programmingCourse = Course::factory()->create([
            'title' => 'Laravel Basics',
            'category_id' => $programmingCategory->id,
            'instructor_id' => $instructor->id,
            'price' => 99.99,
            'average_rating' => 4.5,
            'total_ratings' => 10,
        ]);

        $designCourse = Course::factory()->create([
            'title' => 'UI/UX Design',
            'category_id' => $designCategory->id,
            'instructor_id' => $instructor->id,
            'price' => 149.99,
            'average_rating' => 4.8,
            'total_ratings' => 25,
        ]);

        // Create learning paths
        $programmingPath = LearningPath::factory()->create([
            'name' => 'Web Development Path',
            'description' => 'Learn web development from scratch',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $designPath = LearningPath::factory()->create([
            'name' => 'Design Mastery',
            'description' => 'Master the art of design',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Attach courses to learning paths
        $programmingPath->courses()->attach($programmingCourse->id, ['order' => 1]);
        $designPath->courses()->attach($designCourse->id, ['order' => 1]);

        // Test filtering by programming category
        $response = $this->getJson('/api/learning-paths?category='.$programmingCategory->id);

        $response->assertStatus(200);

        $paths = $response->json();
        $this->assertCount(1, $paths);
        $this->assertEquals('Web Development Path', $paths[0]['name']);
        $this->assertTrue(collect($paths[0]['courses'])->contains('title', 'Laravel Basics'));

        // Test filtering by design category
        $response = $this->getJson('/api/learning-paths?category='.$designCategory->id);

        $response->assertStatus(200);

        $paths = $response->json();
        $this->assertCount(1, $paths);
        $this->assertEquals('Design Mastery', $paths[0]['name']);
        $this->assertTrue(collect($paths[0]['courses'])->contains('title', 'UI/UX Design'));

        // Test without category filter (should return all paths)
        $response = $this->getJson('/api/learning-paths');

        $response->assertStatus(200);

        $paths = $response->json();
        $this->assertCount(2, $paths);
    }

    public function test_learning_paths_filter_returns_empty_when_category_has_no_courses()
    {
        // Create a category
        $category = Category::factory()->create([
            'name' => 'Empty Category',
            'slug' => 'empty-category',
        ]);

        // Create a learning path with no courses
        $learningPath = LearningPath::factory()->create([
            'name' => 'Empty Path',
            'description' => 'A path with no courses',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Test filtering by category with no courses
        $response = $this->getJson('/api/learning-paths?category='.$category->id);

        $response->assertStatus(200);

        $paths = $response->json();
        $this->assertCount(0, $paths);
    }
}
