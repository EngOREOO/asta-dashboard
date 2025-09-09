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

        // Create permissions safely (only if not exists)
        $permissions = [
            // Course permissions
            'create courses',
            'edit courses',
            'delete courses',
            'view courses',
            'approve courses',
            
            // Lesson permissions
            'create lessons',
            'edit lessons',
            'delete lessons',
            'view lessons',
            
            // User management
            'manage users',
            'manage roles',
            
            // Enrollment permissions
            'enroll in courses',
            'view enrolled courses',
            
            // Content permissions
            'upload content',
            'manage content',
            'view content',
            
            // Assessment permissions
            'create assessments',
            'take assessments',
            'grade assessments',
        ];

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
            'create courses',
            'edit courses',
            'delete courses',
            'view courses',
            'create lessons',
            'edit lessons',
            'delete lessons',
            'view lessons',
            'upload content',
            'manage content',
            'view content',
            'create assessments',
            'grade assessments',
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->syncPermissions([
            'view courses',
            'view lessons',
            'view content',
            'take assessments',
            'enroll in courses',
            'view enrolled courses',
        ]);
    }
}
