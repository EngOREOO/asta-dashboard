<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Grouped permissions aligned with sidebar sections (CRUD: read, create, edit, delete)
        $groups = [
            'users' => ['read','create','edit','delete'],
            'instructors' => ['read','create','edit','delete'],
            'instructor-applications' => ['read','create','edit','delete'],
            'roles-permissions' => ['read','create','edit','delete'],

            'courses' => ['read','create','edit','delete'],
            'categories' => ['read','create','edit','delete'],
            'course-levels' => ['read','create','edit','delete'],
            'topics' => ['read','create','edit','delete'],

            'assessments' => ['read','create','edit','delete'],
            'quizzes' => ['read','create','edit','delete'],

            'enrollments' => ['read','create','edit','delete'],
            'student-progress' => ['read'],

            'coupons' => ['read','create','edit','delete'],
            'degrees' => ['read','create','edit','delete'],
            'learning-paths' => ['read','create','edit','delete'],
            'certificates' => ['read','create','edit','delete'],

            'reviews' => ['read','create','edit','delete'],
            'testimonials' => ['read','create','edit','delete'],
            'comments' => ['read','create','edit','delete'],

            'files' => ['read','create','edit','delete'],
            'analytics' => ['read'],
            'system-settings' => ['read','edit'],
        ];

        $permissions = [];
        foreach ($groups as $module => $actions) {
            foreach ($actions as $action) {
                $permissions[] = "$module.$action";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles safely
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Super Admin has all permissions (for platform owners)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        $instructorRole = Role::firstOrCreate(['name' => 'instructor']);
        $instructorRole->syncPermissions([
            'courses.read','courses.create','courses.edit','courses.delete',
            'categories.read','categories.create','categories.edit','categories.delete',
            'assessments.read','assessments.create','assessments.edit',
            'quizzes.read','quizzes.create','quizzes.edit',
            'files.read','files.create','files.edit','files.delete',
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->syncPermissions([
            'courses.read','assessments.read','quizzes.read','enrollments.read',
        ]);
    }
}
