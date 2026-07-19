<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Report date-range filter. Defaults mirror the legacy screens: first of
 * the current month through today (or just today for the daily cash report).
 */
readonly class DateRange
{
    public function __construct(
        public Carbon $from,
        public Carbon $to,
    ) {}

    public static function fromRequest(Request $request, bool $defaultToday = false): self
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $defaultFrom = $defaultToday ? today() : today()->startOfMonth();

        return new self(
            $request->filled('from') ? Carbon::parse($request->string('from')) : $defaultFrom,
            $request->filled('to') ? Carbon::parse($request->string('to')) : today(),
        );
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    public function bounds(): array
    {
        return [$this->from->copy()->startOfDay(), $this->to->copy()->endOfDay()];
    }
}
