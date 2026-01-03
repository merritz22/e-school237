<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'system',
        'school',
        'description',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($subject) {
            if (empty($subject->slug)) {
                $subject->slug = Str::slug($subject->name);
            }
        });
    }

    // Relations
    /**
     * supports in this levels.
     */
    public function supports(): HasMany
    {
        return $this->hasMany(EducationalResource::class);
    }

    /**
     * Evaluation subjects by level category.
     */
    public function evaluationSubjectsByLevel(): HasMany
    {
        return $this->hasMany(EvaluationSubject::class, 'level_id');
    }
}
