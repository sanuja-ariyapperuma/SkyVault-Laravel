<?php

namespace App\Repositories\Customer;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CustomerRepositoryInterface
{
    public function search(string $term, int $limit = 5): EloquentCollection;

    public function customerDetails(string $customerId): ?Customer;
}