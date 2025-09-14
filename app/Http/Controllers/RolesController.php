<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->paginate(20);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        [$groups, $permissions] = $this->permissionMatrix();
        return view('roles.create', compact('groups','permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'is_super' => 'sometimes|boolean',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['is_super'])) {
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($data['permissions'] ?? []);
        }
        return redirect()->route('roles.index')->with('success','Role created');
    }

    public function edit(Role $role)
    {
        [$groups, $permissions] = $this->permissionMatrix();
        $role->load('permissions');
        return view('roles.edit', compact('role','groups','permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'is_super' => 'sometimes|boolean',
            'permissions' => 'array',
        ]);
        $role->name = $data['name'];
        $role->save();
        if (!empty($data['is_super'])) {
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($data['permissions'] ?? []);
        }
        return redirect()->route('roles.index')->with('success','Role updated');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success','Role deleted');
    }

    // User assignment helpers
    public function assignUserRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => 'sometimes|array',
            'role_to_add' => 'sometimes|string|exists:roles,name',
            'action' => 'sometimes|string|in:add,update'
        ]);

        if ($request->input('action') === 'add' && !empty($data['role_to_add'])) {
            // Remove all existing roles first (user can only have one role)
            $user->syncRoles([]);
            // Add the new single role
            $user->assignRole($data['role_to_add']);
            return back()->with('success', 'تم تعيين الدور الجديد بنجاح');
        } elseif (!empty($data['roles'])) {
            // Update all roles (original functionality) - but only keep the first one
            $roles = array_slice($data['roles'], 0, 1); // Only take the first role
            $user->syncRoles($roles);
            return back()->with('success', 'تم تحديث الدور بنجاح');
        }

        return back()->with('error', 'لم يتم تحديد أي دور');
    }

    public function revokeUserRole(User $user, Role $role)
    {
        $user->removeRole($role);
        return back()->with('success','Role revoked');
    }

    public function giveUserPermission(Request $request, User $user)
    {
        $data = $request->validate([
            'permission' => 'sometimes|string',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        if (!empty($data['permissions'])) {
            // Grant multiple permissions
            $user->givePermissionTo($data['permissions']);
            $count = count($data['permissions']);
            return back()->with('success', "تم منح {$count} صلاحية بنجاح");
        } elseif (!empty($data['permission'])) {
            // Grant single permission (backward compatibility)
            $user->givePermissionTo($data['permission']);
            return back()->with('success', 'تم منح الصلاحية بنجاح');
        }

        return back()->with('error', 'لم يتم تحديد أي صلاحية');
    }

    public function revokeUserPermission(User $user, Permission $permission)
    {
        $user->revokePermissionTo($permission);
        return back()->with('success','Permission revoked');
    }

    private function permissionMatrix(): array
    {
        $groups = [
            'إدارة المستخدمين' => 'users,instructors,instructor-applications,roles-permissions',
            'إدارة الدورات' => 'courses,categories,course-levels,topics',
            'الاختبارات والتقييم' => 'assessments,quizzes',
            'إدارة الطلاب' => 'enrollments,student-progress',
            'الكوبونات والخصومات' => 'coupons',
            'المسارات المهنية' => 'degrees,learning-paths',
            'الشهادات' => 'certificates',
            'التعليقات والآراء' => 'reviews,testimonials,comments',
            'الملفات' => 'files',
            'التحليلات' => 'analytics',
            'إعدادات النظام' => 'system-settings',
        ];
        $permissions = Permission::all()->pluck('name')->toArray();
        return [$groups, $permissions];
    }
}


