<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PaymentCalculatorValidationTest extends TestCase
{
    public static function invalidPayloadProvider(): array
    {
        $currentYear = (int) Carbon::now()->format('Y');

        return [
            'Month greater than 12' => [
                'payload' => ['year' => $currentYear + 1, 'month' => 13],
                'expectedErrorField' => 'month',
            ],
            'Month less than 1' => [
                'payload' => ['year' => $currentYear + 1, 'month' => 0],
                'expectedErrorField' => 'month',
            ],
            'Past year' => [
                'payload' => ['year' => 2020, 'month' => 5],
                'expectedErrorField' => 'year',
            ],
            'Future year beyond allowed range' => [
                'payload' => ['year' => $currentYear + 5, 'month' => 5],
                'expectedErrorField' => 'year',
            ],
        ];
    }

    #[DataProvider('invalidPayloadProvider')]
    public function test_it_returns_422_unprocessable_entity_for_invalid_input(array $payload, string $expectedErrorField): void
    {
        $response = $this->postJson(route('calculatePaymentDate'), $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([$expectedErrorField]);
    }
}
