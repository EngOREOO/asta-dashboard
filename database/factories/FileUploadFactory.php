<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileUpload>
 */
class FileUploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileTypes = ['video', 'pdf', 'ppt', 'doc', 'image', 'other'];
        $fileType = $this->faker->randomElement($fileTypes);

        $mimeTypes = [
            'video' => 'video/mp4',
            'pdf' => 'application/pdf',
            'ppt' => 'application/vnd.ms-powerpoint',
            'doc' => 'application/msword',
            'image' => 'image/jpeg',
            'other' => 'application/octet-stream',
        ];

        return [
            'course_id' => Course::factory(),
            'material_id' => null, // Optional, can be set later
            'uploaded_by' => User::factory(),
            'file_name' => $this->faker->word().'.'.$fileType,
            'original_name' => $this->faker->word().'.'.$fileType,
            'file_path' => 'uploads/'.$this->faker->numberBetween(1, 100).'/'.$fileType.'/'.$this->faker->uuid.'.'.$fileType,
            'file_type' => $fileType,
            'mime_type' => $mimeTypes[$fileType],
            'file_size' => $this->faker->numberBetween(1024, 100 * 1024 * 1024), // 1KB to 100MB
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'failed']),
            'progress' => $this->faker->numberBetween(0, 100),
            'error_message' => null,
            'duration' => $fileType === 'video' ? $this->faker->numberBetween(30, 3600) : null, // 30 seconds to 1 hour
            'thumbnail_path' => $fileType === 'video' ? 'thumbnails/'.$this->faker->uuid.'.jpg' : null,
            'metadata' => [
                'uploaded_at' => now()->toISOString(),
                'file_extension' => $fileType,
            ],
        ];
    }

    /**
     * Indicate that the upload is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'progress' => 100,
            'error_message' => null,
        ]);
    }

    /**
     * Indicate that the upload is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'progress' => $this->faker->numberBetween(1, 99),
            'error_message' => null,
        ]);
    }

    /**
     * Indicate that the upload failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'progress' => 0,
            'error_message' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the upload is a video.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_type' => 'video',
            'mime_type' => 'video/mp4',
            'duration' => $this->faker->numberBetween(30, 3600),
            'thumbnail_path' => 'thumbnails/'.$this->faker->uuid.'.jpg',
        ]);
    }

    /**
     * Indicate that the upload is a document.
     */
    public function document(): static
    {
        $docTypes = ['pdf', 'ppt', 'doc'];
        $docType = $this->faker->randomElement($docTypes);

        $mimeTypes = [
            'pdf' => 'application/pdf',
            'ppt' => 'application/vnd.ms-powerpoint',
            'doc' => 'application/msword',
        ];

        return $this->state(fn (array $attributes) => [
            'file_type' => $docType,
            'mime_type' => $mimeTypes[$docType],
            'duration' => null,
            'thumbnail_path' => null,
        ]);
    }
}
