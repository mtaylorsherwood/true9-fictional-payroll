<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\CalculatePaymentDateAction;
use App\Http\Requests\CalculatePaymentDateRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

final class PaymentCalculator extends Component
{
    public int $month;
    public int $year;
    /**
     * @var array<string, mixed>|null
     */
    public ?array $result;

    public function mount(): void
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function calculate(CalculatePaymentDateAction $action): void
    {
        $this->validate(
            CalculatePaymentDateRequest::getRules($this->year),
            new CalculatePaymentDateRequest()->messages(),
        );

        $this->result = $action->execute((int) $this->year, (int) $this->month);
    }

    public function render(): View
    {
        return view('livewire.payment-calculator');
    }
}
