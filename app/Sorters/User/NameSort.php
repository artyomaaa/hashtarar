<?php

namespace App\Sorters\User;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class NameSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $query
            ->orderBy('users.firstname', $direction)
            ->orderBy('users.lastname', $direction);
    }
}
