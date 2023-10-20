<?php

namespace App\Sorters\MediatorCourse;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class MediatorNameSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->leftJoin('users', 'users.id', '=', 'mediator_courses.mediator_id')
            ->orderBy('users.firstname', $direction)
            ->orderBy('users.lastname', $direction);
    }
}
