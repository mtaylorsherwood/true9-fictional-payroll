<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Cmixin\BusinessTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviours for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        BusinessTime::enable('Illuminate\Support\Carbon', [
            'timezone' => config('app.timezone'),
            'monday' => ['09:00-17:00'],
            'tuesday' => ['09:00-17:00'],
            'wednesday' => ['09:00-17:00'],
            'thursday' => ['09:00-17:00'],
            'friday' => ['09:00-17:00'],
            'saturday' => [],
            'sunday' => [],
            'holidays' => self::getBankHolidays(),
        ]);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * This hard-coding isn't ideal but suits the purpose of this test application.
     *
     * Ideally, this would be configured via a config file and populated via an api, likely using https://www.gov.uk/bank-holidays or other service
     *
     * @return array<string, string>
     */
    private function getBankHolidays(): array
    {
        return [
            'New Year’s Day (2026-01-01)' => '2026-01-01',
            'Good Friday (2026-04-03)' => '2026-04-03',
            'Easter Monday (2026-04-06)' => '2026-04-06',
            'Early May bank holiday (2026-05-04)' => '2026-05-04',
            'Spring bank holiday (2026-05-25)' => '2026-05-25',
            'Summer bank holiday (2026-08-31)' => '2026-08-31',
            'Christmas Day (2026-12-25)' => '2026-12-25',
            'Boxing Day (2026-12-28)' => '2026-12-28',
            'New Year’s Day (2027-01-01)' => '2027-01-01',
            'Good Friday (2027-03-26)' => '2027-03-26',
            'Easter Monday (2027-03-29)' => '2027-03-29',
            'Early May bank holiday (2027-05-03)' => '2027-05-03',
            'Spring bank holiday (2027-05-31)' => '2027-05-31',
            'Summer bank holiday (2027-08-30)' => '2027-08-30',
            'Christmas Day (2027-12-27)' => '2027-12-27',
            'Boxing Day (2027-12-28)' => '2027-12-28',
            'New Year’s Day (2028-01-03)' => '2028-01-03',
            'Good Friday (2028-04-14)' => '2028-04-14',
            'Easter Monday (2028-04-17)' => '2028-04-17',
            'Early May bank holiday (2028-05-01)' => '2028-05-01',
            'Spring bank holiday (2028-05-29)' => '2028-05-29',
            'Summer bank holiday (2028-08-28)' => '2028-08-28',
            'Christmas Day (2028-12-25)' => '2028-12-25',
            'Boxing Day (2028-12-26)' => '2028-12-26',
        ];
    }
}
