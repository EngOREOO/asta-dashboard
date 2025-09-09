<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\InstructorRating;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        
        // Get all instructors and students
        $instructors = User::role('instructor')->get();
        $students = User::role('student')->get();
        $courses = Course::all();

        if ($instructors->isEmpty()) {
            $this->command->info('No instructors found. Skipping instructor ratings.');
            return;
        }

        if ($courses->isEmpty()) {
            $this->command->info('No courses found. Skipping instructor ratings.');
            return;
        }

        // Create instructor ratings for courses
        foreach ($courses as $course) {
            $instructor = $course->instructor;
            
            // Create 3-8 ratings for each instructor's course
            $ratingCount = rand(3, 8);
            
            for ($i = 0; $i < $ratingCount; $i++) {
                $student = $students->random();
                
                InstructorRating::firstOrCreate([
                    'instructor_id' => $instructor->id,
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ], [
                    'rating' => rand(3, 5),
                    'comment' => $faker->paragraph(2),
                ]);
            }
        }

        $this->command->info('Instructor ratings seeded successfully!');
    }
} 