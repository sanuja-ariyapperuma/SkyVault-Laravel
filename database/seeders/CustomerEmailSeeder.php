<?php

namespace Database\Seeders;

use App\Models\CustomerEmail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerEmailSeeder extends Seeder
{
    public function run(): void
    {
        $customerId = 'be27d49f-f140-4f3e-b9a3-081eab4c3d22';
        
        $emails = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'email' => 'john.doe.primary@example.com',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'email' => 'john.doe.work@example.com',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'email' => 'john.doe.personal@example.com',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'email' => 'j.doe.backup@example.com',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'customer_id' => $customerId,
                'email' => 'johnny.doe@example.com',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('customer_emails')->insert($emails);
        
        $this->command->info("Successfully created 5 email addresses for customer {$customerId}!");
    }
}
