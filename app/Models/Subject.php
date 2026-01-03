<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'slug',
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
     * Articles in this subject.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * supports in this subject.
     */
    public function supports(): HasMany
    {
        return $this->hasMany(EducationalResource::class);
    }

    /**
     * Evaluation subjects by subject category.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(EvaluationSubject::class, 'subject_id');
    }

}
