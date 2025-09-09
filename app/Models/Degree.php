<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Degree extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'name_ar',
        'provider',
        'level',
        'description',
        'is_active',
        'sort_order',
        'duration_months',
        'credit_hours',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'duration_months' => 'integer',
        'credit_hours' => 'integer',
    ];

    /**
     * Get the courses associated with this degree.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Instructors who have courses under this degree.
     */
    public function instructors()
    {
        return $this->belongsToMany(User::class, 'courses', 'degree_id', 'instructor_id')->distinct();
    }
}
