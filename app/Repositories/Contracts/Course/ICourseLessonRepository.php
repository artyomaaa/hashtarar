<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Course;

interface ICourseLessonRepository
{
    public function getLessons(int $courseId);

    public function findById(int $courseId, int $lessonId);

    public function getLessonIds(int $courseId): array;
}
