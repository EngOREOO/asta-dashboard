<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'duration_minutes',
        'max_attempts',
        'passing_score',
        'is_active',
        'randomize_questions',
        'show_results_immediately',
        'allow_review',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'randomize_questions' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'passing_score' => 'decimal:2',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->available_from && $now < $this->available_from) {
            return false;
        }

        if ($this->available_until && $now > $this->available_until) {
            return false;
        }

        return true;
    }

    public function getTotalPointsAttribute(): int
    {
        return $this->questions->sum('points');
    }

    public function canUserAttempt(User $user): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if (!$this->max_attempts) {
            return true;
        }

        $userAttempts = $this->attempts()->where('user_id', $user->id)->count();
        return $userAttempts < $this->max_attempts;
    }
}
