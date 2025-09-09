<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningPath;
use App\Models\Course;
use Illuminate\Support\Str;

class LearningPathSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::pluck('id')->all();
        if (empty($courses)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $name = 'Path ' . $i;
            $path = LearningPath::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => 'Auto-generated learning path #' . $i,
                    'is_active' => true,
                    'sort_order' => $i,
                ]
            );

            // Pick 3-8 random courses and attach in random order
            $num = random_int(3, min(8, count($courses)));
            $selected = collect($courses)->shuffle()->take($num)->values();
            $sync = [];
            foreach ($selected as $index => $courseId) {
                $sync[$courseId] = ['order' => $index + 1];
            }
            $path->courses()->syncWithoutDetaching($sync);
        }
    }
}


