<?php

namespace App\Filters\Mediator;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $query
                ->whereHas('user', function ($query) use ($value) {
                    $query
                        ->where('firstname', 'LIKE', "%{$value}%")
                        ->orWhere('lastname', 'LIKE', "%{$value}%")
                        ->orWhere('ssn', 'LIKE', "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                });
        });
    }
}
