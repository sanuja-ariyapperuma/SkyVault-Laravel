<?php

namespace App\Transformers;

use App\Models\Customer;

class CustomerTransformer
{
    public static function forShow(Customer $customer, array $salutations): array
    {
        return [
            'customerId' => $customer->id,
            'salutations' => $salutations,
            'customer_first_name' => $customer->first_name,
            'customer_last_name' => $customer->last_name,
            'customer_salutation' => $customer->salutation,
            'customer_email' => $customer->emails()->first()?->email,
            'customer_phone' => $customer->phones()->first()?->phone_number,
            'customer_address' => $customer->addresses()->first(),
            'customer_communication_method' => $customer->communication_method,
            'customer_salutations' => $customer->salutation,
            'customer_staff_member' => $customer->user ? $customer->user->first_name . " " . $customer->user->last_name : null,
        ];
    }

    public static function forSearch(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'salutation' => $customer->salutation,
            'email' => $customer->emails()->first()?->email,
            'phone' => $customer->phones()->first()?->phone_number,
        ];
    }
}
