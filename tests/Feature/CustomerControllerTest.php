<?php

namespace Tests\Feature;

use App\Services\CustomerService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Mockery;

class CustomerControllerTest extends TestCase
{
    use WithoutMiddleware;

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
}
