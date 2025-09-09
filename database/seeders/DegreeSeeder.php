<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $degrees = [
            [
                'name' => 'Primary School',
                'name_ar' => 'المرحلة الابتدائية',
                'level' => 1,
                'description' => 'Primary education (grades 1-6)',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Intermediate School',
                'name_ar' => 'المرحلة المتوسطة',
                'level' => 2,
                'description' => 'Intermediate education (grades 7-9)',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Secondary School',
                'name_ar' => 'المرحلة الثانوية',
                'level' => 3,
                'description' => 'Secondary education (grades 10-12)',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Diploma',
                'name_ar' => 'دبلوم',
                'level' => 4,
                'description' => 'Diploma or associate degree',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Bachelor\'s Degree',
                'name_ar' => 'بكالوريوس',
                'level' => 5,
                'description' => 'Undergraduate degree',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Master\'s Degree',
                'name_ar' => 'ماجستير',
                'level' => 6,
                'description' => 'Postgraduate degree',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Doctorate',
                'name_ar' => 'دكتوراه',
                'level' => 7,
                'description' => 'Doctoral degree',
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($degrees as $degree) {
            Degree::updateOrCreate(['level' => $degree['level']], $degree);
        }
    }
}
