<?php

namespace App\Sorters\Judge;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class CourtSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->leftJoin('courts', 'court.id', '=', 'judge_details.court_id')
            ->orderBy('courts.name', $direction);
    }
}
