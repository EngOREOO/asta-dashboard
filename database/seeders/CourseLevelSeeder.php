<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseLevel;
use Faker\Factory as Faker;

class CourseLevelSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create 3-5 levels per course
            $levelCount = rand(3, 5);
            
            for ($i = 1; $i <= $levelCount; $i++) {
                $levelNames = [
                    'المستوى الأول - الأساسيات',
                    'المستوى الثاني - المتقدم',
                    'المستوى الثالث - الاحترافي',
                    'المستوى الرابع - التطبيق العملي',
                    'المستوى الخامس - المشاريع',
                ];

                $levelDescriptions = [
                    'في هذا المستوى ستتعلم الأساسيات والمفاهيم الأولية',
                    'هذا المستوى يتضمن مفاهيم متقدمة وتطبيقات عملية',
                    'مستوى احترافي للمتعلمين ذوي الخبرة',
                    'تطبيق عملي للمفاهيم النظرية',
                    'مشاريع عملية لتطبيق ما تم تعلمه',
                ];

                $levelName = $levelNames[($i - 1) % count($levelNames)];
                $levelDescription = $levelDescriptions[($i - 1) % count($levelDescriptions)];

                CourseLevel::create([
                    'course_id' => $course->id,
                    'level_name' => $levelName,
                    'description' => $levelDescription,
                    'order' => $i,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Course levels seeded successfully!');
    }
}
