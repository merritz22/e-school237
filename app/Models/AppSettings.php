<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    protected $fillable = ['code', 'value', 'is_active', 'description'];
}
