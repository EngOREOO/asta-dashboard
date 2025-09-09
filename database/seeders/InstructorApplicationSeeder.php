<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\InstructorApplication;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InstructorApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        
        $students = User::role('student')->get();
        $admin = User::role('admin')->first();

        $availableFields = [
            'Web Development',
            'Mobile Development', 
            'Data Science',
            'Machine Learning',
            'Artificial Intelligence',
            'Cybersecurity',
            'Cloud Computing',
            'DevOps',
            'UI/UX Design',
            'Digital Marketing',
        ];

        $jobTitles = [
            'Senior Web Developer',
            'Mobile App Developer',
            'Data Scientist',
            'Machine Learning Engineer',
            'AI Specialist',
            'Cybersecurity Expert',
            'Cloud Architect',
            'DevOps Engineer',
            'UX/UI Designer',
            'Digital Marketing Specialist',
        ];

        // Create instructor applications for some students
        foreach ($students->random(10) as $student) {
            $field = $availableFields[array_rand($availableFields)];
            $jobTitle = $jobTitles[array_rand($jobTitles)];
            
            $status = ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])];
            
            $application = InstructorApplication::firstOrCreate([
                'user_id' => $student->id,
            ], [
                'status' => $status,
                'field' => $field,
                'job_title' => $jobTitle,
                'phone' => $faker->phoneNumber(),
                'bio' => $faker->paragraph(4),
                'cv_url' => 'cvs/' . $student->id . '.pdf',
                'admin_feedback' => $status === 'rejected' ? $faker->paragraph(2) : null,
                'submitted_at' => now()->subDays(rand(1, 30)),
                'reviewed_at' => $status !== 'pending' ? now()->subDays(rand(1, 15)) : null,
                'reviewed_by' => $status !== 'pending' ? $admin->id : null,
            ]);

            // If approved, assign instructor role
            if ($status === 'approved' && !$student->hasRole('instructor')) {
                $student->assignRole('instructor');
            }
        }

        $this->command->info('Instructor applications seeded successfully!');
    }
} 