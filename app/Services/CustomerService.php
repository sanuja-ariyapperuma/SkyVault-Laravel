<?php

namespace App\Services;

use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Support\Collection;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customer
    ) {}

    public function search(string $term): Collection
    {
        if (strlen($term) < 2) {
            return collect();
        }

        return $this->customer->search($term);
    }

    public function customerDetails(string $customerId)
    {
        return $this->customer->customerDetails($customerId);
    }
}
