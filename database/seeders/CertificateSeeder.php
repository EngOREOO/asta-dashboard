<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        
        $students = User::role('student')->get();
        $courses = Course::all();

        // Create certificates for completed courses
        foreach ($students as $student) {
            $enrolledCourses = $student->enrolledCourses;
            
            foreach ($enrolledCourses as $course) {
                // Check if student completed the course (80% progress)
                $totalMaterials = $course->materials()->count();
                $completedMaterials = $course->materials()
                    ->whereHas('progress', function ($query) use ($student) {
                        $query->where('user_id', $student->id)
                              ->whereNotNull('completed_at');
                    })->count();
                
                $progressPercentage = $totalMaterials > 0 ? ($completedMaterials / $totalMaterials) * 100 : 0;
                
                // Create certificate if course is completed (80% or more)
                if ($progressPercentage >= 80) {
                    Certificate::firstOrCreate([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                    ], [
                        'issued_at' => now()->subDays(rand(1, 90)),
                        'certificate_url' => 'certificates/' . $student->id . '_' . $course->id . '.pdf',
                    ]);
                }
            }
        }

        $this->command->info('Certificates seeded successfully!');
    }
} 