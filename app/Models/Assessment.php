<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'created_by',
        'title',
        'description',
        'type',
        'duration_minutes',
        'max_attempts',
        'passing_score',
        'total_questions',
        'is_active',
        'randomize_questions',
        'show_results_immediately',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'randomize_questions' => 'boolean',
        'show_results_immediately' => 'boolean',
        'duration_minutes' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'integer',
        'total_questions' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function assignments()
    {
        return $this->hasMany(AssessmentAssignment::class);
    }

    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
}
