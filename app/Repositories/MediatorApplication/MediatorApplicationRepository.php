<?php

declare(strict_types=1);

namespace App\Repositories\MediatorApplication;

use App\Filters\Application\CreatedBetweenFilter;
use App\Filters\MediatorApplication\SearchFilter;
use App\Models\MediatorApplication;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\MediatorApplication\IMediatorApplicationRepository;
use App\Sorters\MediatorApplication\CaseTypeSort;
use App\Sorters\MediatorApplication\MediatorNameSort;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;


final class MediatorApplicationRepository
    extends BaseRepository
    implements IMediatorApplicationRepository
{
    public function __construct(MediatorApplication $model, private readonly IUserRepository $userRepository)
    {
        parent::__construct($model);
    }


    public function getMediatorApplicationsById(int $mediatorId): object
    {
        return $this->model->with('mediator')->where('user_id', $mediatorId)->get();
    }


    public function getMediatorApplications(int|null $mediatorApplicationId, int $userId): object
    {
        $user = $this->userRepository->findById($userId);
        $query = QueryBuilder::for($this->model)
            ->select('mediator_applications.*')
            ->with(['caseTypes'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('application_type_id'),
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
            );
        if (!$user->isEmployeeOrAdmin()) {
            $query->where('user_id', $userId);
        }
        if (is_null($mediatorApplicationId)) {
            return $query->paginate(request()->query("per_page", 20));
        }

        return $query->where('id', $mediatorApplicationId)->first();
    }

    public function getAuthMediatorApplications(int $mediatorId): array
    {
        return $this->model->where('user_id', $mediatorId)->get()->toArray();
    }


}
