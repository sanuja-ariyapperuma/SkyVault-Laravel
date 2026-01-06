<?php

namespace App\Repositories\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    public function search(string $term, int $limit = 5): Collection;
}