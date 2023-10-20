<?php

namespace App\Filters\Course;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CreatedBetweenFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $from = is_array($value) ? $value[0] : $value;
            $to = $value[1] ?? null;

            if ($from && $to) {
                $query->whereDate('created_at', '>=', $from);
                $query->whereDate('created_at', '<=', $to);
            } else if (!$to) {
                $query->where('created_at', '=', $from);
            }
        });
    }
}
