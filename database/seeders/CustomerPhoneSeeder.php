<?php

namespace Database\Seeders;

use App\Models\CustomerPhone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerPhoneSeeder extends Seeder
{
    public function run(): void
    {
        $customerId = 'be27d49f-f140-4f3e-b9a3-081eab4c3d22';
        
        $phoneNumbers = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'phone_number' => '+1-555-0101',
                'is_whatsapp' => false,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'phone_number' => '+1-555-0102',
                'is_whatsapp' => true,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'phone_number' => '+1-555-0103',
                'is_whatsapp' => false,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'phone_number' => '+1-555-0104',
                'is_whatsapp' => true,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'phone_number' => '+1-555-0105',
                'is_whatsapp' => false,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('customer_phones')->insert($phoneNumbers);
        
        $this->command->info("Successfully created 5 phone numbers for customer {$customerId}!");
    }
}
