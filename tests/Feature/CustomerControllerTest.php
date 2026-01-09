<?php

namespace Tests\Feature;

use App\Services\CustomerService;
use App\Services\MetaDataService;
use App\Enums\Salutation;
use App\Enums\CommiunicationMethod;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use App\Models\User;

class CustomerControllerTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user for view rendering
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com'
        ]);
        $this->actingAs($user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_search_with_valid_term_returns_successful_response()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $mockResults = collect([
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe']
        ]);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with('John')
            ->andReturn($mockResults);

        $response = $this->postJson('/customer/search', [
            'term' => 'John'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => $mockResults->toArray(),
                     'message' => 'Search completed successfully'
                 ]);
    }

    public function test_search_with_minimum_valid_term_succeeds()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $mockResults = collect([]);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with('Jo')
            ->andReturn($mockResults);

        $response = $this->postJson('/customer/search', [
            'term' => 'Jo'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [],
                     'message' => 'Search completed successfully'
                 ]);
    }

    public function test_search_with_maximum_valid_term_succeeds()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $longTerm = str_repeat('a', 100);
        $mockResults = collect([]);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with($longTerm)
            ->andReturn($mockResults);

        $response = $this->postJson('/customer/search', [
            'term' => $longTerm
        ]);

        $response->assertStatus(200);
    }

    public function test_search_with_missing_term_returns_validation_error()
    {
        $response = $this->postJson('/customer/search', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['term']);
    }

    public function test_search_with_short_term_returns_validation_error()
    {
        $response = $this->postJson('/customer/search', [
            'term' => 'J'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['term']);
    }

    public function test_search_with_long_term_returns_validation_error()
    {
        $longTerm = str_repeat('a', 101);

        $response = $this->postJson('/customer/search', [
            'term' => $longTerm
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['term']);
    }

    public function test_search_with_non_string_term_returns_validation_error()
    {
        $response = $this->postJson('/customer/search', [
            'term' => 123
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['term']);
    }

    public function test_search_with_empty_string_returns_validation_error()
    {
        $response = $this->postJson('/customer/search', [
            'term' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['term']);
    }

    public function test_search_when_service_throws_exception_returns_error_response()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with('John')
            ->andThrow(new \Exception('Database connection failed'));

        $response = $this->postJson('/customer/search', [
            'term' => 'John'
        ]);

        $response->assertStatus(500)
                 ->assertJson([
                     'success' => false,
                     'data' => [],
                     'message' => 'Search failed: Database connection failed'
                 ]);
    }

    public function test_search_with_special_characters_handles_correctly()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $mockResults = collect([]);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with('John@Doe#123')
            ->andReturn($mockResults);

        $response = $this->postJson('/customer/search', [
            'term' => 'John@Doe#123'
        ]);

        $response->assertStatus(200);
    }

    public function test_search_with_whitespace_only_returns_validation_error()
    {
        $response = $this->postJson('/customer/search', [
            'term' => '   '
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['term']);
    }

    public function test_search_with_multi_word_term_succeeds()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $mockResults = collect([
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe']
        ]);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with('John Doe')
            ->andReturn($mockResults);

        $response = $this->postJson('/customer/search', [
            'term' => 'John Doe'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Search completed successfully'
                 ]);
    }

    public function test_search_service_is_called_with_exact_term()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);

        $mockResults = collect([]);

        $mockCustomerService->shouldReceive('search')
            ->once()
            ->with('exact-term-123')
            ->andReturn($mockResults);

        $response = $this->postJson('/customer/search', [
            'term' => 'exact-term-123'
        ]);

        $response->assertStatus(200);
    }

    public function test_show_with_invalid_uuid_format_returns_400_error()
    {
        $response = $this->get('/customer/invalid-uuid');
        $response->assertStatus(400);
    }

    public function test_show_with_nonexistent_customer_returns_404_error()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $mockMetaDataService = Mockery::mock(MetaDataService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);
        $this->app->instance(MetaDataService::class, $mockMetaDataService);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();

        $mockMetaDataService->shouldReceive('salutations')->once()->andReturn($salutations);
        $mockCustomerService->shouldReceive('getCustomerShowData')
            ->once()
            ->with($customerId, $salutations)
            ->andReturn(null);

        $response = $this->get("/customer/{$customerId}");
        $response->assertStatus(404);
    }

    public function test_show_with_malformed_uuid_returns_400_error()
    {
        $malformedUuids = [
            '550e8400-e29b-41d4-a716',
            '550e8400-e29b-41d4-a716-44665544zzzz',
            'not-a-uuid-at-all',
            '123-456-789'
        ];

        foreach ($malformedUuids as $uuid) {
            $response = $this->get("/customer/{$uuid}");
            $response->assertStatus(400);
        }
    }

    public function test_show_with_service_exception_propagates_error()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $mockMetaDataService = Mockery::mock(MetaDataService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);
        $this->app->instance(MetaDataService::class, $mockMetaDataService);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();

        $mockMetaDataService->shouldReceive('salutations')->once()->andReturn($salutations);
        $mockCustomerService->shouldReceive('getCustomerShowData')
            ->once()
            ->with($customerId, $salutations)
            ->andThrow(new \Exception('Database error'));

        $response = $this->get("/customer/{$customerId}");
        $response->assertStatus(500);
    }

    public function test_show_with_valid_uuid_and_existing_customer_returns_success()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $mockMetaDataService = Mockery::mock(MetaDataService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);
        $this->app->instance(MetaDataService::class, $mockMetaDataService);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();
        $customerData = [
            'customerId' => $customerId,
            'salutations' => $salutations,
            'customer_first_name' => 'John',
            'customer_last_name' => 'Doe',
            'customer_salutation' => Salutation::Mr,
            'customer_email' => 'john.doe@example.com',
            'customer_phone' => '+1234567890',
            'customer_address' => null,
            'customer_communication_method' => \App\Enums\CommiunicationMethod::EMAIL,
            'customer_salutations' => Salutation::Mr,
            'customer_staff_member' => null,
        ];

        $mockMetaDataService->shouldReceive('salutations')->once()->andReturn($salutations);
        $mockCustomerService->shouldReceive('getCustomerShowData')
            ->once()
            ->with($customerId, $salutations)
            ->andReturn($customerData);

        $response = $this->get("/customer/{$customerId}");
        $response->assertStatus(200);
    }

    public function test_show_with_empty_customer_id_returns_400_error()
    {
        $response = $this->get('/customer/');
        $response->assertStatus(404);
    }

    public function test_show_with_null_customer_id_returns_400_error()
    {
        $response = $this->get('/customer/null');
        $response->assertStatus(400);
    }

    public function test_show_calls_meta_service_before_customer_service()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $mockMetaDataService = Mockery::mock(MetaDataService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);
        $this->app->instance(MetaDataService::class, $mockMetaDataService);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();

        $mockMetaDataService->shouldReceive('salutations')->once()->andReturn($salutations);
        $mockCustomerService->shouldReceive('getCustomerShowData')
            ->once()
            ->with($customerId, $salutations)
            ->andReturn(null);

        $this->get("/customer/{$customerId}");
    }

    public function test_show_with_valid_uuid_v4_format_succeeds()
    {
        $mockCustomerService = Mockery::mock(CustomerService::class);
        $mockMetaDataService = Mockery::mock(MetaDataService::class);
        $this->app->instance(CustomerService::class, $mockCustomerService);
        $this->app->instance(MetaDataService::class, $mockMetaDataService);

        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $salutations = Salutation::cases();
        $customerData = [
            'customerId' => $customerId,
            'salutations' => $salutations,
            'customer_first_name' => 'John',
            'customer_last_name' => 'Doe',
            'customer_salutation' => Salutation::Mr,
            'customer_email' => 'john.doe@example.com',
            'customer_phone' => '+1234567890',
            'customer_address' => null,
            'customer_communication_method' => \App\Enums\CommiunicationMethod::EMAIL,
            'customer_salutations' => Salutation::Mr,
            'customer_staff_member' => null,
        ];

        $mockMetaDataService->shouldReceive('salutations')->once()->andReturn($salutations);
        $mockCustomerService->shouldReceive('getCustomerShowData')
            ->once()
            ->with($customerId, $salutations)
            ->andReturn($customerData);

        $response = $this->get("/customer/{$customerId}");
        $response->assertStatus(200);
    }
}
