<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InVideoQuiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_id',
        'quiz_name',
        'description',
        'timestamp',
        'questions_count',
        'questions',
        'is_active',
        'order',
    ];

    protected $casts = [
        'questions' => 'array',
        'is_active' => 'boolean',
        'questions_count' => 'integer',
        'order' => 'integer',
    ];

    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'material_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeForMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }
}
