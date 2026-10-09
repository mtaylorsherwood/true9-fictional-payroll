<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CalculatePaymentDateAction;
use App\Http\Requests\CalculatePaymentDateRequest;
use Illuminate\Http\JsonResponse;

final class PaymentCalculatorController extends Controller
{
    public function __invoke(CalculatePaymentDateRequest $request, CalculatePaymentDateAction $action): JsonResponse
    {
        $result = $action->execute((int) $request->validated('year'), (int) $request->validated('month'));

        return response()->json($result);
    }
}
