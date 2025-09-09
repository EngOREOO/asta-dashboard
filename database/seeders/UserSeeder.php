<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // Create Admin User
        // $admin = User::firstOrCreate(
        //     ['email' => 'admin@asta.com'],
        //     [
        //         'name' => 'أحمد محمد علي',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //         'profile_photo_path' => 'images/asta.png',
        //         'bio' => 'مدير النظام في منصة أستا التعليمية',
        //         'birth_date' => '1985-03-15',
        //     ]
        // );
        // if (! $admin->hasRole('admin')) {
        //     $admin->assignRole('admin');
        // }

        // Create Super Admin User
        $super = User::firstOrCreate(
            ['email' => 'super@asta.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superpassword'),
                'email_verified_at' => now(),
                'profile_photo_path' => 'images/asta.png',
                'bio' => 'الحساب الأعلى صلاحية لإدارة المنصة بالكامل',
                'birth_date' => '1980-01-01',
            ]
        );
        if (! $super->hasRole('super-admin')) {
            $super->assignRole('super-admin');
        }

        // Create Test Users for each role
        // $testUsers = [
        //     [
        //         'email' => 'instructor@asta.com',
        //         'name' => 'د. محمد إبراهيم فتحي',
        //         'role' => 'instructor',
        //         'bio' => 'خبير في تطوير الويب والذكاء الاصطناعي مع أكثر من 10 سنوات من الخبرة',
        //         'birth_date' => '1988-07-22',
        //     ],
        //     [
        //         'email' => 'student@asta.com',
        //         'name' => 'أحمد علي حسن',
        //         'role' => 'student',
        //         'bio' => 'طالب مهتم بتطوير الويب والبرمجة',
        //         'birth_date' => '2000-11-08',
        //     ],
        // ];

        // foreach ($testUsers as $userData) {
        //     $user = User::firstOrCreate(
        //         ['email' => $userData['email']],
        //         [
        //             'name' => $userData['name'],
        //             'password' => Hash::make('password'),
        //             'email_verified_at' => now(),
        //             'profile_photo_path' => 'images/asta.png',
        //             'bio' => $userData['bio'],
        //             'birth_date' => $userData['birth_date'],
        //         ]
        //     );
        //     if (! $user->hasRole($userData['role'])) {
        //         $user->assignRole($userData['role']);
        //     }
        // }

        // // Create Multiple Instructors
        // $instructorNames = [
        //     'د. سارة أحمد محمد',
        //     'أ. خالد عبدالله علي',
        //     'د. فاطمة حسن محمود',
        //     'أ. عمر محمد سعيد',
        //     'د. نورا أحمد كمال',
        //     'أ. يوسف علي حسن',
        //     'د. ليلى محمد عبدالله',
        //     'أ. أحمد سعيد محمود',
        // ];

        // $instructorBios = [
        //     'خبيرة في تطوير تطبيقات الموبايل مع خبرة 8 سنوات',
        //     'مطور ويب متقدم ومتخصص في React و Node.js',
        //     'خبيرة في علوم البيانات والذكاء الاصطناعي',
        //     'مطور متخصص في أمن المعلومات والهاكينج الأخلاقي',
        //     'خبيرة في تصميم واجهات المستخدم وتجربة المستخدم',
        //     'مطور متخصص في تطبيقات الويب المتقدمة',
        //     'خبيرة في إدارة المشاريع البرمجية',
        //     'مطور متخصص في قواعد البيانات وتحليل البيانات',
        // ];

        // for ($i = 0; $i < count($instructorNames); $i++) {
        //     $instructor = User::create([
        //         'name' => $instructorNames[$i],
        //         'email' => 'instructor'.($i + 1).'@asta.com',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //         'profile_photo_path' => 'profile-photos/instructor'.($i + 1).'.jpg',
        //         'bio' => $instructorBios[$i],
        //         'birth_date' => $faker->date('Y-m-d', '-25 years'),
        //     ]);
        //     $instructor->assignRole('instructor');
        // }

        // // Create Multiple Students
        // $studentNames = [
        //     'علي محمد أحمد',
        //     'فاطمة حسن علي',
        //     'أحمد سعيد محمود',
        //     'نورا عبدالله محمد',
        //     'يوسف علي حسن',
        //     'ليلى محمد سعيد',
        //     'خالد أحمد فتحي',
        //     'سارة حسن محمود',
        //     'عمر محمد علي',
        //     'نادية أحمد حسن',
        //     'محمد سعيد عبدالله',
        //     'هدى علي محمد',
        //     'أحمد فتحي سعيد',
        //     'فاطمة محمود علي',
        //     'يوسف حسن محمد',
        // ];

        // for ($i = 0; $i < count($studentNames); $i++) {
        //     $student = User::create([
        //         'name' => $studentNames[$i],
        //         'email' => 'student'.($i + 1).'@asta.com',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //         'profile_photo_path' => 'profile-photos/student'.($i + 1).'.jpg',
        //         'bio' => $faker->sentence(10),
        //         'birth_date' => $faker->date('Y-m-d', '-18 years'),
        //     ]);
        //     $student->assignRole('student');
        // }

        // // Create Additional Random Students
        // for ($i = 0; $i < 50; $i++) {
        //     $student = User::create([
        //         'name' => $faker->name(),
        //         'email' => $faker->unique()->safeEmail(),
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //         'profile_photo_path' => 'profile-photos/default.jpg',
        //         'bio' => $faker->sentence(10),
        //         'birth_date' => $faker->date('Y-m-d', '-18 years'),
        //     ]);
        //     $student->assignRole('student');
        // }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@asta.com / password');
        $this->command->info('Super Admin: super@asta.com / superpassword');
    }
}
