<?php

namespace Tests\Unit;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Services\CustomerService;
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
}
