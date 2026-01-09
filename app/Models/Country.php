<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        parent::booted();
        
        static::creating(function ($model) {
            // Prevent inserting records to country table, except in tests
            if (app()->environment() !== 'testing') {
                return false;
            }
        });
    }
}