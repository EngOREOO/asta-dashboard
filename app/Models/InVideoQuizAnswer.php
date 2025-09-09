<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InVideoQuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'in_video_quiz_id',
        'course_material_id',
        'course_id',
        'answers',
        'score',
        'correct_answers',
        'total_questions',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'decimal:2',
        'correct_answers' => 'integer',
        'total_questions' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inVideoQuiz(): BelongsTo
    {
        return $this->belongsTo(InVideoQuiz::class);
    }

    public function courseMaterial(): BelongsTo
    {
        return $this->belongsTo(CourseMaterial::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Calculate score based on answers
     */
    public function calculateScore(): void
    {
        $quiz = $this->inVideoQuiz;
        $questions = $quiz->questions ?? [];
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $totalPoints = 0;

        foreach ($questions as $index => $question) {
            $userAnswer = $this->answers[$index] ?? null;
            $totalPoints += $question['points'] ?? 1;

            if ($this->isAnswerCorrect($question, $userAnswer)) {
                $correctAnswers += $question['points'] ?? 1;
            }
        }

        $this->update([
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'score' => $totalPoints > 0 ? ($correctAnswers / $totalPoints) * 100 : 0,
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if a user answer is correct
     */
    private function isAnswerCorrect(array $question, $userAnswer): bool
    {
        if ($userAnswer === null) {
            return false;
        }

        $correctAnswer = $question['correct_answer'] ?? null;
        $questionType = $question['type'] ?? 'multiple_choice';

        switch ($questionType) {
            case 'true_false':
                return strtolower($userAnswer) === strtolower($correctAnswer);

            case 'single_choice':
                return $userAnswer === $correctAnswer;

            case 'multiple_choice':
                if (is_array($userAnswer) && is_array($correctAnswer)) {
                    return empty(array_diff($userAnswer, $correctAnswer)) &&
                           empty(array_diff($correctAnswer, $userAnswer));
                }

                return $userAnswer === $correctAnswer;

            default:
                return false;
        }
    }

    /**
     * Get percentage score
     */
    public function getPercentageAttribute(): float
    {
        return round($this->score ?? 0, 1);
    }

    /**
     * Check if quiz is passed (assuming 70% is passing)
     */
    public function getIsPassedAttribute(): bool
    {
        return ($this->score ?? 0) >= 70;
    }
}
