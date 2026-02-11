<?php

namespace App\Services;

use App\Enums\Salutation;
use App\Models\Country;

class MetaDataService
{
    public function salutations(): array
    {
        return Salutation::cases();
    }

    public function countries(): array
    {
        return Country::orderBy('name')->get()->pluck('name', 'id')->toArray();
    }
}
