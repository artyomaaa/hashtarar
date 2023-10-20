<?php

namespace App\Sorters\Mediator;

use App\Enums\ApplicationStatuses;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class FinishedApplicationsCountSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->leftJoinSub(
                Application::query()
                    ->select('mediator_id', DB::raw('count(*) as finished_applications_count'))
                    ->where('status', ApplicationStatuses::FINISHED->value)
                    ->groupBy('mediator_id'),
                'finished_applications',
                'finished_applications.mediator_id',
                '=',
                'mediator_details.user_id'
            )
            ->orderBy('finished_applications_count', $direction);
    }
}
