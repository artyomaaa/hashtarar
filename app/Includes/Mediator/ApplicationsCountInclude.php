<?php

namespace App\Includes\Mediator;

use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Builder;

class ApplicationsCountInclude implements IncludeInterface
{
    public function __construct(
        public string $status = ""
    )
    {

    }

    public function __invoke(Builder $query, string $include)
    {
        $query->withCount([
            "applications as $include" => function ($query) {
                if ($this->status) {
                    $query->where('status', $this->status);
                }
            },
        ]);
    }
}
