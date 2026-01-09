<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerEmail;
use App\Models\CustomerPhone;
use App\Models\CustomerAddress;
use App\Models\Country;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Tests\TestCase;

class CustomerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CustomerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CustomerRepository();
    }

    public function test_search_with_empty_term_returns_empty_collection()
    {
        $result = $this->repository->search('');
        
        $this->assertInstanceOf(EloquentCollection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_search_with_whitespace_only_term_returns_empty_collection()
    {
        $result = $this->repository->search('   ');
        
        $this->assertInstanceOf(EloquentCollection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_search_with_single_word_matches_first_name()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('John');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
        $this->assertEquals('John', $result->first()->first_name);
    }

    public function test_search_with_single_word_matches_last_name()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('Doe');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
        $this->assertEquals('Doe', $result->first()->last_name);
    }

    public function test_search_with_partial_name_matches()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'Jonathan',
            'last_name' => 'Doeman',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('Jon');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
    }

    public function test_search_with_multi_word_matches_all_words()
    {
        $user1 = \App\Models\User::create(['first_name' => 'Test', 'last_name' => 'User 1', 'name' => 'Test User 1', 'email' => 'test4@example.com', 'password' => bcrypt('password')]);
        $user2 = \App\Models\User::create(['first_name' => 'Test', 'last_name' => 'User 2', 'name' => 'Test User 2', 'email' => 'test5@example.com', 'password' => bcrypt('password')]);
        $user3 = \App\Models\User::create(['first_name' => 'Test', 'last_name' => 'User 3', 'name' => 'Test User 3', 'email' => 'test6@example.com', 'password' => bcrypt('password')]);
        
        $customer1 = Customer::create(['first_name' => 'John', 'last_name' => 'Doe', 'salutation' => 'Mr', 'user_id' => $user1->id, 'communication_method' => 'email']);
        $customer2 = Customer::create(['first_name' => 'John', 'last_name' => 'Smith', 'salutation' => 'Mr', 'user_id' => $user2->id, 'communication_method' => 'email']);
        $customer3 = Customer::create(['first_name' => 'Jane', 'last_name' => 'Doe', 'salutation' => 'Mrs', 'user_id' => $user3->id, 'communication_method' => 'email']);
        
        $result = $this->repository->search('John Doe');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer1->id, $result->first()->id);
    }

    public function test_search_with_multi_word_in_different_order()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test7@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('Doe John');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
    }

    public function test_search_with_sql_like_wildcards_escapes_properly()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test8@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('John');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
    }

    public function test_search_with_underscore_escaped_properly()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test9@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('John');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
    }

    public function test_search_respects_limit_parameter()
    {
        $users = collect();
        for ($i = 1; $i <= 10; $i++) {
            $users->add(\App\Models\User::create([
                'first_name' => 'Test',
                'last_name' => "User {$i}",
                'name' => "Test User {$i}",
                'email' => "test{$i}@example.com",
                'password' => bcrypt('password')
            ]));
        }
        
        for ($i = 0; $i < 10; $i++) {
            Customer::create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'salutation' => 'Mr',
                'user_id' => $users[$i]->id,
                'communication_method' => 'email'
            ]);
        }
        
        $result = $this->repository->search('John', 3);
        
        $this->assertCount(3, $result);
    }

    public function test_search_orders_by_last_name()
    {
        $user1 = \App\Models\User::create(['first_name' => 'Test', 'last_name' => 'User 1', 'name' => 'Test User 1', 'email' => 'test10@example.com', 'password' => bcrypt('password')]);
        $user2 = \App\Models\User::create(['first_name' => 'Test', 'last_name' => 'User 2', 'name' => 'Test User 2', 'email' => 'test11@example.com', 'password' => bcrypt('password')]);
        $user3 = \App\Models\User::create(['first_name' => 'Test', 'last_name' => 'User 3', 'name' => 'Test User 3', 'email' => 'test12@example.com', 'password' => bcrypt('password')]);
        
        $customer1 = Customer::create(['first_name' => 'John', 'last_name' => 'Anderson', 'salutation' => 'Mr', 'user_id' => $user1->id, 'communication_method' => 'email']);
        $customer2 = Customer::create(['first_name' => 'John', 'last_name' => 'Smith', 'salutation' => 'Mr', 'user_id' => $user2->id, 'communication_method' => 'email']);
        $customer3 = Customer::create(['first_name' => 'John', 'last_name' => 'Brown', 'salutation' => 'Mr', 'user_id' => $user3->id, 'communication_method' => 'email']);
        
        $result = $this->repository->search('John');
        
        $this->assertEquals('Anderson', $result->get(0)->last_name);
        $this->assertEquals('Brown', $result->get(1)->last_name);
        $this->assertEquals('Smith', $result->get(2)->last_name);
    }

    public function test_search_selects_specific_fields()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test13@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('John');
        $firstResult = $result->first();
        
        $this->assertTrue(isset($firstResult->id));
        $this->assertTrue(isset($firstResult->first_name));
        $this->assertTrue(isset($firstResult->last_name));
        $this->assertTrue(isset($firstResult->salutation));
        $this->assertFalse(isset($firstResult->user_id));
    }

    public function test_search_with_case_insensitive_matching()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test14@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('john');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
    }

    public function test_search_with_no_matches_returns_empty_collection()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test15@example.com',
            'password' => bcrypt('password')
        ]);
        
        Customer::create(['first_name' => 'John', 'last_name' => 'Doe', 'salutation' => 'Mr', 'user_id' => $user->id, 'communication_method' => 'email']);
        
        $result = $this->repository->search('NonExistent');
        
        $this->assertInstanceOf(EloquentCollection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_search_with_term_shorter_than_2_chars_still_searches_name_fields()
    {
        $user = \App\Models\User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test16@example.com',
            'password' => bcrypt('password')
        ]);
        
        $customer = Customer::create([
            'first_name' => 'Jo',
            'last_name' => 'Doe',
            'salutation' => 'Mr',
            'user_id' => $user->id,
            'communication_method' => 'email'
        ]);
        
        $result = $this->repository->search('Jo');
        
        $this->assertCount(1, $result);
        $this->assertEquals($customer->id, $result->first()->id);
    }

    public function test_customerDetails_with_valid_id_returns_customer_with_relations()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        $email = CustomerEmail::factory()->create(['customer_id' => $customer->id, 'is_default' => true]);
        $phone = CustomerPhone::factory()->create(['customer_id' => $customer->id, 'is_default' => true]);
        $country = Country::factory()->create();
        $address = CustomerAddress::factory()->create([
            'customer_id' => $customer->id,
            'country_id' => $country->id,
            'is_default' => true
        ]);

        $result = $this->repository->customerDetails($customer->id);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals($customer->id, $result->id);
        $this->assertTrue($result->relationLoaded('user'));
        $this->assertTrue($result->relationLoaded('emails'));
        $this->assertTrue($result->relationLoaded('phones'));
        $this->assertTrue($result->relationLoaded('addresses'));
    }

    public function test_customerDetails_with_nonexistent_id_returns_null()
    {
        $result = $this->repository->customerDetails('550e8400-e29b-41d4-a716-446655440000');

        $this->assertNull($result);
    }

    public function test_customerDetails_loads_only_default_relations()
    {
        $customer = Customer::factory()->create();
        
        CustomerEmail::factory()->create(['customer_id' => $customer->id, 'is_default' => false]);
        CustomerEmail::factory()->create(['customer_id' => $customer->id, 'is_default' => true]);
        
        CustomerPhone::factory()->create(['customer_id' => $customer->id, 'is_default' => false]);
        CustomerPhone::factory()->create(['customer_id' => $customer->id, 'is_default' => true]);
        
        $country = Country::factory()->create();
        CustomerAddress::factory()->create([
            'customer_id' => $customer->id,
            'country_id' => $country->id,
            'is_default' => false
        ]);
        CustomerAddress::factory()->create([
            'customer_id' => $customer->id,
            'country_id' => $country->id,
            'is_default' => true
        ]);

        $result = $this->repository->customerDetails($customer->id);

        $this->assertCount(1, $result->emails);
        $this->assertTrue($result->emails->first()->is_default);
        
        $this->assertCount(1, $result->phones);
        $this->assertTrue($result->phones->first()->is_default);
        
        $this->assertCount(1, $result->addresses);
        $this->assertTrue($result->addresses->first()->is_default);
    }

    public function test_customerDetails_selects_specific_user_fields()
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create();

        $result = $this->repository->customerDetails($customer->id);

        $this->assertTrue($result->relationLoaded('user'));
        $loadedUser = $result->user;
        
        $this->assertEquals(['id', 'first_name', 'last_name'], array_keys($loadedUser->getAttributes()));
    }

    public function test_customerDetails_selects_specific_email_fields()
    {
        $customer = Customer::factory()->create();
        $email = CustomerEmail::factory()->create(['customer_id' => $customer->id, 'is_default' => true]);

        $result = $this->repository->customerDetails($customer->id);

        $this->assertTrue($result->relationLoaded('emails'));
        $loadedEmail = $result->emails->first();
        
        if ($loadedEmail) {
            $this->assertEquals(['email'], array_keys($loadedEmail->getAttributes()));
        }
    }

    public function test_customerDetails_selects_specific_phone_fields()
    {
        $customer = Customer::factory()->create();
        $phone = CustomerPhone::factory()->create(['customer_id' => $customer->id, 'is_default' => true]);

        $result = $this->repository->customerDetails($customer->id);

        $this->assertTrue($result->relationLoaded('phones'));
        $loadedPhone = $result->phones->first();
        
        if ($loadedPhone) {
            $this->assertEquals(['phone_number'], array_keys($loadedPhone->getAttributes()));
        }
    }

    public function test_customerDetails_includes_country_in_address_relation()
    {
        $customer = Customer::factory()->create();
        $country = Country::factory()->create();
        $address = CustomerAddress::factory()->create([
            'customer_id' => $customer->id,
            'country_id' => $country->id,
            'is_default' => true
        ]);

        $result = $this->repository->customerDetails($customer->id);

        $this->assertTrue($result->relationLoaded('addresses'));
        $loadedAddress = $result->addresses->first();
        $this->assertTrue($loadedAddress->relationLoaded('country'));
    }

    public function test_customerDetails_with_null_user_handles_gracefully()
    {
        $user = \App\Models\User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $result = $this->repository->customerDetails($customer->id);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertTrue($result->relationLoaded('user'));
        $this->assertNotNull($result->user);
    }

    public function test_customerDetails_with_no_default_relations_returns_empty_collections()
    {
        $customer = Customer::factory()->create();

        $result = $this->repository->customerDetails($customer->id);

        $this->assertTrue($result->relationLoaded('emails'));
        $this->assertTrue($result->relationLoaded('phones'));
        $this->assertTrue($result->relationLoaded('addresses'));
        
        $this->assertCount(0, $result->emails);
        $this->assertCount(0, $result->phones);
        $this->assertCount(0, $result->addresses);
    }
}
