<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MediatorExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'exam_result',
        'specializations',
    ];

    protected $casts = [
        'qualifications' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
