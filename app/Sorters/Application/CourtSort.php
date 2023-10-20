<?php

namespace App\Sorters\Application;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class CourtSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->leftJoin('judge_details', 'applications.judge_id', '=', 'judge_details.user_id')
            ->leftJoin('courts', 'judge_details.court_id', '=', 'courts.id')
            ->orderBy('courts.name', $direction);
    }
}
