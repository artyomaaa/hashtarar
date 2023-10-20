<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_hours',
        'min_hours_for_exam',
        'is_training',
        'start_date',
        'end_date',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class);
    }

    public function mediators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mediator_courses', 'course_id', 'mediator_id')->using(MediatorCourse::class);
    }

    public function exam(): HasOne
    {
        return $this->HasOne(Exam::class);
    }
}
