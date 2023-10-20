<?php

declare(strict_types=1);

namespace App\Repositories\Course;

use App\Filters\Course\CourseLessonDateBetweenFilter;
use App\Models\CourseLesson;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Course\ICourseLessonRepository as CourseLessonRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CourseLessonRepository
    extends BaseRepository
    implements CourseLessonRepositoryContract
{
    public function __construct(CourseLesson $model)
    {
        parent::__construct($model);
    }

    public function getLessons(int $courseId): LengthAwarePaginator|Collection|array
    {
        return QueryBuilder::for($this->model)
            ->where('course_id', $courseId)
            ->allowedFilters([
                AllowedFilter::custom('date_between', new CourseLessonDateBetweenFilter),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts(
                'created_at',
                'address',
                'date',
            )
            ->allowedIncludes(['course'])
            ->paginate(request()->query("per_page", 20));
    }

    public function findById(int $courseId, int $lessonId): CourseLesson|null
    {
        return $this->model->where('id', $lessonId)->where('course_id', $courseId)->firstOrFail();
    }

    public function getLessonIds(int $courseId): array
    {
        return $this->model
            ->where('course_id', $courseId)
            ->pluck('id')
            ->toArray();
    }
}
