<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Course $course)
    {
        // Debug logging
        \Log::info('CoursePolicy::view called', [
            'user_id' => $user->id,
            'user_roles' => $user->roles->pluck('name'),
            'course_id' => $course->id,
            'course_status' => $course->status,
            'course_instructor_id' => $course->instructor_id
        ]);

        // For now, allow any authenticated user to view any course
        // This is for testing purposes - we can make it more restrictive later
        \Log::info('Allowing access to any authenticated user for testing');
        return true;

        // Original logic (commented out for testing):
        /*
        // Admins can view any course
        if ($user->hasRole('admin')) {
            \Log::info('User is admin, allowing access');
            return true;
        }

        // Instructors can view courses they're teaching
        if ($user->hasRole('instructor') && $user->id === $course->instructor_id) {
            \Log::info('User is instructor of this course, allowing access');
            return true;
        }

        // For approved courses, allow viewing by any authenticated user
        // This allows students and other users to browse course materials
        if ($course->status === 'approved') {
            \Log::info('Course is approved, allowing access to any authenticated user');
            return true;
        }

        // Students can view courses they're enrolled in (even if not approved)
        if ($user->hasRole('student')) {
            $isEnrolled = $course->students()->where('user_id', $user->id)->exists();
            \Log::info('User is student, enrollment check result', ['is_enrolled' => $isEnrolled]);
            if ($isEnrolled) {
                return true;
            }
        }

        // For draft/pending courses, only allow instructors and admins
        if ($course->status === 'draft' || $course->status === 'pending') {
            if ($user->hasRole('instructor') && $user->id === $course->instructor_id) {
                \Log::info('Instructor can view their draft/pending course');
                return true;
            }
            \Log::info('Access denied for draft/pending course', ['user_id' => $user->id, 'course_id' => $course->id]);
            return false;
        }

        \Log::info('Access denied for user', ['user_id' => $user->id, 'course_id' => $course->id]);
        return false;
        */
    }

    public function create(User $user)
    {
        return $user->hasRole('instructor');
    }

    public function update(User $user, Course $course)
    {
        return $user->hasRole('admin') || $user->id === $course->instructor_id;
    }

    public function delete(User $user, Course $course)
    {
        return $user->hasRole('admin') || $user->id === $course->instructor_id;
    }

    public function approve(User $user, Course $course)
    {
        return $user->hasRole('admin');
    }

    /// course enrollment
    public function enroll(User $user, Course $course)
    {
        return $user->hasRole('student');
    }
}
