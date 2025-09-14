<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CheckDashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = $request->user();

        // Check if user has any dashboard-related permissions
        $dashboardPermissions = [
            'users.read', 'users.create', 'users.edit', 'users.delete',
            'instructors.read', 'instructors.create', 'instructors.edit', 'instructors.delete',
            'instructor-applications.read', 'instructor-applications.create', 'instructor-applications.edit', 'instructor-applications.delete',
            'roles-permissions.read', 'roles-permissions.create', 'roles-permissions.edit', 'roles-permissions.delete',
            'courses.read', 'courses.create', 'courses.edit', 'courses.delete',
            'categories.read', 'categories.create', 'categories.edit', 'categories.delete',
            'course-levels.read', 'course-levels.create', 'course-levels.edit', 'course-levels.delete',
            'topics.read', 'topics.create', 'topics.edit', 'topics.delete',
            'assessments.read', 'assessments.create', 'assessments.edit', 'assessments.delete',
            'quizzes.read', 'quizzes.create', 'quizzes.edit', 'quizzes.delete',
            'enrollments.read', 'enrollments.create', 'enrollments.edit', 'enrollments.delete',
            'student-progress.read',
            'coupons.read', 'coupons.create', 'coupons.edit', 'coupons.delete',
            'degrees.read', 'degrees.create', 'degrees.edit', 'degrees.delete',
            'learning-paths.read', 'learning-paths.create', 'learning-paths.edit', 'learning-paths.delete',
            'certificates.read', 'certificates.create', 'certificates.edit', 'certificates.delete',
            'reviews.read', 'reviews.create', 'reviews.edit', 'reviews.delete',
            'testimonials.read', 'testimonials.create', 'testimonials.edit', 'testimonials.delete',
            'comments.read', 'comments.create', 'comments.edit', 'comments.delete',
            'files.read', 'files.create', 'files.edit', 'files.delete',
            'analytics.read',
            'system-settings.read', 'system-settings.edit'
        ];

        // Check if user has any of these permissions (either through role or direct permission)
        $hasDashboardAccess = false;
        foreach ($dashboardPermissions as $permission) {
            if ($user->can($permission)) {
                $hasDashboardAccess = true;
                break;
            }
        }

        // If user has no dashboard permissions, redirect to unauthorized page
        if (!$hasDashboardAccess) {
            abort(403, 'ليس لديك صلاحية للوصول إلى لوحة التحكم');
        }

        return $next($request);
    }
}
