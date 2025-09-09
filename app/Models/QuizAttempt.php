<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'score',
        'total_questions',
        'correct_answers',
        'started_at',
        'completed_at',
        'answers',
        'is_passed',
        'time_taken_minutes',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'answers' => 'array',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'attempt_number' => 'integer',
        'time_taken_minutes' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateScore(): void
    {
        if (!$this->completed_at) {
            return;
        }

        $quiz = $this->quiz()->with('questions.correctAnswers')->first();
        $totalPoints = $quiz->questions->sum('points');
        $earnedPoints = 0;

        foreach ($quiz->questions as $question) {
            $userAnswer = $this->answers[$question->id] ?? null;
            
            if ($question->isCorrectAnswer($userAnswer)) {
                $earnedPoints += $question->points;
            }
        }

        $this->score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $this->correct_answers = $earnedPoints;
        $this->is_passed = $quiz->passing_score ? $this->score >= $quiz->passing_score : false;
        
        $this->save();
    }

    public function getPercentageAttribute(): float
    {
        return round($this->score ?? 0, 1);
    }

    public function getDurationAttribute(): string
    {
        if (!$this->time_taken_minutes) {
            return 'N/A';
        }

        $hours = floor($this->time_taken_minutes / 60);
        $minutes = $this->time_taken_minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . 'm';
    }
}
