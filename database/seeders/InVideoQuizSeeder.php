<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseMaterial;
use App\Models\InVideoQuiz;
use Faker\Factory as Faker;

class InVideoQuizSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');
        
        // Get only video materials
        $videoMaterials = CourseMaterial::where('type', 'video')->get();

        foreach ($videoMaterials as $material) {
            // Create 1-3 in-video quizzes per video material
            $quizCount = rand(1, 3);
            
            for ($i = 1; $i <= $quizCount; $i++) {
                $quizNames = [
                    'اختبار سريع',
                    'تقييم الفهم',
                    'أسئلة تفاعلية',
                    'اختبار المفاهيم',
                    'تقييم المهارات',
                ];

                $quizName = $quizNames[($i - 1) % count($quizNames)];
                
                // Generate random timestamp between 00:01:00 and 00:45:00
                $minutes = rand(1, 45);
                $seconds = rand(0, 59);
                $timestamp = sprintf('00:%02d:%02d', $minutes, $seconds);

                // Generate sample questions
                $questions = [
                    [
                        'question' => 'ما هو المفهوم الرئيسي الذي تم شرحه في هذا الجزء؟',
                        'type' => 'multiple_choice',
                        'options' => [
                            'المفهوم الأول',
                            'المفهوم الثاني', 
                            'المفهوم الثالث',
                            'المفهوم الرابع'
                        ],
                        'correct_answer' => 'المفهوم الأول',
                        'points' => 10
                    ],
                    [
                        'question' => 'هل هذا المفهوم صحيح؟',
                        'type' => 'true_false',
                        'options' => ['صحيح', 'خطأ'],
                        'correct_answer' => 'صحيح',
                        'points' => 5
                    ]
                ];

                InVideoQuiz::create([
                    'material_id' => $material->id,
                    'quiz_name' => $quizName,
                    'description' => 'اختبار تفاعلي لقياس فهم الطالب للمحتوى',
                    'timestamp' => $timestamp,
                    'questions' => $questions,
                    'questions_count' => count($questions),
                    'order' => $i,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('In-video quizzes seeded successfully!');
    }
}
