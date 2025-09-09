<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileUpload extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'material_id',
        'uploaded_by',
        'file_name',
        'original_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'status',
        'progress',
        'error_message',
        'duration',
        'thumbnail_path',
        'metadata',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'progress' => 'integer',
        'duration' => 'integer',
        'metadata' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';

    const STATUS_IN_PROGRESS = 'in_progress';

    const STATUS_COMPLETED = 'completed';

    const STATUS_FAILED = 'failed';

    // File type constants
    const TYPE_VIDEO = 'video';

    const TYPE_PDF = 'pdf';

    const TYPE_PPT = 'ppt';

    const TYPE_DOC = 'doc';

    const TYPE_IMAGE = 'image';

    const TYPE_OTHER = 'other';

    /**
     * Get the course that owns the file upload.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the material that owns the file upload.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(CourseMaterial::class);
    }

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get the duration in human readable format.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (! $this->duration) {
            return '00:00';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Check if the upload is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the upload is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if the upload failed.
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if the file is a video.
     */
    public function isVideo(): bool
    {
        return $this->file_type === self::TYPE_VIDEO;
    }

    /**
     * Check if the file is a document.
     */
    public function isDocument(): bool
    {
        return in_array($this->file_type, [self::TYPE_PDF, self::TYPE_PPT, self::TYPE_DOC]);
    }

    /**
     * Scope for completed uploads.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for in-progress uploads.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for failed uploads.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for video files.
     */
    public function scopeVideos($query)
    {
        return $query->where('file_type', self::TYPE_VIDEO);
    }

    /**
     * Scope for document files.
     */
    public function scopeDocuments($query)
    {
        return $query->whereIn('file_type', [self::TYPE_PDF, self::TYPE_PPT, self::TYPE_DOC]);
    }
}
