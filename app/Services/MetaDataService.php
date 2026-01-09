<?php

namespace App\Services;

use App\Enums\Salutation;

class MetaDataService
{
    public function salutations(): array
    {
        return Salutation::cases();
    }
}
