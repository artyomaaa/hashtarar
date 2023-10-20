<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Filters\Admin\SearchByRoleId;
use App\Filters\Admin\SearchFilter;
use App\Models\User;
use App\Repositories\Contracts\IUserRepository as UserRepositoryContract;
use App\Sorters\User\NameSort;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final class UserRepository
    extends BaseRepository
    implements UserRepositoryContract
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): User|null
    {
        return $this->model->where('email', $email)->first();
    }

    public function findById(int $id): User|null
    {
        return $this->model->where('id', $id)->with(['role', 'mediatorDetails', 'judgeDetails','mediatorCourse'])->first();
    }

    public function firstOrFailBySsn(string $ssn): User|null
    {
        return $this->model->where('ssn', $ssn)->firstOrFail();
    }

    public function findBySsn(string $ssn): User|null
    {
        return $this->model->where('ssn', $ssn)->first();
    }

    public function firstOrCreate(array $data): User
    {
        return $this->model->firstOrCreate(
            [
                "ssn" => $data["ssn"]
            ],
            $data
        );
    }

    public function getExamParticipantMediators(array $countMediatorIds)
    {
        return $this->model->whereIn('id', $countMediatorIds)->get();
    }


    public function getAllStaffOrEmployee($userId = null): LengthAwarePaginator
    {
        $query = QueryBuilder::for($this->model)
            ->with(['role'])
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter),
                AllowedFilter::custom('role_id', new SearchByRoleId),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                AllowedSort::custom('name', new NameSort()),
            );

        if (!is_null($userId)) {
            $query->where('id', $userId);
        }

        return $query->paginate(request()->query("per_page", 20));
    }
}
