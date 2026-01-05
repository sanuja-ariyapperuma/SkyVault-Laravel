<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerEmail extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted()
    {
        parent::booted(); // call base class booted

        static::saving(function ($email) {
            if ($email->is_default) {
                CustomerEmail::where('customer_id', $email->customer_id)
                    ->where('id', '!=', $email->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}