<?php

namespace Tests\Unit;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Services\CustomerService;
use App\Models\Customer;
use App\Enums\Salutation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Tests\TestCase;
use Mockery;

class CustomerServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_search_with_term_less_than_2_characters_returns_empty_collection()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $result = $customerService->search('a');

        $this->assertInstanceOf(SupportCollection::class, $result);
        $this->assertTrue($result->isEmpty());
        
        $mockRepository->shouldNotReceive('search');
    }

    public function test_search_with_single_character_returns_empty_collection()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $result = $customerService->search('J');

        $this->assertInstanceOf(SupportCollection::class, $result);
        $this->assertTrue($result->isEmpty());
        
        $mockRepository->shouldNotReceive('search');
    }

    public function test_search_with_empty_string_returns_empty_collection()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $result = $customerService->search('');

        $this->assertInstanceOf(SupportCollection::class, $result);
        $this->assertTrue($result->isEmpty());
        
        $mockRepository->shouldNotReceive('search');
    }

    public function test_search_with_exactly_2_characters_calls_repository()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $expectedResults = new Collection([
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe']
        ]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with('Jo')
            ->andReturn($expectedResults);

        $result = $customerService->search('Jo');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($expectedResults, $result);
    }

    public function test_search_with_term_longer_than_2_characters_calls_repository()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $expectedResults = new Collection([
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe']
        ]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with('John')
            ->andReturn($expectedResults);

        $result = $customerService->search('John');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($expectedResults, $result);
    }

    public function test_search_delegates_to_repository_with_exact_term()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $searchTerm = 'John Doe';
        $expectedResults = new Collection([]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults);

        $result = $customerService->search($searchTerm);

        $this->assertEquals($expectedResults, $result);
    }

    public function test_search_with_special_characters_calls_repository()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $searchTerm = 'John@Doe#123';
        $expectedResults = new Collection([]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults);

        $result = $customerService->search($searchTerm);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($expectedResults, $result);
    }

    public function test_search_with_multi_word_term_calls_repository()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $searchTerm = 'John Michael Doe';
        $expectedResults = new Collection([
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe']
        ]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults);

        $result = $customerService->search($searchTerm);

        $this->assertEquals($expectedResults, $result);
    }

    public function test_search_returns_collection_from_repository()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $repositoryResult = new Collection([
            ['id' => 1, 'first_name' => 'Jane', 'last_name' => 'Smith'],
            ['id' => 2, 'first_name' => 'Bob', 'last_name' => 'Johnson']
        ]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with('Jane')
            ->andReturn($repositoryResult);

        $result = $customerService->search('Jane');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($repositoryResult, $result);
        $this->assertCount(2, $result);
    }

    public function test_search_with_whitespace_only_term_calls_repository()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $searchTerm = '  ';
        $expectedResults = new Collection([]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults);

        $result = $customerService->search($searchTerm);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($expectedResults, $result);
    }

    public function test_search_with_numeric_string_calls_repository_when_length_valid()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $customerService = new CustomerService($mockRepository);

        $searchTerm = '12345';
        $expectedResults = new Collection([]);

        $mockRepository->shouldReceive('search')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults);

        $result = $customerService->search($searchTerm);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($expectedResults, $result);
    }

    public function test_customerDetails_with_valid_id_returns_customer()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $mockCustomer = Mockery::mock(Customer::class);

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andReturn($mockCustomer);

        $result = $service->customerDetails($customerId);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals($mockCustomer, $result);
    }

    public function test_customerDetails_with_nonexistent_id_returns_null()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andReturn(null);

        $result = $service->customerDetails($customerId);

        $this->assertNull($result);
    }

    public function test_customerDetails_with_repository_exception_propagates_error()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andThrow(new \Exception('Database connection failed'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database connection failed');

        $service->customerDetails($customerId);
    }

    public function test_getCustomerShowData_with_existing_customer_returns_transformed_data()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();
        $mockCustomer = Mockery::mock(Customer::class);
        
        // Mock all the attribute accesses that CustomerTransformer will need
        $mockCustomer->shouldReceive('getAttribute')->andReturnUsing(function($key) {
            return match($key) {
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'salutation' => Salutation::Mr,
                'communication_method' => 'email',
                'user' => null,
                default => null,
            };
        });
        
        // Mock setAttribute to allow property assignment
        $mockCustomer->shouldReceive('setAttribute')->andReturnNull();
        
        // Mock relations
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);
        
        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);
        
        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andReturn($mockCustomer);

        $result = $service->getCustomerShowData($customerId, $salutations);

        $this->assertIsArray($result);
        $this->assertNotNull($result);
    }

    public function test_getCustomerShowData_with_nonexistent_customer_returns_null()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andReturn(null);

        $result = $service->getCustomerShowData($customerId, $salutations);

        $this->assertNull($result);
    }

    public function test_getCustomerShowData_calls_customerDetails_with_correct_id()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();
        $mockCustomer = Mockery::mock(Customer::class);
        
        // Mock all the attribute accesses that CustomerTransformer will need
        $mockCustomer->shouldReceive('getAttribute')->andReturnUsing(function($key) {
            return match($key) {
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'salutation' => Salutation::Mr,
                'communication_method' => 'email',
                'user' => null,
                default => null,
            };
        });
        
        // Mock setAttribute to allow property assignment
        $mockCustomer->shouldReceive('setAttribute')->andReturnNull();
        
        // Mock relations
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);
        
        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);
        
        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andReturn($mockCustomer);

        $service->getCustomerShowData($customerId, $salutations);
    }

    public function test_getCustomerShowData_with_empty_salutations_array_still_works()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = [];
        $mockCustomer = Mockery::mock(Customer::class);
        
        // Mock all the attribute accesses that CustomerTransformer will need
        $mockCustomer->shouldReceive('getAttribute')->andReturnUsing(function($key) {
            return match($key) {
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'salutation' => Salutation::Mr,
                'communication_method' => 'email',
                'user' => null,
                default => null,
            };
        });
        
        // Mock setAttribute to allow property assignment
        $mockCustomer->shouldReceive('setAttribute')->andReturnNull();
        
        // Mock relations
        $mockEmailsRelation = Mockery::mock(HasMany::class);
        $mockPhonesRelation = Mockery::mock(HasMany::class);
        $mockAddressesRelation = Mockery::mock(HasMany::class);
        
        $mockCustomer->shouldReceive('emails')->andReturn($mockEmailsRelation);
        $mockCustomer->shouldReceive('phones')->andReturn($mockPhonesRelation);
        $mockCustomer->shouldReceive('addresses')->andReturn($mockAddressesRelation);
        
        $mockEmailsRelation->shouldReceive('first')->andReturn(null);
        $mockPhonesRelation->shouldReceive('first')->andReturn(null);
        $mockAddressesRelation->shouldReceive('first')->andReturn(null);

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andReturn($mockCustomer);

        $result = $service->getCustomerShowData($customerId, $salutations);

        $this->assertIsArray($result);
        $this->assertNotNull($result);
    }

    public function test_getCustomerShowData_with_repository_exception_propagates_error()
    {
        $mockRepository = Mockery::mock(CustomerRepositoryInterface::class);
        $service = new CustomerService($mockRepository);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();

        $mockRepository->shouldReceive('customerDetails')
            ->once()
            ->with($customerId)
            ->andThrow(new \Exception('Database error'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database error');

        $service->getCustomerShowData($customerId, $salutations);
    }
}
