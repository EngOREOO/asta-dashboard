<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            // CategorySeeder::class,
            // DegreeSeeder::class,
            // InstructorSeeder::class,
            // CourseSeeder::class,
            // CourseMaterialSeeder::class,
            // StudentSeeder::class,
            // EnrollmentSeeder::class,
            // ReviewSeeder::class,
            // AssessmentSeeder::class,
            // CertificateSeeder::class,
            // InstructorApplicationSeeder::class,
            // PartnerSeeder::class,
            // CourseLevelSeeder::class,
            // InVideoQuizSeeder::class,
            // LearningPathSeeder::class,
        ]);
    }
}
