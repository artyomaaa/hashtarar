<?php

namespace App\Filters\Application;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CourtFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($value) {
            $query
                ->whereHas('judge.judgeDetails.court', function ($query) use ($value) {
                    $query->where('id', $value);
                });
        });
    }
}
