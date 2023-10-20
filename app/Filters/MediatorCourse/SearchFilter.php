<?php

namespace App\Filters\MediatorCourse;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $query
                ->orWhereHas('mediator', function ($query) use ($value) {
                    $query
                        ->where('firstname', 'LIKE', "%{$value}%")
                        ->orWhere('lastname', 'LIKE', "%{$value}%");
                });
        });
    }
}
