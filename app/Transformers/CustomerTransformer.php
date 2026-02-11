<?php

namespace App\Transformers;

use App\Models\Customer;

class CustomerTransformer
{
    public static function forShow(Customer $customer, array $salutations): array
    {
        $phones = $customer->phones()->get();
        $phoneCount = $phones->count();
        
        $phoneDisplay = 'No phone numbers added';
        if ($phoneCount > 0) {
            $defaultPhone = $phones->firstWhere('is_default', true) ?? $phones->first();
            if ($phoneCount === 1) {
                $phoneDisplay = $defaultPhone->phone_number;
            } else {
                $otherCount = $phoneCount - 1;
                $phoneDisplay = $defaultPhone->phone_number . ' (+' . $otherCount . ' more)';
            }
        }
        
        $emails = $customer->emails()->get();
        $emailCount = $emails->count();
        
        $emailDisplay = 'No email addresses added';
        if ($emailCount > 0) {
            $defaultEmail = $emails->firstWhere('is_default', true) ?? $emails->first();
            if ($emailCount === 1) {
                $emailDisplay = $defaultEmail->email;
            } else {
                $otherCount = $emailCount - 1;
                $emailDisplay = $defaultEmail->email . ' (+' . $otherCount . ' more)';
            }
        }
        
        return [
            'customerId' => $customer->id,
            'salutations' => $salutations,
            'customer_first_name' => $customer->first_name,
            'customer_last_name' => $customer->last_name,
            'customer_salutation' => $customer->salutation,
            'customer_email' => $emailDisplay,
            'customer_phone' => $phoneDisplay,
            'customer_address' => $customer->addresses()->where('is_default', true)->first() ?? $customer->addresses()->first(),
            'customer_communication_method' => $customer->communication_method,
            'customer_salutations' => $customer->salutation,
            'customer_staff_member' => $customer->user ? $customer->user->first_name . " " . $customer->user->last_name : null,
            'phones' => $phones->map(function ($phone) {
                return [
                    'id' => $phone->id,
                    'number' => $phone->phone_number,
                    'isDefault' => $phone->is_default,
                    'isWhatsapp' => $phone->is_whatsapp
                ];
            })->toArray(),
            'emails' => $emails->map(function ($email) {
                return [
                    'id' => $email->id,
                    'email' => $email->email,
                    'isDefault' => $email->is_default ?? false
                ];
            })->toArray(),
            'addresses' => $customer->addresses()->with(['country'])->get()->map(function ($address) {
                return [
                    'id' => $address->id,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country_id' => $address->country_id,
                    'country_name' => $address->country->name ?? null,
                    'isDefault' => $address->is_default ?? false
                ];
            })->toArray()
        ];
    }

    public static function forSearch(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'salutation' => $customer->salutation?->value,
            'email' => $customer->emails()->first()?->email,
            'phone' => $customer->phones()->first()?->phone_number,
        ];
    }
}
