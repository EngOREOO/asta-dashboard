<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DegreeApiTest extends TestCase
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

    public function test_can_get_all_degrees_with_average_rating_and_price(): void
    {
        // Create degrees
        $degree1 = Degree::factory()->create([
            'name' => 'Primary School',
            'name_ar' => 'المرحلة الابتدائية',
            'level' => 1,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $degree2 = Degree::factory()->create([
            'name' => 'Intermediate School',
            'name_ar' => 'المرحلة المتوسطة',
            'level' => 2,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Create instructor
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        // Create courses with ratings and prices
        Course::factory()->create([
            'instructor_id' => $instructor->id,
            'degree_id' => $degree1->id,
            'price' => 25.00,
            'average_rating' => 4.5,
            'total_ratings' => 10,
            'status' => 'approved',
        ]);

        Course::factory()->create([
            'instructor_id' => $instructor->id,
            'degree_id' => $degree1->id,
            'price' => 35.00,
            'average_rating' => 4.0,
            'total_ratings' => 8,
            'status' => 'approved',
        ]);

        Course::factory()->create([
            'instructor_id' => $instructor->id,
            'degree_id' => $degree2->id,
            'price' => 45.00,
            'average_rating' => 4.8,
            'total_ratings' => 15,
            'status' => 'approved',
        ]);

        $response = $this->getJson('/api/degrees');

        $response->assertStatus(200);

        // Check if response is wrapped in data key
        $responseData = $response->json();
        if (isset($responseData['data'])) {
            $degrees = $responseData['data'];
        } else {
            $degrees = $responseData;
        }

        $this->assertCount(2, $degrees);

        // Check structure
        $this->assertArrayHasKey('id', $degrees[0]);
        $this->assertArrayHasKey('average_rating', $degrees[0]);
        $this->assertArrayHasKey('price', $degrees[0]);

        // Find degrees by name and check values
        $primaryDegree = collect($degrees)->firstWhere('name', 'Primary School');
        $intermediateDegree = collect($degrees)->firstWhere('name', 'Intermediate School');

        $this->assertNotNull($primaryDegree);
        $this->assertNotNull($intermediateDegree);

        $this->assertEquals(2, $primaryDegree['courses_count']);
        $this->assertEquals(4.3, $primaryDegree['average_rating']); // (4.5 + 4.0) / 2 = 4.25, rounded to 4.3
        $this->assertEquals(30.00, $primaryDegree['price']); // (25.00 + 35.00) / 2 = 30.00

        $this->assertEquals(1, $intermediateDegree['courses_count']);
        $this->assertEquals(4.8, $intermediateDegree['average_rating']);
        $this->assertEquals(45.00, $intermediateDegree['price']);
    }

    public function test_can_get_specific_degree_with_average_rating_and_price(): void
    {
        $degree = Degree::factory()->create([
            'name' => 'Bachelor\'s Degree',
            'name_ar' => 'بكالوريوس',
            'level' => 5,
            'is_active' => true,
            'sort_order' => 5,
        ]);

        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        Course::factory()->create([
            'instructor_id' => $instructor->id,
            'degree_id' => $degree->id,
            'price' => 99.99,
            'average_rating' => 4.7,
            'total_ratings' => 25,
            'status' => 'approved',
        ]);

        Course::factory()->create([
            'instructor_id' => $instructor->id,
            'degree_id' => $degree->id,
            'price' => 149.99,
            'average_rating' => 4.2,
            'total_ratings' => 18,
            'status' => 'approved',
        ]);

        $response = $this->getJson("/api/degrees/{$degree->id}");

        $response->assertStatus(200);

        $responseData = $response->json();
        if (isset($responseData['data'])) {
            $degreeData = $responseData['data'];
        } else {
            $degreeData = $responseData;
        }

        $this->assertEquals($degree->id, $degreeData['id']);
        $this->assertEquals('Bachelor\'s Degree', $degreeData['name']);
        $this->assertEquals(2, $degreeData['courses_count']);
        $this->assertEquals(4.5, $degreeData['average_rating']); // (4.7 + 4.2) / 2 = 4.45, rounded to 4.5
        $this->assertEquals(124.99, $degreeData['price']); // (99.99 + 149.99) / 2 = 124.99, rounded to 124.99
    }

    public function test_degrees_with_no_courses_return_zero_ratings_and_prices(): void
    {
        $degree = Degree::factory()->create([
            'name' => 'Empty Degree',
            'name_ar' => 'مسار فارغة',
            'level' => 8,
            'is_active' => true,
            'sort_order' => 8,
        ]);

        $response = $this->getJson("/api/degrees/{$degree->id}");

        $response->assertStatus(200);

        $responseData = $response->json();
        if (isset($responseData['data'])) {
            $degreeData = $responseData['data'];
        } else {
            $degreeData = $responseData;
        }

        $this->assertEquals($degree->id, $degreeData['id']);
        $this->assertEquals(0, $degreeData['courses_count']);
        $this->assertEquals(0, $degreeData['average_rating']);
        $this->assertEquals(0, $degreeData['price']);
    }

    public function test_only_active_degrees_are_returned(): void
    {
        Degree::factory()->create([
            'name' => 'Active Degree',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Degree::factory()->create([
            'name' => 'Inactive Degree',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/degrees');

        $response->assertStatus(200);

        $responseData = $response->json();
        if (isset($responseData['data'])) {
            $degrees = $responseData['data'];
        } else {
            $degrees = $responseData;
        }

        $this->assertCount(1, $degrees);

        $activeDegree = collect($degrees)->firstWhere('name', 'Active Degree');
        $inactiveDegree = collect($degrees)->firstWhere('name', 'Inactive Degree');

        $this->assertNotNull($activeDegree);
        $this->assertNull($inactiveDegree);
    }

    public function test_degrees_are_ordered_by_sort_order(): void
    {
        Degree::factory()->create([
            'name' => 'Second Degree',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Degree::factory()->create([
            'name' => 'First Degree',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/degrees');

        $response->assertStatus(200);

        $responseData = $response->json();
        if (isset($responseData['data'])) {
            $degrees = $responseData['data'];
        } else {
            $degrees = $responseData;
        }

        $this->assertCount(2, $degrees);
        $this->assertEquals('First Degree', $degrees[0]['name']);
        $this->assertEquals('Second Degree', $degrees[1]['name']);
    }
}
