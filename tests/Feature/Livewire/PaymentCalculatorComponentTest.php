<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\PaymentCalculator;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

final class PaymentCalculatorComponentTest extends TestCase
{
    protected function tearDown(): void
    {
        // Reset Carbon time-travel after each test execution
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_component_renders_successfully(): void
    {
        Livewire::test(PaymentCalculator::class)
            ->assertStatus(200)
            ->assertSee('Payment Period');
    }

    public function test_component_validates_out_of_bounds_input(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 1, 1));

        Livewire::test(PaymentCalculator::class)
            ->set('year', 2026)
            ->set('month', 13)
            ->call('calculate')
            ->assertHasErrors(['month']);
    }

    public function test_component_prevents_selecting_past_months_in_current_year(): void
    {
        // Freeze time to October 2026
        Carbon::setTestNow(Carbon::create(2026, 10, 1));

        // Choosing May (5) in current year 2026 should fail our validation as it is a previous month
        Livewire::test(PaymentCalculator::class)
            ->set('year', 2026)
            ->set('month', 5)
            ->call('calculate')
            ->assertHasErrors(['month']);
    }

    public function test_component_calculates_and_displays_dates_when_submitted(): void
    {
        $current_year = Carbon::now()->year;
        Carbon::setTestNow(Carbon::create($current_year, 1, 1));

        Livewire::test(PaymentCalculator::class)
            ->set('year', $current_year)
            ->set('month', 5)
            ->call('calculate')
            ->assertHasNoErrors()
            ->assertSet('result.payday', '2026-05-29')
            ->assertSet('result.payment_processing_date', '2026-05-22')
            ->assertSee('29/05/2026')
            ->assertSee('22/05/2026');
    }
}
