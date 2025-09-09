<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Degree;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');

        // Get all instructors
        $instructors = User::role('instructor')->get();
        $categories = Category::all();
        $degrees = Degree::all();

        if ($instructors->isEmpty()) {
            $this->command->error('No instructors found. Please run UserSeeder first.');

            return;
        }

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        if ($degrees->isEmpty()) {
            $this->call(DegreeSeeder::class);
            $degrees = Degree::all();
        }

        $courseData = [
            [
                'title' => 'دورة تطوير الويب الشاملة',
                'description' => 'دورة شاملة لتعلم تطوير الويب من الصفر حتى الاحتراف، تشمل HTML, CSS, JavaScript, PHP, MySQL',
                'price' => 299.99,
            ],
            [
                'title' => 'دورة الذكاء الاصطناعي للمبتدئين',
                'description' => 'دورة مقدمة في الذكاء الاصطناعي وتعلم الآلة، تشمل المفاهيم الأساسية والتطبيقات العملية',
                'price' => 399.99,
            ],
            [
                'title' => 'دورة تطوير تطبيقات الموبايل',
                'description' => 'تعلم تطوير تطبيقات الموبايل باستخدام Flutter و React Native',
                'price' => 349.99,
            ],
            [
                'title' => 'دورة أمن المعلومات والهاكينج الأخلاقي',
                'description' => 'دورة متخصصة في أمن المعلومات وتقنيات الحماية من الاختراق',
                'price' => 499.99,
            ],
            [
                'title' => 'دورة علوم البيانات وتحليل البيانات',
                'description' => 'دورة شاملة في علوم البيانات وتقنيات تحليل البيانات الضخمة',
                'price' => 449.99,
            ],
            [
                'title' => 'دورة تصميم واجهات المستخدم UI/UX',
                'description' => 'دورة متخصصة في تصميم واجهات المستخدم وتجربة المستخدم',
                'price' => 299.99,
            ],
            [
                'title' => 'دورة إدارة المشاريع البرمجية',
                'description' => 'دورة شاملة في إدارة المشاريع البرمجية باستخدام منهجيات Agile و Scrum',
                'price' => 399.99,
            ],
            [
                'title' => 'دورة تطوير تطبيقات الويب المتقدمة',
                'description' => 'دورة متقدمة في تطوير تطبيقات الويب باستخدام React, Node.js, MongoDB',
                'price' => 549.99,
            ],
            [
                'title' => 'دورة قواعد البيانات وتحليل البيانات',
                'description' => 'دورة شاملة في قواعد البيانات وتقنيات تحليل البيانات',
                'price' => 349.99,
            ],
            [
                'title' => 'دورة التسويق الرقمي والتجارة الإلكترونية',
                'description' => 'دورة شاملة في التسويق الرقمي وإدارة المتاجر الإلكترونية',
                'price' => 249.99,
            ],
        ];

        foreach ($courseData as $index => $courseInfo) {
            $instructor = $instructors->random();
            $category = $categories->random();
            $degree = $degrees->random();

            $course = Course::create([
                'instructor_id' => $instructor->id,
                'category_id' => $category->id,
                'degree_id' => $degree->id,
                'title' => $courseInfo['title'],
                'slug' => \Str::slug($courseInfo['title']),
                'description' => $courseInfo['description'],
                'thumbnail' => 'images/asta.png',
                'price' => $courseInfo['price'],
                'status' => ['draft', 'pending', 'approved'][array_rand(['draft', 'pending', 'approved'])],
            ]);
        }

        // Create additional random courses
        for ($i = 0; $i < 15; $i++) {
            $instructor = $instructors->random();
            $category = $categories->random();
            $degree = $degrees->random();

            Course::create([
                'instructor_id' => $instructor->id,
                'category_id' => $category->id,
                'degree_id' => $degree->id,
                'title' => $faker->sentence(4),
                'slug' => $faker->slug(),
                'description' => $faker->paragraph(5),
                'thumbnail' => 'images/asta.png',
                'price' => $faker->randomFloat(2, 99, 599),
                'status' => ['draft', 'pending', 'approved'][array_rand(['draft', 'pending', 'approved'])],
            ]);
        }

        $this->command->info('Courses seeded successfully!');
    }
}
