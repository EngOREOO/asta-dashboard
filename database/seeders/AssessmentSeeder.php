<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        $instructors = User::role('instructor')->get();
        $students = User::role('student')->get();

        foreach ($courses as $course) {
            // Create a quiz
            $quiz = Assessment::create([
                'course_id' => $course->id,
                'created_by' => $instructors->random()->id,
                'title' => 'Course Quiz',
                'description' => 'Test your knowledge of the course material',
                'type' => 'quiz',
            ]);

            // Add questions
            $questions = [
                [
                    'question' => 'What is the main topic of this course?',
                    'type' => 'mcq',
                    'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
                    'correct_answer' => 'Option A',
                    'points' => 10,
                ],
                [
                    'question' => 'Explain the key concept in your own words.',
                    'type' => 'text',
                    'options' => null,
                    'correct_answer' => null,
                    'points' => 20,
                ],
                [
                    'question' => 'True or False: The concept is always true.',
                    'type' => 'mcq',
                    'options' => ['True', 'False'],
                    'correct_answer' => 'True',
                    'points' => 5,
                ],
            ];

            foreach ($questions as $questionData) {
                AssessmentQuestion::create([
                    'assessment_id' => $quiz->id,
                    ...$questionData,
                ]);
            }

            // Assign to random students (up to 3 or all available if less than 3)
            $studentCount = min(3, $students->count());
            if ($studentCount > 0) {
                $quiz->assignments()->createMany(
                    $students->random($studentCount)->map(fn ($student) => [
                        'user_id' => $student->id,
                        'assigned_by' => $instructors->random()->id,
                        'assigned_at' => now(),
                    ])
                );
            }
        }
    }
}
