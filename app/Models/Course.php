<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'category_id',
        'degree_id',
        'title',
        'slug',
        'code',
        'description',
        'thumbnail',
        'price',
        'duration',
        'level',
        'category',
        'specialization',
        'status',
        'rejection_reason',
        'average_rating',
        'total_ratings',
        'is_featured',
        'allow_comments',
        'allow_notes',
        'allow_ratings',
        'overview',
        'prerequisites',
        'learning_objectives',
        'target_audience',
        'difficulty_level',
        'language',
        'estimated_duration',
        'duration_days',
        'awarding_institution',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'allow_notes' => 'boolean',
        'allow_ratings' => 'boolean',
        'estimated_duration' => 'integer',
        'duration_days' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            $course->slug = static::generateUniqueSlug($course->slug ?: $course->title);
        });

        static::updating(function ($course) {
            // If title or slug changed, ensure uniqueness
            if ($course->isDirty('title') || $course->isDirty('slug')) {
                $course->slug = static::generateUniqueSlug($course->slug ?: $course->title, $course->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        if ($base === '' || $base === null) {
            $base = Str::random(6);
        }

        $slug = $base;
        $i = 1;
        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.($i++);
        }
        return $slug;
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class)->orderBy('order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function levels()
    {
        return $this->hasMany(CourseLevel::class)->orderBy('order');
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class);
    }

    public function notes()
    {
        return $this->hasMany(CourseNote::class);
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    /**
     * Get the reviews for the course.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get all reviews including pending ones (for admin).
     */
    public function allReviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the category that owns the course.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the degree associated with the course.
     */
    public function degree()
    {
        return $this->belongsTo(Degree::class);
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

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withTimestamps()
            ->withPivot('enrolled_at', 'progress', 'completed_at', 'grade', 'notes');
    }

    public function learningPaths()
    {
        return $this->belongsToMany(LearningPath::class)
            ->withPivot('order')
            ->withTimestamps();
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class)->ordered();
    }

    public function activeTopics()
    {
        return $this->hasMany(Topic::class)->active()->ordered();
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_course');
    }

    public function getDiscountedPriceAttribute()
    {
        $price = (float) ($this->price ?? 0);
        if ($price <= 0) {
            return 0.0;
        }
        // Check active coupons that apply to all or this course
        $coupon = \App\Models\Coupon::active()
            ->where(function ($q) {
                $q->where('applies_to', 'all')
                  ->orWhereHas('courses', function ($c) {
                      $c->where('courses.id', $this->id);
                  });
            })
            ->orderByDesc('percentage')
            ->first();
        if (! $coupon) {
            return $price;
        }
        $discounted = $price * (1 - ((float) $coupon->percentage / 100));
        return round($discounted, 2);
    }

    /**
     * Get the URL to the course's thumbnail.
     *
     * @return string|null
     */
    public function getThumbnailUrlAttribute()
    {
        if (! $this->thumbnail) {
            return null;
        }

        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        return asset($this->thumbnail);
    }
}
