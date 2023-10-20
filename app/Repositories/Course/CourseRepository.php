<?php

declare(strict_types=1);

namespace App\Repositories\Course;

use App\Filters\Course\CreatedBetweenFilter;
use App\Models\Course;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Course\ICourseRepository as CourseRepositoryContract;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CourseRepository
    extends BaseRepository
    implements CourseRepositoryContract
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getCourses(): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->with('lessons')
            ->allowedFilters([
                AllowedFilter::exact('is_training'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'name',
                'duration_hours',
                'min_hours_for_exam',
                'created_at',
            )
            ->paginate(request()->query("per_page", 20));
    }

    public function getMediatorCourses(int $mediatorId): LengthAwarePaginator
    {
        $query = QueryBuilder::for($this->model)
            ->with(['mediators', 'exam','lessons'])
            ->where('start_date', '>=', Carbon::now()->format('Y-m-d'))
            ->where('end_date', '<=', Carbon::now()->format('Y-m-d'))
            ->whereHas('mediators', function ($query) use ($mediatorId) {
                $query->where('mediator_id', $mediatorId);
            });

        return $query
            ->allowedFilters([
                AllowedFilter::exact('is_training'),
                AllowedFilter::custom('created_between', new CreatedBetweenFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'name',
                'duration_hours',
                'min_hours_for_exam',
                'created_at',
            )
            ->paginate(request()->query("per_page", 20));
    }

    public function getMinHoursForExamByCourseId(int $courseId): int|null
    {
        return $this->model->where('id', $courseId)->value('min_hours_for_exam');
    }
}
