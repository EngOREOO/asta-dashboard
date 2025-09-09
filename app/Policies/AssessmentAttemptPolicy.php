<?php

namespace App\Policies;

use App\Models\AssessmentAttempt;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssessmentAttemptPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their attempts
    }

    public function view(User $user, AssessmentAttempt $attempt): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('instructor')) {
            return $attempt->assessment->course->instructor_id === $user->id;
        }

        return $attempt->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('student');
    }

    public function update(User $user, AssessmentAttempt $attempt): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('instructor')) {
            return $attempt->assessment->course->instructor_id === $user->id;
        }

        return $attempt->user_id === $user->id && $attempt->status === 'in_progress';
    }

    public function delete(User $user, AssessmentAttempt $attempt): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('instructor')) {
            return $attempt->assessment->course->instructor_id === $user->id;
        }

        return false;
    }

    public function grade(User $user, AssessmentAttempt $attempt): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('instructor') && $attempt->assessment->course->instructor_id === $user->id;
    }
}
