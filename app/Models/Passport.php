<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passport extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function visas(): HasMany
    {
        return $this->hasMany(Visa::class);
    }

    protected static function booted()
    {
        parent::booted(); // call base class booted

        static::saving(function ($passport) {
            if ($passport->is_primary) {
                Passport::where('customer_id', $passport->customer_id)
                    ->where('id', '!=', $passport->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}