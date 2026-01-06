<?php

namespace App\Repositories\Customer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CustomerRepositoryInterface
{
    public function search(string $term, int $limit = 5): EloquentCollection;
}