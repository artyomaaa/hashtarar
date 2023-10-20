<?php

declare(strict_types=1);

namespace App\Repositories\Mediator;

use App\Enums\ApplicationStatuses;
use App\Filters\Mediator\SearchByGroupIdFilter;
use App\Filters\Mediator\SearchFilter;
use App\Includes\Mediator\ApplicationsCountInclude;
use App\Includes\Mediator\RejectedApplicationsCountInclude;
use App\Includes\Mediator\ResolvedApplicationsCountInclude;
use App\Models\MediatorDetails;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Mediator\IMediatorRepository as MediatorRepositoryContract;
use App\Sorters\Mediator\FinishedApplicationsCountSort;
use App\Sorters\Mediator\InProgressApplicationsCountSort;
use App\Sorters\Mediator\NameSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class MediatorRepository
    extends BaseRepository
    implements MediatorRepositoryContract
{
    public function __construct(MediatorDetails $model)
    {
        parent::__construct($model);
    }

    public function getMediators(): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('mediator_details.*')
            ->with(['user', 'user.mediatorExams.course'])
            ->allowedIncludes([
                AllowedInclude::custom('rejected_applications_count', new RejectedApplicationsCountInclude),
                AllowedInclude::custom('resolved_applications_count', new ResolvedApplicationsCountInclude),
                AllowedInclude::custom('in_progress_applications_count', new ApplicationsCountInclude(ApplicationStatuses::IN_PROGRESS->value)),
                AllowedInclude::custom('finished_applications_count', new ApplicationsCountInclude(ApplicationStatuses::FINISHED->value)),
            ])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::custom('search', new SearchFilter),
                AllowedFilter::custom('group_id', new SearchByGroupIdFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'group_id',
                AllowedSort::custom('name', new NameSort),
                AllowedSort::custom('in_progress_applications_count', new InProgressApplicationsCountSort),
                AllowedSort::custom('finished_applications_count', new FinishedApplicationsCountSort)
            )
            ->paginate(request()->query("per_page", 20));
    }

    public function findById(int $mediatorId): Model|QueryBuilder|null
    {
        return QueryBuilder::for($this->model)
            ->whereHas('user', function ($query) use ($mediatorId) {
                $query->where('id', $mediatorId);
            })
            ->allowedIncludes([
                AllowedInclude::custom('rejected_applications_count', new RejectedApplicationsCountInclude),
                AllowedInclude::custom('resolved_applications_count', new ResolvedApplicationsCountInclude),
                AllowedInclude::custom('in_progress_applications_count', new ApplicationsCountInclude(ApplicationStatuses::IN_PROGRESS->value)),
                AllowedInclude::custom('finished_applications_count', new ApplicationsCountInclude(ApplicationStatuses::FINISHED->value)),
                'attachments'
            ])
            ->firstOrFail();
    }

    public function findByUserId(int $userId): MediatorDetails|null
    {
        return $this->model->where('user_id', $userId)->firstOrFail();
    }

    public function updateOrCreate(array $data): MediatorDetails
    {
        return $this->model->updateOrCreate(
            ['user_id' => $data['user_id']],
            $data
        );
    }

    public function getMediatorDetailsGroupId(int $mediatorId): int|null
    {
        return $this->model->where('user_id', $mediatorId)->value('group_id');
    }

}
