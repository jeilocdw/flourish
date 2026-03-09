<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name', 'short_name', 'base_unit', 'operator', 'operator_value', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
