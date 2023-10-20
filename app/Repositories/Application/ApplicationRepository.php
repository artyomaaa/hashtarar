<?php

declare(strict_types=1);

namespace App\Repositories\Application;

use App\Filters\Application\CitizenSearchFilter;
use App\Filters\Application\CourtFilter;
use App\Filters\Application\CreatedBetweenFilter;
use App\Filters\Application\MediatorSearchFilter;
use App\Filters\Application\NotNewFilter;
use App\Filters\Application\SearchFilter;
use App\Models\Application;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Application\IApplicationRepository as ApplicationRepositoryContract;
use App\Sorters\Application\CaseTypeSort;
use App\Sorters\Application\CitizenNameSort;
use App\Sorters\Application\CourtSort;
use App\Sorters\Application\JudgeNameSort;
use App\Sorters\Application\MediatorNameSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class ApplicationRepository
    extends BaseRepository
    implements ApplicationRepositoryContract
{
    public function __construct(Application $model)
    {
        parent::__construct($model);
    }

    public function getApplications(): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('applications.*')
            ->with(['mediator', 'caseType', 'citizen'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('case_type_id'),
                AllowedFilter::custom('not_new', new NotNewFilter),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'number',
                AllowedSort::custom('case_type', new CaseTypeSort()),
                AllowedSort::custom('mediator_name', new MediatorNameSort()),
                AllowedSort::custom('citizen_name', new CitizenNameSort()),
            )
            ->allowedIncludes(['attachments', 'comments'])
            ->whereNotNull('citizen_id')
            ->whereNull('judge_id')
            ->paginate(request()->query("per_page", 20));
    }

    public function getClaimLetters(): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('applications.*')
            ->with(['mediator', 'caseType', 'judge', 'citizen', 'judge.judgeDetails', 'judge.judgeDetails.court'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('case_type_id'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
                AllowedFilter::custom('court_id', new CourtFilter),
                AllowedFilter::custom('search', new SearchFilter),
                AllowedFilter::custom('not_new', new NotNewFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'number',
                AllowedSort::custom('case_type', new CaseTypeSort()),
                AllowedSort::custom('mediator_name', new MediatorNameSort()),
                AllowedSort::custom('judge_name', new JudgeNameSort),
                AllowedSort::custom('citizen_name', new CitizenNameSort()),
                AllowedSort::custom('court', new CourtSort),
            )
            ->allowedIncludes(['attachments', 'comments'])
            ->whereNotNull('judge_id')
            ->paginate(request()->query("per_page", 20));
    }

    public function getMediatorClaimLettersBy(int $mediatorId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('applications.*')
            ->with(['citizen', 'caseType', 'judge', 'judge.judgeDetails', 'judge.judgeDetails.court'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('case_type_id'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
                AllowedFilter::custom('court_id', new CourtFilter),
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'number',
                AllowedSort::custom('case_type', new CaseTypeSort()),
                AllowedSort::custom('citizen_name', new CitizenNameSort()),
                AllowedSort::custom('judge_name', new JudgeNameSort),
                AllowedSort::custom('court', new CourtSort),
            )
            ->allowedIncludes(['attachments', 'comments'])
            ->whereNotNull('judge_id')
            ->where('mediator_id', $mediatorId)
            ->paginate(request()->query("per_page", 20));
    }

    public function getCitizenApplicationsBy(int $citizenId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('applications.*')
            ->with(['mediator', 'caseType'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('case_type_id'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
                AllowedFilter::custom('search', new MediatorSearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'number',
                AllowedSort::custom('case_type', new CaseTypeSort()),
                AllowedSort::custom('mediator_name', new MediatorNameSort()),
            )
            ->allowedIncludes(['attachments', 'comments'])
            ->where('citizen_id', $citizenId)
            ->paginate(request()->query("per_page", 20));
    }

    public function getJudgeApplicationsBy(int $judgeOrMediatorId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('applications.*')
            ->with(['mediator', 'caseType', 'citizen', 'attachments'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('case_type_id'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
                AllowedFilter::custom('search', new MediatorSearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'number',
                AllowedSort::custom('case_type', new CaseTypeSort()),
                AllowedSort::custom('mediator_name', new MediatorNameSort()),
            )
            ->allowedIncludes(['attachments', 'comments'])
            ->where('judge_id', $judgeOrMediatorId)
            ->orWhere('mediator_id', $judgeOrMediatorId)
            ->paginate(request()->query("per_page", 20));
    }

    public function getMediatorApplicationsBy(int $mediatorId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('applications.*')
            ->with(['citizen', 'caseType'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('case_type_id'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
                AllowedFilter::custom('search', new CitizenSearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'status',
                'created_at',
                'number',
                AllowedSort::custom('case_type', new CaseTypeSort()),
                AllowedSort::custom('citizen_name', new CitizenNameSort()),
            )
            ->allowedIncludes(['attachments', 'comments'])
            ->whereNull('judge_id')
            ->where('mediator_id', $mediatorId)
            ->paginate(request()->query("per_page", 20));
    }

    public function findById(int $applicationId): Model|QueryBuilder|null
    {
        return QueryBuilder::for($this->model)
            ->with([
                'caseType',
                'attachments',
                'mediator',
                'mediator.mediatorExams',
                'mediator.mediatorDetails',
                'citizen',
                'result',
            ])
            ->allowedIncludes(['judge'])
            ->where('id', $applicationId)
            ->firstOrFail();
    }
}
