<?php

namespace App\Includes\Mediator;

use App\Enums\ApplicationResultStatuses;
use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Builder;

class ResolvedApplicationsCountInclude implements IncludeInterface
{
    public function __invoke(Builder $query, string $include)
    {
        $query->withCount([
            "results as $include" => function ($query) {
                $query->where('status', ApplicationResultStatuses::RESOLVED->value);
            },
        ]);
    }
}
