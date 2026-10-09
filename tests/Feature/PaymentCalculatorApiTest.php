<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PaymentCalculatorApiTest extends TestCase
{
    protected function tearDown(): void
    {
        // Back to the future...
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Dataset, based on the current set of bank holidays.
     *
     * Flaky in real life as it wouldn't cover the future, but suitable for our current use case.
     *
     * @return array<array-key, array{
     *     year: int,
     *     month: int,
     *     expectedPayday: string,
     *     expectedProcessing: string,
     *     wasShiftedForHoliday: bool,
     * }>
     */
    public static function paymentDateScenariosProvider(): array
    {
        return [
            'Standard month (October 2026)' => [
                'year' => 2026,
                'month' => 10,
                'expectedPayday' => '2026-10-30',        // Friday (2nd to last day)
                'expectedProcessing' => '2026-10-26',    // Monday (4 business days prior)
                'wasShiftedForHoliday' => false,
            ],
            '2nd to last day is Saturday (May 2026)' => [
                'year' => 2026,
                'month' => 5,
                'expectedPayday' => '2026-05-29',        // Rolled back from Sat 30th to Fri 29th
                'expectedProcessing' => '2026-05-22',    // Skips Mon 25th Spring Bank Holiday
                'wasShiftedForHoliday' => false,
            ],
            'Payday lands on Summer Bank Holiday (August 2027)' => [
                'year' => 2027,
                'month' => 8,
                'expectedPayday' => '2027-08-27',        // Rolled back from Mon 30th (Holiday) to Fri 27th
                'expectedProcessing' => '2027-08-23',    // 4 business days prior
                'wasShiftedForHoliday' => true,
            ],
            'December Christmas lead-time (December 2026)' => [
                'year' => 2026,
                'month' => 12,
                'expectedPayday' => '2026-12-30',        // Wednesday 30th
                'expectedProcessing' => '2026-12-22',    // Skips Christmas (Dec 25) & Boxing Day (Dec 28)
                'wasShiftedForHoliday' => false,
            ],
        ];
    }

    #[DataProvider('paymentDateScenariosProvider')]
    public function test_it_calculates_correct_payday_and_processing_date(int $year, int $month, string $expectedPayday, string $expectedProcessing, bool $wasShiftedForHoliday): void
    {
        // Hit eighty-eight miles per hour to go back to the start of the year, so all months are valid
        Carbon::setTestNow(Carbon::createFromDate($year, 1, 1));

        $response = $this->postJson(route('calculatePaymentDate'), [
            'year' => $year,
            'month' => $month,
        ]);

        $response->assertOk()
            ->assertJson([
                'year' => $year,
                'month' => $month,
                'payday' => $expectedPayday,
                'payment_processing_date' => $expectedProcessing,
                'pay_date_adjusted_for_bank_holiday' => $wasShiftedForHoliday,

            ]);
    }
}
