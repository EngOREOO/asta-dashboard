<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'field',
        'job_title',
        'phone',
        'bio',
        'cv_url',
        'admin_feedback',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Available fields for instructor applications
    public static $availableFields = [
        'Web Development',
        'Mobile Development',
        'Data Science',
        'Machine Learning',
        'Artificial Intelligence',
        'Cybersecurity',
        'Cloud Computing',
        'DevOps',
        'UI/UX Design',
        'Digital Marketing',
        'Business Analysis',
        'Project Management',
        'Finance & Accounting',
        'Language Learning',
        'Music & Arts',
        'Health & Fitness',
        'Cooking & Culinary',
        'Photography',
        'Video Production',
        'Writing & Content Creation'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function canReapply(): bool
    {
        // Can reapply if rejected and 30 days have passed
        return $this->isRejected() && $this->reviewed_at->diffInDays(now()) >= 30;
    }

    public function getNextReapplyDate(): string
    {
        if ($this->isRejected()) {
            return $this->reviewed_at->addDays(30)->format('Y-m-d');
        }
        return null;
    }
} 