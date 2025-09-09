<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'instructor_id' => User::factory(), // or use an existing instructor's ID
            'title' => $this->faker->sentence(4),
            'slug' => Str::slug($this->faker->unique()->sentence(4)),
            'description' => $this->faker->paragraph,
            'thumbnail' => null,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'status' => 'approved',
            'rejection_reason' => null,
        ];
    }
}