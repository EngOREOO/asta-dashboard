<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'bio',
        'birth_date',
        'teaching_field',
        'job_title',
        'phone',
        'phones',
        'social_links',
        'district',
        'street',
        'city',
        'department',
        'specialization',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'provider_token',
        'provider_refresh_token',
        'roles', // Hide the roles relationship from JSON output
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phones' => 'array',
            'social_links' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot('enrolled_at', 'progress', 'completed_at', 'grade', 'notes')
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot('progress', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Get courses where this user is the instructor
     */
    public function instructorCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function materialCompletions(): HasMany
    {
        return $this->hasMany(MaterialCompletion::class);
    }

    /**
     * Get clean role names for JSON output
     */
    public function getRoleAttribute(): array
    {
        return $this->getRoleNames()->toArray();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (! $this->profile_photo_path) {
            return null;
        }

        if (filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)) {
            return $this->profile_photo_path;
        }

        return asset($this->profile_photo_path);
    }

    public function completedMaterials(): HasManyThrough
    {
        return $this->hasManyThrough(
            CourseMaterial::class,
            MaterialCompletion::class,
            'user_id',
            'id',
            'id',
            'course_material_id'
        );
    }

    public function getInitialsAttribute(): string
    {
        // يتعامل مع الأسماء العربية/المركبة ويشيل المسافات الزايدة
        $parts = preg_split('/\s+/u', trim((string) $this->name), -1, PREG_SPLIT_NO_EMPTY);

        // خُد أول حرف من أول جزئين كحد أقصى (اختياري)
        $parts = array_slice($parts, 0, 2);

        return collect($parts)
            ->map(fn ($p) => mb_substr($p, 0, 1, 'UTF-8'))
            ->implode('');
    }

    public function courseMaterialProgress()
    {
        return $this->hasMany(CourseMaterialProgress::class);
    }

    /**
     * Get the user's wishlist courses.
     */
    public function wishlist()
    {
        return $this->belongsToMany(Course::class, 'user_wishlist')->withTimestamps();
    }

    public function instructorRatings()
    {
        return $this->hasMany(InstructorRating::class, 'instructor_id');
    }

    public function instructorApplication()
    {
        return $this->hasOne(InstructorApplication::class);
    }

    /**
     * Get total students across all instructor courses
     */
    public function getTotalStudentsAttribute()
    {
        if (! $this->hasRole('instructor')) {
            return 0;
        }

        return $this->instructorCourses()
            ->join('course_user', 'courses.id', '=', 'course_user.course_id')
            ->distinct('course_user.user_id')
            ->count('course_user.user_id');
    }

    public function reviewedApplications()
    {
        return $this->hasMany(InstructorApplication::class, 'reviewed_by');
    }

    /**
     * Boot method to auto-enroll new students in the welcome course
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Auto-enroll new students in the welcome course
            if ($user->hasRole('student')) {
                $welcomeCourse = Course::where('title', 'الدورة الترحيبية')
                    ->where('status', 'published')
                    ->first();

                if ($welcomeCourse && ! $user->enrolledCourses()->where('course_id', $welcomeCourse->id)->exists()) {
                    $user->enrolledCourses()->attach($welcomeCourse->id, ['enrolled_at' => now()]);
                }
            }
        });
    }
}
