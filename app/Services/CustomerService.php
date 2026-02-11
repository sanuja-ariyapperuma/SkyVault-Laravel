<?php

namespace App\Services;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Transformers\CustomerTransformer;
use App\Models\CustomerPhone;
use App\Models\CustomerEmail;
use App\Models\CustomerAddress;
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

        $results = $this->customer->search($term);
        
        // Handle case where repository returns arrays instead of Customer objects
        if ($results->isNotEmpty() && is_array($results->first())) {
            return $results;
        }
        
        return $results->map(fn($customer) => CustomerTransformer::forSearch($customer));
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

    public function addPhone(string $customerId, array $phoneData): CustomerPhone
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        return $customer->phones()->create([
            'phone_number' => $phoneData['phone_number'],
            'is_default' => $phoneData['is_default'] ?? false,
            'is_whatsapp' => $phoneData['is_whatsapp'] ?? false,
        ]);
    }

    public function updatePhone(string $customerId, string $phoneId, array $phoneData): CustomerPhone
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $phone = $customer->phones()->where('id', $phoneId)->first();
        
        if (is_null($phone)) {
            throw new \Exception('Phone number not found');
        }

        $phone->update([
            'phone_number' => $phoneData['phone_number'],
            'is_whatsapp' => $phoneData['is_whatsapp'] ?? false,
        ]);

        return $phone;
    }

    public function deletePhone(string $customerId, string $phoneId): void
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $phone = $customer->phones()->where('id', $phoneId)->first();
        
        if (is_null($phone)) {
            throw new \Exception('Phone number not found');
        }

        $phone->delete();
    }

    public function addEmail(string $customerId, array $emailData): CustomerEmail
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        return $customer->emails()->create([
            'email' => $emailData['email'],
        ]);
    }

    public function updateEmail(string $customerId, string $emailId, array $emailData): CustomerEmail
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $email = $customer->emails()->where('id', $emailId)->first();
        
        if (is_null($email)) {
            throw new \Exception('Email address not found');
        }

        $email->update([
            'email' => $emailData['email'],
        ]);

        return $email;
    }

    public function deleteEmail(string $customerId, string $emailId): void
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $email = $customer->emails()->where('id', $emailId)->first();
        
        if (is_null($email)) {
            throw new \Exception('Email address not found');
        }

        $email->delete();
    }

    public function setDefaultEmail(string $customerId, string $emailId): void
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $email = $customer->emails()->where('id', $emailId)->first();
        
        if (is_null($email)) {
            throw new \Exception('Email address not found');
        }

        // Remove default status from all emails for this customer
        $customer->emails()->where('is_default', true)->update(['is_default' => false]);
        
        // Set the new default email
        $email->update(['is_default' => true]);
    }

    public function addAddress(string $customerId, array $addressData): CustomerAddress
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        return $customer->addresses()->create([
            'address_line_1' => $addressData['address_line_1'],
            'address_line_2' => $addressData['address_line_2'] ?? null,
            'city' => $addressData['city'],
            'state' => $addressData['state'] ?? null,
            'postal_code' => $addressData['postal_code'] ?? null,
            'country_id' => $addressData['country_id'],
        ]);
    }

    public function updateAddress(string $customerId, string $addressId, array $addressData): CustomerAddress
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $address = $customer->addresses()->where('id', $addressId)->first();
        
        if (is_null($address)) {
            throw new \Exception('Address not found');
        }

        $address->update([
            'address_line_1' => $addressData['address_line_1'],
            'address_line_2' => $addressData['address_line_2'] ?? null,
            'city' => $addressData['city'],
            'state' => $addressData['state'] ?? null,
            'postal_code' => $addressData['postal_code'] ?? null,
            'country_id' => $addressData['country_id'],
        ]);

        return $address;
    }

    public function deleteAddress(string $customerId, string $addressId): void
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $address = $customer->addresses()->where('id', $addressId)->first();
        
        if (is_null($address)) {
            throw new \Exception('Address not found');
        }

        $address->delete();
    }

    public function setDefaultAddress(string $customerId, string $addressId): void
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $address = $customer->addresses()->where('id', $addressId)->first();
        
        if (is_null($address)) {
            throw new \Exception('Address not found');
        }

        // Remove default status from all addresses for this customer
        $customer->addresses()->where('is_default', true)->update(['is_default' => false]);
        
        // Set the new default address
        $address->update(['is_default' => true]);
    }

    public function setDefaultPhone(string $customerId, string $phoneId): void
    {
        $customer = $this->customerDetails($customerId);
        
        if (is_null($customer)) {
            throw new \Exception('Customer not found');
        }

        $phone = $customer->phones()->where('id', $phoneId)->first();
        
        if (is_null($phone)) {
            throw new \Exception('Phone number not found');
        }

        // Remove default status from all phones for this customer
        $customer->phones()->where('is_default', true)->update(['is_default' => false]);
        
        // Set the new default phone
        $phone->update(['is_default' => true]);
    }
}
