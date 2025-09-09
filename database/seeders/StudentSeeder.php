<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseMaterialProgress;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        
        // Get all students
        $students = User::role('student')->get();
        $courses = Course::all();

        // Create wishlist entries for students
        foreach ($students as $student) {
            // Add 2-5 courses to wishlist (or all courses if less than 2)
            $wishlistCount = min(rand(2, 5), $courses->count());
            $randomCourses = $courses->random($wishlistCount);
            
            foreach ($randomCourses as $course) {
                $student->wishlist()->attach($course->id);
            }
        }

        // Create course material progress for enrolled students
        foreach ($students as $student) {
            $enrolledCourses = $student->enrolledCourses;
            
            foreach ($enrolledCourses as $course) {
                $materials = $course->materials;
                
                foreach ($materials as $material) {
                    // 70% chance of starting the material
                    if (rand(1, 100) <= 70) {
                        $isCompleted = rand(1, 100) <= 60; // 60% chance of completion
                        
                        CourseMaterialProgress::firstOrCreate([
                            'user_id' => $student->id,
                            'course_material_id' => $material->id,
                        ], [
                            'completed_at' => $isCompleted ? now()->subDays(rand(1, 30)) : null,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Student data seeded successfully!');
    }
} 