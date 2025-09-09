<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programming',
                'description' => 'Learn programming languages and software development',
                'image_url' => 'https://via.placeholder.com/300x200?text=Programming',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Build websites and web applications',
                'image_url' => 'https://via.placeholder.com/300x200?text=Web+Development',
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Create mobile applications for iOS and Android',
                'image_url' => 'https://via.placeholder.com/300x200?text=Mobile+Development',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Learn data analysis, machine learning, and AI',
                'image_url' => 'https://via.placeholder.com/300x200?text=Data+Science',
            ],
            [
                'name' => 'Business',
                'description' => 'Business, finance, and entrepreneurship courses',
                'image_url' => 'https://via.placeholder.com/300x200?text=Business',
            ],
            [
                'name' => 'Design',
                'description' => 'Graphic design, UI/UX, and digital art',
                'image_url' => 'https://via.placeholder.com/300x200?text=Design',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image_url' => $category['image_url'],
            ]);
        }
    }
}
