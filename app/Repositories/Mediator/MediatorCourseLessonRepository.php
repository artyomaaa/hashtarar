<?php

declare(strict_types=1);

namespace App\Repositories\Mediator;

use App\Enums\MediatorAttendances;
use App\Filters\MediatorCourse\SearchFilter;
use App\Models\MediatorCourseLesson;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\Mediator\IMediatorCourseLessonRepository as MediatorCourseLessonRepositoryContract;
use App\Sorters\MediatorCourse\MediatorNameSort;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;


final class MediatorCourseLessonRepository
    extends BaseRepository
    implements MediatorCourseLessonRepositoryContract
{
    public function __construct(MediatorCourseLesson $model)
    {
        parent::__construct($model);
    }

    public function getByLessonId(int $courseId, int $lessonId): Collection
    {
        return QueryBuilder::for($this->model)
            ->with(['mediator', 'courseLesson'])
            ->whereHas('courseLesson', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('course_lesson_id', $lessonId)
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


    public function mediatorsAttendanceToLesson(array $mediatorIds, int $courseLessonId): void
    {
        $data = [];
        foreach ($mediatorIds as $mediatorId) {
            $data[] = [
                'mediator_id' => $mediatorId,
                'course_lesson_id' => $courseLessonId,
                'mediator_attendances' => MediatorAttendances::PRESENT->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->model->insert($data);
    }

    public function getMediatorIdsByLessonIds(array $lessonIds): array
    {
        return $this->model->whereIn('course_lesson_id', $lessonIds)->pluck('mediator_id')->toArray();
    }
}
