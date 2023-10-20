<?php

namespace App\Filters\Application;

use App\Enums\ApplicationStatuses;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class NotNewFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            if ($value) {
                $query->whereNot('status', ApplicationStatuses::NEW->value);
            }
        });
    }
}
