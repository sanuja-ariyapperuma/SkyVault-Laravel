<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Passport;
use Illuminate\Database\Eloquent\Factories\Factory;

class PassportFactory extends Factory
{
    protected $model = Passport::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'passport_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{7}'),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'other_names' => $this->faker->optional()->firstName(),
            'expiry_date' => $this->faker->dateTimeBetween('+1 year', '+10 years'),
        ];
    }
}
