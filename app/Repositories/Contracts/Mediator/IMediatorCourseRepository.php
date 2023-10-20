<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Mediator;

interface IMediatorCourseRepository
{
    public function getByCourseId(int $courseId);

    public function getRegisteredCourseMediatorIds(int $courseId): array;

    public function checkMediatorExistInCourse(int $mediatorId, int $courseId);
}
