<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerEmail;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEmailFactory extends Factory
{
    protected $model = CustomerEmail::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_primary' => true,
        ];
    }
}
