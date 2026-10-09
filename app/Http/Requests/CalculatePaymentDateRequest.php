<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class CalculatePaymentDateRequest extends FormRequest
{
    /**
     * In a real-world application we would want to implement authorisation logic via a role or set of permissions
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return self::getRules((int) $this->input('year'));
    }

    /**
     * Ruleset to validate the incoming request either via the API or the frontend
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public static function getRules(?int $year = null): array
    {
        return [
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                function ($attribute, $value, $fail) use ($year) {
                    if ($year == now()->endOfYear()->format('Y') && $value < now()->endOfMonth()->format('n')) {
                        $fail('The chosen month must be either ' . now()->endOfMonth()->format('F') . ' or a subsequent month, when the year is the current year.');
                    }
                },
            ],
            'year' => [
                'required',
                'integer',
                'min:' . now()->startOfYear()->format('Y'),
                'max:' . now()->endOfYear()->addYears(2)->format('Y'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'year.min' => 'The year must be greater than or equal to ' . now()->startOfYear()->format('Y'),
            'year.max' => 'The year must be less than or equal to ' . now()->endOfYear()->addYears(2)->format('Y'),
        ];
    }
}
