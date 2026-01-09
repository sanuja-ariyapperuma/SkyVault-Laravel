<?php

namespace Tests\Unit;

use App\Transformers\CustomerTransformer;
use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerEmail;
use App\Models\CustomerPhone;
use App\Models\CustomerAddress;
use App\Models\Country;
use App\Enums\Salutation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use Mockery;

class CustomerTransformerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_forShow_with_complete_customer_data_returns_correct_structure()
    {
        $mockCustomer = Mockery::mock(Customer::class);
        $mockUser = Mockery::mock(User::class);
        $mockEmail = Mockery::mock(CustomerEmail::class);
        $mockPhone = Mockery::mock(CustomerPhone::class);
        $mockAddress = Mockery::mock(CustomerAddress::class);
        $mockCountry = Mockery::mock(Country::class);
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);
        $salutations = Salutation::cases();

        $mockCustomer->shouldReceive('getAttribute')->with('id')->andReturn('550e8400-e29b-41d4-a716-446655440000');
        $mockCustomer->shouldReceive('getAttribute')->with('first_name')->andReturn('John');
        $mockCustomer->shouldReceive('getAttribute')->with('last_name')->andReturn('Doe');
        $mockCustomer->shouldReceive('getAttribute')->with('salutation')->andReturn(Salutation::Mr);
        $mockCustomer->shouldReceive('getAttribute')->with('communication_method')->andReturn('email');
        $mockCustomer->shouldReceive('getAttribute')->with('user')->andReturn($mockUser);

        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);

        $mockEmailsRelation->shouldReceive('first')->andReturn($mockEmail);
        $mockPhonesRelation->shouldReceive('first')->andReturn($mockPhone);
        $mockAddressesRelation->shouldReceive('first')->andReturn($mockAddress);

        $mockEmail->shouldReceive('getAttribute')->with('email')->andReturn('john.doe@example.com');
        $mockPhone->shouldReceive('getAttribute')->with('phone_number')->andReturn('+1234567890');
        $mockAddress->shouldReceive('getAttribute')->with('country')->andReturn($mockCountry);

        $mockUser->shouldReceive('getAttribute')->with('first_name')->andReturn('Jane');
        $mockUser->shouldReceive('getAttribute')->with('last_name')->andReturn('Smith');

        $result = CustomerTransformer::forShow($mockCustomer, $salutations);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('customerId', $result);
        $this->assertArrayHasKey('salutations', $result);
        $this->assertArrayHasKey('customer_first_name', $result);
        $this->assertArrayHasKey('customer_last_name', $result);
        $this->assertArrayHasKey('customer_salutation', $result);
        $this->assertArrayHasKey('customer_email', $result);
        $this->assertArrayHasKey('customer_phone', $result);
        $this->assertArrayHasKey('customer_address', $result);
        $this->assertArrayHasKey('customer_communication_method', $result);
        $this->assertArrayHasKey('customer_salutations', $result);
        $this->assertArrayHasKey('customer_staff_member', $result);

        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $result['customerId']);
        $this->assertEquals($salutations, $result['salutations']);
        $this->assertEquals('John', $result['customer_first_name']);
        $this->assertEquals('Doe', $result['customer_last_name']);
        $this->assertEquals(Salutation::Mr, $result['customer_salutation']);
        $this->assertEquals('john.doe@example.com', $result['customer_email']);
        $this->assertEquals('+1234567890', $result['customer_phone']);
        $this->assertEquals('email', $result['customer_communication_method']);
        $this->assertEquals(Salutation::Mr, $result['customer_salutations']);
        $this->assertEquals('Jane Smith', $result['customer_staff_member']);
    }

    public function test_forShow_with_null_relations_handles_gracefully()
    {
        $mockCustomer = Mockery::mock(Customer::class);
        $salutations = Salutation::cases();
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);

        $mockCustomer->shouldReceive('getAttribute')->with('id')->andReturn('550e8400-e29b-41d4-a716-446655440000');
        $mockCustomer->shouldReceive('getAttribute')->with('first_name')->andReturn('John');
        $mockCustomer->shouldReceive('getAttribute')->with('last_name')->andReturn('Doe');
        $mockCustomer->shouldReceive('getAttribute')->with('salutation')->andReturn(Salutation::Mr);
        $mockCustomer->shouldReceive('getAttribute')->with('communication_method')->andReturn('email');
        $mockCustomer->shouldReceive('getAttribute')->with('user')->andReturn(null);

        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);

        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $result = CustomerTransformer::forShow($mockCustomer, $salutations);

        $this->assertIsArray($result);
        $this->assertNull($result['customer_email']);
        $this->assertNull($result['customer_phone']);
        $this->assertNull($result['customer_address']);
        $this->assertNull($result['customer_staff_member']);
    }

    public function test_forShow_with_empty_salutations_array_still_works()
    {
        $mockCustomer = Mockery::mock(Customer::class);
        $salutations = [];
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);

        $mockCustomer->shouldReceive('getAttribute')->with('id')->andReturn('550e8400-e29b-41d4-a716-446655440000');
        $mockCustomer->shouldReceive('getAttribute')->with('first_name')->andReturn('John');
        $mockCustomer->shouldReceive('getAttribute')->with('last_name')->andReturn('Doe');
        $mockCustomer->shouldReceive('getAttribute')->with('salutation')->andReturn(Salutation::Mr);
        $mockCustomer->shouldReceive('getAttribute')->with('communication_method')->andReturn('email');
        $mockCustomer->shouldReceive('getAttribute')->with('user')->andReturn(null);

        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);

        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $result = CustomerTransformer::forShow($mockCustomer, $salutations);

        $this->assertIsArray($result);
        $this->assertEquals([], $result['salutations']);
    }

    public function test_forShow_includes_all_required_keys()
    {
        $mockCustomer = Mockery::mock(Customer::class);
        $salutations = Salutation::cases();
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);

        $mockCustomer->shouldReceive('getAttribute')->with('id')->andReturn('550e8400-e29b-41d4-a716-446655440000');
        $mockCustomer->shouldReceive('getAttribute')->with('first_name')->andReturn('John');
        $mockCustomer->shouldReceive('getAttribute')->with('last_name')->andReturn('Doe');
        $mockCustomer->shouldReceive('getAttribute')->with('salutation')->andReturn(Salutation::Mr);
        $mockCustomer->shouldReceive('getAttribute')->with('communication_method')->andReturn('email');
        $mockCustomer->shouldReceive('getAttribute')->with('user')->andReturn(null);

        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);

        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $result = CustomerTransformer::forShow($mockCustomer, $salutations);

        $expectedKeys = [
            'customerId',
            'salutations',
            'customer_first_name',
            'customer_last_name',
            'customer_salutation',
            'customer_email',
            'customer_phone',
            'customer_address',
            'customer_communication_method',
            'customer_salutations',
            'customer_staff_member'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $result);
        }
    }

    public function test_forShow_with_different_salutation_values()
    {
        $mockCustomer = Mockery::mock(Customer::class);
        $salutations = Salutation::cases();
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);

        $mockCustomer->shouldReceive('getAttribute')->with('id')->andReturn('550e8400-e29b-41d4-a716-446655440000');
        $mockCustomer->shouldReceive('getAttribute')->with('first_name')->andReturn('Jane');
        $mockCustomer->shouldReceive('getAttribute')->with('last_name')->andReturn('Doe');
        $mockCustomer->shouldReceive('getAttribute')->with('salutation')->andReturn(Salutation::Dr);
        $mockCustomer->shouldReceive('getAttribute')->with('communication_method')->andReturn('phone');
        $mockCustomer->shouldReceive('getAttribute')->with('user')->andReturn(null);

        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);

        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $result = CustomerTransformer::forShow($mockCustomer, $salutations);

        $this->assertEquals(Salutation::Dr, $result['customer_salutation']);
        $this->assertEquals(Salutation::Dr, $result['customer_salutations']);
        $this->assertEquals('phone', $result['customer_communication_method']);
    }
}
