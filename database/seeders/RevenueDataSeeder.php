<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create instructor
        $instructor = User::where('email', 'instructor@asta.com')->first();
        if (! $instructor) {
            $instructor = User::create([
                'name' => 'د. محمد إبراهيم',
                'email' => 'instructor@asta.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $instructor->assignRole('instructor');
        }

        // Create sample courses with realistic data
        $courses = [
            [
                'title' => 'دورة علوم البيانات المستوى الأول',
                'description' => 'دورة شاملة في علوم البيانات للمبتدئين',
                'price' => 299.99,
                'thumbnail' => 'https://example.com/thumbnail1.jpg',
                'status' => 'approved',
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::parse('2023-01-15'),
                'updated_at' => Carbon::parse('2023-01-15'),
            ],
            [
                'title' => 'دورة الذكاء الاصطناعي المتقدمة',
                'description' => 'دورة متقدمة في الذكاء الاصطناعي والتعلم الآلي',
                'price' => 499.99,
                'thumbnail' => 'https://example.com/thumbnail2.jpg',
                'status' => 'approved',
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::parse('2023-03-20'),
                'updated_at' => Carbon::parse('2023-03-20'),
            ],
            [
                'title' => 'دورة تطوير الويب الشاملة',
                'description' => 'دورة كاملة في تطوير الويب من الصفر',
                'price' => 399.99,
                'thumbnail' => 'https://example.com/thumbnail3.jpg',
                'status' => 'approved',
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::parse('2023-06-10'),
                'updated_at' => Carbon::parse('2023-06-10'),
            ],
            [
                'title' => 'دورة أمن المعلومات',
                'description' => 'دورة متخصصة في أمن المعلومات وحماية البيانات',
                'price' => 599.99,
                'thumbnail' => 'https://example.com/thumbnail4.jpg',
                'status' => 'approved',
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::parse('2023-09-05'),
                'updated_at' => Carbon::parse('2023-01-05'),
            ],
            [
                'title' => 'دورة تحليل البيانات المتقدمة',
                'description' => 'دورة متقدمة في تحليل البيانات والإحصائيات',
                'price' => 449.99,
                'thumbnail' => 'https://example.com/thumbnail5.jpg',
                'status' => 'approved',
                'instructor_id' => $instructor->id,
                'created_at' => Carbon::parse('2023-11-15'),
                'updated_at' => Carbon::parse('2023-11-15'),
            ],
        ];

        foreach ($courses as $courseData) {
            // Check if course already exists by title
            $existingCourse = Course::where('title', $courseData['title'])->first();

            if (! $existingCourse) {
                $course = Course::create($courseData);

                // Create sample enrollments for each course with realistic dates
                $this->createSampleEnrollments($course, $instructor);
            } else {
                // Use existing course and just add enrollments
                $this->createSampleEnrollments($existingCourse, $instructor);
            }
        }

        $this->command->info('Revenue data seeded successfully!');
    }

    private function createSampleEnrollments($course, $instructor)
    {
        // Get or create sample students
        $students = $this->getOrCreateStudents();

        // Create enrollments with realistic distribution across years
        $enrollmentYears = [
            2016 => 5,   // 5 students enrolled in 2016
            2017 => 8,   // 8 students enrolled in 2017
            2018 => 12,  // 12 students enrolled in 2018
            2019 => 18,  // 18 students enrolled in 2019 (highest year)
            2020 => 15,  // 15 students enrolled in 2020
            2021 => 20,  // 20 students enrolled in 2021
            2022 => 25,  // 25 students enrolled in 2022
            2023 => 30,  // 30 students enrolled in 2023
        ];

        foreach ($enrollmentYears as $year => $studentCount) {
            $selectedStudents = $students->random(min($studentCount, $students->count()));

            foreach ($selectedStudents as $student) {
                // Check if enrollment already exists
                $existingEnrollment = DB::table('course_user')
                    ->where('user_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();

                if (! $existingEnrollment) {
                    // Create enrollment with realistic date within the year
                    $enrollmentDate = Carbon::create($year, rand(1, 12), rand(1, 28));

                    DB::table('course_user')->insert([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'enrolled_at' => $enrollmentDate->toDateTimeString(),
                        'created_at' => $enrollmentDate->toDateTimeString(),
                        'updated_at' => $enrollmentDate->toDateTimeString(),
                    ]);
                }
            }
        }
    }

    private function getOrCreateStudents()
    {
        $students = collect();

        // Create 50 sample students if they don't exist
        for ($i = 1; $i <= 50; $i++) {
            $student = User::firstOrCreate(
                ['email' => "student{$i}@asta.com"],
                [
                    'name' => fake()->name(),
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'created_at' => Carbon::now()->subDays(rand(1, 1000)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 1000)),
                ]
            );

            if (! $student->hasRole('student')) {
                $student->assignRole('student');
            }

            $students->push($student);
        }

        return $students;
    }
}
