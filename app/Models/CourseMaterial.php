<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'topic_id',
        'level_id',
        'assessment_id',
        'title',
        'type',
        'file_path',
        'duration',
        'order',
        'is_active',
    ];

    protected $casts = [
        'duration' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the topic that owns the material.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the level that owns the material.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    /**
     * Get the file uploads for this material.
     */
    public function fileUploads(): HasMany
    {
        return $this->hasMany(FileUpload::class, 'material_id');
    }

    public function inVideoQuizzes(): HasMany
    {
        return $this->hasMany(InVideoQuiz::class, 'material_id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    public function progress()
    {
        return $this->hasMany(\App\Models\CourseMaterialProgress::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(MaterialCompletion::class);
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->completions()->where('user_id', $user->id)->exists();
    }
}
