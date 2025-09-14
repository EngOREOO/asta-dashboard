<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_image',
        'rating',
        'comment',
        'is_approved',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'user_image_url',
        'stars_html',
    ];

    /**
     * Get the full URL for the user image
     */
    public function getUserImageUrlAttribute(): ?string
    {
        if (! $this->user_image) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->user_image, FILTER_VALIDATE_URL)) {
            return $this->user_image;
        }

        // Return the full path to the image
        return asset('storage/'.$this->user_image);
    }

    /**
     * Get HTML for star rating display
     */
    public function getStarsHtmlAttribute(): string
    {
        $stars = '';
        $rating = $this->rating;

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="ti ti-star-filled text-yellow-400"></i>';
            } else {
                $stars .= '<i class="ti ti-star text-gray-300"></i>';
            }
        }

        return $stars;
    }

    /**
     * Scope for approved testimonials
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for featured testimonials
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordering by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
