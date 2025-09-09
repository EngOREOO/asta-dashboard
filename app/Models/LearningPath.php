<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LearningPath extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $appends = [
        'average_rating',
        'total_price',
        'formatted_price',
        'image_url',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('course_learning_path.order');
    }

    /**
     * Get the average rating of all courses in this learning path
     */
    public function getAverageRatingAttribute()
    {
        if (! $this->relationLoaded('courses')) {
            return 0;
        }

        $courses = $this->courses;
        if ($courses->isEmpty()) {
            return 0;
        }

        $totalRating = $courses->sum('average_rating');
        $coursesWithRatings = $courses->where('average_rating', '>', 0)->count();

        return $coursesWithRatings > 0 ? round($totalRating / $coursesWithRatings, 1) : 0;
    }

    /**
     * Get the total price of all courses in this learning path
     */
    public function getTotalPriceAttribute()
    {
        if (! $this->relationLoaded('courses')) {
            return 0;
        }

        return $this->courses->sum('price');
    }

    /**
     * Get the formatted total price
     */
    public function getFormattedPriceAttribute()
    {
        $total = $this->total_price;

        if ($total == 0) {
            return 'مجاني';
        }

        return number_format($total, 2);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Return the full path to the image in public/images directory
        return asset('images/'.$this->image);
    }
}
