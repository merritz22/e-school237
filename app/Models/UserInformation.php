<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    // ✅ Forcer le bon nom de table
    protected $table = 'user_informations';
    
    protected $fillable = [
        'user_id', 'establishment', 'birth_date', 'gender',
        'profession_id', 'current_level_id',
        'needs_special_support', 'class_filter_enabled', 'is_complete',
    ];

    protected $casts = [
        'birth_date'             => 'date',
        'needs_special_support'  => 'boolean',
        'class_filter_enabled'   => 'boolean',
        'is_complete'            => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function currentLevel()
    {
        return $this->belongsTo(Level::class, 'current_level_id');
    }
}