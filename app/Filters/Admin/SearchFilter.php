<?php

namespace App\Filters\Admin;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where(function ($query) use ($value) {
            $query
                ->where('firstname', 'LIKE', "%{$value}%")
                ->orWhere('lastname', 'LIKE', "%{$value}%")
                ->orWhere('email', 'LIKE', "%{$value}%")
                ->orWhere('phone', 'LIKE', "%{$value}%")
                ->orWhereHas('role', function ($query) use ($value) {
                    $query
                        ->where('name', 'LIKE', "%{$value}%");
                });
        });
    }
}
