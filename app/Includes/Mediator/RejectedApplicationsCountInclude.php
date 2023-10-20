<?php

namespace App\Includes\Mediator;

use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Builder;

class RejectedApplicationsCountInclude implements IncludeInterface
{
    public function __invoke(Builder $query, string $include)
    {
        $query->withCount([
            "rejections as $include"
        ]);
    }
}
