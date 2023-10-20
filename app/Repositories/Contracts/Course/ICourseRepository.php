<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Course;

interface ICourseRepository
{
    public function getCourses();

    public function getMinHoursForExamByCourseId(int $courseId): int|null;

    public function getMediatorCourses(int $mediatorId);

}
