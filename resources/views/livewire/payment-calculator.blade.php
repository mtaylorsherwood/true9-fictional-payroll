<div>
    @if ($errors->any())
        <div class="my-3 rounded-md bg-red-50 p-4 dark:bg-red-500/15 dark:outline dark:outline-red-500/25">
            <div class="flex">
                <div class="shrink-0">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 text-red-400">
                        <path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                        There {{ $errors->count() === 1 ? 'is' : 'are' }} {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('error', $errors->count()) }} with your submission
                    </h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-200/80">
                        <ul role="list" class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($result)
        <div class="mb-6 rounded-md bg-blue-50 p-4 dark:bg-blue-500/10 dark:outline dark:outline-blue-500/20">
            <div class="flex">
                <div class="shrink-0">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 text-blue-400">
                        <path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Payment processing date is: <strong class="font-semibold">{{ $result['formatted']['payment_processing_date'] }}</strong>
                        for a payday of <strong class="font-semibold">{{ $result['formatted']['payday'] }}</strong>.
                        @if ($result['pay_date_adjusted_for_bank_holiday'] ?? false)
                            <span class="block mt-1 text-xs text-blue-600 dark:text-blue-400">* Payday adjusted to preceding working day due to a bank holiday.</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="calculate">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">
                <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Payment Period</h2>
                <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Please select the month and year to calculate the date salaries must be paid by.</p>
                <div class="mt-10 grid grid-cols-2 gap-x-6 gap-y-8 sm:grid-cols-8">
                    <div class="sm:col-span-4">
                        <label for="month" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Month</label>
                        <div class="mt-2 grid grid-cols-1">
                            <select id="month" wire:model="month" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus-visible:outline-indigo-500">
                                @for($i = 1; $i <= 12; $i++)
                                    @php $m = \Illuminate\Support\Carbon::create()->month($i) @endphp
                                    <option value="{{ $m->format('n') }}">{{ $m->format('F') }}</option>
                                @endfor
                            </select>
                            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <label for="year" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Year</label>
                        <div class="mt-2 grid grid-cols-1">
                            <select id="year" wire:model="year" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus-visible:outline-indigo-500">
                                @php $currentYear = \Illuminate\Support\Carbon::now()->year @endphp
                                @for($i = $currentYear; $i <= ($currentYear + 2); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" wire:loading.attr="disabled" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:focus-visible:outline-indigo-500 disabled:opacity-50">
                <span wire:loading.remove>Submit</span>
                <span wire:loading>Calculating...</span>
            </button>
        </div>
    </form>
</div>
