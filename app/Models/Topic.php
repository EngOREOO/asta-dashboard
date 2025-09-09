<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the course that owns the topic.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for this topic.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'topic_id')->orderBy('order');
    }

    /**
     * Get active lessons for this topic.
     */
    public function activeLessons(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'topic_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * Scope for active topics.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered topics.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
