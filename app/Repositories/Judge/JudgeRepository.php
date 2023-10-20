<?php

declare(strict_types=1);

namespace App\Repositories\Judge;

use App\Filters\Mediator\SearchFilter;
use App\Models\JudgeDetails;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Judge\IJudgeRepository as JudgeRepositoryContract;
use App\Sorters\Judge\CourtSort;
use App\Sorters\Judge\NameSort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class JudgeRepository
    extends BaseRepository
    implements JudgeRepositoryContract
{
    public function __construct(JudgeDetails $model)
    {
        parent::__construct($model);
    }

    public function getJudges(): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->select('judge_details.*')
            ->with(['court', 'user'])
            ->allowedFilters([
                AllowedFilter::exact('court_id'),
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'created_at',
                'address',
                AllowedSort::custom('name', new NameSort),
                AllowedSort::custom('court', new CourtSort),
            )
            ->paginate(request()->query("per_page", 20));
    }

    public function findById(int $judgeId): Model|QueryBuilder|null
    {
        return QueryBuilder::for($this->model)
            ->with(['user', 'court'])
            ->where('id', $judgeId)
            ->firstOrFail();
    }

    public function findByUserId(int $userId): JudgeDetails|null
    {
        return $this->model->where('user_id', $userId)->firstOrFail();
    }

    public function updateOrCreate(array $data): JudgeDetails
    {
        return $this->model->updateOrCreate(
            ['user_id' => $data['user_id']],
            $data
        );
    }
}
