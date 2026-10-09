<?php

declare(strict_types=1);

namespace App\Actions;

use Carbon\Constants\UnitValue;
use Illuminate\Support\Carbon;

final class CalculatePaymentDateAction
{
    /**
     * @param int $year
     * @param int $month
     *
     * @return array{
     *      year: int,
     *      month: int,
     *      payday: string,
     *      payment_processing_date: string,
     *      pay_date_adjusted_for_bank_holiday: bool,
     *      formatted: array{
     *          payday: string,
     *          payment_processing_date: string
     *      }
     *  }
     */
    public function execute(int $year, int $month): array
    {
        $pay_day = Carbon::create($year, $month)->lastOfMonth()->subDay();

        if ($pay_day->isWeekend()) {
            $pay_day->previousWeekday();
        }

        // If the payday falls on a Bank Holiday, the banks would not clear it, so move back another day
        $pay_day_adjusted = false;
        if (!$pay_day->isBusinessDay()) {
            $pay_day->subBusinessDay();
            $pay_day_adjusted = true;
        }

        // Working on the assumption that the processing time starts on the day after payment is initially made
        // In a real-world scenario, we could account for this changing i.e. if a payment made before midday counts that day as day 1, via a configuration setting, defaulting to 4 business days
        $payment_processing_date = $pay_day->clone()->subBusinessDays(4);

        return [
            'year' => $year,
            'month' => $month,
            'payday' => $pay_day->format('Y-m-d'),
            'payment_processing_date' => $payment_processing_date->format('Y-m-d'),
            'pay_date_adjusted_for_bank_holiday' => $pay_day_adjusted,
            'formatted' => [
                'payday' => $pay_day->format('d/m/Y'),
                'payment_processing_date' => $payment_processing_date->format('d/m/Y'),
            ],
        ];
    }
}
