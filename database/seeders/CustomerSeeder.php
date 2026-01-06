<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Enums\Salutation;
use App\Enums\CommiunicationMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = [
            'James', 'Mary', 'John', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda', 'William', 'Elizabeth',
            'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Charles', 'Karen',
            'Christopher', 'Nancy', 'Daniel', 'Lisa', 'Matthew', 'Betty', 'Anthony', 'Helen', 'Mark', 'Sandra',
            'Donald', 'Ashley', 'Steven', 'Kimberly', 'Paul', 'Donna', 'Andrew', 'Emily', 'Joshua', 'Carol',
            'Kenneth', 'Michelle', 'Kevin', 'Amanda', 'Brian', 'Melissa', 'George', 'Deborah', 'Timothy', 'Stephanie',
            'Ronald', 'Rebecca', 'Edward', 'Sharon', 'Jason', 'Laura', 'Jeffrey', 'Sarah', 'Ryan', 'Kimberly',
            'Jacob', 'Ashley', 'Gary', 'Judith', 'Nicholas', 'Maria', 'Eric', 'Heather', 'Jonathan', 'Megan',
            'Stephen', 'Emma', 'Larry', 'Olivia', 'Justin', 'Chloe', 'Scott', 'Samantha', 'Brandon', 'Natalie',
            'Benjamin', 'Sophia', 'Samuel', 'Ava', 'Gregory', 'Isabella', 'Alexander', 'Mia', 'Patrick', 'Emily',
            'Raymond', 'Madison', 'Jack', 'Abigail', 'Dennis', 'Charlotte', 'Jerry', 'Amelia', 'Tyler', 'Harper'
        ];
        
        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
            'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
            'Lee', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker',
            'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores', 'Green',
            'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell', 'Carter', 'Roberts', 'Phillips',
            'Evans', 'Turner', 'Diaz', 'Parker', 'Collins', 'Edwards', 'Stewart', 'Morris', 'Morris', 'Murphy',
            'Rogers', 'Reed', 'Cook', 'Morgan', 'Bell', 'Bailey', 'Cooper', 'Richardson', 'Cox', 'Howard',
            'Ward', 'Peterson', 'Gray', 'James', 'Watson', 'Brooks', 'Kelly', 'Sanders', 'Price', 'Bennett',
            'Wood', 'Barnes', 'Ross', 'Henderson', 'Coleman', 'Jenkins', 'Perry', 'Powell', 'Long', 'Patterson',
            'Hughes', 'Washington', 'Butler', 'Simmons', 'Foster', 'Gonzales', 'Bryant', 'Alexander', 'Russell', 'Griffin'
        ];
        
        $middleInitials = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $suffixes = ['', ' Jr', ' Sr', ' II', ' III', ' IV'];
        
        $salutations = Salutation::cases();
        $communicationMethods = CommiunicationMethod::cases();
        
        // Use admin user ID for all customers
        $userId = '53a3e936-0e97-4620-83d0-cfd65a6cd4bc';
        
        $batchSize = 1000;
        $totalCustomers = 50000;
        
        $this->command->info("Creating {$totalCustomers} customers in batches of {$batchSize}...");
        
        // Generate unique combinations with middle initials and suffixes
        $allCombinations = [];
        $combinationIndex = 0;
        
        foreach ($firstNames as $firstName) {
            foreach ($lastNames as $lastName) {
                foreach ($middleInitials as $middleInitial) {
                    foreach ($suffixes as $suffix) {
                        if ($combinationIndex >= $totalCustomers) break 4;
                        
                        $fullName = trim($firstName . ' ' . $middleInitial . '. ' . $lastName . $suffix);
                        $allCombinations[] = [
                            'first_name' => trim($firstName . ' ' . $middleInitial . '.'),
                            'last_name' => trim($lastName . $suffix),
                            'full_name_check' => $fullName
                        ];
                        $combinationIndex++;
                    }
                }
            }
        }
        
        // Shuffle combinations for randomness
        shuffle($allCombinations);
        
        // Process in batches
        for ($batch = 0; $batch < $totalCustomers / $batchSize; $batch++) {
            $customers = [];
            $batchCustomers = min($batchSize, $totalCustomers - ($batch * $batchSize));
            
            for ($i = 0; $i < $batchCustomers; $i++) {
                $nameCombo = array_pop($allCombinations);
                
                $customers[] = [
                    'id' => \Illuminate\Support\Str::uuid(),
                    'first_name' => $nameCombo['first_name'],
                    'last_name' => $nameCombo['last_name'],
                    'user_id' => $userId,
                    'salutation' => $salutations[array_rand($salutations)]->value,
                    'communication_method' => $communicationMethods[array_rand($communicationMethods)]->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('customers')->insert($customers);
            
            $processed = min(($batch + 1) * $batchSize, $totalCustomers);
            $this->command->info("Processed {$processed} / {$totalCustomers} customers");
        }
        
        $this->command->info("Successfully created {$totalCustomers} unique customers!");
    }
}
