<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MediatorCourse extends Pivot
{
    use HasFactory;

    protected $table = 'mediator_courses';

    protected $fillable = [
        'mediator_id',
        'course_id',
    ];

    public function mediator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mediator_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
