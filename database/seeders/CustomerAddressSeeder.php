<?php

namespace Database\Seeders;

use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {
        $customerId = 'be27d49f-f140-4f3e-b9a3-081eab4c3d22';
        
        // Get country IDs from the countries table
        $countries = DB::table('countries')->pluck('id', 'code');
        
        $addresses = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'address_line_1' => '123 Main Street',
                'address_line_2' => 'Apt 4B',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country_id' => $countries['USA'] ?? null,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'address_line_1' => '456 Park Avenue',
                'address_line_2' => 'Suite 200',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10022',
                'country_id' => $countries['USA'] ?? null,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'address_line_1' => '789 Beach Road',
                'address_line_2' => null,
                'city' => 'Miami',
                'state' => 'FL',
                'postal_code' => '33139',
                'country_id' => $countries['USA'] ?? null,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'address_line_1' => '321 Oak Street',
                'address_line_2' => 'Unit 5',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90028',
                'country_id' => $countries['USA'] ?? null,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'address_line_1' => '555 Elm Court',
                'address_line_2' => null,
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60601',
                'country_id' => $countries['USA'] ?? null,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('customer_addresses')->insert($addresses);
        
        $this->command->info("Successfully created 5 addresses for customer {$customerId}!");
    }
}
