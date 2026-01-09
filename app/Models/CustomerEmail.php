<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerEmail extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted()
    {
        parent::booted(); // call base class booted for UUID generation

        // Completely disable the default email logic in tests to avoid interference
        if (app()->environment() === 'testing') {
            return;
        }

        static::saving(function ($email) {
            if ($email->is_default) {
                CustomerEmail::where('customer_id', $email->customer_id)
                    ->where('id', '!=', $email->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}