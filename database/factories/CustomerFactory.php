<?php

namespace Database\Factories;

use App\Enums\Salutation;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'user_id' => \App\Models\User::factory(),
            'salutation' => $this->faker->randomElement(Salutation::cases()),
            'communication_method' => 'email',
        ];
    }
}
