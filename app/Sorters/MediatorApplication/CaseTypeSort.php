<?php

namespace App\Sorters\MediatorApplication;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class CaseTypeSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->leftJoin('case_types', 'mediator_applications.application_type_id', '=', 'case_types.id')
            ->orderBy('case_types.name', $direction);
    }
}
