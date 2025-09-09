<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssessmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view assessments
    }

    public function view(User $user, Assessment $assessment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('instructor')) {
            return $assessment->course->instructor_id === $user->id;
        }

        if ($user->hasRole('student')) {
            return $assessment->course->students()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'instructor']);
    }

    public function update(User $user, Assessment $assessment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('instructor') && $assessment->course->instructor_id === $user->id;
    }

    public function delete(User $user, Assessment $assessment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('instructor') && $assessment->course->instructor_id === $user->id;
    }

    public function assign(User $user, Assessment $assessment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('instructor') && $assessment->course->instructor_id === $user->id;
    }
}
