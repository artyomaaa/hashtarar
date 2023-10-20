<?php

namespace App\Filters\ApplicationMeetingHistory;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DateBetweenFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $from = is_array($value) ? $value[0] : $value;
            $to = $value[1] ?? null;

            if ($from && $to) {
                $query->whereDate('date', '>=', $from);
                $query->whereDate('date', '<=', $to);
            } else if (!$to) {
                $query->where('date', '=', $from);
            }
        });
    }
}
