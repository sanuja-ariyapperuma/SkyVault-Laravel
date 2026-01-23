<?php

namespace Database\Seeders;

use App\Models\Passport;
use App\Models\Country;
use App\Models\Nationality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportSeeder extends Seeder
{
    public function run(): void
    {
        $customerId = 'be27d49f-f140-4f3e-b9a3-081eab4c3d22';
        
        // Get first nationality and country for the passport
        $nationality = Nationality::first();
        $country = Country::first();
        
        if (!$nationality || !$country) {
            if ($this->command) {
                $this->command->error('Nationality or Country not found. Please run NationalitiesTableSeeder and CountriesTableSeeder first.');
            }
            return;
        }
        
        // Check if customer exists
        $customerExists = DB::table('customers')->where('id', $customerId)->exists();
        if (!$customerExists) {
            if ($this->command) {
                $this->command->error("Customer with ID {$customerId} not found.");
            }
            return;
        }
        
        // Check if passport already exists for this customer
        $existingPassport = Passport::where('customer_id', $customerId)->first();
        if ($existingPassport) {
            if ($this->command) {
                $this->command->info("Passport already exists for customer {$customerId}.");
            }
            return;
        }
        
        Passport::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'customer_id' => $customerId,
            'passport_number' => 'US1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'other_names' => 'Michael',
            'gender' => 'male',
            'date_of_birth' => '1990-01-15',
            'issue_date' => '2020-01-01',
            'expiry_date' => '2030-01-01',
            'place_of_birth' => 'New York, USA',
            'place_of_issue' => 'New York, USA',
            'nationality_id' => $nationality->id,
            'country_of_passport_id' => $country->id,
            'is_primary' => true,
        ]);
        
        if ($this->command) {
            $this->command->info("Successfully created passport for customer {$customerId}.");
        }
    }
}
