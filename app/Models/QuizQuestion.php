<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'points',
        'order',
        'explanation',
        'image',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'points' => 'integer',
        'order' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class)->orderBy('order');
    }

    public function correctAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class)->where('is_correct', true);
    }

    public function getCorrectAnswerAttribute()
    {
        if ($this->type === 'true_false') {
            return $this->correctAnswers->first()?->answer_text;
        }
        
        if ($this->type === 'multiple_choice') {
            return $this->correctAnswers->pluck('answer_text');
        }
        
        return $this->correctAnswers->first()?->answer_text;
    }

    public function isCorrectAnswer($answer): bool
    {
        if ($this->type === 'true_false') {
            return $this->correctAnswers->first()?->answer_text === $answer;
        }
        
        if ($this->type === 'multiple_choice') {
            if (is_array($answer)) {
                $correctAnswers = $this->correctAnswers->pluck('answer_text')->toArray();
                return empty(array_diff($answer, $correctAnswers)) && empty(array_diff($correctAnswers, $answer));
            }
            return $this->correctAnswers->where('answer_text', $answer)->exists();
        }
        
        if ($this->type === 'short_answer') {
            return strtolower(trim($answer)) === strtolower(trim($this->correctAnswers->first()?->answer_text ?? ''));
        }
        
        return false;
    }
}
