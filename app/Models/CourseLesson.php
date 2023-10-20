<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'date',
        'address',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function mediators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mediator_course_lessons', 'course_lesson_id', 'mediator_id')->using(MediatorCourseLesson::class);
    }
}
