<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['courses as courses_count'])
            ->with(['roles']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->role($request->role);
        }

        $users = $query->orderByDesc('id')->paginate(20);

        return view('users.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function show(User $user)
    {
        // Load roles and courses taught/enrolled depending on your relationships
        $user->load(['roles', 'courses']);

        // If there is a relation for courses taught (e.g., instructorCourses), try to load it gracefully
        if (method_exists($user, 'instructorCourses')) {
            $user->load('instructorCourses');
        }

        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function create()
    {
        $roles = [];
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $roles = \Spatie\Permission\Models\Role::query()->pluck('name', 'id');
        }

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'integer'],
        ]);

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        if (! empty($data['role_id']) && class_exists(\Spatie\Permission\Models\Role::class) && method_exists($user, 'syncRoles')) {
            $role = \Spatie\Permission\Models\Role::find($data['role_id']);
            if ($role) {
                $user->syncRoles([$role->name]);
            }
        }

        return redirect()->route('users.show', $user)->with('status', 'User created successfully');
    }

    public function edit(User $user)
    {
        $roles = [];
        $user->load('roles');
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $roles = \Spatie\Permission\Models\Role::query()->pluck('name', 'id');
        }

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'integer'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (! empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        if (array_key_exists('role_id', $data) && class_exists(\Spatie\Permission\Models\Role::class) && method_exists($user, 'syncRoles')) {
            if ($data['role_id']) {
                $role = \Spatie\Permission\Models\Role::find($data['role_id']);
                if ($role) {
                    $user->syncRoles([$role->name]);
                }
            } else {
                $user->syncRoles([]);
            }
        }

        return redirect()->route('users.show', $user)->with('status', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('status', 'User deleted successfully');
    }
}
