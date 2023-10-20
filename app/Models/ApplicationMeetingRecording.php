<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationMeetingRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'name',
        'path',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(ApplicationMeetingHistory::class);
    }
}
