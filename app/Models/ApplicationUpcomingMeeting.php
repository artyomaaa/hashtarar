<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationUpcomingMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'type',
        'date',
        'address',
        'url',
        'code',
        'status',
        'start',
        'end',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
