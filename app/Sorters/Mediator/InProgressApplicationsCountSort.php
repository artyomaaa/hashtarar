<?php

namespace App\Sorters\Mediator;

use App\Enums\ApplicationStatuses;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class InProgressApplicationsCountSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->leftJoinSub(
                Application::query()
                    ->select('mediator_id', DB::raw('count(*) as in_progress_applications_count'))
                    ->where('status', ApplicationStatuses::IN_PROGRESS->value)
                    ->groupBy('mediator_id'),
                'in_progress_applications',
                'in_progress_applications.mediator_id',
                '=',
                'mediator_details.user_id'
            )
            ->orderBy('in_progress_applications_count', $direction);
    }
}
