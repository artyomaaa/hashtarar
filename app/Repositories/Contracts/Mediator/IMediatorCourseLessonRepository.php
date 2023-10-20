<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Mediator;

interface IMediatorCourseLessonRepository
{
    public function getByLessonId(int $courseId, int $lessonId);


    public function mediatorsAttendanceToLesson(array $mediatorIds, int $courseLessonId);

    public function getMediatorIdsByLessonIds(array $lessonIds): array;
}
