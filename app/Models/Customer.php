<?php

namespace App\Models;

use App\Enums\CommiunicationMethod;
use App\Enums\Salutation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(CustomerPhone::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(CustomerEmail::class);
    }

    public function frequentFlyingNumbers(): HasMany
    {
        return $this->hasMany(CustomerFrequentFlyingNumber::class);
    }

    public function passports(): HasMany
    {
        return $this->hasMany(Passport::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function familyGroup(): BelongsTo
    {
        return $this->belongsTo(FamilyGroup::class);
    }

    protected function casts(): array
    {
        return [
            'communication_method' => CommiunicationMethod::class,
            'salutation' => Salutation::class,
        ];
    }

    protected static function booted()
    {
        static::deleted(function ($customer) {
            $familyGroup = $customer->familyGroup;
            if ($familyGroup && $familyGroup->customers()->count() === 0) {
                $familyGroup->delete();
            }
        });
    }


    
}