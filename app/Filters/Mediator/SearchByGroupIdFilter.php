<?php

namespace App\Filters\Mediator;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchByGroupIdFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $query->when(
                ($value == 0),
                function ($query) use ($value) {
                    $query->whereNull('group_id');
                },
                function ($query) use ($value) {
                    $query->where('group_id', 'LIKE', "%{$value}%");
                }
            );
        });
    }
}
