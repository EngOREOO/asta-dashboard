<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRating;
use App\Models\InstructorRating;
use App\Models\Review;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        $students = User::role('student')->get();
        $courses = Course::all();

        // Create course reviews
        foreach ($courses as $course) {
            $reviewCount = rand(5, 25);

            for ($i = 0; $i < $reviewCount; $i++) {
                $student = $students->random();

                // Create course review
                Review::firstOrCreate([
                    'course_id' => $course->id,
                    'user_id' => $student->id,
                ], [
                    'rating' => rand(3, 5),
                    'message' => $faker->paragraph(3),
                    'is_approved' => rand(1, 100) <= 90, // 90% approved
                ]);

                // Create course rating
                CourseRating::firstOrCreate([
                    'course_id' => $course->id,
                    'user_id' => $student->id,
                ], [
                    'rating' => rand(3, 5),
                    'comment' => $faker->paragraph(2),
                ]);

                // Create instructor rating
                InstructorRating::firstOrCreate([
                    'instructor_id' => $course->instructor_id,
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ], [
                    'rating' => rand(3, 5),
                    'comment' => $faker->paragraph(2),
                ]);
            }
        }

        $this->command->info('Reviews seeded successfully!');
    }
}
