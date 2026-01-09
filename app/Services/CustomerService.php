<?php

namespace App\Services;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Transformers\CustomerTransformer;
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

        return $this->customer->search($term)
            ->map(fn($customer) => CustomerTransformer::forSearch($customer));
    }

    public function customerDetails(string $customerId)
    {
        return $this->customer->customerDetails($customerId);
    }

    public function getCustomerShowData(string $customerId, array $salutations): ?array
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            return null;
        }

        return CustomerTransformer::forShow($customer, $salutations);
    }
}
