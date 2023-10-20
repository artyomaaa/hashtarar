<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Filters\ApplicationMeetingHistory\DateBetweenFilter;
use App\Models\ApplicationMeetingHistory;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationMeetingHistoryRepository as ApplicationMeetingHistoryRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ApplicationMeetingHistoryRepository
    extends BaseRepository
    implements ApplicationMeetingHistoryRepositoryContract
{
    public function __construct(ApplicationMeetingHistory $model)
    {
        parent::__construct($model);
    }

    public function findById(int $meetingHistoryId, int $applicationId): ApplicationMeetingHistory|null
    {
        return $this->model->where('id', $meetingHistoryId)->where('application_id', $applicationId)->firstOrFail();
    }

    public function getMeetingHistoriesBy(int $applicationId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->with(['application', 'recordings'])
            ->where('application_id', $applicationId)
            ->allowedFilters([
                AllowedFilter::custom('date_between', new DateBetweenFilter),
                AllowedFilter::exact('type'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'date',
                'created_at'
            )
            ->paginate(request()->query("per_page", 20));
    }
}
