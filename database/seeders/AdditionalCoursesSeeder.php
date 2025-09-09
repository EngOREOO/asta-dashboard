<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Degree;
use Faker\Factory as Faker;

class AdditionalCoursesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');
        
        $instructors = User::role('instructor')->get();
        $categories = Category::all();
        $degrees = Degree::all();

        // Add more courses to reach 25 total
        for ($i = 2; $i <= 25; $i++) {
            Course::create([
                'instructor_id' => $instructors->random()->id,
                'category_id' => $categories->random()->id,
                'degree_id' => $degrees->random()->id,
                'title' => 'دورة رقم ' . $i,
                'slug' => 'course-' . $i,
                'description' => 'وصف الدورة رقم ' . $i,
                'thumbnail' => 'courses/course-' . $i . '.jpg',
                'price' => rand(100, 600),
                'status' => ['draft', 'pending', 'approved'][array_rand(['draft', 'pending', 'approved'])],
            ]);
        }

        $this->command->info('Additional courses seeded successfully!');
    }
} 