<?php

use Carbon\Carbon;

if (! function_exists('next_friday_closest_to_15th')) {
    /**
     * Next Friday on/after the 15th of a month.
     * @param int|null $month Numeric month (1–12); defaults to current month
     * @param int|null $year  Four-digit year; defaults to current year
     * @return array{date:string,read:string}
     */
    function next_friday_closest_to_15th(?int $month = null, ?int $year = null): array
    {
        $base = Carbon::create(
            $year  ?? now()->year,
            $month ?? now()->month,
            15
        );

        if (! $base->isFriday()) {
            $base->next(Carbon::FRIDAY);
        }

        return [
            'date' => $base->format('d-m-Y'), // e.g. 17-10-2025
            'read' => $base->format('F jS'),  // e.g. October 17th
        ];
    }
}
