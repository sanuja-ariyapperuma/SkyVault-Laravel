<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPhone extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted()
    {
        parent::booted();

        static::saving(function ($phone) {
            if ($phone->is_default) {
                CustomerPhone::where('customer_id', $phone->customer_id)
                    ->where('id', '!=', $phone->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}