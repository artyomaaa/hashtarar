<?php

declare(strict_types=1);

namespace App\Repositories\Mediator;

use App\Filters\MediatorCourse\SearchFilter;
use App\Models\MediatorCourse;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Mediator\IMediatorCourseRepository as MediatorCourseRepositoryContract;
use App\Sorters\MediatorCourse\MediatorNameSort;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;


final class MediatorCourseRepository
    extends BaseRepository
    implements MediatorCourseRepositoryContract
{
    public function __construct(MediatorCourse $model)
    {
        parent::__construct($model);
    }

    public function getByCourseId(int $courseId): Collection
    {
        return QueryBuilder::for($this->model)
            ->with(['mediator', 'course'])
            ->where('course_id', $courseId)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'created_at',
                AllowedSort::custom('mediator_name', new MediatorNameSort),
            )
            ->get();
    }

    public function getRegisteredCourseMediatorIds(int $courseId): array
    {
        return $this->model->where('course_id', $courseId)->pluck('mediator_id')->toArray();
    }

    public function checkMediatorExistInCourse(int $mediatorId, int $courseId): object|null
    {
        return $this->model->where(['course_id' => $courseId, 'mediator_id' => $mediatorId])->first();
    }
}
