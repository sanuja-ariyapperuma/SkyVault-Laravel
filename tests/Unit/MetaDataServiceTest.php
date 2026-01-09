<?php

namespace Tests\Unit;

use App\Services\MetaDataService;
use App\Enums\Salutation;
use Tests\TestCase;

class MetaDataServiceTest extends TestCase
{
    public function test_salutations_returns_all_enum_cases()
    {
        $service = new MetaDataService();
        $result = $service->salutations();

        $this->assertIsArray($result);
        $this->assertCount(7, $result);
        
        $expectedCases = Salutation::cases();
        $this->assertEquals($expectedCases, $result);
        
        $this->assertContains(Salutation::Mr, $result);
        $this->assertContains(Salutation::Mrs, $result);
        $this->assertContains(Salutation::Miss, $result);
        $this->assertContains(Salutation::Dr, $result);
        $this->assertContains(Salutation::Prof, $result);
        $this->assertContains(Salutation::Rev, $result);
        $this->assertContains(Salutation::Hon, $result);
    }

    public function test_salutations_returns_correct_values()
    {
        $service = new MetaDataService();
        $result = $service->salutations();

        $salutationValues = array_map(fn($case) => $case->value, $result);
        
        $this->assertContains('Mr', $salutationValues);
        $this->assertContains('Mrs', $salutationValues);
        $this->assertContains('Miss', $salutationValues);
        $this->assertContains('Dr', $salutationValues);
        $this->assertContains('Prof', $salutationValues);
        $this->assertContains('Rev', $salutationValues);
        $this->assertContains('Hon', $salutationValues);
    }

    public function test_salutations_returns_same_result_on_multiple_calls()
    {
        $service = new MetaDataService();
        
        $firstCall = $service->salutations();
        $secondCall = $service->salutations();
        
        $this->assertEquals($firstCall, $secondCall);
        $this->assertSame(count($firstCall), count($secondCall));
    }

    public function test_salutations_returns_enum_instances_not_strings()
    {
        $service = new MetaDataService();
        $result = $service->salutations();

        foreach ($result as $salutation) {
            $this->assertInstanceOf(Salutation::class, $salutation);
        }
    }
}
