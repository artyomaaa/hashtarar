<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MediatorCourseLesson extends Pivot
{
    use HasFactory;

    protected $table = 'mediator_course_lessons';

    protected $fillable = [
        'mediator_id',
        'course_lesson_id',
    ];

    public function mediator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mediator_id');
    }

    public function courseLesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }
}
