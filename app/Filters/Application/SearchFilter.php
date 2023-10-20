<?php

namespace App\Filters\Application;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $query
                ->where('number', 'LIKE', "%{$value}%")
                ->orWhereHas('citizen', function ($query) use ($value) {
                    $query
                        ->where('firstname', 'LIKE', "%{$value}%")
                        ->orWhere('lastname', 'LIKE', "%{$value}%");
                })
                ->orWhereHas('mediator', function ($query) use ($value) {
                    $query
                        ->where('firstname', 'LIKE', "%{$value}%")
                        ->orWhere('ssn', 'LIKE', "%{$value}%")
                        ->orWhere('lastname', 'LIKE', "%{$value}%");
                })
                ->orWhereHas('judge', function ($query) use ($value) {
                    $query
                        ->where('firstname', 'LIKE', "%{$value}%")
                        ->orWhere('lastname', 'LIKE', "%{$value}%");
                });
        });
    }
}
