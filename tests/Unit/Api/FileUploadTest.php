<?php

namespace Tests\Unit\Api;

use App\Models\Category;
use App\Models\Course;
use App\Models\FileUpload;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
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

        // Set up storage for testing
        Storage::fake('local');
    }

    public function test_instructor_can_initiate_file_upload()
    {
        $uploadData = [
            'file_name' => 'test_video.mp4',
            'file_type' => 'video',
            'file_size' => 1024 * 1024, // 1MB
            'mime_type' => 'video/mp4',
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/file-uploads/courses/{$this->course->id}/initiate", $uploadData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'upload' => [
                    'id',
                    'file_name',
                    'status',
                    'progress',
                    'upload_url',
                ],
            ]);

        $this->assertDatabaseHas('file_uploads', [
            'course_id' => $this->course->id,
            'file_name' => 'test_video.mp4',
            'file_type' => 'video',
            'status' => 'pending',
            'progress' => 0,
        ]);
    }

    public function test_instructor_can_upload_file_chunk()
    {
        // First create an upload record
        $upload = FileUpload::factory()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
            'file_name' => 'test_video.mp4',
            'file_type' => 'video',
            'status' => 'pending',
        ]);

        $chunkData = [
            'chunk' => UploadedFile::fake()->create('chunk', 1024), // 1KB chunk
            'chunk_number' => 0,
            'total_chunks' => 1,
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/file-uploads/{$upload->id}/chunk", $chunkData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'upload' => [
                    'id',
                    'progress',
                    'status',
                ],
            ]);

        // Check that the upload was completed
        $this->assertDatabaseHas('file_uploads', [
            'id' => $upload->id,
            'status' => 'completed',
            'progress' => 100,
        ]);
    }

    public function test_instructor_can_get_upload_status()
    {
        $upload = FileUpload::factory()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
            'status' => 'completed',
            'progress' => 100,
        ]);

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->getJson("/api/file-uploads/{$upload->id}/status");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'upload' => [
                    'id',
                    'file_name',
                    'status',
                    'progress',
                    'file_path',
                    'file_size',
                    'duration',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_instructor_can_cancel_upload()
    {
        $upload = FileUpload::factory()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->deleteJson("/api/file-uploads/{$upload->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم إلغاء عملية الرفع بنجاح',
            ]);

        // Check that the upload was soft deleted
        $this->assertSoftDeleted('file_uploads', [
            'id' => $upload->id,
        ]);
    }

    public function test_instructor_can_get_course_uploads()
    {
        // Create multiple uploads
        FileUpload::factory()->count(3)->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
        ]);

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->getJson("/api/file-uploads/courses/{$this->course->id}/uploads");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'uploads' => [
                    '*' => [
                        'id',
                        'file_name',
                        'file_type',
                        'status',
                        'progress',
                        'file_size',
                        'duration',
                        'uploader',
                        'created_at',
                    ],
                ],
            ]);

        $this->assertCount(3, $response->json('uploads'));
    }

    public function test_non_instructor_cannot_initiate_upload()
    {
        $user = User::factory()->create();

        $uploadData = [
            'file_name' => 'test_video.mp4',
            'file_type' => 'video',
            'file_size' => 1024 * 1024,
            'mime_type' => 'video/mp4',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/file-uploads/courses/{$this->course->id}/initiate", $uploadData);

        $response->assertStatus(403);
    }

    public function test_instructor_cannot_upload_to_other_instructor_course()
    {
        $otherInstructor = User::factory()->create();
        $otherInstructor->assignRole('instructor');

        $otherCourse = Course::factory()->create([
            'instructor_id' => $otherInstructor->id,
            'category_id' => $this->category->id,
        ]);

        $uploadData = [
            'file_name' => 'test_video.mp4',
            'file_type' => 'video',
            'file_size' => 1024 * 1024,
            'mime_type' => 'video/mp4',
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/file-uploads/courses/{$otherCourse->id}/initiate", $uploadData);

        $response->assertStatus(403);
    }

    public function test_upload_validation_requires_file_name()
    {
        $uploadData = [
            'file_type' => 'video',
            'file_size' => 1024 * 1024,
            'mime_type' => 'video/mp4',
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/file-uploads/courses/{$this->course->id}/initiate", $uploadData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file_name']);
    }

    public function test_upload_validation_requires_valid_file_type()
    {
        $uploadData = [
            'file_name' => 'test_video.mp4',
            'file_type' => 'invalid_type',
            'file_size' => 1024 * 1024,
            'mime_type' => 'video/mp4',
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/file-uploads/courses/{$this->course->id}/initiate", $uploadData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file_type']);
    }

    public function test_chunk_upload_validation_requires_chunk_file()
    {
        $upload = FileUpload::factory()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
        ]);

        $chunkData = [
            'chunk_number' => 0,
            'total_chunks' => 1,
        ];

        $response = $this->actingAs($this->instructor, 'sanctum')
            ->postJson("/api/file-uploads/{$upload->id}/chunk", $chunkData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['chunk']);
    }

    public function test_file_upload_factory_creates_valid_uploads()
    {
        $upload = FileUpload::factory()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
        ]);

        $this->assertNotNull($upload->id);
        $this->assertNotNull($upload->file_name);
        $this->assertNotNull($upload->file_type);
        $this->assertNotNull($upload->mime_type);
        $this->assertGreaterThan(0, $upload->file_size);
    }

    public function test_file_upload_factory_states_work_correctly()
    {
        $completedUpload = FileUpload::factory()->completed()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
        ]);

        $this->assertEquals('completed', $completedUpload->status);
        $this->assertEquals(100, $completedUpload->progress);

        $videoUpload = FileUpload::factory()->video()->create([
            'course_id' => $this->course->id,
            'uploaded_by' => $this->instructor->id,
        ]);

        $this->assertEquals('video', $videoUpload->file_type);
        $this->assertEquals('video/mp4', $videoUpload->mime_type);
        $this->assertNotNull($videoUpload->duration);
        $this->assertNotNull($videoUpload->thumbnail_path);
    }
}
