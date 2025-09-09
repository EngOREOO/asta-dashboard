<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseMaterialProgress;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');

        // Get all students and courses
        $students = User::role('student')->get();
        $courses = Course::all();

        if ($students->isEmpty()) {
            $this->command->error('No students found. Please run UserSeeder first.');

            return;
        }

        if ($courses->isEmpty()) {
            $this->command->error('No courses found. Please run CourseSeeder first.');

            return;
        }

        // Enroll students in courses with realistic patterns
        foreach ($students as $student) {
            // Each student enrolls in 2-6 courses (or all courses if less than 2)
            $enrollmentCount = min(rand(2, 6), $courses->count());
            $randomCourses = $courses->random($enrollmentCount);

            foreach ($randomCourses as $course) {
                // Enroll student in course
                $enrollmentDate = now()->subDays(rand(1, 90));
                $student->enrolledCourses()->syncWithoutDetaching([
                    $course->id => ['enrolled_at' => $enrollmentDate],
                ]);

                // Create progress for course materials
                $materials = $course->materials;
                $totalMaterials = $materials->count();
                $completedMaterials = rand(0, $totalMaterials);

                foreach ($materials as $index => $material) {
                    $isCompleted = $index < $completedMaterials;
                    $completedAt = $isCompleted ? $enrollmentDate->copy()->addDays(rand(1, 30)) : null;

                    CourseMaterialProgress::firstOrCreate([
                        'user_id' => $student->id,
                        'course_material_id' => $material->id,
                    ], [
                        'completed_at' => $completedAt,
                    ]);
                }
            }
        }

        $this->command->info('Enrollments seeded successfully!');
        $this->command->info('Students enrolled in 2-6 courses each with realistic progress.');
    }
}
