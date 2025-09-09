<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseMaterial;
use Faker\Factory as Faker;

class CourseMaterialSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create 5-12 materials per course
            $materialCount = rand(5, 12);
            
            for ($i = 1; $i <= $materialCount; $i++) {
                $materialType = ['video', 'pdf', 'image'][array_rand(['video', 'pdf', 'image'])];
                $isFree = $i <= 2; // First 2 materials are free
                
                $materialTitles = [
                    'مقدمة في الدورة',
                    'أساسيات المفهوم',
                    'التطبيق العملي',
                    'التمارين والتدريبات',
                    'المراجعة الشاملة',
                    'الاختبار النهائي',
                    'المشروع العملي',
                    'المراجع والموارد',
                    'الأسئلة الشائعة',
                    'التطبيقات المتقدمة',
                    'حل المشاكل',
                    'الخاتمة والتوصيات',
                ];

                $materialDescriptions = [
                    'مقدمة شاملة في الموضوع وتوضيح الأهداف التعليمية',
                    'شرح مفصل لأساسيات الموضوع مع أمثلة عملية',
                    'تطبيق عملي للمفاهيم النظرية',
                    'تمارين وتدريبات لتعزيز الفهم',
                    'مراجعة شاملة لجميع المفاهيم المطروحة',
                    'اختبار نهائي لتقييم مستوى الفهم',
                    'مشروع عملي لتطبيق ما تم تعلمه',
                    'مراجع وموارد إضافية للتعلم',
                    'إجابات على الأسئلة الشائعة',
                    'تطبيقات متقدمة للموضوع',
                    'حل المشاكل الشائعة',
                    'خاتمة وتوصيات للتعلم المستقبلي',
                ];

                $title = $materialTitles[($i - 1) % count($materialTitles)];
                $description = $materialDescriptions[($i - 1) % count($materialDescriptions)];
                
                // Generate realistic file paths based on type
                $filePath = $this->generateFilePath($materialType, $course->id, $i);
                
                // Generate realistic duration and file size
                $duration = $this->generateDuration($materialType);
                $fileSize = $this->generateFileSize($materialType);

                // Get a random level for this course
                $level = $course->levels()->inRandomOrder()->first();
                
                CourseMaterial::create([
                    'course_id' => $course->id,
                    'level_id' => $level ? $level->id : null,
                    'title' => $title,
                    'description' => $description,
                    'type' => $materialType,
                    'file_path' => $filePath,
                    'order' => $i,
                    'is_free' => $isFree,
                    'duration' => $duration,
                    'file_size' => $fileSize,
                ]);
            }
        }

        $this->command->info('Course materials seeded successfully!');
    }

    private function generateFilePath($type, $courseId, $materialNumber)
    {
        $extensions = [
            'video' => ['mp4', 'avi', 'mov'],
            'pdf' => ['pdf'],
            'image' => ['jpg', 'png', 'gif'],
        ];

        $extension = $extensions[$type][array_rand($extensions[$type])];
        return "course-materials/course-{$courseId}/material-{$materialNumber}.{$extension}";
    }

    private function generateDuration($type)
    {
        switch ($type) {
            case 'video':
                return rand(5, 45); // 5-45 minutes
            case 'pdf':
                return rand(10, 30); // 10-30 minutes to read
            case 'image':
                return rand(2, 5); // 2-5 minutes to view
            default:
                return rand(5, 20);
        }
    }

    private function generateFileSize($type)
    {
        switch ($type) {
            case 'video':
                return rand(50, 500) * 1024 * 1024; // 50-500 MB
            case 'pdf':
                return rand(1, 10) * 1024 * 1024; // 1-10 MB
            case 'image':
                return rand(100, 2000) * 1024; // 100KB-2MB
            default:
                return rand(1, 50) * 1024 * 1024;
        }
    }
}
