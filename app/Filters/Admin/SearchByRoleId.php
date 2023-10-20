<?php

namespace App\Filters\Admin;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchByRoleId implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where(function ($query) use ($value) {
            $query
                ->where('role_id', 'LIKE', "%{$value}%");
        });
    }
}
